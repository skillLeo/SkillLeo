@extends('tenant.manage.app')

@section('title', 'Personal Information - ' . $user->name)

@section('main')
    {{-- Flash Messages --}}
    @if (session('status'))
        <div class="alert alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <strong>Whoops!</strong> Please fix the following:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1 class="page-title">Personal Information</h1>
            <p class="page-subtitle">Basic details and social links</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-secondary" onclick="importFromLinkedIn()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                </svg>
                Import from LinkedIn
            </button>
        </div>
    </div>

    <form id="personalInfoForm" method="POST" action="{{ route('tenant.profile.update', $username) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Banner Image Section --}}
        <div class="content-section banner-section">
            <div class="section-header">
                <h3 class="section-title">Banner Image</h3>
                <p class="section-desc">Add a cover photo to personalize your profile</p>
            </div>

            <div class="banner-preview-container">
                @php
                    $bannerSrc = $user->banner_url ? ($user->banner_url . '?v=' . $user->banner_version) : null;
                @endphp
                
                @if($bannerSrc)
                    <div class="banner-preview has-banner" id="bannerPreview">
                        <img src="{{ $bannerSrc }}" alt="Banner" id="bannerImg">
                        <div class="banner-overlay">
                            <button type="button" class="btn btn-primary" onclick="openModal('editBannerModal')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Change Banner
                            </button>
                            <button type="button" class="btn btn-danger-outline" onclick="removeBanner()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                @else
                    <div class="banner-preview empty" id="bannerPreview">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <p>No banner image</p>
                        <button type="button" class="btn btn-primary" onclick="openModal('editBannerModal')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            Upload Banner
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Profile Photo --}}
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Profile Photo</h3>
                <p class="section-desc">Your photo helps people recognize you</p>
            </div>

            <div class="photo-upload-area">
                <div class="photo-preview-large" id="photoPreview">
                    @php
                        $avatarSrc = $user->avatar_url ?? null;
                    @endphp
                    @if ($avatarSrc)
                        <img id="avatarImg" src="{{ $avatarSrc }}" alt="{{ $user->name }}"
                        
                        
                        referrerpolicy="no-referrer" crossorigin="anonymous"
                        onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                    @else
                        <svg id="avatarFallback" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    @endif
                </div>
                <div class="photo-actions">
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('avatarInput').click()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Upload Photo
                    </button>
                    @if ($avatarSrc)
                        <button type="button" class="btn btn-danger-outline" onclick="removePhoto()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    @endif
                    <span class="photo-hint">JPG or PNG. Max 5MB. Recommended: 400×400px</span>
                </div>
            </div>
        </div>

        {{-- Basic Information --}}
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Basic Information</h3>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Professional Headline</label>
                    <input type="text" name="headline" class="form-control" maxlength="120" placeholder="e.g., Full Stack Developer | Laravel Expert" value="{{ old('headline', $user->headline) }}">
                    <div class="char-counter">
                        <span id="headlineCount">{{ strlen(old('headline', $user->headline ?? '')) }}</span> / 120
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">About</label>
                    <textarea name="about" class="form-control" rows="5" maxlength="2000" placeholder="Tell us about yourself...">{{ old('about', $user->about) }}</textarea>
                    <div class="char-counter">
                        <span id="aboutCount">{{ strlen(old('about', $user->about ?? '')) }}</span> / 2000
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" placeholder="+1 (555) 000-0000" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Location</label>
                    <div class="location-autocomplete-wrapper">
                        <input type="text" id="locationSearchInput" class="form-control location-search-input" placeholder="Start typing your city..." value="{{ old('location', $user->location) }}" autocomplete="off">
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

                    <input type="hidden" name="city" id="cityHiddenField" value="{{ old('city', $user->city) }}">
                    <input type="hidden" name="state" id="stateHiddenField" value="{{ old('state', $user->state) }}">
                    <input type="hidden" name="country" id="countryHiddenField" value="{{ old('country', $user->country) }}">
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Social Links</h3>
                <p class="section-desc">Connect your professional profiles</p>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0077b5">
                            <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                        </svg>
                        LinkedIn
                    </label>
                    <input type="url" name="linkedin" class="form-control" placeholder="https://linkedin.com/in/yourprofile" value="{{ old('linkedin', $user->profile->social_links['linkedin'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#333">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                        GitHub
                    </label>
                    <input type="url" name="github" class="form-control" value="{{ old('github', $user->profile->social_links['github'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#1DA1F2">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                        Twitter / X
                    </label>
                    <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $user->profile->social_links['twitter'] ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#FF5722">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Website
                    </label>
                    <input type="url" name="website" class="form-control" value="{{ old('website', $user->profile->social_links['website'] ?? '') }}">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                </svg>
                Save Changes
            </button>
        </div>
    </form>
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">Live Preview</h3>
            <p class="inspector-desc">See how your profile looks</p>
        </div>

        <div class="preview-card">
            <div class="preview-avatar" id="previewAvatar">
                @if ($avatarSrc)
                    <img id="previewAvatarImg" src="{{ $avatarSrc }}" alt="{{ $user->name }}"
                      referrerpolicy="no-referrer" crossorigin="anonymous"
        onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';"
                    >
                @else
                    <svg id="previewAvatarFallback" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                @endif
            </div>
            <div class="preview-name" id="previewName">
                {{ trim(old('name', $user->name) . ' ' . old('last_name', $user->last_name)) ?: $user->name }}
            </div>
            <div class="preview-headline" id="previewHeadline">
                {{ old('headline', $user->headline) ?? 'Your headline will appear here' }}
            </div>
            <div class="preview-about" id="previewAbout">
                {{ old('about', $user->about) ?? 'Your about will appear here...' }}
            </div>
        </div>

        <div class="help-section">
            <h4 class="help-title">💡 Quick Tips</h4>
            <ul class="help-list">
                <li>Use a professional headshot</li>
                <li>Write a compelling headline</li>
                <li>Tell your story in the about</li>
                <li>Link your professional profiles</li>
            </ul>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* ============ BANNER SECTION ============ */
.banner-section {
    padding: 24px !important;
}

.banner-preview-container {
    width: 100%;
}

.banner-preview {
    width: 100%;
    height: 240px;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--border);
    transition: all 0.3s ease;
}

.banner-preview.empty {
    flex-direction: column;
    gap: 16px;
}

.banner-preview.empty svg {
    color: var(--text-muted);
    opacity: 0.2;
}

.banner-preview.empty p {
    font-size: 15px;
    color: var(--text-muted);
    margin: 0;
    font-weight: 500;
}

.banner-preview.has-banner {
    border-style: solid;
    border-width: 1px;
}

.banner-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-preview:hover .banner-overlay {
    opacity: 1;
}

/* ============ REST OF STYLES (KEEP EXISTING) ============ */
/* [Previous CSS continues here] */
</style>
@endpush

@push('scripts')
<script>
// Location autocomplete (keep existing)
// Avatar preview (keep existing)
// Character counters (keep existing)

function removeBanner() {
    if (confirm('Remove banner image?')) {
        // Submit form to remove banner
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("tenant.banner.update") }}';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="banner_clear" value="1">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function importFromLinkedIn() {
    alert('LinkedIn import feature coming soon!');
}
</script>
@endpush
@push('styles')
    <style>
        /* ============ LOCATION AUTOCOMPLETE ============ */
        .location-autocomplete-wrapper {
            position: relative;
        }

        .location-search-input.has-selection {
            display: none;
        }

        .location-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-height: 280px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .location-dropdown.show {
            display: block;
        }

        .location-item {
            padding: 10px 14px;
            cursor: pointer;
            transition: background 0.15s ease;
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
            background: var(--apc-bg);
        }

        .location-icon {
            width: 16px;
            height: 16px;
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
            min-width: 0;
        }

        .location-primary {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-body);
        }

        .location-secondary {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .location-loading,
        .location-empty {
            padding: 14px;
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
        }

        .location-selected {
            padding: 10px 14px;
            background: rgba(var(--accent-rgb), 0.08);
            border: 1px solid var(--accent);
            border-radius: 6px;
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
            font-size: 13px;
        }

        .location-clear-btn {
            background: none;
            border: none;
            padding: 4px;
            cursor: pointer;
            color: var(--text-muted);
            transition: color 0.15s ease;
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









        .content-area {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ============ PAGE HEADER ============ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 6px 0;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
            font-weight: 400;
        }

        .page-actions {
            display: flex;
            gap: 8px;
        }

        /* ============ CONTENT SECTIONS ============ */
 

        .section-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 4px 0;
            letter-spacing: -0.01em;
        }

        .section-desc {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* ============ PHOTO UPLOAD (LINKEDIN STYLE) ============ */
        .photo-upload-area {
            display: flex;
            align-items: flex-start;
            gap: 24px;
        }

        .photo-preview-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .photo-preview-large:hover {
            transform: scale(1.02);
            border-color: var(--accent);
        }

        .photo-preview-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-preview-large svg {
            width: 40px;
            height: 40px;
            color: var(--text-muted);
            opacity: 0.4;
        }

        .photo-actions {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .photo-actions .btn {
            width: fit-content;
        }

        .photo-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ============ FORM ELEMENTS (MODERN MINIMAL) ============ */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-grid .full-width {
            grid-column: 1 / -1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-body);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 2px;
        }

        .form-label svg {
            flex-shrink: 0;
            width: 16px;
            height: 16px;
        }

        .required {
            color: #ff5630;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--input-bg);
            color: var(--input-text);
            transition: all 0.15s ease;
        }

        .form-control:hover {
            border-color: rgba(var(--accent-rgb), 0.4);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.6;
        }

        textarea.form-control {
            height: auto;
            min-height: 100px;
            padding: 12px;
            resize: vertical;
            line-height: 1.5;
        }

        .char-counter {
            text-align: right;
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        /* ============ BUTTONS (JIRA/LINEAR STYLE) ============ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 32px;
            padding: 0 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
        }

        .btn svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(var(--accent-rgb), 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: var(--card);
            color: var(--text-body);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--apc-bg);
            border-color: var(--text-muted);
        }

        .btn-danger-outline {
            background: transparent;
            color: #ff5630;
            border: 1px solid #ff5630;
        }

        .btn-danger-outline:hover {
            background: rgba(255, 86, 48, 0.08);
        }

        .btn-lg {
            height: 40px;
            padding: 0 24px;
            font-size: 14px;
        }

        /* ============ FORM ACTIONS ============ */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            margin-top: 32px;
        }

        /* ============ INSPECTOR PANEL (RIGHT SIDEBAR) ============ */


        .inspector-header {
            margin-bottom: 8px;
        }

        .inspector-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 4px 0;
            letter-spacing: -0.01em;
        }

        .inspector-desc {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
        }

        /* ============ PREVIEW CARD (COMPACT & CLEAN) ============ */
        .preview-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .preview-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid var(--border);
        }

        .preview-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-avatar svg {
            width: 24px;
            height: 24px;
            color: var(--text-muted);
            opacity: 0.5;
        }

        .preview-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 4px 0;
            letter-spacing: -0.01em;
        }

        .preview-headline {
            font-size: 13px;
            color: var(--text-body);
            margin: 0 0 12px 0;
            line-height: 1.4;
        }

        .preview-about {
            font-size: 12px;
            line-height: 1.5;
            color: var(--text-muted);
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ============ HELP SECTION (NOTION STYLE) ============ */
        .help-section {
            background: linear-gradient(135deg, #0052CC 0%, #0065FF 100%);
            border-radius: 8px;
            padding: 16px;
            color: white;
        }

        .help-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .help-list li {
            font-size: 12px;
            padding-left: 20px;
            position: relative;
            line-height: 1.4;
            opacity: 0.95;
        }

        .help-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            font-weight: 700;
            opacity: 0.8;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .content-area {
                padding: 20px 16px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .content-section {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .photo-upload-area {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .photo-actions {
                align-items: center;
            }

            .inspector-panel {
                position: static;
            }
        }

        /* ============ DARK MODE SUPPORT ============ */
        @media (prefers-color-scheme: dark) {
            .photo-preview-large {
                background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            }

            .preview-avatar {
                background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            }
        }

        /* ============ SMOOTH ANIMATIONS ============ */
        @media (prefers-reduced-motion: no-preference) {

            .content-section,
            .btn,
            .form-control {
                transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            }
        }

        /* ============ FOCUS VISIBLE ============ */
        .btn:focus-visible,
        .form-control:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }
    </style>
@endpush




@push('scripts')
    <script>
        // ============ LOCATION AUTOCOMPLETE ============
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
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
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
                    items[activeIndex].scrollIntoView({
                        block: 'nearest'
                    });
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

        // Avatar preview
        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').innerHTML =
                    `<img src="${e.target.result}" alt="Preview">`;
                document.getElementById('previewAvatar').innerHTML =
                    `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        });

        function removePhoto() {
            const defaultSvg = `<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
    </svg>`;
            document.getElementById('photoPreview').innerHTML = defaultSvg;
            document.getElementById('previewAvatar').innerHTML = `<svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </svg>`;
            document.getElementById('avatarInput').value = '';
        }

        // Character counters
        ['headline', 'about'].forEach(name => {
            const input = document.querySelector(`[name="${name}"]`);
            const counter = document.getElementById(`${name}Count`);
            if (input && counter) {
                counter.textContent = input.value.length;
                input.addEventListener('input', () => counter.textContent = input.value.length);
            }
        });

        function importFromLinkedIn() {
            alert('LinkedIn import feature coming soon!');
        }
    </script>
@endpush
