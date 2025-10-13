<style>
    /* ===== BRAND LOGO ===== */
    .brand-logo-light,
    .brand-logo-dark {
        height: 40px;
        width: auto;
        max-width: 180px;
        object-fit: contain;
        display: block;
        transition: opacity 0.2s ease;
    }

    .brand:hover .brand-logo-light,
    .brand:hover .brand-logo-dark {
        opacity: 0.85;
    }

    .brand-logo-dark {
        display: none;
    }

    [data-theme="dark"] .brand-logo-light {
        display: none;
    }

    [data-theme="dark"] .brand-logo-dark {
        display: block;
    }

    @media (max-width: 768px) {

        .brand-logo-light,
        .brand-logo-dark {
            height: 35px;
            max-width: 140px;
        }
    }

    .brand-mark,
    .brand-text {
        display: none;
    }

    /* ===== NAVIGATION BASE ===== */
    .top-nav {
        background: var(--nav-bg);
        backdrop-filter: blur(12px) saturate(180%) brightness(105%);
        -webkit-backdrop-filter: blur(16px) saturate(180%) brightness(105%);
        border-bottom: 1px solid var(--nav-border);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.03);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    /* ===== DARK MODE NAVIGATION ===== */
    [data-theme="dark"] .top-nav {
        background: var(--nav-bg-gradient);
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px) saturate(160%);
        border-bottom: 1px solid var(--nav-border-dark);
        box-shadow: var(--nav-shadow-dark);
    }

    /* ===== SEARCH BAR ===== */
    .search-wrap {
        transition: all 0.25s ease;
    }

    .search-wrap:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(19, 81, 216, 0.1), 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    [data-theme="dark"] .search-wrap {
        background: var(--search-bg-dark);
        border: 1px solid var(--search-border-dark);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .search-wrap:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(74, 143, 255, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    [data-theme="dark"] .search-wrap i {
        color: var(--search-icon-dark);
        transition: color 0.2s ease;
    }

    [data-theme="dark"] .search-wrap:focus-within i {
        color: var(--accent);
    }

    [data-theme="dark"] .search-input {
        color: var(--search-text-dark);
    }

    [data-theme="dark"] .search-input::placeholder {
        color: var(--search-placeholder-dark);
    }

    /* ===== ICON BUTTONS - REFINED ===== */
    .icon-btn {
        position: relative;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .icon-btn i {
        transition: transform 0.2s ease;
    }

    .icon-btn:hover i {
        transform: scale(1.1);
    }

    .icon-btn:active {
        transform: scale(0.95);
    }

    [data-theme="dark"] .icon-btn {
        background: var(--icon-btn-bg-dark);
        color: var(--icon-color-dark);
        border: 1px solid var(--nav-border-dark);
    }

    [data-theme="dark"] .icon-btn:hover {
        background: var(--icon-btn-hover-dark);
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 143, 255, 0.2);
    }

    [data-theme="dark"] .icon-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(74, 143, 255, 0.15);
    }

    /* ===== SHARE BUTTON - PREMIUM ===== */
    .share-btn {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .share-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .share-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .share-btn i,
    .share-btn span {
        position: relative;
        z-index: 1;
    }

    .share-btn:active {
        transform: scale(0.96);
    }

    [data-theme="dark"] .share-btn {
        background: var(--share-btn-bg-dark);
        border: 1px solid rgba(74, 143, 255, 0.3);
        box-shadow: 0 4px 12px rgba(74, 143, 255, 0.2);
    }

    [data-theme="dark"] .share-btn:hover {
        background: var(--share-btn-hover-dark);
        box-shadow: 0 6px 20px rgba(74, 143, 255, 0.35);
        transform: translateY(-2px);
    }

    [data-theme="dark"] .share-btn:active {
        transform: translateY(0) scale(0.96);
        box-shadow: 0 3px 10px rgba(74, 143, 255, 0.25);
    }

    /* ===== BADGES - POLISHED ===== */
    .badge-count {
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .badge-dot {
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.7);
        }

        50% {
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(255, 59, 48, 0);
        }
    }

    [data-theme="dark"] .badge-dot,
    [data-theme="dark"] .badge-count {
        background: var(--badge-bg-dark);
        border-color: var(--badge-border-dark);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }

    /* ===== AVATAR - REFINED ===== */
    .nav-avatar {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .nav-avatar:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    [data-theme="dark"] .nav-avatar {
        border: 2px solid var(--nav-border-dark);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    [data-theme="dark"] .nav-avatar:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 20px rgba(74, 143, 255, 0.3);
    }

    /* ===== LIGHT MODE ENHANCEMENTS ===== */
    .search-wrap i {
        transition: color 0.2s ease;
    }

    .search-wrap:focus-within i {
        color: var(--accent);
    }

    /* ===== ACCESSIBILITY ===== */
    .icon-btn:focus-visible,
    .share-btn:focus-visible,
    .search-input:focus-visible {
        outline: none !important;
    }

    /* ===== RESPONSIVE POLISH ===== */
    @media (max-width: 768px) {
        .share-btn span {
            display: inline;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
        }
    }

    @media (min-width: 769px) {
        .nav-inner {
            padding: 10px 24px;
        }
    }

    .search-suggestions {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        max-height: 400px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 9999;
    }

    .search-suggestions.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    [data-theme="dark"] .search-suggestions {
        background: rgba(27, 31, 35, 0.98);
        border-color: var(--nav-border-dark);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(20px);
    }

    /* Search Header */
    .search-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    [data-theme="dark"] .search-header {
        border-color: var(--nav-border-dark);
    }

    .search-header i {
        font-size: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: sparkle 2s ease-in-out infinite;
    }

    @keyframes sparkle {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    .search-header-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.3px;
    }

    [data-theme="dark"] .search-header-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Suggestion Items */
    .suggestion-item {
        padding: 14px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .suggestion-item:hover {
        background: rgba(74, 143, 255, 0.08);
        border-left-color: var(--accent);
    }

    [data-theme="dark"] .suggestion-item:hover {
        background: rgba(74, 143, 255, 0.15);
    }

    .suggestion-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        color: var(--accent);
        transition: all 0.2s ease;
    }

    .suggestion-item:hover .suggestion-icon {
        transform: scale(1.1) rotate(5deg);

    }

    .suggestion-content {
        flex: 1;
        min-width: 0;
    }

    .suggestion-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    [data-theme="dark"] .suggestion-title {
        color: var(--text-heading);
    }

    .suggestion-match {
        color: var(--accent);
        font-weight: 700;
    }

    .suggestion-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-badge {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-trending {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .badge-ai {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        color: white;
    }

    .badge-popular {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    /* No Results */
    .no-results {
        padding: 40px 20px;
        text-align: center;
    }

    .no-results i {
        font-size: 48px;
        color: var(--text-disabled);
        margin-bottom: 12px;
        opacity: 0.5;
    }

    .no-results-text {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* Loading State */
    .search-loading {
        padding: 24px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: var(--text-muted);
        font-size: 14px;
    }

    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 3px solid var(--border);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Scrollbar */
    .search-suggestions::-webkit-scrollbar {
        width: 6px;
    }

    .search-suggestions::-webkit-scrollbar-track {
        background: transparent;
    }

    .search-suggestions::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    [data-theme="dark"] .search-suggestions::-webkit-scrollbar-thumb {
        background: var(--nav-border-dark);
    }

    /* Wrapper for positioning */
    .search-wrap {
        position: relative;
    }
 
    /* ... your existing styles ... */

    /* ===== PROFILE DROPDOWN/SIDEBAR ===== */
    .profile-menu-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 9998;
    }

    .profile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .profile-menu {
        position: fixed;
        background: var(--card);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 9999;
    }

    [data-theme="dark"] .profile-menu {
        background: rgba(27, 31, 35, 0.98);
        border: 1px solid var(--nav-border-dark);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(20px);
    }

    /* Mobile: Full-height Sidebar */
    @media (max-width: 767px) {
        .profile-menu {
            top: 0;
            right: 0;
            bottom: 0;
            width: 85%;
            max-width: 320px;
            transform: translateX(100%);
            overflow-y: auto;
        }

        .profile-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
    }

    /* Desktop/Tablet: Dropdown */
    @media (min-width: 768px) {
        .profile-menu {
            top: 60px;
            right: 20px;
            width: 320px;
            border-radius: 16px;
            transform: translateY(-10px);
        }

        .profile-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-overlay {
            display: none;
        }
    }

    /* Profile Header */
    .profile-header {
        padding: 24px 20px;
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    [data-theme="dark"] .profile-header {
        border-color: var(--nav-border-dark);
        background: linear-gradient(135deg, rgba(74, 143, 255, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }

    .profile-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--border);
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .profile-close-btn:hover {
        background: var(--accent);
        color: white;
        transform: rotate(90deg);
    }

    [data-theme="dark"] .profile-close-btn {
        background: var(--icon-btn-bg-dark);
    }

    @media (min-width: 768px) {
        .profile-close-btn {
            display: none;
        }
    }

    .profile-avatar-large {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        margin-bottom: 12px;
        border: 3px solid var(--accent);
        box-shadow: 0 4px 16px rgba(74, 143, 255, 0.2);
    }

    .profile-user-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 4px;
    }

    .profile-user-email {
        font-size: 13px;
        color: var(--text-muted);
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 6px 12px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
        border: 1px solid rgba(16, 185, 129, 0.2);
        font-size: 12px;
        font-weight: 600;
        color: #059669;
    }

    [data-theme="dark"] .profile-badge {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
        color: #10b981;
    }

    .profile-badge i {
        font-size: 10px;
    }

    /* Menu Sections */
    .profile-section {
        padding: 12px 8px;
        border-bottom: 1px solid var(--border);
    }

    [data-theme="dark"] .profile-section {
        border-color: var(--nav-border-dark);
    }

    .profile-section:last-child {
        border-bottom: none;
    }

    .profile-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-subtle);
        padding: 8px 12px;
        margin-bottom: 4px;
    }

    /* Menu Items */
    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        border: 1px solid transparent;
    }

    .profile-menu-item:hover {
        background: rgba(74, 143, 255, 0.08);
        border-color: rgba(74, 143, 255, 0.2);
        transform: translateX(4px);
    }

    [data-theme="dark"] .profile-menu-item:hover {
        background: rgba(74, 143, 255, 0.15);
    }

    .profile-menu-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .profile-menu-item:hover .profile-menu-item-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Icon Colors */
    .icon-blue {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .icon-purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .icon-green {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .icon-orange {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
    }

    .icon-red {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .icon-gray {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .icon-yellow {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    [data-theme="dark"] .icon-blue {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }

    [data-theme="dark"] .icon-purple {
        background: rgba(139, 92, 246, 0.2);
        color: #a78bfa;
    }

    [data-theme="dark"] .icon-green {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    [data-theme="dark"] .icon-orange {
        background: rgba(249, 115, 22, 0.2);
        color: #fb923c;
    }

    [data-theme="dark"] .icon-red {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
    }

    [data-theme="dark"] .icon-gray {
        background: rgba(107, 114, 128, 0.2);
        color: #9ca3af;
    }

    [data-theme="dark"] .icon-yellow {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    .profile-menu-item-content {
        flex: 1;
        min-width: 0;
    }

    .profile-menu-item-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: 2px;
    }

    .profile-menu-item-desc {
        font-size: 12px;
        color: var(--text-subtle);
        line-height: 1.3;
    }

    .profile-menu-item-arrow {
        color: var(--text-subtle);
        font-size: 14px;
        transition: transform 0.2s ease;
    }

    .profile-menu-item:hover .profile-menu-item-arrow {
        transform: translateX(4px);
    }

    /* Theme Toggle Switch */
    .theme-toggle-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 12px;
        border-radius: 10px;
    }

    .theme-switch {
        position: relative;
        width: 52px;
        height: 28px;
        background: var(--border);
        border-radius: 14px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .theme-switch.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .theme-switch-slider {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .theme-switch.active .theme-switch-slider {
        transform: translateX(24px);
    }

    .theme-switch-slider i {
        color: #f59e0b;
    }

    .theme-switch.active .theme-switch-slider i {
        color: #8b5cf6;
    }

    /* Logout Button */
    .profile-logout-btn {
        margin: 12px 8px;
        padding: 14px 12px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        border: 1px solid rgba(239, 68, 68, 0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 600;
        color: #ef4444;
    }

    .profile-logout-btn:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%);
        transform: translateX(4px);
    }

    [data-theme="dark"] .profile-logout-btn {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
        color: #f87171;
    }

    /* Scrollbar */
    .profile-menu::-webkit-scrollbar {
        width: 6px;
    }

    .profile-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .profile-menu::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    /* Avatar Clickable */
    .nav-avatar {
        cursor: pointer;
    }
 
  /* ===== MINIMAL PROFILE DROPDOWN ===== */
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

  /* Desktop positioning */
  @media (min-width: 768px) {
      .profile-dropdown {
          top: 70px;
          right: 20px;
      }
  }

  /* Mobile positioning */
  @media (max-width: 767px) {
      .profile-dropdown {
          top: 70px;
          right: 10px;
          left: 10px;
          width: auto;
          max-width: 400px;
          margin: 0 auto;
      }
  }

  /* Profile Card */
  .profile-card {
      padding: 20px;
      border-bottom: 1px solid var(--border);
      display: flex;
      gap: 12px;
      align-items: flex-start;
  }

  [data-theme="dark"] .profile-card {
      border-color: rgba(255, 255, 255, 0.1);
  }

  .profile-avatar-mini {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      flex-shrink: 0;
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
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
  }

  /* Menu List */
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
      background: rgba(0, 0, 0, 0.05);
  }

  [data-theme="dark"] .profile-menu-link:hover {
      background: rgba(255, 255, 255, 0.1);
  }

  .profile-menu-icon {
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      font-size: 16px;
  }

  .profile-menu-text {
      font-size: 14px;
      font-weight: 500;
      color: var(--text-body);
      flex: 1;
  }

  /* Divider */
  .profile-divider {
      height: 1px;
      background: var(--border);
      margin: 8px 0;
  }

  [data-theme="dark"] .profile-divider {
      background: rgba(255, 255, 255, 0.1);
  }

  /* Dark Mode Toggle - LinkedIn Style */
  .dark-mode-toggle {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      cursor: pointer;
      transition: background 0.15s ease;
  }

  .dark-mode-toggle:hover {
      background: rgba(0, 0, 0, 0.05);
  }

  [data-theme="dark"] .dark-mode-toggle:hover {
      background: rgba(255, 255, 255, 0.1);
  }

  .dark-mode-label {
      display: flex;
      align-items: center;
      gap: 12px;
  }

  /* Toggle Switch */
  .toggle-switch {
      position: relative;
      width: 48px;
      height: 24px;
      background: #ccc;
      border-radius: 12px;
      transition: background 0.3s ease;
  }

  [data-theme="dark"] .toggle-switch {
      background: #4a8fff;
  }

  .toggle-slider {
      position: absolute;
      top: 2px;
      left: 2px;
      width: 20px;
      height: 20px;
      background: white;
      border-radius: 50%;
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

  /* Visitor Mode - Facebook Style */
  .visitor-mode-section {
      background: rgba(74, 143, 255, 0.05);
      padding: 12px 20px;
      margin: 8px 0;
  }

  [data-theme="dark"] .visitor-mode-section {
      background: rgba(74, 143, 255, 0.1);
  }

  .visitor-mode-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0;
      color: var(--text-body);
      text-decoration: none;
      cursor: pointer;
  }

  .visitor-mode-link:hover {
      background: transparent;
  }

  .visitor-mode-icon-wrapper {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      flex-shrink: 0;
  }

  .visitor-mode-content {
      flex: 1;
  }

  .visitor-mode-title {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-heading);
      margin-bottom: 2px;
  }

  .visitor-mode-desc {
      font-size: 12px;
      color: var(--text-muted);
  }

  /* Sign Out */
  .profile-signout {
      padding: 10px 20px;
      color: #f23f43;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 12px;
  }

  .profile-signout:hover {
      background: rgba(242, 63, 67, 0.1);
  }

  /* Avatar clickable */
  .nav-avatar {
      cursor: pointer;
      transition: transform 0.2s ease;
  }

  .nav-avatar:hover {
      transform: scale(1.05);
  }

  /* Smooth theme transition */
  * {
      transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
  }
 
  .profile-signout {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: #ffecec;
      padding: 12px 16px;
      border-radius: 0px;
      cursor: pointer;
      transition: background 0.2s ease;
  }

  .profile-signout:hover {
      background-color: #ffd9d9;
  }

  .profile-signout i,
  .profile-signout span {
      pointer-events: none;
      /* ensures click anywhere triggers parent */
  }
</style>
<!-- Add this after your nav -->
<!-- Profile Menu Overlay -->
<div class="profile-menu-overlay" id="profileOverlay"></div>



<!-- Clean Profile Dropdown -->
<div class="profile-dropdown" id="profileDropdown">
    <!-- Profile Card -->
    <div class="profile-card">
        <img class="profile-avatar-mini" src="{{ $user->avatar ?? 'https://i.pravatar.cc/96?img=13' }}" alt="Profile">
        <div class="profile-info">
            <div class="profile-name">{{ $user->name ?? 'Hassam Mehmood' }}</div>
            <div class="profile-bio">{{ $user->bio ?? 'Full-Stack Developer | AI & Chatbot Expert' }}</div>
        </div>
    </div>

    <!-- Menu Options -->
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

        <!-- Dark Mode Toggle -->
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
    </div>

    <!-- Visitor Mode - Facebook Style -->
    <div class="visitor-mode-section">
        <a href="#" class="visitor-mode-link">
            <div class="visitor-mode-icon-wrapper">
                <i class="fa-solid fa-eye"></i>
            </div>
            <div class="visitor-mode-content">
                <div class="visitor-mode-title">See as Visitor</div>
                <div class="visitor-mode-desc">View your profile as others see it</div>
            </div>
        </a>
    </div>

    <!-- Sign Out -->
    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="profile-signout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Sign Out</span>
    </div>


    <nav class="top-nav">
        <div class="nav-inner">
            <!-- Mobile View -->
            <div class="nav-row nav-row--top">
                <img class="nav-avatar" src="{{ $user->avatar ?? 'https://i.pravatar.cc/64?img=13' }}"
                    alt="Profile">
                <a class="brand" href="#" aria-label="{{ $brandName ?? 'Portfolio' }}">
                    <img class="brand-logo brand-logo-light"
                        src="{{ asset('assets/images/logos/croped_720x200/logo_light.png') }}"
                        alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
                    <img class="brand-logo brand-logo-dark"
                        src="{{ asset('assets/images/logos/croped_720x200/logo_dark.png') }}"
                        alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
                </a>
                <button class="share-btn">
                    <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                </button>
            </div>

            <div class="nav-row nav-row--bottom">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="search-input" type="text" placeholder="Search" id="search-mobile">

                    <!-- Suggestions Dropdown -->
                    <div class="search-suggestions" id="suggestions-mobile">
                        <div class="search-header">
                            <i class="fa-solid fa-sparkles"></i>
                            <span class="search-header-text">AI-POWERED SUGGESTIONS</span>
                        </div>
                        <div id="suggestions-list-mobile"></div>
                    </div>
                </div>
                <a class="icon-btn" href="#" aria-label="Messages">
                    <i class="fa-regular fa-message"></i>
                    <span class="badge badge-count">{{ $messageCount ?? 0 }}</span>
                </a>
                <a class="icon-btn" href="#" aria-label="Notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge badge-dot"></span>
                </a>
            </div>

            <!-- Desktop View -->
            <div class="nav-row nav-row--desktop">
                <a class="brand" href="#" aria-label="{{ $brandName ?? 'Portfolio' }}">
                    <img class="brand-logo brand-logo-light"
                        src="{{ asset('assets/images/logos/croped_720x200/logo_light.png') }}"
                        alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
                    <img class="brand-logo brand-logo-dark"
                        src="{{ asset('assets/images/logos/croped_720x200/logo_dark.png') }}"
                        alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
                </a>

                <div class="search-wrap search-wrap--desktop">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="search-input" type="text" placeholder="Graphic Designer, Web Developer etc."
                        id="search-desktop">

                    <!-- Suggestions Dropdown -->
                    <div class="search-suggestions" id="suggestions-desktop">
                        <div class="search-header">
                            <i class="fa-solid fa-sparkles"></i>
                            <span class="search-header-text">AI-POWERED SUGGESTIONS</span>
                        </div>
                        <div id="suggestions-list-desktop"></div>
                    </div>
                </div>

                <div class="actions">
                    <a class="icon-btn" href="#" aria-label="Messages">
                        <i class="fa-regular fa-message"></i>
                        <span class="badge badge-count">{{ $messageCount ?? 0 }}</span>
                    </a>
                    <a class="icon-btn" href="#" aria-label="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge badge-dot"></span>
                    </a>
                    <button class="share-btn">
                        <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                    </button>
                    <img class="nav-avatar" src="{{ $user->avatar ?? 'https://i.pravatar.cc/64?img=13' }}"
                        alt="Profile">
                </div>
            </div>
        </div>
    </nav>




</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileAvatars = document.querySelectorAll('.nav-avatar');
        const profileDropdown = document.getElementById('profileDropdown');
        const darkModeToggle = document.getElementById('darkModeToggle');

        // Toggle dropdown on avatar click
        profileAvatars.forEach(avatar => {
            avatar.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('active');
            });
        });

        // Close dropdown when clicking anywhere outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.profile-dropdown') && !e.target.closest('.nav-avatar')) {
                profileDropdown.classList.remove('active');
            }
        });

        // Prevent dropdown from closing when clicking inside it
        profileDropdown?.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                profileDropdown.classList.remove('active');
            }
        });

        // Dark Mode Toggle
        darkModeToggle?.addEventListener('click', function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);

            // Update icon
            const icon = this.querySelector('.toggle-slider i');
            icon.className = newTheme === 'dark' ? 'fa-solid fa-moon' : 'fa-solid fa-sun';

            // Save preference
            localStorage.setItem('theme', newTheme);
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            const icon = darkModeToggle?.querySelector('.toggle-slider i');
            if (icon) icon.className = 'fa-solid fa-moon';
        }
    });
 
    document.addEventListener('DOMContentLoaded', function() {
        const profileAvatars = document.querySelectorAll('.nav-avatar');
        const profileMenu = document.getElementById('profileMenu');
        const profileOverlay = document.getElementById('profileOverlay');
        const profileCloseBtn = document.getElementById('profileCloseBtn');
        const themeToggle = document.getElementById('themeToggle');

        // Open profile menu
        profileAvatars.forEach(avatar => {
            avatar.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('active');
                profileOverlay.classList.toggle('active');
                document.body.style.overflow = profileMenu.classList.contains('active') ?
                    'hidden' : '';
            });
        });

        // Close profile menu
        function closeProfileMenu() {
            profileMenu.classList.remove('active');
            profileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        profileCloseBtn?.addEventListener('click', closeProfileMenu);
        profileOverlay?.addEventListener('click', closeProfileMenu);

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && profileMenu.classList.contains('active')) {
                closeProfileMenu();
            }
        });

        // Theme toggle
        themeToggle?.addEventListener('click', function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            this.classList.toggle('active');

            // Update icon
            const icon = this.querySelector('i');
            icon.className = newTheme === 'dark' ? 'fa-solid fa-moon' : 'fa-solid fa-sun';

            // Save preference
            localStorage.setItem('theme', newTheme);
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeToggle?.classList.add('active');
            const icon = themeToggle?.querySelector('i');
            if (icon) icon.className = 'fa-solid fa-moon';
        }
    });
 
    document.addEventListener('DOMContentLoaded', function() {
        // Suggestion database with icons and descriptions
        const suggestionData = [{
                title: 'Web Development',
                icon: 'fa-code',
                desc: 'Full-stack, Frontend & Backend developers',
                badge: 'trending',
                category: 'development'
            },
            {
                title: 'UI/UX Design',
                icon: 'fa-palette',
                desc: 'User interface and experience designers',
                badge: 'popular',
                category: 'design'
            },
            {
                title: 'Mobile App Development',
                icon: 'fa-mobile-screen',
                desc: 'iOS, Android & Cross-platform apps',
                badge: 'ai',
                category: 'development'
            },
            {
                title: 'Graphic Design',
                icon: 'fa-pen-nib',
                desc: 'Branding, logos & visual identity',
                badge: 'popular',
                category: 'design'
            },
            {
                title: 'SaaS Projects',
                icon: 'fa-cloud',
                desc: 'Software as a Service solutions',
                badge: 'trending',
                category: 'business'
            },
            {
                title: 'E-commerce',
                icon: 'fa-cart-shopping',
                desc: 'Online stores & marketplace platforms',
                badge: 'popular',
                category: 'business'
            },
            {
                title: 'Real Estate',
                icon: 'fa-building',
                desc: 'Property management & listings',
                badge: 'trending',
                category: 'business'
            },
            {
                title: 'Digital Marketing',
                icon: 'fa-bullhorn',
                desc: 'SEO, Social Media & Content marketing',
                badge: 'popular',
                category: 'marketing'
            },
            {
                title: 'Content Writing',
                icon: 'fa-pen-fancy',
                desc: 'Blog posts, articles & copywriting',
                badge: 'ai',
                category: 'content'
            },
            {
                title: 'Video Production',
                icon: 'fa-video',
                desc: 'Editing, animation & motion graphics',
                badge: 'trending',
                category: 'media'
            },
            {
                title: 'Photography',
                icon: 'fa-camera',
                desc: 'Product, portrait & commercial photography',
                badge: 'popular',
                category: 'media'
            },
            {
                title: 'AI & Machine Learning',
                icon: 'fa-brain',
                desc: 'Artificial intelligence solutions',
                badge: 'ai',
                category: 'tech'
            },
            {
                title: 'Blockchain Development',
                icon: 'fa-coins',
                desc: 'Web3, NFT & cryptocurrency projects',
                badge: 'trending',
                category: 'tech'
            },
            {
                title: 'Data Analysis',
                icon: 'fa-chart-line',
                desc: 'Business intelligence & insights',
                badge: 'ai',
                category: 'data'
            },
            {
                title: 'Game Development',
                icon: 'fa-gamepad',
                desc: '2D, 3D & mobile gaming',
                badge: 'popular',
                category: 'development'
            },
        ];

        // Initialize search for both mobile and desktop
        initializeSearch('search-mobile', 'suggestions-mobile', 'suggestions-list-mobile');
        initializeSearch('search-desktop', 'suggestions-desktop', 'suggestions-list-desktop');

        function initializeSearch(inputId, dropdownId, listId) {
            const searchInput = document.getElementById(inputId);
            const suggestionsDropdown = document.getElementById(dropdownId);
            const suggestionsList = document.getElementById(listId);
            let debounceTimer;

            if (!searchInput) return;

            // Show suggestions on focus
            searchInput.addEventListener('focus', function() {
                if (this.value.trim()) {
                    performSearch(this.value);
                } else {
                    showTrendingSuggestions();
                }
            });

            // Search on input with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (!query) {
                    showTrendingSuggestions();
                    return;
                }

                // Show loading state
                suggestionsList.innerHTML =
                    '<div class="search-loading"><div class="loading-spinner"></div><span>Searching...</span></div>';
                suggestionsDropdown.classList.add('active');

                debounceTimer = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            // Close on click outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-wrap')) {
                    suggestionsDropdown.classList.remove('active');
                }
            });

            function performSearch(query) {
                const lowerQuery = query.toLowerCase();
                const results = suggestionData.filter(item =>
                    item.title.toLowerCase().includes(lowerQuery) ||
                    item.desc.toLowerCase().includes(lowerQuery) ||
                    item.category.toLowerCase().includes(lowerQuery)
                );

                displayResults(results, query);
            }

            function showTrendingSuggestions() {
                const trending = suggestionData.filter(item => item.badge === 'trending').slice(0, 5);
                displayResults(trending, '');
            }

            function displayResults(results, query) {
                if (results.length === 0) {
                    suggestionsList.innerHTML = `
                    <div class="no-results">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <div class="no-results-text">No results found for "${query}"</div>
                    </div>
                `;
                } else {
                    suggestionsList.innerHTML = results.map(item => {
                        const highlightedTitle = query ?
                            item.title.replace(new RegExp(query, 'gi'), match =>
                                `<span class="suggestion-match">${match}</span>`) :
                            item.title;

                        return `
                        <div class="suggestion-item" onclick="selectSuggestion('${item.title}', '${inputId}')">
                            <div class="suggestion-icon">
                                <i class="fa-solid ${item.icon}"></i>
                            </div>
                            <div class="suggestion-content">
                                <div class="suggestion-title">
                                    ${highlightedTitle}
                                    <span class="suggestion-badge badge-${item.badge}">${item.badge}</span>
                                </div>
                                <div class="suggestion-desc">${item.desc}</div>
                            </div>
                        </div>
                    `;
                    }).join('');
                }

                suggestionsDropdown.classList.add('active');
            }
        }

        // Global function to select suggestion
        window.selectSuggestion = function(title, inputId) {
            const input = document.getElementById(inputId);
            input.value = title;
            document.querySelectorAll('.search-suggestions').forEach(el => el.classList.remove('active'));

            // You can add navigation logic here
            console.log('Selected:', title);
        };
    });
</script>
