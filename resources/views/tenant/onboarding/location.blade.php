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
            <button type="button" class="method-btn active" data-method="manual" aria-pressed="true">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Select Manually
            </button>
            <button type="button" class="method-btn" data-method="gps" aria-pressed="false">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Use GPS
            </button>
        </div>
    </div>

    <div id="manualSection">
        <x-onboarding.select
            name="country"
            id="country"
            label="Country"
            placeholder="Select your country"
            required
            :options="[]"
        />

        <div id="stateGroup" style="display:none;">
            <x-onboarding.select
                name="state"
                id="state"
                label="State/Province"
                placeholder="Select your state"
                required
            />
        </div>

        <div id="cityGroup" style="display:none;">
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
        <div class="detected-location" id="detectedLocation" role="status" aria-live="polite">
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
.method-buttons{display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);margin-top:var(--space-sm)}
.method-btn{padding:var(--space-md);background:var(--card);border:1px solid var(--border);border-radius:var(--radius);cursor:pointer;transition:all var(--transition-base);font-family:var(--font-sans);font-size:var(--fs-body);font-weight:var(--fw-medium);color:var(--text-body);display:flex;align-items:center;justify-content:center;gap:var(--space-sm)}
.method-btn.active{border-color:var(--accent);background:var(--accent-light);color:var(--accent)}
.method-btn:hover{border-color:var(--accent)}
.gps-section{padding:var(--space-xl);background:var(--apc-bg);border-radius:var(--radius);text-align:center;margin-bottom:var(--space-lg);display:none}
.gps-section.show{display:block}
.gps-section p{color:var(--text-body);margin-bottom:var(--space-md)}
.detected-location{margin-top:var(--space-md);padding:var(--space-sm) var(--space-md);background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:var(--radius);color:var(--success);font-size:var(--fs-subtle);display:none}
.detected-location.show{display:block}
@media (max-width:640px){.method-buttons{grid-template-columns:1fr}}
/* subtle success border on selects */
select.success{border-color:var(--success);box-shadow:0 0 0 3px rgba(16,185,129,.15)}
</style>
@endpush

@push('scripts')
<script>
(() => {
  "use strict";

  // ---------- DOM ----------
  const form = document.getElementById('locationForm');
  const countrySelect = document.getElementById('country');
  const stateSelect   = document.getElementById('state');
  const citySelect    = document.getElementById('city');
  const stateGroup    = document.getElementById('stateGroup');
  const cityGroup     = document.getElementById('cityGroup');

  const methodBtns    = document.querySelectorAll('.method-btn');
  const manualSection = document.getElementById('manualSection');
  const gpsSection    = document.getElementById('gpsSection');
  const detectBtn     = document.getElementById('detectBtn');
  const detectedWrap  = document.getElementById('detectedLocation');
  const detectedText  = document.getElementById('detectedText');

  const continueBtn   = document.getElementById('continueBtn');
  const btnText       = document.getElementById('btnText');
  const headerProgress= document.getElementById('headerProgress');
  const stepText      = document.getElementById('stepText');

  // hidden fields (created if missing)
  const tzInput  = ensureHidden('timezone');
  const latInput = ensureHidden('coords[lat]');
  const lngInput = ensureHidden('coords[lng]');
  const srcInput = ensureHidden('source');

  // ---------- API endpoints ----------
  const API = {
    countries: () => fetchJSON(`/api/location/countries`), // [{name, iso2?}]
    states:    (countryName) => fetchJSON(`/api/location/states?country=${encodeURIComponent(countryName)}`), // [{name}]
    cities:    (countryName, stateName) => fetchJSON(`/api/location/cities?country=${encodeURIComponent(countryName)}&state=${encodeURIComponent(stateName)}`), // [{name}]
  };

  // ---------- Utils ----------
  async function fetchJSON(url) {
    const r = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store' });
    if (!r.ok) throw new Error(`HTTP ${r.status} for ${url}`);
    return r.json();
  }

  const resetSelect = (sel, ph) => { sel.innerHTML = `<option value="">${ph}</option>`; sel.disabled = true; sel.classList.remove('success'); };
  const setSuccess  = (el,on)=> el?.classList?.toggle('success',!!on);
  const show        = el => el.style.display = 'block';
  const hide        = el => el.style.display = 'none';
  const tz          = () => { try { return Intl.DateTimeFormat().resolvedOptions().timeZone; } catch { return 'UTC'; } };
  const norm        = s => (s || '').normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().trim();

  function ensureHidden(name) {
    let el = form.querySelector(`input[name="${name}"]`);
    if (!el) {
      el = document.createElement('input');
      el.type = 'hidden'; el.name = name;
      form.appendChild(el);
    }
    return el;
  }

  function selectByText(selectEl, target) {
    if (!target) return false;
    const needle = norm(target);
    const options = Array.from(selectEl.options);
    let hit = options.find(o => norm(o.textContent) === needle);
    if (!hit) hit = options.find(o => norm(o.textContent).includes(needle) || needle.includes(norm(o.textContent)));
    if (!hit) return false;
    selectEl.value = hit.value;
    return true;
  }

  function updateProgress(step) {
    const TOTAL = 8;
    if (headerProgress) headerProgress.style.width = ((step/TOTAL)*100)+'%';
    if (stepText) stepText.textContent = `Step ${step} of ${TOTAL}`;
  }

  function validate() {
    const ok = !!(countrySelect.value && stateSelect.value && citySelect.value);
    if (continueBtn) continueBtn.disabled = !ok;
    return ok;
  }

  function setMethod(method) {
    methodBtns.forEach(b=>{
      const act = b.dataset.method === method;
      b.classList.toggle('active', act);
      b.setAttribute('aria-pressed', act ? 'true':'false');
    });
    if (method === 'manual') {
      manualSection.style.display='block';
      gpsSection.classList.remove('show');
      detectedWrap.classList.remove('show');
      srcInput.value = 'manual';
    } else {
      manualSection.style.display='none';
      gpsSection.classList.add('show');
      srcInput.value = 'nominatim';
    }
  }

  // ---------- Populate helpers ----------
  function applyOptions(select, items, ph) {
    resetSelect(select, ph);
    items.forEach(({ name }) => {
      const o = document.createElement('option');
      o.value = name; o.textContent = name;
      select.appendChild(o);
    });
    select.disabled = items.length === 0;
  }

  async function loadCountries() {
    resetSelect(countrySelect, 'Loading countries...');
    const list = await API.countries();
    const popular = new Set(['Pakistan','United States','United Kingdom','Canada','India']);
    const top = list.filter(x => popular.has(x.name));
    const rest = list.filter(x => !popular.has(x.name)).sort((a,b)=> a.name.localeCompare(b.name));
    applyOptions(countrySelect, [...top, ...rest], 'Select your country');
  }

  async function onCountryChange() {
    const country = countrySelect.value || '';
    setSuccess(countrySelect, !!country);

    resetSelect(stateSelect, 'Select your state');
    resetSelect(citySelect,  'Select your city');
    hide(cityGroup);

    if (!country) { stateGroup.style.display='none'; validate(); return; }

    stateGroup.style.display='block';
    stateSelect.innerHTML = `<option value="">Loading states...</option>`;
    const rows = await API.states(country);
    applyOptions(stateSelect, rows, 'Select your state');
    validate();
  }

  async function onStateChange() {
    const country = countrySelect.value || '';
    const state = stateSelect.value || '';
    setSuccess(stateSelect, !!state);

    resetSelect(citySelect, 'Select your city');
    hide(cityGroup);
    if (!country || !state) { validate(); return; }

    cityGroup.style.display='block';
    citySelect.innerHTML = `<option value="">Loading cities...</option>`;
    const rows = await API.cities(country, state);
    applyOptions(citySelect, rows, 'Select your city');
    validate();
  }

  function onCityChange() {
    setSuccess(citySelect, !!citySelect.value);
    validate();
  }

  // ---------- Reverse geocode (English) ----------
  async function reverseEnglish(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=12&addressdetails=1&accept-language=en`;
    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    if (!res.ok) throw new Error('Reverse geocode failed');
    const data = await res.json();
    const a = data.address || {};
    const english = {
      country: a.country || '',
      country_code: (a.country_code || '').toUpperCase(),
      state: a.state || a.region || a.province || a.state_district || '',
      city: a.city || a.town || a.village || a.municipality || a.county || ''
    };
    return { english, raw: data };
  }

  // ---------- GPS flow ----------
  async function detectLocation() {
    if (!navigator.geolocation) return alert('Geolocation not supported');

    detectBtn.disabled = true;
    detectBtn.innerHTML = '<div class="loading-spinner"></div> Detecting...';
    try {
      const pos = await new Promise((res, rej) =>
        navigator.geolocation.getCurrentPosition(res, rej, { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 })
      );
      const { latitude: lat, longitude: lng } = pos.coords || {};

      const { english, raw } = await reverseEnglish(lat, lng);

      // expose & cache
      window.__gps_en = { coords:{lat,lng}, country:{name:english.country, iso2:english.country_code}, state:{name:english.state}, city:{name:english.city}, source:'nominatim', raw };
      try { localStorage.setItem('gps_detected_en', JSON.stringify(window.__gps_en)); } catch {}

      // UI feedback
      setMethod('gps');
      detectedText.textContent = `${english.city}, ${english.state}, ${english.country}`;
      detectedWrap.classList.add('show');

      // set hidden inputs
      tzInput.value  = tz();
      latInput.value = lat;
      lngInput.value = lng;

      // populate selects to match English names
      await loadCountries();
      if (selectByText(countrySelect, english.country)) {
        setSuccess(countrySelect, true);
        await onCountryChange();
      }
      if (selectByText(stateSelect, english.state)) {
        setSuccess(stateSelect, true);
        await onStateChange();
      }
      if (selectByText(citySelect, english.city)) {
        setSuccess(citySelect, true);
        onCityChange();
      }

      validate();
      detectBtn.innerHTML = '✓ Location Detected';
      setTimeout(()=> detectBtn.textContent = 'Detect My Location', 1600);

    } catch (err) {
      console.error(err);
      alert('Unable to detect location. Please select manually.');
      detectBtn.textContent = 'Detect My Location';
    } finally {
      detectBtn.disabled = false;
    }
  }

  // ---------- Submit ----------
  form.addEventListener('submit', (e)=>{
    if (!validate()) { e.preventDefault(); return; }
    tzInput.value = tzInput.value || tz();      // set timezone if empty
    if (btnText) btnText.innerHTML = '<div class="loading-spinner"></div>';
    if (continueBtn) continueBtn.disabled = true;
    if (headerProgress && stepText) {
      const NEXT = 3;
      headerProgress.style.width = ((NEXT/8)*100)+'%';
      stepText.textContent = `Step ${NEXT} of 8`;
    }
  });

  // ---------- Events ----------
  methodBtns.forEach(btn => btn.addEventListener('click', ()=> setMethod(btn.dataset.method)));
  if (detectBtn) detectBtn.addEventListener('click', detectLocation);
  countrySelect.addEventListener('change', onCountryChange);
  stateSelect.addEventListener('change', onStateChange);
  citySelect.addEventListener('change', onCityChange);

  // ---------- Boot ----------
  (async ()=>{
    try {
      updateProgress(2);
      setMethod('manual');
      resetSelect(stateSelect, 'Select your state');
      resetSelect(citySelect,  'Select your city');
      tzInput.value = tz();
      await loadCountries();
    } catch (e) {
      console.error(e);
    }
  })();
})();
</script>
@endpush
