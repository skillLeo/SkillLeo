<section class="hero-merged">
    {{-- Edit Profile Button --}}
    <button class="edit-card icon-btn edit-profile-btn" aria-label="Edit card">
        <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
    </button>

    <div class="photo-wrap">
        <div class="photo-ring">
            <div class="photo-circle">
                @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    <x-ui.icon name="user" size="lg" color="secondary" class="mb-2" />
                    Upload your<br>profile photo
                @endif
                
                {{-- Professional Desktop Status Badge --}}
                @php
                    $isOnline = false;
                    $lastSeenText = 'Offline';
                    
                    if (isset($user->last_seen_at)) {
                        $lastSeenTime = \Carbon\Carbon::parse($user->last_seen_at);
                        $now = \Carbon\Carbon::now();
                        $diffInMinutes = $now->diffInMinutes($lastSeenTime);
                        
                        if ($diffInMinutes < 1) {
                            $isOnline = true;
                            $lastSeenText = 'Active now';
                        } elseif ($diffInMinutes < 60) {
                            $lastSeenText = 'Active ' . $diffInMinutes . 'm ago';
                        } elseif ($diffInMinutes < 1440) {
                            $hours = floor($diffInMinutes / 60);
                            $lastSeenText = 'Active ' . $hours . 'h ago';
                        } elseif ($diffInMinutes < 10080) {
                            $days = floor($diffInMinutes / 1440);
                            $lastSeenText = $days . 'd ago';
                        } else {
                            $lastSeenText = 'Last seen ' . $lastSeenTime->format('M d');
                        }
                    }
                @endphp
                
                <span class="desktop-status-badge {{ $isOnline ? 'online' : 'offline' }}" 
                      title="{{ $lastSeenText }}">
                    @if($isOnline)
                        <span class="pulse-ring"></span>
                    @endif
                </span>
            </div>
        </div>
        
        {{-- Status Text Below Avatar --}}
        @if(isset($user->last_seen_at))
            <div class="desktop-status-text">
                @if($isOnline)
                    <span class="status-indicator online">
                        <span class="status-dot"></span>
                        Active now
                    </span>
                @else
                    <span class="status-indicator offline">{{ $lastSeenText }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="name-row">
        <h2 class="name">{{ $user->name }}</h2>
    </div>

    <div class="stack">
        {{ implode(' · ', $user->skills ?? ['PHP', 'Laravel', 'React']) }}
    </div>

    <div class="loc">
        <x-ui.icon name="location" size="xs" color="secondary" />
        {{ $user->location ?? 'Location not specified' }}
    </div>

    <div class="hr"></div>

    <div class="about-row">
        <span class="label">About:</span>
        <x-ui.icon name="about" size="xs" color="secondary" class="hover-lift clickable" />
    </div>

    <p class="about-text">
        {{ Illuminate\Support\Str::limit($user->about, 110) }}
        @if (Illuminate\Support\Str::length($user->about) > 110)
            <a href="#" class="see-more-inline">
                See more
            </a>
        @endif
    </p>

    <div class="hr2"></div>

    <div class="cta">
        <x-ui.button variant="solid" shape="square" color="primary" size="sm" class="btn-chat">
            <x-ui.icon name="message" variant="outlined" size="sm" />
            Chat!
        </x-ui.button>

        <x-ui.button variant="outlined" shape="square" color="primary" size="sm" class="btn-follow">
            <x-ui.icon name="user-plus" size="sm" variant="outlined" />
            Follow
        </x-ui.button>

        <button class="menu-kebab icon-btn" id="desktopMenuBtn" aria-label="More options">
            <x-ui.icon name="more-vertical" variant="outlined" size="lg" class="color-muted" />
        </button>
    </div>

    <div class="socials">
        @if ($user->facebook)
            <a href="{{ $user->facebook }}" aria-label="Facebook" class="social-link">
                <x-ui.icon name="facebook" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->instagram)
            <a href="{{ $user->instagram }}" aria-label="Instagram" class="social-link">
                <x-ui.icon name="instagram" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->twitter)
            <a href="{{ $user->twitter }}" aria-label="Twitter" class="social-link">
                <x-ui.icon name="twitter" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->linkedin)
            <a href="{{ $user->linkedin }}" aria-label="LinkedIn" class="social-link">
                <x-ui.icon name="linkedin" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif

        <a href="#" class="social-link" aria-label="Download CV">
            <x-ui.icon name="download" size="sm" color="secondary" class="hover-lift" />
        </a>
    </div>
</section>

{{-- Desktop Dropdown Menu --}}
<div class="desktop-dropdown" id="desktopDropdown">
    <button class="desktop-dropdown-item">
        <x-ui.icon name="edit" size="sm" color="secondary" />
        <span>Edit Profile</span>
    </button>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="eye" size="sm" color="secondary" />
        <span>View as Visitor</span>
    </button>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="download" size="sm" color="secondary" />
        <span>Download CV</span>
    </button>
    <div class="desktop-dropdown-divider"></div>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="share" size="sm" color="secondary" />
        <span>Share Profile</span>
    </button>
    <button class="desktop-dropdown-item danger">
        <x-ui.icon name="flag" size="sm" color="secondary" />
        <span>Report</span>
    </button>
</div>


    <style>
        .cta {
            display: grid;
            grid-template-columns: 40% 40% 10%;
            gap: 8px;
            align-items: center;
            margin-top: 16px;
        }

        .cta .menu-kebab {
            width: 100%;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.18s ease;
            border: none;
        }

        .cta .menu-kebab:hover {
            background: var(--apc-bg);
        }

        .cta .menu-kebab .ui-icon {
            width: 18px !important;
            height: 18px !important;
        }

        .cta .btn-chat,
        .cta .btn-follow {
            width: 100%;
        }

        @media (max-width: 768px) {
            .cta {
                grid-template-columns: 1fr 1fr 60px;
                gap: 6px;
            }
        }
    </style>

    <div class="socials">
        @if ($user->facebook)
            <a href="{{ $user->facebook }}" aria-label="Facebook" class="social-link">
                <x-ui.icon name="facebook" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->instagram)
            <a href="{{ $user->instagram }}" aria-label="Instagram" class="social-link">
                <x-ui.icon name="instagram" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->twitter)
            <a href="{{ $user->twitter }}" aria-label="Twitter" class="social-link">
                <x-ui.icon name="twitter" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->linkedin)
            <a href="{{ $user->linkedin }}" aria-label="LinkedIn" class="social-link">
                <x-ui.icon name="linkedin" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif

        <a href="#" class="social-link" aria-label="Download CV">
            <x-ui.icon name="download" size="sm" color="secondary" class="hover-lift" />
        </a>
    </div>
</section>

{{-- Desktop Dropdown Menu (Outside section) --}}
<div class="desktop-dropdown" id="desktopDropdown">
    <button class="desktop-dropdown-item">
        <x-ui.icon name="edit" size="sm" color="secondary" />
        <span>Edit Profile</span>
    </button>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="eye" size="sm" color="secondary" />
        <span>View as Visitor</span>
    </button>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="download" size="sm" color="secondary" />
        <span>Download CV</span>
    </button>
    <div class="desktop-dropdown-divider"></div>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="share" size="sm" color="secondary" />
        <span>Share Profile</span>
    </button>
    <button class="desktop-dropdown-item danger">
        <x-ui.icon name="flag" size="sm" color="secondary" />
        <span>Report</span>
    </button>
</div>

<style>
    .desktop-dropdown {
        display: none;
        position: fixed;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        min-width: 220px;
        max-width: 240px;
        z-index: 999999;
        animation: slideDown 0.2s ease;
        overflow: hidden;
    }

    .desktop-dropdown.active {
        display: block;
    }

    .desktop-dropdown-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: transparent;
        border: none;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: background 0.2s ease;
        text-align: left;
        font-family: inherit;
    }

    .desktop-dropdown-item:hover {
        background: var(--apc-bg);
    }

    .desktop-dropdown-item .ui-icon {
        flex-shrink: 0;
    }

    .desktop-dropdown-item.danger {
        color: var(--error);
    }

    .desktop-dropdown-item.danger .ui-icon {
        color: var(--error);
    }

    .desktop-dropdown-divider {
        height: 1px;
        background: var(--border);
        margin: 4px 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit profile button
        const editProfileBtn = document.querySelector('.edit-profile-btn');
        if (editProfileBtn) {
            editProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openModal('editProfileModal');
            });
        }

        // Handle desktop menu button
        const menuBtn = document.getElementById('desktopMenuBtn');
        if (menuBtn) {
            menuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                toggleDesktopDropdown(e);
            });
        }

        // Handle dropdown items
        const dropdownItems = document.querySelectorAll('.desktop-dropdown-item');
        dropdownItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                closeDesktopDropdown();
                
                // Handle each action
                if (index === 0) openModal('editProfileModal');
                else if (index === 1) viewAsVisitor();
                else if (index === 2) downloadCV();
                else if (index === 3) shareProfile();
                else if (index === 4) reportProfile();
            });
        });
    });

    function toggleDesktopDropdown(event) {
        const dropdown = document.getElementById('desktopDropdown');
        const button = document.getElementById('desktopMenuBtn');

        if (!dropdown || !button) return;

        const isActive = dropdown.classList.contains('active');

        if (!isActive) {
            const rect = button.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            // Position dropdown below and aligned to the right edge of button
            dropdown.style.top = (rect.bottom + scrollTop + 8) + 'px';
            dropdown.style.left = (rect.right + scrollLeft - 220) + 'px'; // 220px is dropdown width
            dropdown.classList.add('active');
        } else {
            dropdown.classList.remove('active');
        }
    }

    function closeDesktopDropdown() {
        const dropdown = document.getElementById('desktopDropdown');
        if (dropdown) {
            dropdown.classList.remove('active');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('desktopDropdown');
        const button = document.getElementById('desktopMenuBtn');

        if (dropdown && button) {
            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        }
    });

    // Close on scroll
    window.addEventListener('scroll', function() {
        closeDesktopDropdown();
    });

    // Utility functions
    function viewAsVisitor() {
        window.open(window.location.href + '?preview=1', '_blank');
    }

    function downloadCV() {
        window.location.href = '#';
    }

    function shareProfile() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $user->name }}',
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied!');
        }
    }

    function reportProfile() {
        if (confirm('Report this profile?')) {
            // Handle report
        }
    }
</script>