@props(['user' => null])

<x-modals.edits.base-modal id="editProfileModal" title="Edit Profile" size="lg">
    <form id="profileForm" method="POST" action="{{ route('tenant.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Profile Photo --}}
        <div class="modal-section photo-section">
            <div class="photo-upload-wrap">
                <div class="photo-preview-large" id="photoPreviewLarge">
                    @if(($user->avatar ?? false) || ($user->avatar_url ?? false))
                        <img src="{{ $user->avatar ?? $user->avatar_url }}" alt="{{ $user->name ?? 'User' }}"
                        referrerpolicy="no-referrer"
                        crossorigin="anonymous"
                        onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';"
                    
                        >
                    @else
                        <i class="fa-solid fa-user" style="font-size: 40px; color: var(--text-muted);"></i>
                    @endif
                </div>
                <div class="photo-actions">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" hidden>
                    <button type="button" class="btn-photo-upload" onclick="document.getElementById('avatarInput').click()">
                        <i class="fa-solid fa-camera"></i>
                        Upload Photo
                    </button>
                    @if(($user->avatar ?? false) || ($user->avatar_url ?? false))
                        <button type="button" class="btn-photo-remove" onclick="removeAvatar()">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    @endif
                </div>
                <p class="photo-hint">JPG or PNG. Max 5MB.</p>
            </div>
        </div>

        {{-- Basic Info --}}
        <div class="modal-section">
            <h3 class="section-title">Basic Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" class="form-input" value="{{ $user->first_name ?? '' }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-input" value="{{ $user->last_name ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Headline</label>
                <textarea name="headline" class="form-textarea" rows="2" maxlength="120" placeholder="e.g., Full Stack Developer | Laravel Expert">{{ $user->headline ?? '' }}</textarea>
                <div class="char-count"><span id="headlineCount">0</span> / 120</div>
            </div>

            <div class="form-group">
                <label class="form-label">About</label>
                <textarea name="about" class="form-textarea" rows="6" maxlength="2000" placeholder="Tell us about yourself...">{{ $user->bio ?? '' }}</textarea>
                <div class="char-count"><span id="aboutCount">0</span> / 2000</div>
            </div>
        </div>

        {{-- Location & Contact --}}
        <div class="modal-section">
            <h3 class="section-title">Location & Contact</h3>
            
            {{-- Location Autocomplete --}}
            <div class="form-group">
                <label class="form-label">Location</label>
                <div class="location-autocomplete-wrapper">
                    <input 
                        type="text" 
                        id="locationSearchInput" 
                        class="form-input location-search-input"
                        placeholder="Start typing your city (e.g., Sargodha, Punjab, Pakistan)"
                        value="{{ $user->location ?? '' }}"
                        autocomplete="off"
                    />
                    <div class="location-dropdown" id="locationDropdown"></div>
                    
                    <div class="location-selected" id="locationSelected" style="display:none;">
                        <div class="location-selected-content">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span id="locationSelectedText"></span>
                        </div>
                        <button type="button" class="location-clear-btn" onclick="clearLocationSelection()">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                {{-- Hidden fields for form submission --}}
                <input type="hidden" name="city" id="cityHiddenField" value="{{ $user->city ?? '' }}">
                <input type="hidden" name="state" id="stateHiddenField" value="{{ $user->state ?? '' }}">
                <input type="hidden" name="country" id="countryHiddenField" value="{{ $user->country ?? '' }}">
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-input" value="{{ $user->email ?? '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-input" value="{{ $user->phone ?? '' }}" placeholder="+1 (555) 000-0000">
            </div>
        </div>

        {{-- Social Links --}}
        <div class="modal-section">
            <h3 class="section-title">Social Links</h3>
            
            @php
                $socialLinks = $user->profile->social_links ?? [];
            @endphp

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-linkedin"></i> LinkedIn</label>
                <input type="url" name="linkedin" class="form-input" value="{{ $user->linkedin ?? '' }}" placeholder="https://linkedin.com/in/yourprofile">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-x-twitter"></i> Twitter</label>
                <input type="url" name="twitter" class="form-input" value="{{ $user->twitter ?? '' }}" placeholder="https://twitter.com/yourhandle">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-facebook"></i> Facebook</label>
                <input type="url" name="facebook" class="form-input" value="{{ $user->facebook ?? '' }}" placeholder="https://facebook.com/yourprofile">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-instagram"></i> Instagram</label>
                <input type="url" name="instagram" class="form-input" value="{{ $user->instagram ?? '' }}" placeholder="https://instagram.com/yourhandle">
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editProfileModal')">Cancel</button>
        <button type="submit" form="profileForm" class="btn-modal btn-save">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<style>
.modal-section {
    margin-bottom: 28px;
    padding-bottom: 28px;
    border-bottom: 1px solid var(--border);
}

.modal-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 16px;
}

.photo-section {
    background: var(--apc-bg);
    padding: 20px;
    border-radius: 8px;
    border-bottom: none;
    margin-bottom: 24px;
}

.photo-upload-wrap {
    display: flex;
    align-items: center;
    gap: 20px;
}

.photo-preview-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--card);
    border: 3px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.photo-preview-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-photo-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-photo-upload:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.btn-photo-remove {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    color: #dc2626;
    border: 1px solid #dc2626;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-photo-remove:hover {
    background: #dc2626;
    color: white;
}

.photo-hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-body);
    margin-bottom: 6px;
}

.form-label i {
    margin-right: 6px;
    color: var(--text-muted);
}

.required {
    color: #dc2626;
    margin-left: 2px;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--input-border);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    background: var(--input-bg);
    color: var(--input-text);
    transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    resize: vertical;
    line-height: 1.6;
}

.char-count {
    text-align: right;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Location Autocomplete Styles */
.location-autocomplete-wrapper {
    position: relative;
}

.location-search-input.has-selection {
    display: none;
}

.location-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 4px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-height: 280px;
    overflow-y: auto;
    z-index: 10000;
    display: none;
}

.location-dropdown.show {
    display: block;
}

.location-item {
    padding: 10px 14px;
    cursor: pointer;
    transition: background 0.2s ease;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.location-item:last-child {
    border-bottom: none;
}

.location-item:hover,
.location-item.active {
    background: var(--accent-light);
}

.location-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.location-icon.city {
    color: var(--accent);
}

.location-icon.state {
    color: #8b5cf6;
}

.location-icon.country {
    color: #f59e0b;
}

.location-text {
    flex: 1;
}

.location-primary {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-body);
}

.location-secondary {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
}

.location-loading,
.location-empty {
    padding: 14px;
    text-align: center;
    color: var(--text-muted);
    font-size: 13px;
}

.location-selected {
    padding: 10px 14px;
    background: var(--accent-light);
    border: 1.5px solid var(--accent);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.location-selected-content {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--accent);
    font-weight: 500;
    font-size: 14px;
}

.location-clear-btn {
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: var(--text-muted);
    transition: color 0.2s ease;
    display: flex;
    align-items: center;
}

.location-clear-btn:hover {
    color: var(--accent);
}

.location-dropdown::-webkit-scrollbar {
    width: 6px;
}

.location-dropdown::-webkit-scrollbar-track {
    background: var(--bg);
}

.location-dropdown::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

@media (max-width: 640px) {
    .photo-upload-wrap {
        flex-direction: column;
        text-align: center;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .location-dropdown {
        max-height: 200px;
    }
}
</style>

<script>
// ============ Location Autocomplete Logic ============
(function() {
    const searchInput = document.getElementById('locationSearchInput');
    const dropdown = document.getElementById('locationDropdown');
    const selectedDiv = document.getElementById('locationSelected');
    const selectedText = document.getElementById('locationSelectedText');
    
    const cityField = document.getElementById('cityHiddenField');
    const stateField = document.getElementById('stateHiddenField');
    const countryField = document.getElementById('countryHiddenField');
    
    let debounceTimer;
    let activeIndex = -1;
    let currentResults = [];

    const icons = {
        city: '<svg class="location-icon city" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>',
        state: '<svg class="location-icon state" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
        country: '<svg class="location-icon country" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>'
    };

    async function searchLocations(query) {
        if (query.length < 2) return [];
        
        try {
            const response = await fetch(`/api/location/search?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (!response.ok) throw new Error('Search failed');
            return await response.json();
        } catch (error) {
            console.error('Location search error:', error);
            return [];
        }
    }

    function renderDropdown(results) {
        currentResults = results;
        
        if (!results || results.length === 0) {
            dropdown.innerHTML = '<div class="location-empty">No locations found</div>';
            dropdown.classList.add('show');
            return;
        }

        const html = results.map((item, index) => `
            <div class="location-item" data-index="${index}">
                ${icons[item.type]}
                <div class="location-text">
                    <div class="location-primary">${escapeHtml(item.display)}</div>
                    ${item.type === 'city' ? `<div class="location-secondary">${escapeHtml(item.country)}</div>` : ''}
                </div>
            </div>
        `).join('');

        dropdown.innerHTML = html;
        dropdown.classList.add('show');
        activeIndex = -1;

        dropdown.querySelectorAll('.location-item').forEach((item, index) => {
            item.addEventListener('click', () => selectLocation(results[index]));
        });
    }

    function selectLocation(location) {
        // Only accept complete city locations
        if (location.type !== 'city') {
            alert('Please select a complete city location');
            return;
        }

        cityField.value = location.city || '';
        stateField.value = location.state || '';
        countryField.value = location.country || '';
        
        selectedText.textContent = location.full_display;
        searchInput.classList.add('has-selection');
        selectedDiv.style.display = 'flex';
        dropdown.classList.remove('show');
    }

    window.clearLocationSelection = function() {
        cityField.value = '';
        stateField.value = '';
        countryField.value = '';
        
        searchInput.classList.remove('has-selection');
        selectedDiv.style.display = 'none';
        searchInput.value = '';
        searchInput.focus();
    };

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            dropdown.classList.remove('show');
            return;
        }
        
        dropdown.innerHTML = '<div class="location-loading">Searching...</div>';
        dropdown.classList.add('show');
        
        debounceTimer = setTimeout(async () => {
            const results = await searchLocations(query);
            renderDropdown(results);
        }, 300);
    });

    searchInput.addEventListener('keydown', (e) => {
        const items = dropdown.querySelectorAll('.location-item');
        
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

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.location-autocomplete-wrapper')) {
            dropdown.classList.remove('show');
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize: If location exists, show selected state
    if (cityField.value && stateField.value && countryField.value) {
        selectedText.textContent = `${cityField.value}, ${stateField.value}, ${countryField.value}`;
        searchInput.classList.add('has-selection');
        selectedDiv.style.display = 'flex';
    }
})();

// ============ Other Existing Functions ============

// Avatar preview
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photoPreviewLarge');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});

function removeAvatar() {
    const preview = document.getElementById('photoPreviewLarge');
    preview.innerHTML = '<i class="fa-solid fa-user" style="font-size: 40px; color: var(--text-muted);"></i>';
    document.getElementById('avatarInput').value = '';
}

// Character counters
document.querySelector('[name="headline"]')?.addEventListener('input', function(e) {
    document.getElementById('headlineCount').textContent = e.target.value.length;
});

document.querySelector('[name="about"]')?.addEventListener('input', function(e) {
    document.getElementById('aboutCount').textContent = e.target.value.length;
});

// Initialize counters
document.addEventListener('DOMContentLoaded', function() {
    const headline = document.querySelector('[name="headline"]');
    const about = document.querySelector('[name="about"]');
    
    if (headline) document.getElementById('headlineCount').textContent = headline.value.length;
    if (about) document.getElementById('aboutCount').textContent = about.value.length;
});
</script>