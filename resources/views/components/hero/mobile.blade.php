{{-- ============================================ --}}
{{-- MOBILE HERO SECTION WITH ONLINE STATUS --}}
{{-- ============================================ --}}

<section class="hero-mobile">
    <div class="hm-banner">
        <img src="#" alt="Banner">
        
        {{-- Avatar with Online Status Badge --}}
        <div class="hm-avatar">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                    style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
            @else
                <i class="fa-solid fa-camera u-ic-md" style="margin-bottom: 4px"></i>
            @endif
            
            {{-- Professional Online Status Badge --}}
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
            
            <span class="hm-status-badge {{ $isOnline ? 'online' : 'offline' }}" 
                  title="{{ $lastSeenText }}">
                @if($isOnline)
                    <span class="pulse-ring"></span>
                @endif
            </span>
        </div>
        
        <span class="hm-generate">
            <i class="fa-solid fa-wand-magic-sparkles" style="margin-right: 6px"></i>
            Generate
        </span>
    </div>

    <div class="hm-body">
        <h2 class="hm-name">
            {{ $user->name }}
            {{-- @if($user->open_to_work)
                <span class="hm-otw">Open to work</span>
            @endif --}}
        </h2>
        
        {{-- Online Status Text Below Name --}}
        @if(isset($user->last_seen_at))
            <div class="hm-online-status">
                @if($isOnline)
                    <span class="status-dot online"></span>
                    <span class="status-text online">Active now</span>
                @else
                    <span class="status-text">{{ $lastSeenText }}</span>
                @endif
            </div>
        @endif

        <div class="hm-title">
            {{ $user->bio ?? 'Professional Developer & Designer' }}
            <a href="#">
                See more
                <i class="fa-solid fa-chevron-down u-ic-xxs" style="margin-left: 4px"></i>
            </a>
        </div>

        <div class="hm-chips">
            @foreach($user->skills ?? [] as $skill)
                <span class="hm-chip">{{ $skill }}</span>
            @endforeach
        </div>

        <div class="hm-loc">
            <i class="fa-solid fa-location-dot u-ic-xs"></i>
            {{ $user->location ?? 'Location not specified' }}
        </div>

        <div class="hm-ctas">
            <button class="hm-btn hm-btn-chat">Let's Chat!</button>
            <button class="hm-btn">Follow</button>
            <button class="hm-kebab" id="hmKebabBtn" onclick="toggleMobileDropdown(event)" aria-label="More">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="hm-socials">
            @if($user->facebook)
                <a class="hm-social" href="{{ $user->facebook }}"><i class="fa-brands fa-facebook-f"></i></a>
            @endif
            @if($user->instagram)
                <a class="hm-social" href="{{ $user->instagram }}"><i class="fa-brands fa-instagram"></i></a>
            @endif
            @if($user->twitter)
                <a class="hm-social" href="{{ $user->twitter }}"><i class="fa-brands fa-x-twitter"></i></a>
            @endif
            @if($user->linkedin)
                <a class="hm-social" href="{{ $user->linkedin }}"><i class="fa-brands fa-linkedin-in"></i></a>
            @endif
            <a class="hm-cv" href="{{ $user->cv_url ?? '#' }}">
                <i class="fa-solid fa-download"></i>  Resume
            </a>
        </div>

        <div class="hm-sep"></div>
    </div>
</section>

{{-- Dropdown Menu --}}
<div class="hm-dropdown" id="hmDropdown">
    <button class="hm-dropdown-item" onclick="openModal('editProfileModal'); closeDropdown();">
        <i class="fa-solid fa-pen"></i>
        <span>Edit Profile</span>
    </button>
    <button class="hm-dropdown-item" onclick="viewAsVisitor(); closeDropdown();">
        <i class="fa-solid fa-eye"></i>
        <span>View as Visitor</span>
    </button>
    <button class="hm-dropdown-item" onclick="downloadCV(); closeDropdown();">
        <i class="fa-solid fa-download"></i>
        <span>Download CV</span>
    </button>
    <div class="hm-dropdown-divider"></div>
    <button class="hm-dropdown-item" onclick="shareProfile(); closeDropdown();">
        <i class="fa-solid fa-share-nodes"></i>
        <span>Share Profile</span>
    </button>
    <button class="hm-dropdown-item danger" onclick="reportProfile(); closeDropdown();">
        <i class="fa-solid fa-flag"></i>
        <span>Report</span>
    </button>
</div>


<style>
/* Dropdown Styles */
.hm-dropdown {
    display: none;
    position: fixed;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    min-width: 240px;
    z-index: 999999;
    animation: slideDown 0.2s ease;
    overflow: hidden;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hm-dropdown.active {
    display: block;
}

.hm-dropdown-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
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

.hm-dropdown-item:hover {
    background: var(--apc-bg);
}

.hm-dropdown-item:first-child {
    border-radius: var(--radius) var(--radius) 0 0;
}

.hm-dropdown-item:last-child {
    border-radius: 0 0 var(--radius) var(--radius);
}

.hm-dropdown-item i {
    width: 18px;
    font-size: 16px;
    color: var(--text-muted);
    flex-shrink: 0;
}

.hm-dropdown-item.danger {
    color: var(--error);
}

.hm-dropdown-item.danger i {
    color: var(--error);
}

.hm-dropdown-divider {
    height: 1px;
    background: var(--border);
    margin: 4px 0;
}
</style>

<script>
function toggleMobileDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('hmDropdown');
    const button = document.getElementById('hmKebabBtn');
    
    // Toggle dropdown
    const isActive = dropdown.classList.contains('active');
    
    if (!isActive) {
        // Position dropdown relative to button
        const rect = button.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + 8) + 'px';
        dropdown.style.right = (window.innerWidth - rect.right) + 'px';
        dropdown.classList.add('active');
    } else {
        dropdown.classList.remove('active');
    }
}

function closeDropdown() {
    const dropdown = document.getElementById('hmDropdown');
    dropdown.classList.remove('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('hmDropdown');
    const kebab = document.getElementById('hmKebabBtn');
    
    if (dropdown && !dropdown.contains(e.target) && e.target !== kebab && !kebab?.contains(e.target)) {
        dropdown.classList.remove('active');
    }
});

// Close dropdown on scroll
window.addEventListener('scroll', function() {
    closeDropdown();
});

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