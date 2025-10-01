@extends('layouts.onboarding')

@section('title', 'Location Information - ProMatch')

@php
    $currentStep = 2;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Location Information" />
        <h1 class="form-title">Where are you located?</h1>
        <p class="form-subtitle">Help clients find you and set the right timezone</p>
    </div>

    <form id="locationForm" action="{{ route('tenant.onboarding.location.store') }}" method="POST">
        @csrf

        <!-- Method Selection -->
        <div class="method-selector" role="group" aria-label="Choose how to set your location">
            <label class="form-label">Choose how to set your location</label>
            <div class="method-buttons">
                <button type="button" class="method-btn active" data-method="manual" aria-pressed="true">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Select Manually
                </button>
                <button type="button" class="method-btn" data-method="gps" aria-pressed="false">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Use GPS
                </button>
            </div>
        </div>

        <!-- Manual Selection -->
        <div id="manualSection">
            <x-forms.select 
                name="country"
                label="Country"
                placeholder="Select your country"
                required
                :options="[
                    'pk' => 'Pakistan',
                    'us' => 'United States',
                    'uk' => 'United Kingdom',
                    'ca' => 'Canada',
                    'in' => 'India'
                ]"
            />

            <div id="stateGroup" style="display: none;">
                <x-forms.select 
                    name="state"
                    label="State/Province"
                    placeholder="Select your state"
                    required
                />
            </div>

            <div id="cityGroup" style="display: none;">
                <x-forms.select 
                    name="city"
                    label="City"
                    placeholder="Select your city"
                    required
                />
            </div>
        </div>

        <!-- GPS Section -->
        <div class="gps-section" id="gpsSection" aria-live="polite">
            <p>Click the button below to automatically detect your location</p>
            <button type="button" class="gps-btn" id="detectBtn">Detect My Location</button>
            <div class="detected-location" id="detectedLocation">
                <strong>Location Detected:</strong> <span id="detectedText"></span>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('tenant.onboarding.personal') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </x-ui.button>

            <x-ui.button variant="primary" type="submit" id="continueBtn" disabled>
                <span id="btnText">Continue</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-ui.button>
        </div>
    </form>
@endsection

@push('styles')
<style>
    .method-selector { margin-bottom: 32px; }
    
    .method-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 20px;
    }

    .method-btn {
        padding: 16px;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        cursor: pointer;
        transition: all .2s ease;
        text-align: center;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .method-btn svg {
        width: 18px;
        height: 18px;
    }

    .method-btn.active {
        border-color: var(--dark);
        background: var(--gray-100);
        color: var(--dark);
    }

    .method-btn:hover {
        border-color: var(--dark);
        background: var(--gray-100);
    }

    .gps-section {
        padding: 24px;
        background: var(--gray-100);
        border-radius: 8px;
        text-align: center;
        margin-bottom: 24px;
        display: none;
    }

    .gps-section.show {
        display: block;
        animation: fadeIn .3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .gps-btn {
        padding: 12px 24px;
        background: var(--dark);
        color: var(--white);
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all .2s ease;
        margin-top: 16px;
    }

    .gps-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .gps-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .detected-location {
        margin-top: 16px;
        padding: 10px 14px;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 6px;
        color: var(--success);
        font-size: 13px;
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
    // ProMatch — Location Step (Page 2)
    // Matches Page 3's polish, structure & background
    // ===============================
    (() => {
      "use strict";

      // ------- Location Data -------
      const locationData = {
        pk: { name: 'Pakistan', states: {
          'punjab': { name: 'Punjab', cities: ['Lahore', 'Faisalabad', 'Rawalpindi', 'Multan'] },
          'sindh': { name: 'Sindh', cities: ['Karachi', 'Hyderabad', 'Sukkur'] },
          'kpk': { name: 'Khyber Pakhtunkhwa', cities: ['Peshawar', 'Mardan', 'Mingora'] },
          'balochistan': { name: 'Balochistan', cities: ['Quetta', 'Gwadar'] }
        }},
        us: { name: 'United States', states: {
          'ca': { name: 'California', cities: ['Los Angeles', 'San Francisco', 'San Diego'] },
          'ny': { name: 'New York', cities: ['New York City', 'Buffalo', 'Rochester'] },
          'tx': { name: 'Texas', cities: ['Houston', 'Dallas', 'Austin'] }
        }},
        uk: { name: 'United Kingdom', states: {
          'england': { name: 'England', cities: ['London', 'Manchester', 'Birmingham'] },
          'scotland': { name: 'Scotland', cities: ['Edinburgh', 'Glasgow', 'Aberdeen'] }
        }},
        ca: { name: 'Canada', states: {
          'ontario': { name: 'Ontario', cities: ['Toronto', 'Ottawa', 'Hamilton'] },
          'quebec': { name: 'Quebec', cities: ['Montreal', 'Quebec City', 'Laval'] }
        }},
        in: { name: 'India', states: {
          'maharashtra': { name: 'Maharashtra', cities: ['Mumbai', 'Pune', 'Nagpur'] },
          'delhi': { name: 'Delhi', cities: ['New Delhi', 'Delhi'] }
        }}
      };

      // ------- DOM Elements -------
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
      const detectedLocation = document.getElementById('detectedLocation');
      const detectedText = document.getElementById('detectedText');

      const continueBtn = document.getElementById('continueBtn');
      const btnText = document.getElementById('btnText');
      const backBtn = document.getElementById('backBtn');

      const headerProgress = document.getElementById('headerProgress');
      const stepText = document.getElementById('stepText');

      // ------- State -------
      let currentMethod = 'manual';
      let selectedLocation = {};
      const CURRENT_STEP = 2;
      const TOTAL_STEPS = 8;
      const NEXT_STEP_URL = '/onboarding/skills';

      // ------- Helpers -------
      const getTimezone = () => {
        try { return Intl.DateTimeFormat().resolvedOptions().timeZone || null; }
        catch { return null; }
      };

      function setSuccess(el, on) {
        if (!el) return;
        el.classList.toggle('success', !!on);
      }

      function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
      }

      function toCode(label) {
        return String(label).toLowerCase().replace(/\s+/g, '-');
      }

      function updateProgress(step) {
        const pct = (step / TOTAL_STEPS) * 100;
        headerProgress.style.width = pct + '%';
        stepText.textContent = `Step ${step} of ${TOTAL_STEPS}`;
      }

      function validateForm() {
        const valid = !!(selectedLocation.country && selectedLocation.state && selectedLocation.city);
        continueBtn.disabled = !valid;
        return valid;
      }

      function populateStates(countryCode) {
        resetSelect(stateSelect, 'Select your state');
        resetSelect(citySelect, 'Select your city');
        stateGroup.style.display = 'none';
        cityGroup.style.display = 'none';

        if (!countryCode || !locationData[countryCode]) return;

        const states = locationData[countryCode].states;
        Object.keys(states).forEach(stateCode => {
          const opt = document.createElement('option');
          opt.value = stateCode;
          opt.textContent = states[stateCode].name;
          stateSelect.appendChild(opt);
        });
        stateSelect.disabled = false;
        stateGroup.style.display = 'block';
      }

      function populateCities(countryCode, stateCode) {
        resetSelect(citySelect, 'Select your city');
        cityGroup.style.display = 'none';
        if (!countryCode || !stateCode) return;

        const state = locationData[countryCode]?.states?.[stateCode];
        if (!state) return;

        state.cities.forEach(city => {
          const opt = document.createElement('option');
          opt.value = toCode(city);
          opt.textContent = city;
          citySelect.appendChild(opt);
        });
        citySelect.disabled = false;
        cityGroup.style.display = 'block';
      }

      function renderDetected(text) {
        detectedText.textContent = text;
        detectedLocation.classList.add('show');
      }

      function clearDetected() {
        detectedText.textContent = '';
        detectedLocation.classList.remove('show');
      }

      function saveToStorage() {
        try { localStorage.setItem('onboarding_location', JSON.stringify(selectedLocation)); } catch {}
      }

      function loadFromStorage() {
        try {
          const raw = localStorage.getItem('onboarding_location');
          if (!raw) return;
          const saved = JSON.parse(raw);
          if (!saved || typeof saved !== 'object') return;

          selectedLocation = saved;

          // Method restore
          setMethod(saved.method === 'gps' ? 'gps' : 'manual');
          if (saved.method === 'gps' && saved.display) {
            renderDetected(saved.display);
          }

          // Populate selects
          if (saved.country) {
            countrySelect.value = saved.country;
            setSuccess(countrySelect, true);
            populateStates(saved.country);
          }
          if (saved.state) {
            stateSelect.value = saved.state;
            setSuccess(stateSelect, true);
            populateCities(saved.country, saved.state);
          }
          if (saved.city) {
            citySelect.value = saved.city;
            setSuccess(citySelect, true);
          }

          validateForm();
        } catch {}
      }

      function setMethod(method) {
        currentMethod = method;
        methodBtns.forEach(b => {
          const isActive = b.dataset.method === method;
          b.classList.toggle('active', isActive);
          b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
        if (method === 'manual') {
          manualSection.style.display = 'block';
          gpsSection.classList.remove('show');
          clearDetected();
        } else {
          manualSection.style.display = 'none';
          gpsSection.classList.add('show');
        }
        validateForm();
      }

      // ------- Events -------
      methodBtns.forEach(btn => {
        btn.addEventListener('click', () => setMethod(btn.dataset.method));
      });

      countrySelect.addEventListener('change', function () {
        const code = this.value;

        // Reset downstream
        stateSelect.value = '';
        citySelect.value = '';
        setSuccess(stateSelect, false);
        setSuccess(citySelect, false);

        if (code && locationData[code]) {
          selectedLocation.country = code;
          selectedLocation.countryName = locationData[code].name;
          populateStates(code);
          setSuccess(countrySelect, true);
        } else {
          setSuccess(countrySelect, false);
          delete selectedLocation.country;
          delete selectedLocation.countryName;
          resetSelect(stateSelect, 'Select your state');
          resetSelect(citySelect, 'Select your city');
          stateGroup.style.display = 'none';
          cityGroup.style.display = 'none';
        }

        delete selectedLocation.state;
        delete selectedLocation.stateName;
        delete selectedLocation.city;
        delete selectedLocation.cityName;
        validateForm();
      });

      stateSelect.addEventListener('change', function () {
        const stateCode = this.value;
        if (stateCode && selectedLocation.country) {
          selectedLocation.state = stateCode;
          selectedLocation.stateName = locationData[selectedLocation.country].states[stateCode].name;
          populateCities(selectedLocation.country, stateCode);
          setSuccess(stateSelect, true);
        } else {
          setSuccess(stateSelect, false);
          delete selectedLocation.state;
          delete selectedLocation.stateName;
          resetSelect(citySelect, 'Select your city');
          cityGroup.style.display = 'none';
        }
        delete selectedLocation.city;
        delete selectedLocation.cityName;
        validateForm();
      });

      citySelect.addEventListener('change', function () {
        const code = this.value;
        if (code) {
          selectedLocation.city = code;
          selectedLocation.cityName = this.options[this.selectedIndex].textContent;
          setSuccess(citySelect, true);
        } else {
          setSuccess(citySelect, false);
          delete selectedLocation.city;
          delete selectedLocation.cityName;
        }
        validateForm();
      });

      // Keyboard: Enter on a select submits if valid
      [countrySelect, stateSelect, citySelect].forEach(sel => {
        sel.addEventListener('keydown', (ev) => {
          if (ev.key === 'Enter') {
            ev.preventDefault();
            if (validateForm()) form.requestSubmit();
          }
        });
      });

      // GPS Detection
      detectBtn.addEventListener('click', function () {
        if (!navigator.geolocation) {
          alert('Geolocation is not supported by your browser');
          return;
        }
        detectBtn.innerHTML = '<div class="loading-spinner"></div> Detecting...';
        detectBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
          (position) => {
            const { latitude, longitude } = position.coords || {};
            // Simulated reverse geocoding result
            setTimeout(() => {
              selectedLocation = {
                country: 'pk',
                countryName: 'Pakistan',
                state: 'sindh',
                stateName: 'Sindh',
                city: 'karachi',
                cityName: 'Karachi',
                method: 'gps',
                lat: latitude || null,
                lng: longitude || null,
                timezone: getTimezone(),
                display: 'Karachi, Sindh, Pakistan'
              };
              renderDetected(selectedLocation.display);
              validateForm();
              detectBtn.innerHTML = 'Detect My Location';
              detectBtn.disabled = false;
            }, 1000);
          },
          (error) => {
            let msg = 'Unable to detect location. Please select manually.';
            if (error && typeof error.code === 'number') {
              if (error.code === 1) msg = 'Permission denied. Please allow location access or select manually.';
              if (error.code === 2) msg = 'Position unavailable. Please try again or select manually.';
              if (error.code === 3) msg = 'Request timed out. Please try again or select manually.';
            }
            alert(msg);
            detectBtn.innerHTML = 'Detect My Location';
            detectBtn.disabled = false;
          },
          { enableHighAccuracy: false, timeout: 7000, maximumAge: 0 }
        );
      });

      // Back
      backBtn.addEventListener('click', (e) => {
        // Optional: navigate to previous step
        // If not desired, set href="#" and handle here.
      });

      // Submit
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!validateForm()) return;

        selectedLocation.timezone = selectedLocation.timezone || getTimezone();
        selectedLocation.savedAt = new Date().toISOString();
        saveToStorage();

        btnText.innerHTML = '<div class="loading-spinner"></div>';
        continueBtn.disabled = true;

        // Move progress to next step (3/8) to mirror Page 3 flow
        updateProgress(CURRENT_STEP + 1);

        setTimeout(() => {
          try { window.location.href = NEXT_STEP_URL; }
          catch { alert('Location saved! Moving to skills…'); }
        }, 700);
      });

      // Boot
      document.addEventListener('DOMContentLoaded', () => {
        updateProgress(CURRENT_STEP); // Start at Step 2/8
        // Reset selects
        resetSelect(stateSelect, 'Select your state');
        resetSelect(citySelect, 'Select your city');
        // Default method
        setMethod('manual');
        // Restore saved (if any)
        loadFromStorage();
      });
    })();
  </script>
@endpush