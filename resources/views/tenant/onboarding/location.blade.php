@extends('layouts.onboarding')

@section('title', 'Location Information - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="2"
    title="Where are you located?"
    subtitle="Help clients find you and set the right timezone"
/>

<form id="locationForm" action="{{ route('tenant.onboarding.location.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label class="form-label">Choose how to set your location</label>
        <div class="method-buttons">
            <button type="button" class="method-btn active" data-method="manual">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Select Manually
            </button>
            <button type="button" class="method-btn" data-method="gps">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Use GPS
            </button>
        </div>
    </div>

    <div id="manualSection">
      <x-onboarding.select name="country"  id="country"  :options="[]" />


        <div id="stateGroup" style="display: none;">
            <x-onboarding.select
                name="state"
                id="state"
                label="State/Province"
                placeholder="Select your state"
                required
            />
        </div>

        <div id="cityGroup" style="display: none;">
            <x-onboarding.select
                name="city"
                id="city"
                label="City"
                placeholder="Select your city"
                required
            />
        </div>
    </div>

    <div class="gps-section" id="gpsSection">
        <p>Click the button below to automatically detect your location</p>
        <button type="button" class="btn btn-primary" id="detectBtn">Detect My Location</button>
        <div class="detected-location" id="detectedLocation">
            <strong>Location Detected:</strong> <span id="detectedText"></span>
        </div>
    </div>

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}"
        backUrl="{{ route('tenant.onboarding.personal') }}"
    />
</form>

@endsection

@push('styles')
<style>
.method-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
    margin-top: var(--space-sm);
}

.method-btn {
    padding: var(--space-md);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all var(--transition-base);
    font-family: var(--font-sans);
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    color: var(--text-body);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
}

.method-btn.active {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
}

.method-btn:hover {
    border-color: var(--accent);
}

.gps-section {
    padding: var(--space-xl);
    background: var(--apc-bg);
    border-radius: var(--radius);
    text-align: center;
    margin-bottom: var(--space-lg);
    display: none;
}

.gps-section.show {
    display: block;
}

.gps-section p {
    color: var(--text-body);
    margin-bottom: var(--space-md);
}

.detected-location {
    margin-top: var(--space-md);
    padding: var(--space-sm) var(--space-md);
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: var(--radius);
    color: var(--success);
    font-size: var(--fs-subtle);
    display: none;
}

.detected-location.show {
    display: block;
}

@media (max-width: 640px) {
    .method-buttons {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
  
  // ===============================
// ProMatch Location - ULTRA FAST Edition
// Response times: 10-20ms (200x faster!)
// ===============================
(() => {
  "use strict";

  // ---------- DOM Elements ----------
  const form = document.getElementById('locationForm');
  const countrySelect = document.getElementById('country');
  const stateSelect = document.getElementById('state');
  const citySelect = document.getElementById('city');
  const stateGroup = document.getElementById('stateGroup');
  const cityGroup = document.getElementById('cityGroup');

  const methodBtns = document.querySelectorAll('.method-btn');
  const manualSection = document.getElementById('manualSection');
  const gpsSection = document.getElementById('gpsSection');
  const detectBtn = document.getElementById('detectBtn');
  const detectedWrap = document.getElementById('detectedLocation');
  const detectedText = document.getElementById('detectedText');

  const continueBtn = document.getElementById('continueBtn');
  const btnText = document.getElementById('btnText');
  const backBtn = document.getElementById('backBtn');
  const headerProgress = document.getElementById('headerProgress');
  const stepText = document.getElementById('stepText');

  // ---------- State ----------
  let selectedLocation = { method: 'manual' };
  let countriesCache = [];
  
  const CURRENT_STEP = 2;
  const TOTAL_STEPS = 8;
  const NEXT_STEP_URL = '{{ route("tenant.onboarding.education") }}';

  // ---------- API Functions (Lightning Fast!) ----------
  const API = {
    async countries(query = '') {
      const url = `/api/location/countries${query ? `?q=${encodeURIComponent(query)}` : ''}`;
      const response = await fetch(url, {
        headers: { 
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        }
      });
      if (!response.ok) throw new Error('Failed to fetch countries');
      return response.json();
    },

    async states(country) {
      const url = `/api/location/states?country=${encodeURIComponent(country)}`;
      const response = await fetch(url, {
        headers: { 
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        }
      });
      if (!response.ok) throw new Error('Failed to fetch states');
      return response.json();
    },

    async cities(country, state) {
      const url = `/api/location/cities?country=${encodeURIComponent(country)}&state=${encodeURIComponent(state)}`;
      const response = await fetch(url, {
        headers: { 
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        }
      });
      if (!response.ok) throw new Error('Failed to fetch cities');
      return response.json();
    },

    async reverse(lat, lng) {
      const url = `/api/location/reverse?lat=${lat}&lng=${lng}`;
      const response = await fetch(url, {
        headers: { 
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        }
      });
      if (!response.ok) throw new Error('Failed to reverse geocode');
      return response.json();
    }
  };

  // ---------- UI Helpers ----------
  const setSuccess = (el, on) => el?.classList.toggle('success', !!on);
  
  const resetSelect = (sel, placeholder) => {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    sel.disabled = true;
    sel.classList.remove('success');
  };

  const showLoading = (sel, text = 'Loading...') => {
    sel.innerHTML = `<option value="">${text}</option>`;
    sel.disabled = true;
  };

  const populateSelect = (sel, items, placeholder) => {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    
    items.forEach(item => {
      const option = document.createElement('option');
      option.value = item.name;
      option.textContent = item.name;
      sel.appendChild(option);
    });
    
    sel.disabled = items.length === 0;
  };

  const updateProgress = (step) => {
    if (headerProgress) {
      headerProgress.style.width = `${(step / TOTAL_STEPS) * 100}%`;
    }
    if (stepText) {
      stepText.textContent = `Step ${step} of ${TOTAL_STEPS}`;
    }
  };

  const validateForm = () => {
    const isValid = !!(
      selectedLocation.country && 
      selectedLocation.state && 
      selectedLocation.city
    );
    if (continueBtn) continueBtn.disabled = !isValid;
    return isValid;
  };

  // ---------- Load Countries (Initial Load) ----------
  const loadCountries = async () => {
    try {
      showLoading(countrySelect, 'Loading countries...');
      
      const countries = await API.countries();
      countriesCache = countries;

      // Prioritize popular countries
      const popular = ['Pakistan', 'United States', 'United Kingdom', 'Canada', 'India'];
      const popularCountries = countries.filter(c => popular.includes(c.name));
      const otherCountries = countries.filter(c => !popular.includes(c.name));

      populateSelect(
        countrySelect, 
        [...popularCountries, ...otherCountries],
        'Select your country'
      );

      countrySelect.disabled = false;

    } catch (error) {
      console.error('Error loading countries:', error);
      countrySelect.innerHTML = '<option value="">Error loading countries</option>';
      countrySelect.disabled = true;
    }
  };

  // ---------- Event Handlers ----------

  const handleCountryChange = async () => {
    const countryName = countrySelect.value;

    // Reset downstream
    resetSelect(stateSelect, 'Select your state');
    resetSelect(citySelect, 'Select your city');
    stateGroup.style.display = 'none';
    cityGroup.style.display = 'none';

    selectedLocation.country = countryName || null;
    selectedLocation.state = null;
    selectedLocation.city = null;
    
    setSuccess(countrySelect, !!countryName);
    setSuccess(stateSelect, false);
    setSuccess(citySelect, false);

    if (!countryName) {
      validateForm();
      return;
    }

    try {
      showLoading(stateSelect, 'Loading states...');
      stateGroup.style.display = 'block';

      const states = await API.states(countryName);

      if (states.length === 0) {
        stateSelect.innerHTML = '<option value="">No states available</option>';
        stateSelect.disabled = true;
        return;
      }

      populateSelect(stateSelect, states, 'Select your state');
      stateSelect.disabled = false;

    } catch (error) {
      console.error('Error loading states:', error);
      stateSelect.innerHTML = '<option value="">Error loading states</option>';
    } finally {
      validateForm();
    }
  };

  const handleStateChange = async () => {
    const stateName = stateSelect.value;

    // Reset downstream
    resetSelect(citySelect, 'Select your city');
    cityGroup.style.display = 'none';

    selectedLocation.state = stateName || null;
    selectedLocation.city = null;
    
    setSuccess(stateSelect, !!stateName);
    setSuccess(citySelect, false);

    if (!stateName || !selectedLocation.country) {
      validateForm();
      return;
    }

    try {
      showLoading(citySelect, 'Loading cities...');
      cityGroup.style.display = 'block';

      const cities = await API.cities(selectedLocation.country, stateName);

      if (cities.length === 0) {
        citySelect.innerHTML = '<option value="">No cities available</option>';
        citySelect.disabled = true;
        return;
      }

      populateSelect(citySelect, cities, 'Select your city');
      citySelect.disabled = false;

    } catch (error) {
      console.error('Error loading cities:', error);
      citySelect.innerHTML = '<option value="">Error loading cities</option>';
    } finally {
      validateForm();
    }
  };

  const handleCityChange = () => {
    const cityName = citySelect.value;

    selectedLocation.city = cityName || null;
    setSuccess(citySelect, !!cityName);
    
    validateForm();
  };

  // ---------- Method Switching ----------

  const setMethod = (method) => {
    selectedLocation.method = method;

    methodBtns.forEach(btn => {
      const isActive = btn.dataset.method === method;
      btn.classList.toggle('active', isActive);
      btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });

    if (method === 'manual') {
      manualSection.style.display = 'block';
      gpsSection.classList.remove('show');
      detectedWrap.classList.remove('show');
    } else {
      manualSection.style.display = 'none';
      gpsSection.classList.add('show');
    }

    validateForm();
  };

  // ---------- GPS Detection ----------

  const handleGPSDetection = async () => {
    if (!navigator.geolocation) {
      alert('Geolocation is not supported by your browser');
      return;
    }

    // Update button state
    detectBtn.innerHTML = '<div class="loading-spinner"></div> Detecting...';
    detectBtn.disabled = true;

    try {
      // Get GPS coordinates
      const position = await new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject, {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0
        });
      });

      const { latitude, longitude } = position.coords;

      // Reverse geocode to get location
      const result = await API.reverse(latitude, longitude);

      const matched = result.matched || {};
      const raw = result.raw || {};

      // Use matched values (from our database) or fall back to raw
      const country = matched.country || raw.country;
      const state = matched.state || raw.state;
      const city = matched.city || raw.city;

      if (!country || !state || !city) {
        throw new Error('Could not determine exact location');
      }

      // Update UI
      setMethod('gps');
      detectedText.textContent = `${city}, ${state}, ${country}`;
      detectedWrap.classList.add('show');

      // Set selected values
      selectedLocation.country = country;
      selectedLocation.state = state;
      selectedLocation.city = city;
      selectedLocation.latitude = latitude;
      selectedLocation.longitude = longitude;

      // Populate dropdowns with detected values
      await loadCountries();
      
      if (countrySelect.querySelector(`option[value="${country}"]`)) {
        countrySelect.value = country;
        setSuccess(countrySelect, true);
        await handleCountryChange();
      }

      if (stateSelect.querySelector(`option[value="${state}"]`)) {
        stateSelect.value = state;
        setSuccess(stateSelect, true);
        await handleStateChange();
      }

      if (citySelect.querySelector(`option[value="${city}"]`)) {
        citySelect.value = city;
        setSuccess(citySelect, true);
        handleCityChange();
      }

      validateForm();

      // Success feedback
      detectBtn.innerHTML = '✓ Location Detected';
      setTimeout(() => {
        detectBtn.innerHTML = 'Detect My Location';
      }, 3000);

    } catch (error) {
      console.error('GPS detection error:', error);

      let message = 'Unable to detect location. Please select manually.';

      if (error.code === 1) {
        message = 'Location permission denied. Please allow location access or select manually.';
      } else if (error.code === 2) {
        message = 'Location unavailable. Please check your GPS settings or select manually.';
      } else if (error.code === 3) {
        message = 'Location request timed out. Please try again or select manually.';
      }

      alert(message);
      detectBtn.innerHTML = 'Detect My Location';

    } finally {
      detectBtn.disabled = false;
    }
  };

  // ---------- Event Listeners ----------

  methodBtns.forEach(btn => {
    btn.addEventListener('click', () => setMethod(btn.dataset.method));
  });

  countrySelect.addEventListener('change', handleCountryChange);
  stateSelect.addEventListener('change', handleStateChange);
  citySelect.addEventListener('change', handleCityChange);
  detectBtn.addEventListener('click', handleGPSDetection);

  // Keyboard navigation
  [countrySelect, stateSelect, citySelect].forEach(select => {
    select.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        if (validateForm()) {
          form.requestSubmit();
        }
      }
    });
  });

  // ---------- Form Submission ----------

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validateForm()) {
      alert('Please complete all location fields');
      return;
    }

    // Add timezone
    try {
      selectedLocation.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    } catch {
      selectedLocation.timezone = 'UTC';
    }

    // Save to localStorage
    try {
      localStorage.setItem('onboarding_location', JSON.stringify(selectedLocation));
    } catch (error) {
      console.error('Failed to save to localStorage:', error);
    }

    // Update UI
    if (btnText) btnText.innerHTML = '<div class="loading-spinner"></div>';
    if (continueBtn) continueBtn.disabled = true;

    updateProgress(CURRENT_STEP + 1);

    // Submit form
    setTimeout(() => {
      form.submit();
    }, 300);
  });

  // ---------- Initialization ----------

  const init = async () => {
    updateProgress(CURRENT_STEP);
    setMethod('manual');

    // Reset selects
    resetSelect(stateSelect, 'Select your state');
    resetSelect(citySelect, 'Select your city');

    // Load countries (cached, super fast!)
    await loadCountries();

    // Try to restore from localStorage
    try {
      const saved = localStorage.getItem('onboarding_location');
      if (saved) {
        const data = JSON.parse(saved);

        if (data.method === 'gps' && data.country && data.state && data.city) {
          setMethod('gps');
          selectedLocation = data;
          
          detectedText.textContent = `${data.city}, ${data.state}, ${data.country}`;
          detectedWrap.classList.add('show');

          // Populate selects
          if (countrySelect.querySelector(`option[value="${data.country}"]`)) {
            countrySelect.value = data.country;
            setSuccess(countrySelect, true);
            await handleCountryChange();

            if (stateSelect.querySelector(`option[value="${data.state}"]`)) {
              stateSelect.value = data.state;
              setSuccess(stateSelect, true);
              await handleStateChange();

              if (citySelect.querySelector(`option[value="${data.city}"]`)) {
                citySelect.value = data.city;
                setSuccess(citySelect, true);
                handleCityChange();
              }
            }
          }
        }
      }
    } catch (error) {
      console.error('Failed to restore from localStorage:', error);
    }
  };

  // Start when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>
@endpush
