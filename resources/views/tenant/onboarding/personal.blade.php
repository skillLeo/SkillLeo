@extends('layouts.onboarding')

@section('title', 'Personal Information - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="1"
    title="What should we call you?"
    subtitle="Tell us your name and we'll create your professional profile"
/>

<form id="personalForm" action="{{ route('tenant.onboarding.personal.store') }}" method="POST">
    @csrf

    <div class="input-row">
        <x-onboarding.input
            label="First Name"
            name="name"
            id="firstName"
            placeholder="John"
            required
            autocomplete="given-name"
        />

        <x-onboarding.input
            label="Last Name"
            name="last_name"
            id="lastName"
            placeholder="Smith"
            required
            autocomplete="family-name"
        />
    </div>

    <x-onboarding.username-input
        label="Choose your username"
        placeholder="john-smith-2024"
    />

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.location') }}"
        backUrl="{{ route('tenant.onboarding.welcome') }}"
    />
</form>

@endsection

@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => {
    // DOM Elements
    const form = document.getElementById('personalForm');
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const username = document.getElementById('username');
    const statusEl = document.getElementById('usernameStatus');
    const previewWrap = document.getElementById('usernamePreview');
    const previewUsername = document.getElementById('previewUsername');
    const successEl = document.getElementById('usernameSuccess');
    const regenBtn = document.getElementById('regenBtn');
    const continueBtn = document.getElementById('continueBtn') || form.querySelector('button[type="submit"]');
    const btnText = document.getElementById('btnText');
  
    // State Management
    let userManuallyEdited = false;
    let requestCounter = 0;
    let debounceTimer = null;
    let lastCheckedUsername = '';
    let isChecking = false;
  
    // Utility: Slugify string for username format
    const slugify = (str) => {
      return (str || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9_-]+/g, '-')
        .replace(/^[-_]+|[-_]+$/g, '')
        .replace(/--+/g, '-')
        .slice(0, 50);
    };
  
    // Generate base username from first and last name
    const generateBaseUsername = () => {
      const first = slugify(firstName.value.trim());
      const last = slugify(lastName.value.trim());
      
      if (first && last) {
        return `${first}-${last}`;
      } else if (first) {
        return first;
      } else if (last) {
        return last;
      }
      return '';
    };
  
    // Generate username candidates with priority order
    const generateCandidates = (base) => {
      if (!base) return [];
      
      const year = new Date().getFullYear();
      const candidates = [
        base,
        `${base}-${year}`,
        `${base}-${randomInt(10, 99)}`,
        `${base}-${randomInt(100, 999)}`,
        `${base}-${randomInt(1000, 9999)}`
      ];
  
      // Add initial-based variant (e.g., j-smith)
      const first = slugify(firstName.value.trim());
      const last = slugify(lastName.value.trim());
      if (first && last && first.length > 0) {
        candidates.splice(2, 0, `${first[0]}-${last}`);
      }
  
      return [...new Set(candidates.map(slugify).filter(c => c.length >= 3))];
    };
  
    // Random integer helper
    const randomInt = (min, max) => {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    };
  
    // UI State Management
    const setUIState = (text, type) => {
      statusEl.textContent = text || '';
      statusEl.dataset.type = type || '';
      
      username.classList.toggle('is-valid', type === 'ok');
      username.classList.toggle('is-invalid', type === 'error');
      
      if (successEl) {
        successEl.classList.toggle('show', type === 'ok');
      }
      
      if (previewWrap && previewUsername) {
        previewWrap.classList.toggle('show', type === 'ok');
      }
      
      if (continueBtn) {
        continueBtn.disabled = type !== 'ok';
      }
  
      // Reset regenerate button to default state
      if (regenBtn && type !== 'error') {
        regenBtn.textContent = 'Regenerate';
        regenBtn.classList.remove('is-suggestion');
        regenBtn.onclick = handleRegenerate;
      }
    };
  
    // Update preview URL
    const updateUsernamePreview = (usernameValue) => {
      if (previewUsername) {
        previewUsername.textContent = usernameValue || '';
      }
    };
  
    // Check username availability via API
    const checkUsernameAvailability = async (usernameValue) => {
      if (!usernameValue || usernameValue.length < 3) {
        setUIState('Username must be at least 3 characters', 'error');
        return { status: 'invalid' };
      }
  
      // Avoid duplicate checks
      if (usernameValue === lastCheckedUsername && !isChecking) {
        return { status: statusEl.dataset.type === 'ok' ? 'available' : 'invalid' };
      }
  
      lastCheckedUsername = usernameValue;
      const currentRequest = ++requestCounter;
      isChecking = true;
  
      setUIState('Checking availability…', 'loading');
  
      try {
        const response = await fetch(
          `{{ route('api.username.check') }}?username=${encodeURIComponent(usernameValue)}`,
          {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
          }
        );
  
        // Ignore stale responses
        if (currentRequest !== requestCounter) {
          return { status: 'stale' };
        }
  
        const data = await response.json();
  
        if (data.status === 'available') {
          setUIState('Username is available ✓', 'ok');
          updateUsernamePreview(usernameValue);
          return { status: 'available', username: usernameValue };
        }
  
        if (data.status === 'self') {
          setUIState('This is already your username ✓', 'ok');
          updateUsernamePreview(usernameValue);
          return { status: 'available', username: usernameValue };
        }
  
        if (data.status === 'taken') {
          setUIState('This username is taken', 'error');
          if (data.suggestion) {
            showSuggestionButton(data.suggestion);
          }
          return { status: 'taken', suggestion: data.suggestion };
        }
  
        setUIState(data.error || 'Invalid username format', 'error');
        return { status: 'invalid' };
  
      } catch (error) {
        if (currentRequest !== requestCounter) {
          return { status: 'stale' };
        }
        setUIState('Connection error. Please try again.', 'error');
        return { status: 'error' };
      } finally {
        isChecking = false;
      }
    };
  
    // Find first available username from candidates
    const findAvailableUsername = async (base) => {
      const candidates = generateCandidates(base);
      
      for (const candidate of candidates) {
        const result = await checkUsernameAvailability(candidate);
        if (result.status === 'available') {
          return candidate;
        }
      }
      
      return null;
    };
  
    // Show suggestion button for taken usernames
    const showSuggestionButton = (suggestion) => {
      if (regenBtn && suggestion) {
        regenBtn.textContent = `Use "${suggestion}"`;
        regenBtn.classList.add('is-suggestion');
        regenBtn.onclick = (e) => {
          e.preventDefault();
          userManuallyEdited = true;
          username.value = suggestion;
          checkUsernameWithDebounce();
        };
      }
    };
  
    // Handle regenerate button click
    const handleRegenerate = async (e) => {
      e.preventDefault();
      
      const base = generateBaseUsername() || slugify(username.value) || 'user';
      const randomizedBase = `${base}-${randomInt(10, 99)}`;
      
      const available = await findAvailableUsername(randomizedBase);
      
      if (available) {
        userManuallyEdited = true;
        username.value = available;
        await checkUsernameAvailability(available);
      } else {
        setUIState('No available usernames found. Try different names.', 'error');
      }
    };
  
    // Debounced username check
    const checkUsernameWithDebounce = () => {
      clearTimeout(debounceTimer);
      
      const sluggedValue = slugify(username.value);
      username.value = sluggedValue;
  
      if (!sluggedValue) {
        setUIState('', '');
        return;
      }
  
      if (sluggedValue.length < 3) {
        setUIState('Username must be at least 3 characters', 'error');
        return;
      }
  
      debounceTimer = setTimeout(() => {
        checkUsernameAvailability(sluggedValue);
      }, 400);
    };
  
    // Auto-suggest username based on first/last name
    const autoSuggestUsername = async () => {
      // Only auto-suggest if user hasn't manually edited the username
      if (userManuallyEdited) return;
  
      const base = generateBaseUsername();
      if (!base) {
        username.value = '';
        setUIState('', '');
        return;
      }
  
      // Immediately set the base username for instant feedback
      username.value = base;
  
      // Check if it's available
      const result = await checkUsernameAvailability(base);
  
      // If taken, automatically find and set an available alternative
      if (result.status === 'taken') {
        const available = await findAvailableUsername(base);
        if (available) {
          username.value = available;
          await checkUsernameAvailability(available);
        }
      }
    };
  
    // Event Listeners
    firstName.addEventListener('input', () => {
      autoSuggestUsername();
    });
  
    lastName.addEventListener('input', () => {
      autoSuggestUsername();
    });
  
    username.addEventListener('input', () => {
      userManuallyEdited = true;
      checkUsernameWithDebounce();
    });
  
    username.addEventListener('blur', () => {
      if (username.value) {
        checkUsernameWithDebounce();
      }
    });
  
    if (regenBtn) {
      regenBtn.addEventListener('click', handleRegenerate);
    }
  
    // Form submission
    form.addEventListener('submit', (e) => {
      if (continueBtn && continueBtn.disabled) {
        e.preventDefault();
        setUIState('Please wait for username validation', 'error');
        return;
      }
  
      if (btnText) {
        btnText.innerHTML = '<span class="loading-spinner"></span>';
      }
      if (continueBtn) {
        continueBtn.disabled = true;
      }
    });
  
    // Initialize: check existing username or auto-suggest
    if (username.value.trim()) {
      userManuallyEdited = true;
      checkUsernameWithDebounce();
    } else {
      autoSuggestUsername();
    }
  });</script>
@endpush
