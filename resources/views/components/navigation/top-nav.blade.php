<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CSS VARIABLES ===== */
    :root {
        --nav-bg: rgba(255, 255, 255, 0.95);
        --nav-border: rgba(0, 0, 0, 0.08);
        --accent: #1351d8;
        --text-heading: #1a1a1a;
        --text-body: #4a4a4a;
        --text-muted: #6b6b6b;
        --text-subtle: #9a9a9a;
        --card: #ffffff;
        --border: rgba(0, 0, 0, 0.1);
        --hover-bg: rgba(0, 0, 0, 0.05);
        --badge-red: #dc2626;
        --badge-blue: #2563eb;
        --badge-green: #059669;
    }

    [data-theme="dark"] {
        --nav-bg: rgba(27, 31, 35, 0.95);
        --nav-border: rgba(255, 255, 255, 0.1);
        --accent: #4a8fff;
        --text-heading: #ffffff;
        --text-body: #e0e0e0;
        --text-muted: #a0a0a0;
        --text-subtle: #808080;
        --card: #1e2226;
        --border: rgba(255, 255, 255, 0.1);
        --hover-bg: rgba(255, 255, 255, 0.08);
    }


    /* ===== NAVIGATION BASE ===== */
    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--nav-bg);
        backdrop-filter: blur(12px) saturate(180%);
        border-bottom: 1px solid var(--nav-border);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .nav-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 12px 20px;
    }

    .nav-row {
        display: none;
    }

    .nav-row--desktop {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .nav-row--desktop {
            display: none;
        }

        .nav-row--top,
        .nav-row--bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
        }
    }

    /* ===== BRAND LOGO ===== */
    .brand {
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .brand-logo {
        height: 36px;
        width: auto;
        max-width: 160px;
        object-fit: contain;
    }

    /* ===== SEARCH BAR ===== */
    .search-wrap {
        flex: 1;
        max-width: 480px;
        position: relative;
        background: #f0f2f5;
        border: 1px solid transparent;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        transition: all 0.25s ease;
    }

    [data-theme="dark"] .search-wrap {
        background: rgba(255, 255, 255, 0.1);
    }

    .search-wrap:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(74, 143, 255, 0.2);
    }

    .search-input {
        flex: 1;
        border: none;
        background: none;
        outline: none;
        font-size: 14px;
        color: var(--text-body);
    }

    .search-input::placeholder {
        color: var(--text-muted);
    }

    /* ===== ACTIONS ===== */
    .actions {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    /* ===== ICON BUTTONS ===== */
    .icon-btn {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: transparent;
        border: none;
        color: var(--text-body);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .icon-btn:hover {
        background: var(--hover-bg);
    }

    .icon-btn i {
        font-size: 20px;
    }

    /* ===== BADGES ===== */
    .badge {
        position: absolute;
        top: 6px;
        right: 6px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        background: var(--badge-red);
        color: white;
        border-radius: 9px;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--nav-bg);
    }

    .badge-dot {
        min-width: 10px;
        height: 10px;
        padding: 0;
        top: 8px;
        right: 8px;
    }

    /* ===== AVATAR ===== */
    .nav-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.2s ease;
        object-fit: cover;
    }

    .nav-avatar:hover {
        transform: scale(1.05);
    }

    /* ===== SHARE BUTTON ===== */
    .share-btn {
        padding: 8px 16px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .share-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 143, 255, 0.3);
    }

    .share-btn span {
        display: none;
    }

    @media (min-width: 768px) {
        .share-btn span {
            display: inline;
        }
    }

    /* ===== DROPDOWN BASE STYLES ===== */
    .dropdown {
        position: fixed;
        background: var(--card);
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 9999;
        width: 380px;
        max-height: 85vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    [data-theme="dark"] .dropdown {
        background: #1e2226;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    }

    .dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Desktop positioning */
    @media (min-width: 768px) {
        .notifications-dropdown {
            top: 60px;
            right: 120px;
        }

        .messages-dropdown {
            top: 60px;
            right: 180px;
        }
    }

    /* Mobile positioning */
    @media (max-width: 767px) {
        .dropdown {
            top: 60px;
            right: 10px;
            left: 10px;
            width: auto;
            max-width: 380px;
            margin: 0 auto;
        }
    }

    /* ===== DROPDOWN HEADER ===== */
    .dropdown-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--card);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .dropdown-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .dropdown-actions {
        display: flex;
        gap: 8px;
    }

    .dropdown-action-btn {
        padding: 6px 10px;
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 12px;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .dropdown-action-btn:hover {
        background: var(--hover-bg);
        color: var(--accent);
    }

    /* ===== DROPDOWN TABS ===== */
    .dropdown-tabs {
        display: flex;
        padding: 0 20px;
        border-bottom: 1px solid var(--border);
        background: var(--card);
        position: sticky;
        top: 53px;
        z-index: 9;
    }

    .dropdown-tab {
        flex: 1;
        padding: 12px;
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .dropdown-tab.active {
        color: var(--accent);
    }

    .dropdown-tab.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20%;
        right: 20%;
        height: 2px;
        background: var(--accent);
    }

    /* ===== DROPDOWN CONTENT ===== */
    .dropdown-content {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }

    /* Scrollbar styling */
    .dropdown-content::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-content::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    /* ===== NOTIFICATION ITEMS ===== */
    .notification-item {
        padding: 12px 20px;
        display: flex;
        gap: 12px;
        cursor: pointer;
        transition: background 0.15s ease;
        border-left: 3px solid transparent;
    }

    .notification-item:hover {
        background: var(--hover-bg);
    }

    .notification-item.unread {
        background: rgba(74, 143, 255, 0.05);
        border-left-color: var(--accent);
    }

    [data-theme="dark"] .notification-item.unread {
        background: rgba(74, 143, 255, 0.1);
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .notification-icon.icon-info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .notification-icon.icon-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .notification-icon.icon-warning {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
    }

    .notification-icon.icon-like {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-text {
        font-size: 14px;
        color: var(--text-body);
        line-height: 1.4;
        margin-bottom: 4px;
    }

    .notification-text strong {
        font-weight: 600;
        color: var(--text-heading);
    }

    .notification-time {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notification-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent);
        flex-shrink: 0;
    }

    /* ===== MESSAGE ITEMS ===== */
    .message-item {
        padding: 12px 20px;
        display: flex;
        gap: 12px;
        cursor: pointer;
        transition: background 0.15s ease;
        position: relative;
    }

    .message-item:hover {
        background: var(--hover-bg);
    }

    .message-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 60%;
        background: var(--accent);
        border-radius: 0 2px 2px 0;
    }

    .message-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
        position: relative;
    }

    .message-status {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid var(--card);
    }

    .message-status.online {
        background: #10b981;
        z-index: 1000;
    }

    .message-status.away {
        background: #f59e0b;
    }

    .message-status.offline {
        background: #9ca3af;
    }

    .message-content {
        flex: 1;
        min-width: 0;
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .message-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-heading);
    }

    .message-time {
        font-size: 12px;
        color: var(--text-muted);
    }

    .message-preview {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .message-item.unread .message-preview {
        color: var(--text-body);
        font-weight: 500;
    }

    /* ===== TYPING INDICATOR ===== */
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    .typing-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--text-muted);
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-10px);
        }
    }

    /* ===== DROPDOWN FOOTER ===== */
    .dropdown-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--border);
        background: var(--card);
        position: sticky;
        bottom: 0;
    }

    .dropdown-footer-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        color: var(--accent);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .dropdown-footer-link:hover {
        background: rgba(74, 143, 255, 0.1);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        background: var(--hover-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: var(--text-muted);
    }

    .empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: 4px;
    }

    .empty-text {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* ===== PROFILE DROPDOWN ===== */
    .profile-dropdown {
        position: fixed;
        background: var(--card);
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 9999;
        width: 320px;
        overflow: hidden;
    }

    [data-theme="dark"] .profile-dropdown {
        background: #242526;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
    }

    .profile-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    @media (min-width: 768px) {
        .profile-dropdown {
            top: 60px;
            right: 20px;
        }
    }

    @media (max-width: 767px) {
        .profile-dropdown {
            top: 60px;
            right: 10px;
            left: 10px;
            width: auto;
            max-width: 380px;
            margin: 0 auto;
        }
    }

    .profile-card {
        padding: 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .profile-avatar-mini {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
    }

    .profile-info {
        flex: 1;
        min-width: 0;
    }

    .profile-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: 2px;
    }

    .profile-bio {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.4;
    }

    .profile-menu-list {
        padding: 8px 0;
    }

    .profile-menu-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        color: var(--text-body);
        text-decoration: none;
        transition: background 0.15s ease;
        cursor: pointer;
    }

    .profile-menu-link:hover {
        background: var(--hover-bg);
    }

    .profile-menu-icon {
        width: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 16px;
    }

    .profile-menu-text {
        font-size: 14px;
        font-weight: 500;
        flex: 1;
    }

    .profile-divider {
        height: 1px;
        background: var(--border);
        margin: 8px 0;
    }

    .dark-mode-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        cursor: pointer;
    }

    .dark-mode-toggle:hover {
        background: var(--hover-bg);
    }

    .dark-mode-label {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toggle-switch {
        position: relative;
        width: 48px;
        height: 24px;
        background: #ccc;
        border-radius: 12px;
        transition: background 0.3s ease;
    }

    [data-theme="dark"] .toggle-switch {
        background: var(--accent);
    }

    .toggle-slider {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #f59e0b;
    }

    [data-theme="dark"] .toggle-slider {
        transform: translateX(24px);
        color: #8b5cf6;
    }

    .profile-signout {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .profile-signout:hover {
        background: rgba(239, 68, 68, 0.2);
    }
</style>


<!-- NAVBAR (HTML ONLY, NO CSS CHANGES) -->
<nav class="top-nav">
    <div class="nav-inner">
        <!-- Mobile View -->
        <div class="nav-row nav-row--top">
            <img class="nav-avatar" src="{{ $user->avatar ?: ('https://i.pravatar.cc/64?u=' . urlencode($user->email ?? $user->id ?? $user->name ?? 'user')) }}" alt="{{ $user->name }}">
            <a class="brand" href="{{route('tenant.profile', $username)}}">
                
                <img class="brand-logo" src="{{ asset('assets/images/logos/croped/logo_light.png') }}" alt="Brand"
                    data-theme-src-light="<img class='brand-logo'
                    src='{{ asset('assets/images/logos/croped/logo_light.png') }}' "
                            data-theme-src-dark="<img class='brand-logo'
                    src='{{ asset('assets/images/logos/croped/logo_dark.png') }}' ">
            </a>
            <button class="share-btn">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
            </button>
        </div>

        <div class="nav-row nav-row--bottom">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Search">
            </div>
            <button class="icon-btn" id="messagesBtnMobile">
                <i class="fa-regular fa-message"></i>
                <span class="badge">3</span>
            </button>
            <button class="icon-btn" id="notificationsBtnMobile">
                <i class="fa-regular fa-bell"></i>
                <span class="badge badge-dot"></span>
            </button>
        </div>

        <!-- Desktop View -->
        <div class="nav-row nav-row--desktop">
            <a class="brand" href="#">
                <img class="brand-logo" src="{{ asset('assets/images/logos/croped/logo_light.png') }}"
                    alt="Brand" data-theme-src-light="{{ asset('assets/images/logos/croped/logo_light.png') }}"
                    data-theme-src-dark="{{ asset('assets/images/logos/croped/logo_dark.png') }}">
            </a>

            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Search for people, jobs, companies...">
            </div>

            <div class="actions">
                <button class="icon-btn" id="messagesBtn">
                    <i class="fa-regular fa-message"></i>
                    <span class="badge">3</span>
                </button>
                <button class="icon-btn" id="notificationsBtn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge">5</span>
                </button>
                <button class="share-btn">
                    <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                </button>
                <img class="nav-avatar" src="{{ $user->avatar ?: ('https://i.pravatar.cc/64?u=' . urlencode($user->email ?? $user->id ?? $user->name ?? 'user')) }}" alt="{{ $user->name }}">
            </div>
        </div>
    </div>
</nav>

<!-- Notifications Dropdown -->
<div class="dropdown notifications-dropdown" id="notificationsDropdown">
    <div class="dropdown-header">
        <h3 class="dropdown-title">Notifications</h3>
        <div class="dropdown-actions">
            <button class="dropdown-action-btn">
                <i class="fa-solid fa-check-double"></i> Mark all read
            </button>
            <button class="dropdown-action-btn">
                <i class="fa-solid fa-gear"></i>
            </button>
        </div>
    </div>

    <div class="dropdown-tabs">
        <button class="dropdown-tab active" data-tab="all">All</button>
        <button class="dropdown-tab" data-tab="mentions">Mentions</button>
        <button class="dropdown-tab" data-tab="updates">Updates</button>
    </div>

    <div class="dropdown-content">
        <!-- All Notifications -->
        <div class="tab-content" data-content="all">
            <div class="notification-item unread">
                <div class="notification-icon icon-like">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>Sarah Johnson</strong> liked your post about " Building scalable web applications"
                    </div>
                    <div class="notification-time">
                        <span class="notification-dot"></span>
                        2 minutes ago
                    </div>
                </div>
            </div>

            <div class="notification-item unread">
                <div class="notification-icon icon-info">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>Michael Chen</strong> started following you
                    </div>
                    <div class="notification-time">
                        <span class="notification-dot"></span>
                        15 minutes ago
                    </div>
                </div>
            </div>

            <div class="notification-item unread">
                <div class="notification-icon icon-success">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        Your project <strong>"E-commerce Platform"</strong> was successfully deployed
                    </div>
                    <div class="notification-time">
                        <span class="notification-dot"></span>
                        1 hour ago
                    </div>
                </div>
            </div>

            <div class="notification-item">
                <div class="notification-icon icon-warning">
                    <i class="fa-solid fa-code-branch"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>Emma Davis</strong> requested to merge changes into main branch
                    </div>
                    <div class="notification-time">
                        2 hours ago
                    </div>
                </div>
            </div>

            <div class="notification-item">
                <div class="notification-icon icon-info">
                    <i class="fa-solid fa-comment"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>Alex Thompson</strong> commented on your design: "Great work on the UI!"
                    </div>
                    <div class="notification-time">
                        3 hours ago
                    </div>
                </div>
            </div>
        </div>

        <!-- Mentions Tab -->
        <div class="tab-content" data-content="mentions" style="display: none;">
            <div class="notification-item unread">
                <div class="notification-icon icon-info">
                    <i class="fa-solid fa-at"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>David Kim</strong> mentioned you in "Project Planning Discussion"
                    </div>
                    <div class="notification-time">
                        <span class="notification-dot"></span>
                        30 minutes ago
                    </div>
                </div>
            </div>
        </div>

        <!-- Updates Tab -->
        <div class="tab-content" data-content="updates" style="display: none;">
            <div class="notification-item">
                <div class="notification-icon icon-success">
                    <i class="fa-solid fa-rocket"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        New features released in version 2.4.0
                    </div>
                    <div class="notification-time">
                        1 day ago
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dropdown-footer">
        <a href="#" class="dropdown-footer-link">
            <i class="fa-solid fa-bell"></i>
            View all notifications
        </a>
    </div>
</div>

<!-- Messages Dropdown -->
<div class="dropdown messages-dropdown" id="messagesDropdown">
    <div class="dropdown-header">
        <h3 class="dropdown-title">Messages</h3>
        <div class="dropdown-actions">
            <button class="dropdown-action-btn">
                <i class="fa-solid fa-edit"></i> New
            </button>
            <button class="dropdown-action-btn">
                <i class="fa-solid fa-filter"></i>
            </button>
        </div>
    </div>

    <div class="dropdown-tabs">
        <button class="dropdown-tab active" data-tab="all-messages">All</button>
        <button class="dropdown-tab" data-tab="unread">Unread (3)</button>
        <button class="dropdown-tab" data-tab="archived">Archived</button>
    </div>

    <div class="dropdown-content">
        <!-- All Messages -->
        <div class="tab-content" data-content="all-messages">
            <div class="message-item unread">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/100?img=5" alt="Sarah">
                    <span class="message-status online"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Sarah Johnson</span>
                        <span class="message-time">2m</span>
                    </div>
                    <div class="message-preview">
                        Hey! I just reviewed your latest pull request. The implementation looks solid, but I have a
                        few suggestions...
                    </div>
                    <div class="typing-indicator">
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    </div>
                </div>
            </div>

            <div class="message-item unread">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/100?img=8" alt="Michael">
                    <span class="message-status online"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Michael Chen</span>
                        <span class="message-time">15m</span>
                    </div>
                    <div class="message-preview">
                        Can we schedule a quick call to discuss the new feature requirements? I have some ideas that
                        might help.
                    </div>
                </div>
            </div>

            <div class="message-item unread">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/100?img=12" alt="Emma">
                    <span class="message-status away"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Emma Davis</span>
                        <span class="message-time">1h</span>
                    </div>
                    <div class="message-preview">
                        Thanks for your help with the deployment! Everything is running smoothly now 🎉
                    </div>
                </div>
            </div>

            <div class="message-item">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/100?img=3" alt="Alex">
                    <span class="message-status offline"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Alex Thompson</span>
                        <span class="message-time">3h</span>
                    </div>
                    <div class="message-preview">
                        The client loved the new design! They want to move forward with the implementation phase.
                    </div>
                </div>
            </div>

            <div class="message-item">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/100?img=20" alt="Lisa">
                    <span class="message-status offline"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Lisa Rodriguez</span>
                        <span class="message-time">5h</span>
                    </div>
                    <div class="message-preview">
                        Here's the updated documentation for the API endpoints. Let me know if you need any
                        clarification.
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread Tab -->
        <div class="tab-content" data-content="unread" style="display: none;">
            <!-- Same unread messages -->
        </div>

        <!-- Archived Tab -->
        <div class="tab-content" data-content="archived" style="display: none;">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-solid fa-archive"></i>
                </div>
                <div class="empty-title">No archived messages</div>
                <div class="empty-text">Messages you archive will appear here</div>
            </div>
        </div>
    </div>

    <div class="dropdown-footer">
        <a href="#" class="dropdown-footer-link">
            <i class="fa-solid fa-message"></i>
            See all messages
        </a>
    </div>
</div>

<!-- PROFILE Dropdown (dynamic name/headline/avatar) -->
<div class="profile-dropdown" id="profileDropdown">
    <div class="profile-card">
        <img class="profile-avatar-mini" src="{{ $user->avatar ?: ('https://i.pravatar.cc/96?u=' . urlencode($user->email ?? $user->id ?? $user->name ?? 'user')) }}" alt="{{ $user->name }}">
        <div class="profile-info">
            <div class="profile-name">{{ $user->name }}</div>
            @if(!empty($user->headline))
                <div class="profile-bio">{{ $user->headline }}</div>
            @endif
            {{-- @if(!empty($user->bio))
                <div class="profile-bio-text">{{ \Illuminate\Support\Str::limit(strip_tags($user->bio), 140) }}</div>
            @endif --}}
        </div>
    </div>

    <div class="profile-menu-list">
        <a href="#" class="profile-menu-link">
            <div class="profile-menu-icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <span class="profile-menu-text">Profile</span>
        </a>

        <a href="#" class="profile-menu-link">
            <div class="profile-menu-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <span class="profile-menu-text">Dashboard</span>
        </a>

        <a href="#" class="profile-menu-link">
            <div class="profile-menu-icon">
                <i class="fa-solid fa-gear"></i>
            </div>
            <span class="profile-menu-text">Settings</span>
        </a>

        <div class="profile-divider"></div>

        <div class="dark-mode-toggle" id="darkModeToggle">
            <div class="dark-mode-label">
                <div class="profile-menu-icon">
                    <i class="fa-solid fa-moon"></i>
                </div>
                <span class="profile-menu-text">Dark Mode</span>
            </div>
            <div class="toggle-switch">
                <div class="toggle-slider">
                    <i class="fa-solid fa-sun"></i>
                </div>
            </div>
        </div>

        <div class="profile-divider"></div>

        <div class="profile-signout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Sign Out</span>
        </div>
    </div>
</div>

<!-- SHARE DROPDOWN (new) -->
<div class="dropdown share-dropdown" id="shareDropdown">
    <div class="dropdown-header">
        <h3 class="dropdown-title">Share profile</h3>
        <div class="dropdown-actions">
            <button class="dropdown-action-btn" id="copyProfileLinkBtn">
                <i class="fa-regular fa-copy"></i> Copy link
            </button>
        </div>
    </div>

    <div class="profile-menu-list">
        <a href="#" class="profile-menu-link share-action" data-network="linkedin">
            <div class="profile-menu-icon"><i class="fa-brands fa-linkedin"></i></div>
            <span class="profile-menu-text">Share on LinkedIn</span>
        </a>
        <a href="#" class="profile-menu-link share-action" data-network="x">
            <div class="profile-menu-icon"><i class="fa-brands fa-x-twitter"></i></div>
            <span class="profile-menu-text">Share on X (Twitter)</span>
        </a>
        <a href="#" class="profile-menu-link share-action" data-network="facebook">
            <div class="profile-menu-icon"><i class="fa-brands fa-facebook"></i></div>
            <span class="profile-menu-text">Share on Facebook</span>
        </a>
        <a href="#" class="profile-menu-link share-action" data-network="whatsapp">
            <div class="profile-menu-icon"><i class="fa-brands fa-whatsapp"></i></div>
            <span class="profile-menu-text">Share on WhatsApp</span>
        </a>
        <a href="#" class="profile-menu-link share-action" data-network="telegram">
            <div class="profile-menu-icon"><i class="fa-brands fa-telegram"></i></div>
            <span class="profile-menu-text">Share on Telegram</span>
        </a>
        <a href="#" class="profile-menu-link share-action" data-network="email">
            <div class="profile-menu-icon"><i class="fa-solid fa-envelope"></i></div>
            <span class="profile-menu-text">Share via Email</span>
        </a>
    </div>

    <div class="dropdown-footer">
        <a href="#" class="dropdown-footer-link" id="systemShareBtn">
            <i class="fa-solid fa-share-nodes"></i>
            Share via device…
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const notificationsBtn = document.getElementById('notificationsBtn');
    const notificationsBtnMobile = document.getElementById('notificationsBtnMobile');
    const messagesBtn = document.getElementById('messagesBtn');
    const messagesBtnMobile = document.getElementById('messagesBtnMobile');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const messagesDropdown = document.getElementById('messagesDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    const navAvatars = document.querySelectorAll('.nav-avatar');
    const darkModeToggle = document.getElementById('darkModeToggle');

    // NEW: Share
    const shareDropdown = document.getElementById('shareDropdown');
    const shareBtns = document.querySelectorAll('.share-btn');
    const copyProfileLinkBtn = document.getElementById('copyProfileLinkBtn');
    const systemShareBtn = document.getElementById('systemShareBtn');

    // Helper: position dropdown near a button (like LinkedIn)
    function positionDropdownNear(dropdown, button) {
        if (!dropdown || !button) return;
        const rect = button.getBoundingClientRect();

        dropdown.style.position = 'fixed';
        dropdown.style.left = 'auto';
        dropdown.style.right = (window.innerWidth - rect.right) + 'px';
        dropdown.style.top = (rect.bottom + 8) + 'px';

        // after it becomes visible, adjust for overflow
        dropdown.classList.add('active');
        requestAnimationFrame(() => {
            const dRect = dropdown.getBoundingClientRect();

            // If off the right edge, align to left of button
            if (dRect.right > window.innerWidth) {
                dropdown.style.right = 'auto';
                dropdown.style.left = rect.left + 'px';
            }
            // If off the bottom, show above button
            if (dRect.bottom > window.innerHeight) {
                dropdown.style.top = (rect.top - dRect.height - 8) + 'px';
            }
        });
    }

    // Core toggle (close others, then toggle current)
    function toggleDropdown(dropdown, button) {
        [notificationsDropdown, messagesDropdown, profileDropdown, shareDropdown].forEach(d => {
            if (d && d !== dropdown) d.classList.remove('active');
        });
        if (!dropdown) return;
        if (dropdown === shareDropdown && button) {
            // Special: share dropdown is anchored to the clicked share button
            if (!dropdown.classList.contains('active')) {
                positionDropdownNear(dropdown, button);
            } else {
                dropdown.classList.remove('active');
            }
        } else {
            dropdown.classList.toggle('active');
        }
    }

    // Notifications
    notificationsBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown(notificationsDropdown, notificationsBtn);
    });
    notificationsBtnMobile?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown(notificationsDropdown, notificationsBtnMobile);
    });

    // Messages
    messagesBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown(messagesDropdown, messagesBtn);
    });
    messagesBtnMobile?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown(messagesDropdown, messagesBtnMobile);
    });

    // Profile
    navAvatars.forEach(avatar => {
        avatar.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(profileDropdown, avatar);
        });
    });

    // NEW: Share (attach to both desktop & mobile buttons)
    shareBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown(shareDropdown, btn);
        });
    });

    // Close on outside click (keeps open when clicking ANY dropdown, profile dropdown, avatar, icon-btn, or share-btn)
    document.addEventListener('click', function(e) {
        if (
            !e.target.closest('.icon-btn') &&
            !e.target.closest('.dropdown') &&
            !e.target.closest('.profile-dropdown') &&
            !e.target.closest('.nav-avatar') &&
            !e.target.closest('.share-btn')
        ) {
            [notificationsDropdown, messagesDropdown, profileDropdown, shareDropdown].forEach(d => {
                d?.classList.remove('active');
            });
        }
    });

    // Tabs inside dropdowns
    document.querySelectorAll('.dropdown-tabs').forEach(tabContainer => {
        const tabs = tabContainer.querySelectorAll('.dropdown-tab');
        const dropdown = tabContainer.closest('.dropdown');

        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.stopPropagation(); // prevent closing
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const tabName = tab.dataset.tab;
                const contents = dropdown.querySelectorAll('.tab-content');
                contents.forEach(content => {
                    content.style.display = (content.dataset.content === tabName) ? 'block' : 'none';
                });
            });
        });
    });

    // Dark mode toggle
    darkModeToggle?.addEventListener('click', function(e) {
        e.stopPropagation();
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);

        const icon = this.querySelector('.toggle-slider i');
        icon.className = newTheme === 'dark' ? 'fa-solid fa-moon' : 'fa-solid fa-sun';

        updateLogos(newTheme);
        localStorage.setItem('theme', newTheme);
    });

    function updateLogos(theme) {
        document.querySelectorAll('.brand-logo').forEach(logo => {
            if (theme === 'dark') {
                logo.src = logo.dataset.themeSrcDark || '{{ asset('assets/images/logos/croped/logo_dark.png') }}';
            } else {
                logo.src = logo.dataset.themeSrcLight || '{{ asset('assets/images/logos/croped/logo_light.png') }}';
            }
        });
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        const icon = darkModeToggle?.querySelector('.toggle-slider i');
        if (icon) icon.className = 'fa-solid fa-moon';
        updateLogos('dark');
    }

    // Mark items read
    document.querySelectorAll('.notification-item, .message-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation(); // keep dropdown open when interacting
            this.classList.remove('unread');
            const dot = this.querySelector('.notification-dot');
            if (dot) dot.style.display = 'none';
        });
    });

    // ESC closes all
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [notificationsDropdown, messagesDropdown, profileDropdown, shareDropdown].forEach(d => {
                d?.classList.remove('active');
            });
        }
    });

    // NEW: Share actions
    const shareTitle = @json((($user->name ?? 'Profile') . (!empty($user->headline) ? ' – ' . $user->headline : '')));
    function openShare(network) {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(shareTitle || document.title || 'My profile');

        let shareUrl = '';
        switch (network) {
            case 'linkedin':
                shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                break;
            case 'x':
                shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${text}`;
                break;
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                break;
            case 'whatsapp':
                shareUrl = `https://wa.me/?text=${text}%20${url}`;
                break;
            case 'telegram':
                shareUrl = `https://t.me/share/url?url=${url}&text=${text}`;
                break;
            case 'email':
                shareUrl = `mailto:?subject=${text}&body=${text}%0A%0A${url}`;
                break;
        }
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'noopener');
            shareDropdown.classList.remove('active');
        }
    }

    document.querySelectorAll('.share-action').forEach(a => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const network = a.getAttribute('data-network');
            openShare(network);
        });
    });

    copyProfileLinkBtn?.addEventListener('click', async (e) => {
        e.preventDefault();
        e.stopPropagation();
        try {
            await navigator.clipboard.writeText(window.location.href);
            alert('Profile link copied!');
        } catch {
            // Fallback
            const tmp = document.createElement('input');
            tmp.value = window.location.href;
            document.body.appendChild(tmp);
            tmp.select();
            document.execCommand('copy');
            document.body.removeChild(tmp);
            alert('Profile link copied!');
        }
        shareDropdown.classList.remove('active');
    });

    systemShareBtn?.addEventListener('click', async (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (navigator.share) {
            try {
                await navigator.share({ title: shareTitle, url: window.location.href });
            } catch (_) {}
        } else {
            // Fallback to copy
            try {
                await navigator.clipboard.writeText(window.location.href);
                alert('Link copied! (System share not available)');
            } catch {
                alert('System share not available');
            }
        }
        shareDropdown.classList.remove('active');
    });

    // Close share dropdown on scroll/resize (like LinkedIn behavior)
    window.addEventListener('scroll', () => shareDropdown?.classList.remove('active'), { passive: true });
    window.addEventListener('resize', () => shareDropdown?.classList.remove('active'));
});
</script>
