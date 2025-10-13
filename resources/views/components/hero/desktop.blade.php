
@php

use Carbon\Carbon;
use Illuminate\Support\Str; 

@endphp

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



    <style>
.user-name {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1; /* only one line */
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 100%;
  font-weight: 600;
  /* font-size: 1rem; */
  color: var(--text-heading);
  line-height: 1.3;
  vertical-align: middle;
  word-break: keep-all;
}

/* Responsive tightening like LinkedIn */
@media (max-width: 480px) {
  .user-name {
    font-size: 0.95rem;
    max-width: 12ch; /* mobile: tighter space */
  }
}
@media (min-width: 481px) and (max-width: 1024px) {
  .user-name {
    max-width: 18ch; /* tablet: moderate space */
  }
}
@media (min-width: 1025px) {
  .user-name {
    max-width: 22ch; /* desktop: full space */
  }
}

    </style>
    <div class="name-row">
        <h2 class="user-name" title="{{ $user->name }}">
            {{ $user->name }}
          </h2>
          
            </div>
    
    {{-- Headline instead of skills --}}
    @if(!empty($user->headline))
      <div class="stack">{{ $user->headline }}</div>
    @endif
    
    {{-- Location from user_profile --}}
    @if(!empty($user->location))
      <div class="loc">
        <x-ui.icon name="location" size="xs" color="secondary" />
        {{ $user->location }}
      </div>
    @endif
    
    <div class="hr"></div>
    
    {{-- About from user_profile->bio, limited to 200 chars --}}
    @php $bio = (string) ($user->bio ?? ''); @endphp
    
    @if($bio !== '')
      <div class="about-row">
        <span class="label">About:</span>
        <x-ui.icon name="about" size="xs" color="secondary" class="hover-lift clickable" />
      </div>
      <p class="about-text">
        {{ Str::limit($bio, 200) }}
        @if(Str::length($bio) > 200)
          <a href="#" class="see-more-inline">See more</a>
        @endif
      </p>
    @endif
    

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
        @php
            $socials = [
                'facebook'  => $user->facebook,
                'instagram' => $user->instagram,
                'twitter'   => $user->twitter,
                'linkedin'  => $user->linkedin,
            ];
            $filled = array_filter($socials);
            $count = count($filled);
        @endphp
    
        @if ($count === 0)
            <button type="button" class="btn-add-social" onclick="openModal('editProfileModal')">
                <i class="fa-solid fa-circle-plus"></i>
                <span>Add social links</span>
            </button>
        @else
            {{-- Show existing links --}}
            @foreach ($filled as $key => $url)
                <a href="{{ $url }}" aria-label="{{ ucfirst($key) }}" target="_blank" rel="noopener" class="social-link">
                    <x-ui.icon name="{{ $key }}" size="sm" color="secondary" class="hover-lift" />
                </a>
            @endforeach
    
            {{-- Add icon if 1–2 only --}}
            @if ($count < 3)
                <button type="button" class="social-link add-more" title="Add more" onclick="openModal('editProfileModal')">
                    <i class="fa-solid fa-plus"></i>
                </button>
            @endif
    
            {{-- CV download --}}
            {{-- <a href="#" class="social-link" aria-label="Download CV">
                <x-ui.icon name="download" size="sm" color="secondary" class="hover-lift" />
            </a> --}}
        @endif
    </div>

    <style>
        .socials {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: var(--card);
    border: 1px solid var(--border);
    color: var(--text-muted);
    transition: all 0.2s ease;
}



.btn-add-social {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: var(--card);
    border: 1.5px dashed var(--border);
    border-radius: 8px;
    color: var(--text-muted);
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    cursor: pointer !important;
}



.social-link.add-more {
    border-style: dashed;
    color: var(--text-muted);
    cursor: pointer !important;

}

.social-link.add-more:hover {
    background: var(--accent);
    color: #fff;
}

    </style>
    
</section>




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
                toggleDesktopDropdown();
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
    
    function toggleDesktopDropdown() {
        const dropdown = document.getElementById('desktopDropdown');
        const button = document.getElementById('desktopMenuBtn');
    
        if (!dropdown || !button) return;
    
        const isActive = dropdown.classList.contains('active');
    
        if (!isActive) {
            // Get button position
            const rect = button.getBoundingClientRect();
            
            // Position dropdown right next to button (LinkedIn style)
            dropdown.style.position = 'fixed';
            dropdown.style.top = (rect.bottom + 8) + 'px';
            dropdown.style.right = (window.innerWidth - rect.right) + 'px';
            dropdown.style.left = 'auto';
            
            // Check if dropdown goes off-screen and adjust
            setTimeout(() => {
                const dropdownRect = dropdown.getBoundingClientRect();
                
                // If goes off right edge, align to left of button
                if (dropdownRect.right > window.innerWidth) {
                    dropdown.style.right = 'auto';
                    dropdown.style.left = rect.left + 'px';
                }
                
                // If goes off bottom, show above button
                if (dropdownRect.bottom > window.innerHeight) {
                    dropdown.style.top = (rect.top - dropdownRect.height - 8) + 'px';
                }
            }, 10);
            
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
    }, { passive: true });
    
    // Close on window resize
    window.addEventListener('resize', function() {
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