{{-- ============================================ --}}
{{-- MOBILE/TABLET HERO SECTION WITH BANNER --}}
{{-- LinkedIn/Facebook Style - Shows below 992px --}}
{{-- ============================================ --}}

<section class="hero-mobile">
    {{-- Banner Container --}}
    <div class="hm-banner-container">
        {{-- Banner Image --}}
        <div class="hm-banner">
            @if($user->banner_url ?? null)
                <img 
                    id="heroBannerImageMobile"
                    src="{{ $user->banner_url }}" 
                    alt="Banner"
                    style="
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        object-position: center center;
                        transform: scale({{ ($user->banner_zoom ?? 100) / 100 }}) translate({{ $user->banner_offset_x ?? 0 }}px, {{ $user->banner_offset_y ?? 0 }}px);
                        transform-origin: center center;
                    "
                >
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);position:relative;">
                    <div style="position:absolute;inset:0;opacity:0.1;background-image:radial-gradient(circle, white 1px, transparent 1px);background-size:20px 20px;"></div>
                </div>
            @endif
            
            {{-- AI Ask Leo Button --}}
            <button class="hm-ai-btn" onclick="openAiAssistant()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" 
                          fill="currentColor" stroke="currentColor" stroke-width="1.5">
                        <animate attributeName="opacity" values="1;0.6;1" dur="2s" repeatCount="indefinite"/>
                    </path>
                </svg>
                <span class="hm-ai-text">Ask Leo AI</span>
                <span class="hm-ai-badge">BETA</span>
            </button>

            {{-- Edit Banner Button --}}
            <button class="hm-edit-btn" onclick="openModal('editBannerModal')" aria-label="Edit Banner">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </button>
        </div>

        {{-- Avatar Positioned at Bottom Left of Banner --}}
        <div class="hm-avatar">
            @if ($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
            @else
                <i class="fa-solid fa-camera"></i>
            @endif

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

            <span class="hm-status {{ $isOnline ? 'online' : '' }}" title="{{ $lastSeenText }}">
                @if ($isOnline)
                    <span class="pulse-ring"></span>
                @endif
            </span>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="hm-content">
        <div class="hm-header">
            <div class="hm-info">
                <h1 class="hm-name">{{ $user->name }}</h1>
                
                {{-- Headline instead of status --}}
                @if($user->headline ?? null)
                    <p class="hm-headline">{{ $user->headline }}</p>
                @else
                    <p class="hm-headline">Professional Developer & Designer</p>
                @endif
            </div>
        </div>

        {{-- Bio with 100 char limit --}}
        @php
            $bio = $user->bio ?? 'I build maintainable and performant web applications. Experienced with Laravel, React, and cloud deployments.';
            $shortBio = strlen($bio) > 100 ? substr($bio, 0, 100) . '...' : $bio;
            $needsExpand = strlen($bio) > 100;
        @endphp
        
        {{-- <div class="hm-bio-container">
            <p class="hm-bio" id="hmBioText">{{ $shortBio }}</p>
            @if($needsExpand)
                <button class="hm-see-more" id="hmSeeMoreBtn" onclick="toggleBio()">
                    See more <i class="fa-solid fa-chevron-down" id="hmBioIcon"></i>
                </button>
            @endif
        </div> --}}
        
        <div style="display:none;" id="hmFullBio">{{ $bio }}</div>

        {{-- @if($user->skills && count($user->skills) > 0)
            <div class="hm-skills">
                @foreach ($user->skills as $skill)
                    <span class="hm-skill">{{ $skill }}</span>
                @endforeach
            </div>
        @endif --}}

        <div class="hm-location">
            <i class="fa-solid fa-location-dot"></i>
            <span>{{ $user->location ?? 'Location not specified' }}</span>
        </div>

        <div class="hm-actions">
            <button class="hm-action primary">
                <i class="fa-solid fa-message"></i>
                Let's Chat!
            </button>
            <button class="hm-action">
                <i class="fa-solid fa-user-plus"></i>
                Follow
            </button>
            <button class="hm-more" id="hmKebabBtn" onclick="toggleMobileDropdown(event)">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="hm-socials">
            @if ($user->facebook)
                <a href="{{ $user->facebook }}" class="hm-social" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            @endif
            @if ($user->instagram)
                <a href="{{ $user->instagram }}" class="hm-social" aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            @endif
            @if ($user->twitter)
                <a href="{{ $user->twitter }}" class="hm-social" aria-label="Twitter">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
            @endif
            @if ($user->linkedin)
                <a href="{{ $user->linkedin }}" class="hm-social" aria-label="LinkedIn">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
            @endif
            {{-- @if ($user->cv_url) --}}
                <a href="#" class="hm-cv" download>
                    <i class="fa-solid fa-download"></i>
                    Resume
                </a>
            {{-- @endif --}}
        </div>
    </div>
</section>

{{-- Dropdown Menu --}}
<div class="hm-dropdown" id="hmDropdown">
    <button class="hm-dropdown-item" onclick="openModal('editProfileModal'); closeDropdown();">
        <i class="fa-solid fa-pen"></i>
        <span>Edit Profile</span>
    </button>
    <button class="hm-dropdown-item" onclick="openModal('editBannerModal'); closeDropdown();">
        <i class="fa-solid fa-image"></i>
        <span>Edit Banner</span>
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
    .hm-avatar img{
        border-radius: 50%;

    }
/* Hero Mobile Styles */
.hero-mobile {
    background: var(--card);
    border: 1px solid var(--border);
    margin-bottom: 16px;
}

/* Banner Container */
.hm-banner-container {
    position: relative;
    width: 100%;
}

/* Banner */
.hm-banner {
    position: relative;
    width: 100%;
    height: 170px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hm-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* AI Button on Banner */
.hm-ai-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(139, 92, 246, 0.4);
    border-radius: 24px;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #6366f1;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3), 0 0 0 1px rgba(139, 92, 246, 0.1) inset;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
}

.hm-ai-btn:active {
    transform: scale(0.96);
}

.hm-ai-text {
    background: linear-gradient(135deg, #8b5cf6, #6366f1, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hm-ai-badge {
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: white;
    font-size: 9px;
    font-weight: 900;
    padding: 3px 6px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
    animation: pulse-badge 2s infinite;
}

@keyframes pulse-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Edit Button */
.hm-edit-btn {
    position: absolute;
    bottom: 12px;
    right: 12px;
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #374151;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
    z-index: 10;
}

.hm-edit-btn:active {
    transform: scale(0.94);
}

/* Avatar - Positioned at bottom left of banner */
.hm-avatar {
    position: absolute;
    bottom: -40px;
    left: 20px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid var(--card);
    background: var(--card);
    /* overflow: hidden; */
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 3rem;
    z-index: 15;
}

.hm-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hm-status {
    position: absolute;
    bottom: 5px;
    right: -2px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 5px solid var(--card);
    background: #9ca3af;
    z-index: 20;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.hm-status.online {
    background: #22c55e;
}

.pulse-ring {
    position: absolute;
    inset: -5px;
    border-radius: 50%;
    background: #22c55e;
    animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    z-index: -1;
}

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(2); opacity: 0; }
}

/* Content Section */
.hm-content {
    padding: 50px 20px 20px;
}

.hm-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 12px;
}

.hm-info {
    flex: 1;
}

.hm-name {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-heading);
    margin: 0 0 4px 0;
    line-height: 1.2;
}

.hm-headline {
    font-size: 15px;
    color: var(--text-body);
    margin: 0;
    line-height: 1.4;
}

.hm-bio-container {
    margin-bottom: 16px;
}

.hm-bio {
    font-size: 15px;
    color: var(--text-body);
    line-height: 1.5;
    margin: 0;
}

.hm-see-more {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.hm-see-more i {
    transition: transform 0.3s ease;
}

.hm-see-more.expanded i {
    transform: rotate(180deg);
}

.hm-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.hm-skill {
    background: var(--apc-bg);
    color: var(--text-body);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid var(--border);
}

.hm-location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

.hm-actions {
    display: grid;
    grid-template-columns: 1fr 1fr 52px;
    gap: 12px;
    margin-bottom: 20px;
}

.hm-action,.hm-more {
    height: 40px;
    /* width: 130px; */
}

.hm-action {

    padding:0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text-body);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.hm-action.primary {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

.hm-action:active {
    transform: scale(0.98);
}

.hm-more {
  
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--card);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-body);
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 18px;
}

.hm-more:active {
    background: var(--apc-bg);
    transform: scale(0.98);
}

.hm-socials {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.hm-social {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid var(--border);
    background: var(--card);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-body);
    text-decoration: none;
    transition: all 0.2s ease;
}

.hm-social:active {
    background: var(--apc-bg);
    transform: scale(0.95);
}

.hm-cv {
    padding: 10px 18px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text-body);
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.hm-cv:active {
    background: var(--apc-bg);
}

/* Dropdown */
.hm-dropdown {
    display: none;
    position: fixed;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.25);
    min-width: 240px;
    z-index: 999999;
    animation: slideDown 0.2s ease;
    overflow: hidden;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
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
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
    text-align: left;
}

.hm-dropdown-item:active {
    background: var(--apc-bg);
}

.hm-dropdown-item i {
    width: 20px;
    font-size: 16px;
    color: var(--text-muted);
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

/* Responsive */
@media (min-width: 768px) {
    .hm-banner {
        height: 150px;
    }
    
    .hm-avatar {
        width: 120px;
        height: 120px;
        bottom: -50px;
        left: 24px;
    }
    
    .hm-content {
        padding-top: 60px;
    }
}

@media (max-width: 480px) {
    .hm-banner {
        height: 150px;
    }
    
    .hm-avatar {
        width: 100px;
        height: 100px;
        bottom: -35px;
        left: 16px;
        border-width: 4px;
    }
    
    .hm-content {
        padding: 45px 16px 16px;
    }
    
    .hm-name {
        font-size: 20px;
    }
    
    .hm-ai-btn {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .hm-ai-text {
        display: none;
    }
    
    .hm-edit-btn {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 375px) {
    .hm-banner {
        height: 120px;
    }
    .hm-action {
        padding: 0 !important;
    }
    
    .hm-avatar {
        width: 90px;
        height: 90px;
        bottom: -30px;
    }
    
    .hm-content {
        padding-top: 40px;
    }
}
</style>

<script>
function openAiAssistant() {
    console.log('Opening AI Assistant...');
    alert('AI Assistant feature coming soon!');
}

// Toggle bio expand/collapse
let bioExpanded = false;
function toggleBio() {
    const bioText = document.getElementById('hmBioText');
    const fullBio = document.getElementById('hmFullBio');
    const btn = document.getElementById('hmSeeMoreBtn');
    const icon = document.getElementById('hmBioIcon');
    
    bioExpanded = !bioExpanded;
    
    if (bioExpanded) {
        bioText.textContent = fullBio.textContent;
        btn.innerHTML = 'See less <i class="fa-solid fa-chevron-up" id="hmBioIcon"></i>';
        btn.classList.add('expanded');
    } else {
        const shortText = fullBio.textContent.substring(0, 100) + '...';
        bioText.textContent = shortText;
        btn.innerHTML = 'See more <i class="fa-solid fa-chevron-down" id="hmBioIcon"></i>';
        btn.classList.remove('expanded');
    }
}

function toggleMobileDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('hmDropdown');
    const button = document.getElementById('hmKebabBtn');
    const isActive = dropdown.classList.contains('active');

    if (!isActive) {
        const rect = button.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + 8) + 'px';
        dropdown.style.right = (window.innerWidth - rect.right) + 'px';
        dropdown.classList.add('active');
    } else {
        dropdown.classList.remove('active');
    }
}

function closeDropdown() {
    document.getElementById('hmDropdown')?.classList.remove('active');
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('hmDropdown');
    const kebab = document.getElementById('hmKebabBtn');
    if (dropdown && !dropdown.contains(e.target) && e.target !== kebab && !kebab?.contains(e.target)) {
        dropdown.classList.remove('active');
    }
});

window.addEventListener('scroll', closeDropdown);

function viewAsVisitor() {
    window.open(window.location.href + '?preview=1', '_blank');
}

function downloadCV() {
    window.location.href = '#';
}

function shareProfile() {
    if (navigator.share) {
        navigator.share({ title: '{{ $user->name }}', url: window.location.href });
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

// Update mobile banner after save
window.addEventListener('bannerUpdated', function(e) {
    const mobileBanner = document.getElementById('heroBannerImageMobile');
    if (mobileBanner && e.detail.url) {
        mobileBanner.src = `${e.detail.url}?v=${Date.now()}`;
        mobileBanner.style.transform = `scale(${(e.detail.zoom||100)/100}) translate(${e.detail.offset_x||0}px, ${e.detail.offset_y||0}px)`;
    }
});
</script>