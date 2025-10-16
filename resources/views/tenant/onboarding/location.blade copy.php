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
        <label class="form-label" for="locationInput">City, State, or Country</label>
        <div class="autocomplete-wrapper">
            <input 
                type="text" 
                id="locationInput" 
                class="form-control location-input"
                placeholder="Start typing (e.g., Sargodha, Punjab, Pakistan)"
                autocomplete="off"
                required
            />
            <div class="autocomplete-dropdown" id="autocompleteDropdown"></div>
            <div class="selected-location" id="selectedLocation" style="display:none;">
                <div class="selected-content">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span id="selectedText"></span>
                </div>
                <button type="button" class="clear-btn" id="clearBtn" aria-label="Clear selection">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <input type="hidden" name="city" id="cityField">
    <input type="hidden" name="state" id="stateField">
    <input type="hidden" name="country" id="countryField">
    <input type="hidden" name="timezone" id="timezoneField">
    <input type="hidden" name="source" value="search">

    <x-onboarding.form-footer 
        skipUrl="{{ route('tenant.onboarding.education') }}"
        backUrl="{{ route('tenant.onboarding.personal') }}"
    />
</form>

@endsection

@push('styles')
<style>
.autocomplete-wrapper {
    position: relative;
}

.location-input {
    width: 100%;
    padding: 12px 16px;
    font-size: 15px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--card);
    color: var(--text-body);
    transition: all var(--transition-base);
}

.location-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.location-input.has-selection {
    display: none;
}

.autocomplete-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 4px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-height: 320px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.autocomplete-dropdown.show {
    display: block;
}

.autocomplete-item {
    padding: 12px 16px;
    cursor: pointer;
    transition: background var(--transition-base);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 12px;
}

.autocomplete-item:last-child {
    border-bottom: none;
}

.autocomplete-item:hover,
.autocomplete-item.active {
    background: var(--accent-light);
}

.autocomplete-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    color: var(--text-subtle);
}

.autocomplete-icon.city {
    color: var(--accent);
}

.autocomplete-icon.state {
    color: #8b5cf6;
}

.autocomplete-icon.country {
    color: #f59e0b;
}

.autocomplete-text {
    flex: 1;
}

.autocomplete-primary {
    font-size: 15px;
    font-weight: 500;
    color: var(--text-body);
    margin-bottom: 2px;
}

.autocomplete-secondary {
    font-size: 13px;
    color: var(--text-subtle);
}

.autocomplete-loading,
.autocomplete-empty {
    padding: 16px;
    text-align: center;
    color: var(--text-subtle);
    font-size: 14px;
}

.selected-location {
    padding: 12px 16px;
    background: var(--accent-light);
    border: 1px solid var(--accent);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.selected-content {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--accent);
    font-weight: 500;
}

.clear-btn {
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: var(--text-subtle);
    transition: color var(--transition-base);
    display: flex;
    align-items: center;
}

.clear-btn:hover {
    color: var(--accent);
}

/* Scrollbar styling */
.autocomplete-dropdown::-webkit-scrollbar {
    width: 8px;
}

.autocomplete-dropdown::-webkit-scrollbar-track {
    background: var(--bg);
}

.autocomplete-dropdown::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}

.autocomplete-dropdown::-webkit-scrollbar-thumb:hover {
    background: var(--text-subtle);
}

@media (max-width: 640px) {
    .autocomplete-dropdown {
        max-height: 240px;
    }
}
</style>
@endpush

@push('scripts')
<script>
(() => {
  "use strict";

  // DOM Elements
  const form = document.getElementById('locationForm');
  const input = document.getElementById('locationInput');
  const dropdown = document.getElementById('autocompleteDropdown');
  const selectedDiv = document.getElementById('selectedLocation');
  const selectedText = document.getElementById('selectedText');
  const clearBtn = document.getElementById('clearBtn');
  
  const cityField = document.getElementById('cityField');
  const stateField = document.getElementById('stateField');
  const countryField = document.getElementById('countryField');
  const timezoneField = document.getElementById('timezoneField');
  
  const continueBtn = document.getElementById('continueBtn');

  // State
  let debounceTimer;
  let selectedLocation = null;
  let activeIndex = -1;

  // Icons
  const icons = {
    city: '<svg class="autocomplete-icon city" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>',
    state: '<svg class="autocomplete-icon state" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
    country: '<svg class="autocomplete-icon country" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>'
  };

  // API Search
  async function searchLocation(query) {
    if (query.length < 2) return [];
    
    try {
      const response = await fetch(`/api/location/search?q=${encodeURIComponent(query)}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      if (!response.ok) throw new Error('Search failed');
      return await response.json();
    } catch (error) {
      console.error('Search error:', error);
      return [];
    }
  }

  // Render dropdown
  function renderDropdown(results) {
    if (!results || results.length === 0) {
      dropdown.innerHTML = '<div class="autocomplete-empty">No locations found</div>';
      dropdown.classList.add('show');
      return;
    }

    const html = results.map((item, index) => `
      <div class="autocomplete-item" data-index="${index}">
        ${icons[item.type]}
        <div class="autocomplete-text">
          <div class="autocomplete-primary">${escapeHtml(item.display)}</div>
          ${item.type === 'city' ? `<div class="autocomplete-secondary">${escapeHtml(item.country)}</div>` : ''}
        </div>
      </div>
    `).join('');

    dropdown.innerHTML = html;
    dropdown.classList.add('show');
    activeIndex = -1;

    // Add click handlers
    dropdown.querySelectorAll('.autocomplete-item').forEach((item, index) => {
      item.addEventListener('click', () => selectLocation(results[index]));
    });
  }

  // Select location
  function selectLocation(location) {
    selectedLocation = location;
    
    // Update hidden fields
    cityField.value = location.city || '';
    stateField.value = location.state || '';
    countryField.value = location.country || '';
    timezoneField.value = getTimezone();
    
    // Update UI
    selectedText.textContent = location.full_display;
    input.classList.add('has-selection');
    selectedDiv.style.display = 'flex';
    dropdown.classList.remove('show');
    
    // Enable submit
    if (continueBtn) continueBtn.disabled = false;
  }

  // Clear selection
  function clearSelection() {
    selectedLocation = null;
    cityField.value = '';
    stateField.value = '';
    countryField.value = '';
    
    input.classList.remove('has-selection');
    selectedDiv.style.display = 'none';
    input.value = '';
    input.focus();
    
    if (continueBtn) continueBtn.disabled = true;
  }

  // Input handler with debounce
  input.addEventListener('input', (e) => {
    const query = e.target.value.trim();
    
    clearTimeout(debounceTimer);
    
    if (query.length < 2) {
      dropdown.classList.remove('show');
      return;
    }
    
    dropdown.innerHTML = '<div class="autocomplete-loading">Searching...</div>';
    dropdown.classList.add('show');
    
    debounceTimer = setTimeout(async () => {
      const results = await searchLocation(query);
      renderDropdown(results);
    }, 300);
  });

  // Keyboard navigation
  input.addEventListener('keydown', (e) => {
    const items = dropdown.querySelectorAll('.autocomplete-item');
    
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = Math.min(activeIndex + 1, items.length - 1);
      updateActiveItem(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = Math.max(activeIndex - 1, -1);
      updateActiveItem(items);
    } else if (e.key === 'Enter' && activeIndex >= 0) {
      e.preventDefault();
      items[activeIndex]?.click();
    } else if (e.key === 'Escape') {
      dropdown.classList.remove('show');
    }
  });

  function updateActiveItem(items) {
    items.forEach((item, index) => {
      item.classList.toggle('active', index === activeIndex);
    });
    
    if (activeIndex >= 0 && items[activeIndex]) {
      items[activeIndex].scrollIntoView({ block: 'nearest' });
    }
  }

  // Click outside to close
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.autocomplete-wrapper')) {
      dropdown.classList.remove('show');
    }
  });

  // Clear button
  clearBtn.addEventListener('click', clearSelection);

  // Form submit
  form.addEventListener('submit', (e) => {
    if (!selectedLocation) {
      e.preventDefault();
      alert('Please select a location from the dropdown');
      return;
    }
    
    if (continueBtn) {
      continueBtn.disabled = true;
      const btnText = continueBtn.querySelector('#btnText');
      if (btnText) btnText.innerHTML = '<div class="loading-spinner"></div>';
    }
  });

  // Utilities
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function getTimezone() {
    try {
      return Intl.DateTimeFormat().resolvedOptions().timeZone;
    } catch {
      return 'UTC';
    }
  }

  // Initialize
  timezoneField.value = getTimezone();
  if (continueBtn) continueBtn.disabled = true;
})();
</script>
@endpush