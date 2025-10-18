@php

    use Carbon\Carbon;
    use Illuminate\Support\Str;

@endphp

<style>
    .user-name {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        /* only one line */
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
            max-width: 12ch;
            /* mobile: tighter space */
        }
    }

    @media (min-width: 481px) and (max-width: 1024px) {
        .user-name {
            max-width: 18ch;
            /* tablet: moderate space */
        }
    }

    @media (min-width: 1025px) {
        .user-name {
            max-width: 22ch;
            /* desktop: full space */
        }
    }
 
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
 

 
<section class="hero-merged">
    {{-- Edit Profile Button --}}
    <button class="edit-card icon-btn edit-profile-btn" aria-label="Edit card">
        <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
    </button>

    <div class="photo-wrap">
        <div class="photo-ring">
            <div class="photo-circle">
                @if ($user->avatar)
<!-- Add this to your Blade template -->
<img 
    src="{{ $user->avatar }}" 
    alt="{{ $user->name }}" 
    referrerpolicy="no-referrer"
    crossorigin="anonymous"
    onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';"

    style="width:100%;height:100%;border-radius:50%;object-fit:cover;"
    >
                    @else
                    <x-ui.icon name="user" size="lg" color="secondary" class="mb-2" />
                    Upload your<br>profile photo
                @endif

                {{-- Online/Last-seen --}}
                @php
                    $isOnline = false; $lastSeenText = 'Offline';
                    if (isset($user->last_seen_at)) {
                        $lastSeenTime = Carbon::parse($user->last_seen_at);
                        $diffInMinutes = now()->diffInMinutes($lastSeenTime);
                        if ($diffInMinutes < 1) { $isOnline = true; $lastSeenText = 'Active now'; }
                        elseif ($diffInMinutes < 60) { $lastSeenText = 'Active '.$diffInMinutes.'m ago'; }
                        elseif ($diffInMinutes < 1440) { $lastSeenText = 'Active '.floor($diffInMinutes/60).'h ago'; }
                        elseif ($diffInMinutes < 10080) { $lastSeenText = floor($diffInMinutes/1440).'d ago'; }
                        else { $lastSeenText = 'Last seen '.$lastSeenTime->format('M d'); }
                    }
                @endphp
                <span class="desktop-status-badge {{ $isOnline ? 'online' : 'offline' }}" title="{{ $lastSeenText }}">
                    @if($isOnline)<span class="pulse-ring"></span>@endif
                </span>
            </div>
        </div>
    </div>

    <div class="name-row">
        <h2 class="user-name" title="{{ $user->name }}">{{ $user->name }}</h2>
    </div>

    @if(!empty($user->headline))
        <div class="stack">{{ $user->headline }}</div>
    @endif

    @if(!empty($user->location))
        <div class="loc">
            <x-ui.icon name="location" size="xs" color="secondary" />
            {{ $user->location }}
        </div>
    @endif

    <div class="hr"></div>

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
            <x-ui.icon name="message" variant="outlined" size="sm" /> Chat!
        </x-ui.button>

        <x-ui.button variant="outlined" shape="square" color="primary" size="sm" class="btn-follow">
            <x-ui.icon name="user-plus" size="sm" variant="outlined" /> Follow
        </x-ui.button>

        <button class="menu-kebab icon-btn" id="desktopMenuBtn" aria-label="More options">
            <x-ui.icon name="more-vertical" variant="outlined" size="lg" class="color-muted" />
        </button>
    </div>

    <div class="socials">
        @php
            $socials = ['facebook'=>$user->facebook,'instagram'=>$user->instagram,'twitter'=>$user->twitter,'linkedin'=>$user->linkedin];
            $filled = array_filter($socials); $count = count($filled);
        @endphp

        @if($count === 0)
            <button type="button" class="btn-add-social" onclick="openModal('editProfileModal')">
                <i class="fa-solid fa-circle-plus"></i><span>Add social links</span>
            </button>
        @else
            @foreach($filled as $key => $url)
                <a href="{{ $url }}" aria-label="{{ ucfirst($key) }}" target="_blank" rel="noopener" class="social-link">
                    <x-ui.icon name="{{ $key }}" size="sm" color="secondary" class="hover-lift" />
                </a>
            @endforeach
            @if($count < 3)
                <button type="button" class="social-link add-more" title="Add more" onclick="openModal('editProfileModal')">
                    <i class="fa-solid fa-plus"></i>
                </button>
            @endif
        @endif
    </div>
</section>

<!-- ======================= DESKTOP ACTIONS DROPDOWN (existing) ======================= -->
<div class="desktop-dropdown" id="desktopDropdown">
    <button class="desktop-dropdown-item">
        <x-ui.icon name="edit" size="sm" color="secondary" /><span>Edit Profile</span>
    </button>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="eye" size="sm" color="secondary" /><span>View as Visitor</span>
    </button>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="download" size="sm" color="secondary" /><span>Download CV</span>
    </button>
    <div class="desktop-dropdown-divider"></div>
    <button class="desktop-dropdown-item">
        <x-ui.icon name="share" size="sm" color="secondary" /><span>Share Profile</span>
    </button>
    <button class="desktop-dropdown-item danger">
        <x-ui.icon name="flag" size="sm" color="secondary" /><span>Report</span>
    </button>
</div>

<!-- ======================= NEW: DESKTOP SHARE SHEET (LinkedIn/Facebook style) ======================= -->
<div class="desktop-dropdown" id="desktopShareDropdown" aria-hidden="true">
    <!-- Header-like first item (non-click) -->
    <div class="desktop-dropdown-item" style="cursor:default;opacity:.85">
        <i class="fa-solid fa-share-nodes ui-icon"></i>
        <span>Share profile</span>
    </div>
    <div class="desktop-dropdown-divider"></div>

    <!-- Copy link -->
    <button class="desktop-dropdown-item" data-share="copy">
        <i class="fa-regular fa-copy ui-icon"></i>
        <span>Copy profile link</span>
    </button>

    <div class="desktop-dropdown-divider"></div>

    <!-- Networks -->
    <button class="desktop-dropdown-item" data-share="linkedin">
        <i class="fa-brands fa-linkedin ui-icon"></i><span>Share on LinkedIn</span>
    </button>
    <button class="desktop-dropdown-item" data-share="x">
        <i class="fa-brands fa-x-twitter ui-icon"></i><span>Share on X (Twitter)</span>
    </button>
    <button class="desktop-dropdown-item" data-share="facebook">
        <i class="fa-brands fa-facebook ui-icon"></i><span>Share on Facebook</span>
    </button>
    <button class="desktop-dropdown-item" data-share="whatsapp">
        <i class="fa-brands fa-whatsapp ui-icon"></i><span>Share on WhatsApp</span>
    </button>
    <button class="desktop-dropdown-item" data-share="telegram">
        <i class="fa-brands fa-telegram ui-icon"></i><span>Share on Telegram</span>
    </button>
    <button class="desktop-dropdown-item" data-share="email">
        <i class="fa-solid fa-envelope ui-icon"></i><span>Share via Email</span>
    </button>

    <div class="desktop-dropdown-divider"></div>

    <!-- Device share -->
    <button class="desktop-dropdown-item" data-share="system">
        <i class="fa-solid fa-share-from-square ui-icon"></i><span>Share via device…</span>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== EXISTING HOOKS =====
    const editProfileBtn = document.querySelector('.edit-profile-btn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function (e) {
            e.stopPropagation(); openModal('editProfileModal');
        });
    }

    const menuBtn = document.getElementById('desktopMenuBtn');
    if (menuBtn) {
        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation(); e.preventDefault(); toggleDesktopDropdown();
        });
    }

    const dropdownItems = document.querySelectorAll('#desktopDropdown .desktop-dropdown-item');

    // ===== NEW: SHARE SHEET SUPPORT =====
    const shareDropdown = document.getElementById('desktopShareDropdown');

    function openShareAtRect(rect){
        if(!shareDropdown) return;
        // Position like the actions dropdown (right aligned to trigger, below)
        shareDropdown.style.position = 'fixed';
        shareDropdown.style.left = 'auto';
        shareDropdown.style.right = (window.innerWidth - rect.right) + 'px';
        shareDropdown.style.top = (rect.bottom + 8) + 'px';
        // Show
        shareDropdown.classList.add('active');

        // Adjust if off-screen
        setTimeout(() => {
            const d = shareDropdown.getBoundingClientRect();
            if (d.right > window.innerWidth) {
                shareDropdown.style.right = 'auto';
                shareDropdown.style.left = rect.left + 'px';
            }
            if (d.bottom > window.innerHeight) {
                shareDropdown.style.top = (rect.top - d.height - 8) + 'px';
            }
        }, 10);
    }

    function closeShareDropdown(){
        shareDropdown?.classList.remove('active');
    }

    // Intercept the "Share Profile" menu item (index 3)
    dropdownItems.forEach((item, index) => {
        item.addEventListener('click', function (e) {
            // Share Profile
            if (index === 3) {
                e.preventDefault(); e.stopPropagation();
                const rect = this.getBoundingClientRect(); // compute before closing the actions menu
                openShareAtRect(rect);
                closeDesktopDropdown();
                return;
            }
            // Default behavior (existing)
            closeDesktopDropdown();
            if (index === 0) openModal('editProfileModal');
            else if (index === 1) viewAsVisitor();
            else if (index === 2) downloadCV();
            else if (index === 4) reportProfile();
        });
    });

    // Share actions
    const shareTitle = @json( ($user->name ?? 'Profile') . (!empty($user->headline) ? ' – '.$user->headline : '') );

    function performShare(network){
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(shareTitle || document.title || 'My profile');

        let shareUrl = '';
        switch(network){
            case 'copy':
                (async () => {
                    try { await navigator.clipboard.writeText(window.location.href); alert('Profile link copied!'); }
                    catch {
                        const tmp = document.createElement('input');
                        tmp.value = window.location.href; document.body.appendChild(tmp);
                        tmp.select(); document.execCommand('copy'); document.body.removeChild(tmp);
                        alert('Profile link copied!');
                    }
                })();
                break;
            case 'linkedin': shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`; break;
            case 'x':        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${text}`; break;
            case 'facebook': shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`; break;
            case 'whatsapp': shareUrl = `https://wa.me/?text=${text}%20${url}`; break;
            case 'telegram': shareUrl = `https://t.me/share/url?url=${url}&text=${text}`; break;
            case 'email':    shareUrl = `mailto:?subject=${text}&body=${text}%0A%0A${url}`; break;
            case 'system':
                if (navigator.share) {
                    navigator.share({title: shareTitle, url: window.location.href}).catch(()=>{});
                } else {
                    alert('System share not available on this device/browser.');
                }
                break;
        }
        if (shareUrl) window.open(shareUrl, '_blank', 'noopener');
        closeShareDropdown();
    }

    document.querySelectorAll('#desktopShareDropdown .desktop-dropdown-item[data-share]').forEach(btn=>{
        btn.addEventListener('click', function(e){
            e.preventDefault(); e.stopPropagation();
            performShare(this.getAttribute('data-share'));
        });
    });

    // ===== EXISTING FUNCTIONS (kept) =====
    window.toggleDesktopDropdown = function(){
        const dropdown = document.getElementById('desktopDropdown');
        const button = document.getElementById('desktopMenuBtn');
        if (!dropdown || !button) return;
        const isActive = dropdown.classList.contains('active');

        if (!isActive) {
            const rect = button.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.top = (rect.bottom + 8) + 'px';
            dropdown.style.right = (window.innerWidth - rect.right) + 'px';
            dropdown.style.left = 'auto';

            setTimeout(() => {
                const dr = dropdown.getBoundingClientRect();
                if (dr.right > window.innerWidth) {
                    dropdown.style.right = 'auto';
                    dropdown.style.left = rect.left + 'px';
                }
                if (dr.bottom > window.innerHeight) {
                    dropdown.style.top = (rect.top - dr.height - 8) + 'px';
                }
            }, 10);

            dropdown.classList.add('active');
        } else {
            dropdown.classList.remove('active');
        }
    }

    window.closeDesktopDropdown = function(){
        document.getElementById('desktopDropdown')?.classList.remove('active');
    }

    // Close on outside click (both menus)
    document.addEventListener('click', function(e){
        const dd = document.getElementById('desktopDropdown');
        const sb = document.getElementById('desktopMenuBtn');
        const sd = document.getElementById('desktopShareDropdown');

        const clickedInsideAny =
            (dd && dd.contains(e.target)) ||
            (sd && sd.contains(e.target)) ||
            (sb && sb.contains(e.target));

        if (!clickedInsideAny) {
            dd?.classList.remove('active');
            sd?.classList.remove('active');
        }
    });

    // Close on scroll/resize & Esc
    window.addEventListener('scroll', function(){ closeDesktopDropdown(); closeShareDropdown(); }, {passive:true});
    window.addEventListener('resize', function(){ closeDesktopDropdown(); closeShareDropdown(); });
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape'){ closeDesktopDropdown(); closeShareDropdown(); }
    });

    // ===== Keep original helpers =====
    window.viewAsVisitor = function(){ window.open(window.location.href + '?preview=1', '_blank'); }
    window.downloadCV    = function(){ window.location.href = '#'; }
    window.reportProfile = function(){ if (confirm('Report this profile?')) { /* Handle report */ } }
    window.shareProfile  = function(){ /* replaced by Share Sheet, keeping stub to avoid breaking references */ }
});
</script>

