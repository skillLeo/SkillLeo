<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== CSS VARIABLES ===== */
    :root {
        --nav-bg: rgba(255, 255, 255, 0.90);
        --nav-border: rgba(0, 0, 0, 0.08);
        --accent-hover: #004182;
        --text-heading: #000000;
        --text-body: #333333;
        --text-muted: #666666;
        --text-subtle: #999999;
        --card: #ffffff;
        --border: rgba(0, 0, 0, 0.1);
        --hover-bg: rgba(0, 0, 0, 0.08);
        --badge-red: #e11d48;
        --badge-blue: #0a66c2;
        --badge-green: #057a55;
    }

    [data-theme="dark"] {
        --nav-bg: rgba(27, 31, 35, 0.90);
        --nav-border: rgba(255, 255, 255, 0.12);
        --accent-hover: #a3d0ff;
        --text-heading: #ffffff;
        --text-body: #e4e6eb;
        --text-muted: #b0b3b8;
        --text-subtle: #8a8d91;
        --card: #1c1e21;
        --border: rgba(255, 255, 255, 0.1);
        --hover-bg: rgba(255, 255, 255, 0.1);
    }

  
 

    /* ===== NAVIGATION BASE ===== */
    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--nav-bg);
        backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid var(--nav-border);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        z-index: 1000;
        transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] .top-nav {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    .top-nav.scrolled {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] .top-nav.scrolled {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
    }

    .nav-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 5px 24px;
    }

    .nav-row {
        display: none;
    }

    /* ===== DESKTOP NAVIGATION ===== */
 

    .nav-row--bottom{margin-top: 10px;}

    /* ===== MOBILE NAVIGATION ===== */
    @media (max-width: 768px) {
        body {
            padding-top: 120px;
        }

        .nav-row--desktop {
            display: none;
        }

        .nav-inner {
            padding: 0 16px;
        }

        .nav-row--top,
        .nav-row--bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Smooth scroll animation */
        .nav-row--top {
            opacity: 1;
            transform: translateY(0);
            max-height: 60px;
            overflow: hidden;
            padding-bottom: 0;

        }

        .top-nav.scrolled .nav-row--top {
            opacity: 0;
            transform: translateY(-100%);
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            margin-bottom: -10px;
        }

        .nav-row--bottom {
            position: relative;
        }

        .top-nav.scrolled .nav-row--bottom {
            padding-top: 12px;
        }
    }

    /* ===== BRAND LOGO ===== */
    .brand {
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 8px;
        margin-right: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .brand:hover {
        background: var(--hover-bg);
        transform: scale(1.02);
    }

    .brand:active {
        transform: scale(0.90);
    }

    .brand-logo {
        height: 38px;
        width: auto;
        max-width: 180px;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .brand {
            margin-right: 0;
            padding: 4px 8px;
        }

        .brand-logo {
            height: 32px;
            max-width: 140px;
        }
    }

    /* ===== SEARCH BAR ===== */
    .search-wrap {
        flex: 1;
        max-width: 320px;
        position: relative;
        background: rgba(234, 237, 242, 0.8);
        border: 1px solid transparent;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 12px;
        height: 36px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 0;
    }

    [data-theme="dark"] .search-wrap {
        background: rgba(255, 255, 255, 0.08);
    }

    .search-wrap:focus-within {
        background: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(10, 102, 194, 0.15);
    }

    [data-theme="dark"] .search-wrap:focus-within {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 3px rgba(112, 181, 249, 0.2);
    }

    .search-wrap .fa-magnifying-glass {
        font-size: 15px;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .search-wrap:focus-within .fa-magnifying-glass {
        color: var(--accent);
    }

    .search-input {
        flex: 1;
        border: none;
        background: none;
        outline: none;
        font-size: 14px;
        font-weight: 400;
        color: var(--text-body);
        transition: all 0.2s ease;
    }

    .search-input::placeholder {
        color: var(--text-muted);
    }

    /* Search as nav-item (768px - 950px) */
    @media (max-width: 950px) and (min-width: 769px) {
        .search-wrap {
            max-width: 60px;
            width: 60px;
            height: 52px;
            padding: 0;
            justify-content: center;
            cursor: pointer;
            background: transparent;
            border-radius: 8px;
        }

        .search-wrap .fa-magnifying-glass {
            font-size: 20px;
            color: var(--text-muted);
        }

        .search-wrap:hover {
            background: var(--hover-bg);
        }

        .search-wrap:hover .fa-magnifying-glass {
            color: var(--text-heading);
        }

        .search-input {
            display: none;
        }

        .search-wrap.expanded {
            max-width: 300px;
            width: 300px;
            padding: 0 12px;
            background: rgba(234, 237, 242, 0.8);
            justify-content: flex-start;
            gap: 10px;
            height: 36px;
            border-radius: 6px;
        }

        [data-theme="dark"] .search-wrap.expanded {
            background: rgba(255, 255, 255, 0.08);
        }

        .search-wrap.expanded .search-input {
            display: block;
        }

        .search-wrap.expanded .fa-magnifying-glass {
            font-size: 15px;
        }

        .search-wrap.expanded:focus-within {
            background: #ffffff;
        }

        [data-theme="dark"] .search-wrap.expanded:focus-within {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Add search text below icon */
        .search-wrap::after {
            content: 'Search';
            position: absolute;
            bottom: -18px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.2s ease;
        }

        .search-wrap:hover::after {
            color: var(--text-heading);
        }

        .search-wrap.expanded::after {
            display: none;
        }
    }

    /* ===== NAVIGATION ACTIONS ===== */
  

    /* ===== NAV ITEMS (Desktop LinkedIn Style) ===== */
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        height: 52px;
        cursor: pointer;
        text-decoration: none;
        color: var(--text-muted);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border-bottom: 3px solid transparent;
        background: transparent;
        border-radius: 0;
    }

    .nav-item:hover {
        color: var(--text-heading);
    }

    .nav-item:hover .nav-item-icon {
        color: var(--text-heading);
    }

    .nav-item:active {
        transform: scale(0.96);
    }

    .nav-item.active {
        color: var(--text-heading);
        border-bottom-color: var(--text-heading);
        font-weight: 600;
    }

    .nav-item.active .nav-item-icon {
        color: var(--text-heading);
    }

    .nav-item-icon {
        font-size: 20px;
        color: var(--text-muted);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-item:hover .nav-item-icon {
        /* transform: scale(1.1); */
    }

    .nav-item-text {
        font-size: 12px;
        font-weight: 500;
        line-height: 1;
        letter-spacing: 0.1px;
        color: var(--text-muted);

    }

    /* Badge on nav items */
    .nav-item-badge {
        position: absolute;
        top: -10px;
        right: -14px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        background: var(--badge-red);
        color: white;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        /* border: 2px solid var(--nav-bg); */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        animation: badgePulse 2s ease-in-out infinite;
    }

    @keyframes badgePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    .nav-item-badge-dot {
        min-width: 8px;
        height: 8px;
        padding: 0;
        top: 2px;
        right: 2px;
        animation: none;
    }

    /* Profile Nav Item */
    .nav-item-profile {
        padding: 0 12px;
    }

    .nav-item-profile .profile-avatar-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-item-profile .nav-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    [data-theme="dark"] .nav-item-profile .nav-avatar {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .nav-item-profile:hover .nav-avatar {
        transform: scale(1.1);
        box-shadow: 0 3px 8px rgba(10, 102, 194, 0.3);
    }

    .nav-item-profile .nav-item-text {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    .nav-item-profile .fa-caret-down {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .nav-item-profile:hover .fa-caret-down,
    .nav-item-profile.active .fa-caret-down {
        transform: rotate(180deg);
    }

    /* Divider */
    .nav-divider {
        width: 1px;
        height: 42px;
        background: var(--border);
        margin: 0 4px;
    }

    /* ===== MOBILE NAV ITEMS ===== */
    @media (max-width: 768px) {
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            min-width: 64px;
            height: 52px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-muted);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: transparent;
            border: none;
            border-radius: 8px;
        }

        .mobile-nav-item:hover {
            background: var(--hover-bg);
            color: var(--text-heading);
        }

        .mobile-nav-item:active {
            transform: scale(0.94);
        }

        .mobile-nav-item-icon {
            font-size: 20px;
            color: var(--text-muted);
            transition: all 0.2s ease;
            position: relative;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-nav-item:hover .mobile-nav-item-icon {
            color: var(--text-heading);
            /* transform: scale(1.08); */
        }

        .mobile-nav-item-text {
            font-size: 11px;
            font-weight: 500;
            line-height: 1;
            letter-spacing: 0.1px;
        }

        .mobile-nav-item .nav-item-badge {
            /* top: 2px;
            right: 4px; */
        }

        .mobile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        }

        .mobile-avatar:hover {
            transform: scale(1.08);
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(10, 102, 194, 0.25);
        }

        [data-theme="dark"] .mobile-avatar {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }

        .mobile-avatar:active {
            transform: scale(1.02);
        }
    }

    /* ===== SHARE BUTTON ===== */
    .share-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: white;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(10, 102, 194, 0.25);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
        margin-left: 8px;
    }

    .share-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(10, 102, 194, 0.35);
    }

    .share-btn:hover::before {
        opacity: 1;
    }

    .share-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(10, 102, 194, 0.3);
    }

    .share-btn i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .share-btn:hover i {
        transform: rotate(15deg) scale(1.1);
    }

    .share-btn span {
        display: none;
    }

    @media (min-width: 768px) {
        .share-btn span {
            display: inline;
        }
    }

    @media (max-width: 950px) and (min-width: 769px) {
        .share-btn {
            padding: 10px 14px;
            margin-left: 4px;
        }
    }

    @media (max-width: 768px) {
        .share-btn {
            padding: 8px 14px;
            font-size: 13px;
            border-radius: 20px;
        }
    }

    /* ===== DROPDOWNS ===== */
    .dropdown {
        position: fixed;
        background: var(--card);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-12px) scale(0.96);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 9999;
        width: 400px;
        max-height: 85vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border);
    }

    [data-theme="dark"] .dropdown {
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.6);
        border-color: rgba(255, 255, 255, 0.08);
    }

    .dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    @media (min-width: 768px) {
        .notifications-dropdown {
            top: 68px;
            right: 160px;
        }

        .messages-dropdown {
            top: 68px;
            right: 240px;
        }
    }

    @media (max-width: 767px) {
        .dropdown {
            top: 130px;
            right: 12px;
            left: 12px;
            width: auto;
            max-width: 420px;
            margin: 0 auto;
        }
    }

    .dropdown-header {
        padding: 20px 24px 16px;
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
        font-size: 18px;
        font-weight: 700;
        color: var(--text-heading);
        letter-spacing: -0.3px;
    }

    .dropdown-actions {
        display: flex;
        gap: 6px;
    }

    .dropdown-action-btn {
        padding: 8px 12px;
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dropdown-action-btn:hover {
        background: var(--hover-bg);
        color: var(--accent);
        transform: translateY(-1px);
    }

    .dropdown-action-btn:active {
        transform: translateY(0);
    }

    .dropdown-tabs {
        display: flex;
        padding: 0 24px;
        border-bottom: 2px solid var(--border);
        background: var(--card);
        position: sticky;
        top: 60px;
        z-index: 9;
        gap: 4px;
    }

    .dropdown-tab {
        flex: 1;
        padding: 14px 12px;
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        position: relative;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 8px 8px 0 0;
    }

    .dropdown-tab:hover {
        color: var(--text-heading);
        background: var(--hover-bg);
    }

    .dropdown-tab.active {
        color: var(--accent);
    }

    .dropdown-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 15%;
        right: 15%;
        height: 3px;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-hover) 100%);
        border-radius: 3px 3px 0 0;
        box-shadow: 0 -2px 8px rgba(10, 102, 194, 0.3);
    }

    .dropdown-content {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }

    .dropdown-content::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-content::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    .dropdown-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--border);
        background: var(--card);
        position: sticky;
        bottom: 0;
    }

    .dropdown-footer-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--accent);
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        padding: 10px;
        border-radius: 10px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.2px;
    }

    .dropdown-footer-link:hover {
        background: rgba(10, 102, 194, 0.1);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(10, 102, 194, 0.15);
    }

    .dropdown-footer-link:active {
        transform: translateY(0);
    }

    /* Profile Dropdown */
    .profile-dropdown {
        position: fixed;
        background: var(--card);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-12px) scale(0.96);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 9999;
        width: 340px;
        overflow: hidden;
        border: 1px solid var(--border);
    }

    [data-theme="dark"] .profile-dropdown {
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.7);
        border-color: rgba(255, 255, 255, 0.08);
    }

    .profile-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    @media (min-width: 768px) {
        .profile-dropdown {
            top: 68px;
            right: 24px;
        }
    }

    @media (max-width: 767px) {
        .profile-dropdown {
            top: 130px;
            right: 12px;
            left: 12px;
            width: auto;
            max-width: 380px;
            margin: 0 auto;
        }
    }

    .profile-card {
        padding: 24px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        gap: 14px;
        align-items: flex-start;
        background: linear-gradient(135deg, rgba(10, 102, 194, 0.03) 0%, transparent 100%);
    }

    [data-theme="dark"] .profile-card {
        background: linear-gradient(135deg, rgba(112, 181, 249, 0.08) 0%, transparent 100%);
    }

    .profile-avatar-mini {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
        border: 3px solid var(--accent);
        box-shadow: 0 3px 12px rgba(10, 102, 194, 0.25);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .profile-avatar-mini:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(10, 102, 194, 0.35);
    }

    .profile-info {
        flex: 1;
        min-width: 0;
    }

    .profile-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 4px;
        letter-spacing: -0.2px;
    }

    .profile-bio {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .profile-menu-list {
        padding: 10px 0;
    }

    .profile-menu-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 24px;
        color: var(--text-body);
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
    }

    .profile-menu-link::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--hover-bg);
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
    }

    .profile-menu-link:hover::after {
        opacity: 1;
    }

    .profile-menu-link:active {
        transform: scale(0.99);
    }

    .profile-menu-icon {
        width: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 17px;
        transition: all 0.2s ease;
    }

    .profile-menu-link:hover .profile-menu-icon {
        color: var(--accent);
        transform: scale(1.1);
    }

    .profile-menu-text {
        font-size: 14px;
        font-weight: 600;
        flex: 1;
        letter-spacing: 0.1px;
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
        padding: 12px 24px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .dark-mode-toggle::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--hover-bg);
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
    }

    .dark-mode-toggle:hover::after {
        opacity: 1;
    }

    .dark-mode-toggle:active {
        transform: scale(0.99);
    }

    .dark-mode-label {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .toggle-switch {
        position: relative;
        width: 52px;
        height: 28px;
        background: #ddd;
        border-radius: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] .toggle-switch {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        box-shadow: 0 2px 8px rgba(112, 181, 249, 0.3);
    }

    .toggle-slider {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #f59e0b;
    }

    [data-theme="dark"] .toggle-slider {
        transform: translateX(24px);
        color: #8b5cf6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .profile-signout {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 24px;
        background: linear-gradient(90deg, rgba(225, 29, 72, 0.08) 0%, transparent 100%);
        color: #e11d48;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
        font-size: 14px;
        border-radius: 0 0 16px 16px;
    }

    .profile-signout:hover {
        background: linear-gradient(90deg, rgba(225, 29, 72, 0.15) 0%, rgba(225, 29, 72, 0.05) 100%);
        transform: translateY(-1px);
    }

    .profile-signout:active {
        transform: translateY(0);
    }

    .profile-signout i {
        font-size: 16px;
    }

    /* Notification Items */
    .notification-item {
        padding: 14px 20px;
        display: flex;
        gap: 14px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 3px solid transparent;
        position: relative;
    }

    .notification-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--hover-bg);
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
    }

    .notification-item:hover::after {
        opacity: 1;
    }

    .notification-item:active {
        transform: scale(0.99);
    }

    .notification-item.unread {
        background: linear-gradient(90deg, rgba(10, 102, 194, 0.05) 0%, transparent 100%);
        border-left-color: var(--accent);
    }

    [data-theme="dark"] .notification-item.unread {
        background: linear-gradient(90deg, rgba(112, 181, 249, 0.12) 0%, transparent 100%);
    }

    .notification-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .notification-item:hover .notification-icon {
        transform: scale(1.08) rotate(5deg);
    }

    .notification-icon.icon-info {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.12) 0%, rgba(59, 130, 246, 0.08) 100%);
        color: #3b82f6;
    }

    .notification-icon.icon-success {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.12) 0%, rgba(5, 150, 105, 0.08) 100%);
        color: #059669;
    }

    .notification-icon.icon-warning {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.12) 0%, rgba(249, 115, 22, 0.08) 100%);
        color: #f97316;
    }

    .notification-icon.icon-like {
        background: linear-gradient(135deg, rgba(225, 29, 72, 0.12) 0%, rgba(225, 29, 72, 0.08) 100%);
        color: #e11d48;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-text {
        font-size: 14px;
        color: var(--text-body);
        line-height: 1.5;
        margin-bottom: 4px;
    }

    .notification-text strong {
        font-weight: 700;
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

    /* Message Items */
    .message-item {
        padding: 14px 20px;
        display: flex;
        gap: 14px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .message-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--hover-bg);
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
    }

    .message-item:hover::after {
        opacity: 1;
    }

    .message-item:active {
        transform: scale(0.99);
    }

    .message-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background: linear-gradient(180deg, var(--accent) 0%, var(--accent-hover) 100%);
        border-radius: 0 4px 4px 0;
        box-shadow: 0 0 8px rgba(10, 102, 194, 0.4);
    }

    .message-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        flex-shrink: 0;
        object-fit: cover;
        position: relative;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .message-item:hover .message-avatar {
        transform: scale(1.05);
        border-color: var(--accent);
        box-shadow: 0 4px 12px rgba(10, 102, 194, 0.3);
    }

    .message-status {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid var(--card);
        transition: all 0.3s ease;
    }

    .message-status.online {
        background: #10b981;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
        animation: onlinePulse 2s ease-in-out infinite;
    }

    @keyframes onlinePulse {
        0%, 100% { box-shadow: 0 0 8px rgba(16, 185, 129, 0.5); }
        50% { box-shadow: 0 0 12px rgba(16, 185, 129, 0.8); }
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
        font-weight: 700;
        color: var(--text-heading);
    }

    .message-time {
        font-size: 12px;
        color: var(--text-muted);
    }

    .message-preview {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .message-item.unread .message-preview {
        color: var(--text-body);
        font-weight: 500;
    }

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
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-8px); }
    }

    /* Empty State */
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
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 4px;
    }

    .empty-text {
        font-size: 14px;
        color: var(--text-muted);
    }
</style>
<style>
    /* ===== CSS VARIABLES ===== */
    :root {
        --nav-bg: rgba(255, 255, 255, 0.90);
        --nav-border: rgba(0, 0, 0, 0.08);
        --accent-hover: #004182;
        --text-heading: #000000;
        --text-body: #333333;
        --text-muted: #666666;
        --text-subtle: #999999;
        --card: #ffffff;
        --border: rgba(0, 0, 0, 0.1);
        --hover-bg: rgba(0, 0, 0, 0.08);
        --badge-red: #e11d48;
        --badge-blue: #0a66c2;
        --badge-green: #057a55;
    }

    [data-theme="dark"] {
        --nav-bg: rgba(27, 31, 35, 0.90);
        --nav-border: rgba(255, 255, 255, 0.12);
        --accent-hover: #a3d0ff;
        --text-heading: #ffffff;
        --text-body: #e4e6eb;
        --text-muted: #b0b3b8;
        --text-subtle: #8a8d91;
        --card: #1c1e21;
        --border: rgba(255, 255, 255, 0.1);
        --hover-bg: rgba(255, 255, 255, 0.1);
    }

    /* ===== NAVIGATION BASE ===== */
    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--nav-bg);
        backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid var(--nav-border);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        z-index: 1000;
        transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] .top-nav {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    .top-nav.scrolled {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] .top-nav.scrolled {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
    }

  

    .nav-row {
        display: none;
    }

    /* ===== DESKTOP NAVIGATION ===== */
    .nav-row--desktop {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 4px 0;
    }

    /* ===== MOBILE NAVIGATION ===== */
    @media (max-width: 768px) {
        body {
            padding-top: 120px;
        }

        .nav-row--desktop {
            display: none;
        }

        .nav-inner {
            padding: 0 16px;
        }

        .nav-row--top,
        .nav-row--bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-row--top {
            opacity: 1;
            transform: translateY(0);
            max-height: 60px;
            overflow: hidden;
        }

        .top-nav.scrolled .nav-row--top {
            opacity: 0;
            transform: translateY(-100%);
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            margin-bottom: -10px;
        }

        .nav-row--bottom {
            position: relative;
        }

        .top-nav.scrolled .nav-row--bottom {
            padding-top: 12px;
        }
    }

    /* ===== BRAND LOGO ===== */
    .brand {
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 8px;
        margin-right: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .brand:hover {
        background: var(--hover-bg);
        transform: scale(1.02);
    }

    .brand:active {
        transform: scale(0.90);
    }

    .brand-logo {
        height: 38px;
        width: auto;
        max-width: 180px;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .brand {
            margin-right: 0;
            padding: 4px 8px;
        }

        .brand-logo {
            height: 32px;
            max-width: 140px;
        }
    }

    /* ===== SEARCH BAR ===== */
    .search-wrap {
        flex: 1;
        max-width: 320px;
        position: relative;
        background: rgba(234, 237, 242, 0.8);
        border: 1px solid transparent;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 12px;
        height: 36px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] .search-wrap {
        background: rgba(255, 255, 255, 0.08);
    }

    .search-wrap:focus-within {
        background: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(10, 102, 194, 0.15);
    }

    [data-theme="dark"] .search-wrap:focus-within {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 3px rgba(112, 181, 249, 0.2);
    }

    .search-wrap .fa-magnifying-glass {
        font-size: 15px;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .search-wrap:focus-within .fa-magnifying-glass {
        color: var(--accent);
    }

    .search-input {
        flex: 1;
        border: none;
        background: none;
        outline: none;
        font-size: 14px;
        font-weight: 400;
        color: var(--text-body);
        transition: all 0.2s ease;
    }

    .search-input::placeholder {
        color: var(--text-muted);
    }

    /* ===== SEARCH NAV ITEM (768px - 950px) ===== */
    .search-nav-item {
        display: none;
    }


    @media (max-width: 880px) and (min-width: 769px) {
    
    .share-btn{display: none !important}
    }


    @media (max-width: 1080px) and (min-width: 769px) {
        .search-wrap {
            display: none !important;
        }

        .search-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            height: 52px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-muted);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border-bottom: 3px solid transparent;
            background: transparent;
            border-radius: 0;
        }

        .search-nav-item:hover {
            color: var(--text-heading);
        }

        .search-nav-item:active {
            transform: scale(0.96);
        }

        .search-nav-item.active {
            color: var(--text-heading);
            border-bottom-color: var(--text-heading);
        }

        .search-nav-item-icon {
            font-size: 20px;
            color: var(--text-muted);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-nav-item:hover .search-nav-item-icon {
            color: var(--text-heading);
            transform: scale(1.1);
        }

        .search-nav-item.active .search-nav-item-icon {
            color: var(--text-heading);
        }

        .search-nav-item-text {
            font-size: 12px;
            font-weight: 500;
            line-height: 1;
            letter-spacing: 0.1px;
        }

        .search-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .search-modal {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            width: 90%;
            max-width: 600px;
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border);
        }

        [data-theme="dark"] .search-modal {
            box-shadow: 0 16px 64px rgba(0, 0, 0, 0.7);
        }

        .search-modal.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .search-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-modal-input-wrap {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(234, 237, 242, 0.6);
            border-radius: 8px;
            padding: 12px 16px;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        [data-theme="dark"] .search-modal-input-wrap {
            background: rgba(255, 255, 255, 0.08);
        }

        .search-modal-input-wrap:focus-within {
            background: rgba(10, 102, 194, 0.05);
            border-color: var(--accent);
        }

        .search-modal-input-wrap .fa-magnifying-glass {
            font-size: 18px;
            color: var(--accent);
        }

        .search-modal-input {
            flex: 1;
            border: none;
            background: none;
            outline: none;
            font-size: 16px;
            font-weight: 500;
            color: var(--text-heading);
        }

        .search-modal-input::placeholder {
            color: var(--text-muted);
        }

        .search-modal-close {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            background: var(--hover-bg);
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-size: 16px;
        }

        .search-modal-close:hover {
            background: var(--accent);
            color: white;
            transform: rotate(90deg);
        }

        .search-modal-content {
            padding: 24px;
            max-height: 400px;
            overflow-y: auto;
        }

        .search-modal-suggestions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .search-suggestion-item {
            padding: 12px 16px;
            border-radius: 8px;
            background: transparent;
            border: none;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-body);
            font-size: 14px;
        }

        .search-suggestion-item:hover {
            background: var(--hover-bg);
            color: var(--text-heading);
        }

        .search-suggestion-item i {
            font-size: 16px;
            color: var(--text-muted);
        }
    }

    /* ===== NAVIGATION ACTIONS ===== */
    .actions {
        display: flex;
        align-items: center;
        gap: .2vw;
        margin-left: auto;
    }

    /* ===== NAV ITEMS (Desktop) ===== */
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        height: 52px;
        cursor: pointer;
        text-decoration: none;
        color: var(--text-muted);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border-bottom: 3px solid transparent;
        background: transparent;
        border-radius: 0;
    }

    .nav-item:hover {
        color: var(--text-heading);
    }

    .nav-item:hover .nav-item-icon {
        color: var(--text-heading);
    }

    .nav-item:active {
        transform: scale(0.96);
    }

    .nav-item.active {
        color: var(--text-heading);
        border-bottom-color: var(--text-heading);
        font-weight: 600;
    }

    .nav-item.active .nav-item-icon {
        color: var(--text-heading);
    }

    .nav-item-icon {
        font-size: 20px;
        color: var(--text-muted);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-item:hover .nav-item-icon {
        /* transform: scale(1.1); */
    }

    .nav-item-text {
        font-size: 12px;
        font-weight: 500;
        line-height: 1;
        letter-spacing: 0.1px;
    }


    @keyframes badgePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

 

    .nav-item-profile {
        padding: 0 12px;
    }

    .nav-item-profile .profile-avatar-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-item-profile .nav-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    [data-theme="dark"] .nav-item-profile .nav-avatar {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .nav-item-profile:hover .nav-avatar {
        transform: scale(1.1);
        box-shadow: 0 3px 8px rgba(10, 102, 194, 0.3);
    }

    .nav-item-profile .nav-item-text {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    .nav-item-profile .fa-caret-down {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    .nav-item-profile:hover .fa-caret-down,
    .nav-item-profile.active .fa-caret-down {
        transform: rotate(180deg);
    }

    .nav-divider {
        width: 1px;
        height: 42px;
        background: var(--border);
        margin: 0 4px;
    }

    /* ===== MOBILE NAV ITEMS ===== */
    @media (max-width: 768px) {
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            min-width: 64px;
            height: 52px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-muted);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: transparent;
            border: none;
            border-radius: 8px;
        }

        .mobile-nav-item:hover {
            background: var(--hover-bg);
            color: var(--text-heading);
        }

        .mobile-nav-item:active {
            transform: scale(0.94);
        }

        .mobile-nav-item-icon {
            font-size: 20px;
            color: var(--text-muted);
            transition: all 0.2s ease;
            position: relative;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-nav-item:hover .mobile-nav-item-icon {
            color: var(--text-heading);
            /* transform: scale(1.08); */
        }

        .mobile-nav-item-text {
            font-size: 11px;
            font-weight: 500;
            line-height: 1;
            letter-spacing: 0.1px;
        }

      
        .mobile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        }

        .mobile-avatar:hover {
            transform: scale(1.08);
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(10, 102, 194, 0.25);
        }

        [data-theme="dark"] .mobile-avatar {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }

        .mobile-avatar:active {
            transform: scale(1.02);
        }
    }

    /* ===== SHARE BUTTON ===== */
    .share-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        color: white;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(10, 102, 194, 0.25);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
        margin-left: 8px;
    }

    .share-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(10, 102, 194, 0.35);
    }

    .share-btn:hover::before {
        opacity: 1;
    }

    .share-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(10, 102, 194, 0.3);
    }

    .share-btn i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .share-btn:hover i {
        transform: rotate(15deg) scale(1.1);
    }

    .share-btn span {
        display: none;
    }

    @media (min-width: 768px) {
        .share-btn span {
            display: inline;
        }
    }

    @media (max-width: 950px) and (min-width: 769px) {
        .share-btn {
            padding: 10px 14px;
            margin-left: 4px;
        }
    }

    @media (max-width: 768px) {
        .share-btn {
            padding: 8px 14px;
            font-size: 13px;
            border-radius: 20px;
        }
    }




    .search-nav-item{
        color: var(--text-muted);
    }


</style>


<!-- NAVIGATION -->

<nav class="top-nav" id="topNav">
    <div class="nav-inner">
        <!-- MOBILE: TOP ROW -->
        <div class="nav-row nav-row--top">
            <img class="mobile-avatar" src="{{$user->avatar}}" alt="User" id="avatarMobile">
            <a class="brand" href="#">
                <img class="brand-logo" src="{{asset('assets/images/logos/croped/logo_light.png')}}" alt="Brand"
                    data-theme-src-light="{{asset('assets/images/logos/croped/logo_light.png')}}"
                    data-theme-src-dark="{{asset('assets/images/logos/croped/logo_dark.png')}}">
            </a>
            <button class="share-btn">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
            </button>
        </div>

        <!-- MOBILE: BOTTOM ROW (STICKY) -->
        <div class="nav-row nav-row--bottom">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Search">
            </div>
            <button class="mobile-nav-item" id="messagesBtnMobile">
                <div class="mobile-nav-item-icon">
                    <i class="fa-regular fa-comment-dots"></i>
                    <span class="nav-item-badge">3</span>
                </div>
                <span class="mobile-nav-item-text">Messaging</span>
            </button>
            <button class="mobile-nav-item" id="notificationsBtnMobile">
                <div class="mobile-nav-item-icon">
                    <i class="fa-regular fa-bell"></i>
                    <span class="nav-item-badge">5</span>
                </div>
                <span class="mobile-nav-item-text">Notifications</span>
            </button>
        </div>

        <!-- DESKTOP ROW -->
        <div class="nav-row nav-row--desktop">
            <a class="brand" href="#">
                <img class="brand-logo" src="{{asset('assets/images/logos/croped/logo_light.png')}}" alt="Brand"
                    data-theme-src-light="{{asset('assets/images/logos/croped/logo_light.png')}}"
                    data-theme-src-dark="{{asset('assets/images/logos/croped/logo_dark.png')}}">
            </a>

            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Search for people, jobs, companies...">
            </div>

            <!-- SEARCH NAV ITEM (Shows 768px-950px) -->
          

            <div class="actions">
                <a href="#" class="nav-item active">
                    <div class="nav-item-icon">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="nav-item-text">Home</span>
                </a>
            
                <a href="#" class="search-nav-item" id="searchNavItem">
                    <div class="search-nav-item-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <span class="search-nav-item-text">Search</span>
                </a>
            
                <a href="#" class="nav-item">
                    <div class="nav-item-icon">
                        <i class="fa-regular fa-comments"></i>
                        <span class="nav-item-badge">99+</span>

                    </div>
                    <span class="nav-item-text">Q&A</span>
                </a>
            
                <a href="#" class="nav-item">
                    <div class="nav-item-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <span class="nav-item-text">Manage</span>
                </a>
            
                <a href="#" class="nav-item" id="messagesBtn">
                    <div class="nav-item-icon">
                        <i class="fa-regular fa-comment-dots"></i>
                        <span class="nav-item-badge">3</span>
                    </div>
                    <span class="nav-item-text">Messaging</span>
                </a>
            
                <a href="#" class="nav-item" id="notificationsBtn">
                    <div class="nav-item-icon">
                        <i class="fa-regular fa-bell"></i>
                        <span class="nav-item-badge">15</span>
                    </div>
                    <span class="nav-item-text">Notifications</span>
                </a>
            
                <div class="nav-divider"></div>
            
                <a href="#" class="nav-item nav-item-profile" id="profileBtn">
                    <div class="profile-avatar-wrapper">
                        <img class="nav-avatar" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                    </div>
                    <span class="nav-item-text">Me <i class="fa-solid fa-caret-down"></i></span>
                </a>
            
                <button class="share-btn">
                    <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Search Modal Overlay (768px-950px) -->
<div class="search-modal-overlay" id="searchModalOverlay"></div>

<!-- Search Modal (768px-950px) -->
{{-- <div class="search-modal" id="searchModal">
    <div class="search-modal-header">
        <div class="search-modal-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="search-modal-input" placeholder="Search for people, jobs, companies..." id="searchModalInput">
        </div>
        <button class="search-modal-close" id="searchModalClose">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="search-modal-content">
        <div class="search-modal-suggestions">
            <button class="search-suggestion-item">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Senior Full Stack Developer</span>
            </button>
            <button class="search-suggestion-item">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>React.js Jobs</span>
            </button>
            <button class="search-suggestion-item">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Tech Companies in San Francisco</span>
            </button>
            <button class="search-suggestion-item">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Web Development Courses</span>
            </button>
            <button class="search-suggestion-item">
                <i class="fa-solid fa-user"></i>
                <span>People in Marketing</span>
            </button>
            <button class="search-suggestion-item">
                <i class="fa-solid fa-building"></i>
                <span>Startups Hiring Remote</span>
            </button>
        </div>
    </div>
</div> --}}
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
        <div class="tab-content" data-content="all">
            <div class="notification-item unread">
                <div class="notification-icon icon-like">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">
                        <strong>Sarah Johnson</strong> liked your post about "Building scalable web applications"
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
        <div class="tab-content" data-content="all-messages">
            <div class="message-item unread">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/104?img=5" alt="Sarah">
                    <span class="message-status online"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Sarah Johnson</span>
                        <span class="message-time">2m</span>
                    </div>
                    <div class="message-preview">
                        Hey! I just reviewed your latest pull request. The implementation looks solid, but I have a few suggestions...
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
                    <img src="https://i.pravatar.cc/104?img=8" alt="Michael">
                    <span class="message-status online"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Michael Chen</span>
                        <span class="message-time">15m</span>
                    </div>
                    <div class="message-preview">
                        Can we schedule a quick call to discuss the new feature requirements? I have some ideas that might help.
                    </div>
                </div>
            </div>

            <div class="message-item unread">
                <div class="message-avatar">
                    <img src="https://i.pravatar.cc/104?img=12" alt="Emma">
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
                    <img src="https://i.pravatar.cc/104?img=3" alt="Alex">
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
                    <img src="https://i.pravatar.cc/104?img=20" alt="Lisa">
                    <span class="message-status offline"></span>
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-name">Lisa Rodriguez</span>
                        <span class="message-time">5h</span>
                    </div>
                    <div class="message-preview">
                        Here's the updated documentation for the API endpoints. Let me know if you need any clarification.
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" data-content="unread" style="display: none;"></div>

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

<!-- Profile Dropdown -->
<div class="profile-dropdown" id="profileDropdown">
    <div class="profile-card">
        <img class="profile-avatar-mini" src="{{ $user->avatar }}" alt="{{ $user->name }}">
        <div class="profile-info">
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-bio">{{ $user->headline }}</div>
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



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const topNav = document.getElementById('topNav');
    const notificationsBtn = document.getElementById('notificationsBtn');
    const notificationsBtnMobile = document.getElementById('notificationsBtnMobile');
    const messagesBtn = document.getElementById('messagesBtn');
    const messagesBtnMobile = document.getElementById('messagesBtnMobile');
    const profileBtn = document.getElementById('profileBtn');
    const avatarMobile = document.getElementById('avatarMobile');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const messagesDropdown = document.getElementById('messagesDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    const darkModeToggle = document.getElementById('darkModeToggle');

    // Mobile smooth scroll behavior
    let lastScrollTop = 0;
    let scrollTimeout;
    
    window.addEventListener('scroll', function() {
        if (window.innerWidth <= 768) {
            clearTimeout(scrollTimeout);
            
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            scrollTimeout = setTimeout(() => {
                if (scrollTop > 70) {
                    topNav.classList.add('scrolled');
                } else {
                    topNav.classList.remove('scrolled');
                }
            }, 10);
            
            lastScrollTop = scrollTop;
        }
    }, { passive: true });

    // Desktop search expand/collapse (768px-950px)
    const searchWrap = document.querySelector('.search-wrap');
    const searchInput = document.querySelector('.search-input');
    
    function handleSearchExpand() {
        const width = window.innerWidth;
        if (width > 768 && width <= 950) {
            let searchClickHandler = function(e) {
                if (!this.classList.contains('expanded')) {
                    e.stopPropagation();
                    this.classList.add('expanded');
                    setTimeout(() => searchInput?.focus(), 100);
                }
            };

            let docClickHandler = function(e) {
                if (searchWrap && !searchWrap.contains(e.target)) {
                    searchWrap.classList.remove('expanded');
                }
            };

            let blurHandler = function() {
                setTimeout(() => {
                    if (searchWrap && !searchInput.value.trim()) {
                        searchWrap.classList.remove('expanded');
                    }
                }, 200);
            };

            searchWrap?.addEventListener('click', searchClickHandler);
            document.addEventListener('click', docClickHandler);
            searchInput?.addEventListener('blur', blurHandler);
        } else {
            searchWrap?.classList.remove('expanded');
        }
    }

    handleSearchExpand();
    window.addEventListener('resize', handleSearchExpand);

    // Core toggle function
    function toggleDropdown(dropdown) {
        [notificationsDropdown, messagesDropdown, profileDropdown].forEach(d => {
            if (d && d !== dropdown) d.classList.remove('active');
        });
        if (dropdown) {
            dropdown.classList.toggle('active');
        }
    }

    // Notifications
    notificationsBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown(notificationsDropdown);
    });
    
    notificationsBtnMobile?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown(notificationsDropdown);
    });

    // Messages
    messagesBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown(messagesDropdown);
    });
    
    messagesBtnMobile?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown(messagesDropdown);
    });

    // Profile (Desktop)
    profileBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown(profileDropdown);
    });

    // Profile (Mobile Avatar)
    avatarMobile?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown(profileDropdown);
    });

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (
            !e.target.closest('.mobile-nav-item') &&
            !e.target.closest('.nav-item') &&
            !e.target.closest('.dropdown') &&
            !e.target.closest('.profile-dropdown') &&
            !e.target.closest('.mobile-avatar')
        ) {
            [notificationsDropdown, messagesDropdown, profileDropdown].forEach(d => {
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
                e.stopPropagation();
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
                logo.src = logo.dataset.themeSrcDark;
            } else {
                logo.src = logo.dataset.themeSrcLight;
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
            e.stopPropagation();
            this.classList.remove('unread');
            const dot = this.querySelector('.notification-dot');
            if (dot) dot.style.display = 'none';
        });
    });

    // ESC closes all dropdowns
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [notificationsDropdown, messagesDropdown, profileDropdown].forEach(d => {
                d?.classList.remove('active');
            });
        }
    });

    // Share button functionality
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Share functionality - integrate with your backend!');
        });
    });

    // Profile menu links
    document.querySelectorAll('.profile-menu-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Clicked:', this.querySelector('.profile-menu-text').textContent);
        });
    });

    // Sign out
    document.querySelector('.profile-signout')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to sign out?')) {
            console.log('User signed out');
            // Add your sign out logic here
        }
    });
});
</script>