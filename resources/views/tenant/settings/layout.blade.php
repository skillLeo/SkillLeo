@extends('layouts.app')

@section('title', 'Settings | SkillLeo')

@stack('styles')
@push('styles')
    <style>
        :root {
            --bg: #f3f2ee;
            --card: #fff;
            --ink: #1a1a1a;
            --border: #e5e5e5;
            --accent: #1351d8;
            --accent-dark: #0d3393;
            --accent-light: #1351d818;
            --space-xs: 8px;
            --space-sm: 12px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --fw-medium: 500;
            --fw-semibold: 600;
            --fw-bold: 700;
            --fs-display: clamp(1.75rem, 1.2vw + 1rem, 2.25rem);
            --fs-h2: 1.25rem;
            --fs-title: 1rem;
            --fs-body: 0.875rem;
            --fs-subtle: 0.8125rem;
            --fs-micro: 0.75rem;
            --sidebar-width: 280px;
            --right-sidebar-width: 320px;
            --text-white: #ffffff;
            --text-heading: #1a1a1a;
            --text-body: #000000af;
            --text-muted: #667085;
            --nav-icon: #444;
            --btn-text-primary: #fff;
            --mb-sections: 9px;
            --sticky-offset: 72px;
            --gradient-border: linear-gradient(135deg, #667eea 0%, #764ba2 35%, #f093fb 70%, #4facfe 100%);
            --gradient-button: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
            --nav-height-mobile: 107px;
            --nav-height-desktop: 64px;
            --input-bg: var(--card);
            --input-text: var(--ink);
            --input-border: var(--border);

            /* Layout Variables */
            --settings-sidebar-width: 280px;
            --settings-content-max: 920px;
            --settings-right-width: 320px;

            /* Spacing System */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;

            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 16px 48px rgba(0, 0, 0, 0.16);

            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-base: 0.2s ease;
            --transition-slow: 0.3s ease;

            /* Z-index Layers */
            --z-sidebar: 1002;
            --z-modal: 9999;
            --z-toggle-btn: 1003;
        }



        [data-theme="dark"] {
            /* Core Background Colors */
            --bg: #000000;
            --card: #1b1f23;
            --ink: #ffffff;

            /* Base text colors */
            --muted: #c0c0c0;
            --muted2: #888888;

            /* Border & Dividers */
            --border: #2d3135;

            /* Accent Colors */
            --accent: #4a8fff;
            --accent-dark: #2e6fd9;
            --accent-light: #4a8fff25;

            /* AI Widget */
            --apc-bg: #1b1f23;

            /* Gradients remain same */
            --gradient-border: linear-gradient(135deg,
                    #667eea 0%,
                    #764ba2 35%,
                    #f093fb 70%,
                    #4facfe 100%);
            --gradient-button: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);

            /* Text Colors by Size/Context - Dark Mode */
            --text-primary: #ffffff;
            --text-heading: #ffffff;
            --text-body: #c0c0c0;
            --text-muted: #9ca3af;
            --text-subtle: #6b7280;
            --text-disabled: #4b5563;
            --text-link: #60a5fa;
            --text-accent: #4a8fff;

            /* Nav specific */
            --nav-bg: #1b1f23;
            --nav-border: #2d3135;
            --nav-text: #c0c0c0;
            --nav-icon: #c0c0c0;

            /* Input/Form colors */
            --input-bg: #1b1f23;
            --input-border: #2d3135;
            --input-text: #ffffff;
            --input-placeholder: #6b7280;

            /* Card specific text */
            --card-title: #ffffff;
            --card-subtitle: #9ca3af;
            --card-desc: #c0c0c0;
            --card-meta: #6b7280;

            /* Button text */
            --btn-text-primary: #ffffff;
            --btn-text-secondary: #c0c0c0;

            /* Special elements */
            --tag-text: #c0c0c0;
            --tag-bg: #1b1f23;
            --tag-border: #4a8fff;
            --badge-bg: #4a8fff;
            --badge-text: #ffffff;

            /* Section headers */
            --section-title: #ffffff;
            --section-text: #c0c0c0;

            /* Avatar/Photo placeholder */
            --photo-placeholder-bg: #2d3135;
            --photo-placeholder-text: #6b7280;
            --photo-circle-bg: #2d3135;
            --photo-circle-text: #6b7280;

            /* Skill items */
            --skill-text: #c0c0c0;
            --skill-divider: #2d3135;

            /* Review card */
            --review-card-bg: #1b1f23;
            --review-card-border: #2d3135;
            --review-name: #ffffff;
            --review-location: #9ca3af;
            --review-text: #c0c0c0;
            --quote-icon: #374151;

            /* Education */
            --edu-text: #9ca3af;
            --edu-date: #6b7280;

            /* About section */
            --about-text: #c0c0c0;

            /* Upload box */
            --upload-text: #ffffff;
            --upload-placeholder: #6b7280;
            --or-text: #6b7280;
            --or-line: #2d3135;

            /* AI Creator */
            --ai-title: #ffffff;
            --ai-desc: #9ca3af;

            /* Social icons */
            --social-icon: #9ca3af;

            /* Navigation Dark Mode */
            --nav-bg-dark: rgba(27, 31, 35, 0.95);
            --nav-bg-gradient: linear-gradient(to bottom,
                    rgba(27, 31, 35, 0.98) 0%,
                    rgba(27, 31, 35, 0.92) 100%);
            --nav-border-dark: rgba(255, 255, 255, 0.08);
            --nav-shadow-dark: 0 1px 3px rgba(0, 0, 0, 0.4),
                0 4px 12px rgba(0, 0, 0, 0.25);

            /* Search Dark */
            --search-bg-dark: rgba(45, 49, 53, 0.8);
            --search-border-dark: rgba(255, 255, 255, 0.12);
            --search-icon-dark: #9ca3af;
            --search-text-dark: #ffffff;
            --search-placeholder-dark: #6b7280;

            /* Icons & Buttons Dark */
            --icon-btn-bg-dark: rgba(45, 49, 53, 0.6);
            --icon-btn-hover-dark: rgba(74, 143, 255, 0.15);
            --icon-color-dark: #c0c0c0;

            /* Share Button Dark */
            --share-btn-bg-dark: linear-gradient(135deg, #4a8fff 0%, #2e6fd9 100%);
            --share-btn-hover-dark: linear-gradient(135deg, #5b9fff 0%, #3e7fe9 100%);

            /* Badge Dark */
            --badge-bg-dark: #ef4444;
            --badge-border-dark: rgba(27, 31, 35, 0.95);
        }

        body {
            font-family: "Inter", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background: linear-gradient(180deg, rgba(19, 81, 216, 0.04) 0%, rgba(19, 81, 216, 0.02) 150px, rgba(255, 255, 255, 0) 300px);
            background-color: var(--bg);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-body);
            font-size: var(--fs-body);
            padding-top: var(--nav-height-desktop);
            -webkit-text-size-adjust: 100%;
            -moz-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }


        /* ============================================
                    2. BASE LAYOUT & CONTAINER
                    ============================================ */
        body {

            padding-top: var(--nav-height-desktop);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        }

        .settings-container {
            display: flex;
            max-width: 1600px;
            margin: 0 auto;
            min-height: 100vh;
            gap: 0;
        }

        /* ============================================
                    3. LEFT SIDEBAR STYLING
                    ============================================ */
        .settings-sidebar {
            width: var(--settings-sidebar-width);
            background: var(--card);
            border-right: 1px solid var(--border);
            position: sticky;
            top: var(--nav-height-desktop);
            height: calc(100vh - var(--nav-height-desktop));
            overflow-y: auto;
            flex-shrink: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.02);
        }

        /* Custom Scrollbar for Sidebar */
        .settings-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .settings-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .settings-sidebar::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        .settings-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* Sidebar Header */
        .settings-sidebar-header {
            padding: var(--space-2xl) var(--space-lg);
            border-bottom: 1px solid var(--border);
            background: linear-gradient(180deg, var(--card) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .settings-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-heading);
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }

        .settings-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
            font-weight: 400;
        }

        /* ============================================
                    4. SEARCH BAR IN SIDEBAR
                    ============================================ */
        .settings-search {
            padding: var(--space-lg);
            border-bottom: 1px solid var(--border);
            background: var(--card);
        }

        .settings-search-wrapper {
            position: relative;
        }

        .settings-search-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 14px;
            background: var(--bg);
            color: var(--text-body);
            transition: all var(--transition-base);
            box-sizing: border-box
        }

        .settings-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            font-size: 14px;
        }

        .settings-search-input:hover {
            border-color: rgba(19, 81, 216, 0.3);
        }

        .settings-search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(19, 81, 216, 0.08);
            background: white;
        }

        .settings-search-input::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }

        /* ============================================
                    5. NAVIGATION MENU
                    ============================================ */
        .settings-nav {
            padding: var(--space-lg) 0;
        }

        .settings-nav-section {
            margin-bottom: var(--space-xl);
        }

        .settings-nav-section-title {
            padding: 0 var(--space-lg);
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: var(--space-md);
        }

        .settings-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .settings-nav-item {
            margin: 3px 0;
        }

        .settings-nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px var(--space-lg);
            color: var(--text-body);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all var(--transition-fast);
            position: relative;
            border-radius: 0;
        }

        .settings-nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: var(--text-muted);
            transition: all var(--transition-fast);
        }

        .settings-nav-link:hover {
            background: rgba(19, 81, 216, 0.04);
            color: var(--text-heading);
            padding-left: calc(var(--space-lg) + 4px);
        }

        .settings-nav-link:hover i {
            color: var(--accent);
            transform: scale(1.1);
        }

        .settings-nav-link.active {
            background: linear-gradient(90deg, rgba(19, 81, 216, 0.1) 0%, rgba(19, 81, 216, 0.05) 100%);
            color: var(--accent);
            font-weight: 600;
        }

        .settings-nav-link.active i {
            color: var(--accent);
        }

        .settings-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--accent);
            border-radius: 0 4px 4px 0;
        }

        /* ============================================
                    6. MAIN CONTENT AREA
                    ============================================ */
        .settings-content {
            flex: 1;
            padding: var(--space-2xl);
            max-width: var(--settings-content-max);
            margin: 0 auto;
            width: 100%;
        }

        /* Page Header */
        .settings-page-header {
            margin-bottom: var(--space-2xl);
        }

        .settings-page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-heading);
            margin: 0 0 10px 0;
            letter-spacing: -0.8px;
        }

        .settings-page-desc {
            font-size: 15px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
        }

        /* ============================================
                    7. CARDS - MODERN DESIGN
                    ============================================ */
        .settings-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: var(--space-xl);
            margin-bottom: var(--space-xl);
            transition: all var(--transition-base);
            box-shadow: var(--shadow-sm);
        }

        .settings-card:hover {
            box-shadow: var(--shadow-md);
            border-color: rgba(19, 81, 216, 0.15);
            transform: translateY(-2px);
        }

        .settings-card-header {
            margin-bottom: var(--space-xl);
            padding-bottom: var(--space-lg);
            border-bottom: 1px solid var(--border);
        }

        .settings-card-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 8px 0;
            letter-spacing: -0.3px;
        }

        .settings-card-desc {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
        }

        .settings-card-body {
            margin-bottom: var(--space-lg);
        }

        .settings-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: var(--space-lg);
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: var(--space-md);
        }

        .settings-card-meta {
            font-size: 13px;
            color: var(--text-muted);
        }

        .settings-card-actions {
            display: flex;
            gap: var(--space-sm);
            flex-wrap: wrap;
        }

        /* ============================================
                    8. FORM ELEMENTS - PROFESSIONAL STYLE
                    ============================================ */
        .settings-form-group {
            margin-bottom: var(--space-xl);
        }

        .settings-form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 10px;
        }

        .settings-form-label .required {
            color: #ef4444;
            margin-left: 2px;
            font-weight: 700;
        }

        .settings-form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 14px;
            color: var(--text-body);
            background: var(--input-bg, white);
            transition: all var(--transition-base);
            font-family: inherit;
        }

        .settings-form-input:hover {
            border-color: rgba(19, 81, 216, 0.3);
        }

        .settings-form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(19, 81, 216, 0.08);
            background: white;
        }

        .settings-form-input::placeholder {
            color: var(--text-muted);
            opacity: 0.6;
        }

        .settings-form-help {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 8px;
            display: block;
            line-height: 1.5;
        }

        .settings-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-lg);
        }

        /* ============================================
                    9. TOGGLE SWITCHES - MODERN IOS STYLE
                    ============================================ */
        .settings-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-lg) 0;
            gap: var(--space-lg);
        }

        .settings-toggle-info {
            flex: 1;
        }

        .settings-toggle-label {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 6px;
        }

        .settings-toggle-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .toggle-switch {
            position: relative;
            width: 52px;
            height: 30px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 30px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: linear-gradient(135deg, var(--accent) 0%, #0d47a1 100%);
            box-shadow: 0 0 12px rgba(19, 81, 216, 0.3);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(22px);
        }

        .toggle-switch:hover .toggle-slider {
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        }

        /* ============================================
                    10. BUTTONS - MODERN & ENGAGING
                    ============================================ */
        .settings-btn {
            padding: 11px 22px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-base);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
            white-space: nowrap;
        }

        .settings-btn i {
            font-size: 14px;
        }

        .settings-btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #0d47a1 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(19, 81, 216, 0.2);
        }

        .settings-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(19, 81, 216, 0.3);
        }

        .settings-btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(19, 81, 216, 0.2);
        }

        .settings-btn-secondary {
            background: var(--card);
            color: var(--text-body);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .settings-btn-secondary:hover {
            background: var(--bg);
            border-color: var(--text-muted);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .settings-btn-danger {
            background: #fee;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .settings-btn-danger:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        /* ============================================
                    11. RIGHT SIDEBAR (OPTIONAL)
                    ============================================ */
        .settings-right-sidebar {
            width: var(--settings-right-width);
            padding: var(--space-2xl) var(--space-lg);
            flex-shrink: 0;
            border-left: 1px solid var(--border);
        }

        .settings-help-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: var(--space-lg);
            margin-bottom: var(--space-lg);
            box-shadow: var(--shadow-sm);
        }

        .settings-help-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 var(--space-sm) 0;
        }

        .settings-help-text {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        /* ============================================
                    12. MOBILE TOGGLE BUTTON
                    ============================================ */
        .settings-toggle-btn {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: var(--z-toggle-btn);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #0d47a1 100%);
            color: white;
            border: none;
            font-size: 22px;
            box-shadow: 0 8px 24px rgba(19, 81, 216, 0.4);
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-base);
        }

        .settings-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 32px rgba(19, 81, 216, 0.5);
        }

        .settings-toggle-btn:active {
            transform: scale(0.95);
        }

        /* ============================================
                    13. RESPONSIVE BREAKPOINTS
                    ============================================ */

        /* Large Screens (1200px+) */
        @media (max-width: 1200px) {
            .settings-right-sidebar {
                display: none;
            }

            .settings-content {
                max-width: 100%;
            }
        }

        /* Tablets & Medium Screens (992px - 768px) */
        @media (max-width: 992px) {
            .settings-sidebar {
                position: fixed;
                left: 0;
                top: var(--nav-height-desktop);
                z-index: var(--z-sidebar);
                transform: translateX(-100%);
                transition: transform var(--transition-slow);
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
                width: 300px;
            }

            .settings-sidebar.active {
                transform: translateX(0);
            }

            .settings-content {
                padding: var(--space-xl) var(--space-lg);
                max-width: 100%;
            }

            .settings-toggle-btn {
                display: flex;
            }

            .settings-form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Mobile Phones (768px - 576px) */
        @media (max-width: 768px) {
            .settings-content {
                padding: var(--space-lg);
            }

            .settings-card {
                padding: var(--space-lg);
                border-radius: var(--radius-md);
            }

            .settings-page-title {
                font-size: 26px;
            }

            .settings-card-title {
                font-size: 18px;
            }

            .settings-card-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .settings-card-actions {
                width: 100%;
            }

            .settings-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Small Mobile (576px and below) */
        @media (max-width: 576px) {
            body {
                padding-top: var(--nav-height-mobile);
            }

            .settings-sidebar {
                top: var(--nav-height-mobile);
                height: calc(100vh - var(--nav-height-mobile));
                width: 100%;
            }

            .settings-page-title {
                font-size: 24px;
            }

            .settings-toggle-btn {
                bottom: 16px;
                left: 16px;
                width: 56px;
                height: 56px;
                font-size: 20px;
            }
        }

        /* ============================================
                    14. UTILITY CLASSES
                    ============================================ */

        /* Spacing Utilities */
        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-1 {
            margin-bottom: var(--space-xs) !important;
        }

        .mb-2 {
            margin-bottom: var(--space-sm) !important;
        }

        .mb-3 {
            margin-bottom: var(--space-md) !important;
        }

        .mb-4 {
            margin-bottom: var(--space-lg) !important;
        }

        .mb-5 {
            margin-bottom: var(--space-xl) !important;
        }

        /* Text Utilities */
        .text-muted {
            color: var(--text-muted) !important;
        }

        .text-body {
            color: var(--text-body) !important;
        }

        .text-heading {
            color: var(--text-heading) !important;
        }

        .text-accent {
            color: var(--accent) !important;
        }

        /* Display Utilities */
        .d-none {
            display: none !important;
        }

        .d-block {
            display: block !important;
        }

        .d-flex {
            display: flex !important;
        }

        .d-grid {
            display: grid !important;
        }

        /* Animation for smooth page load */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .settings-card {
            animation: fadeInUp 0.4s ease-out;
        }

        .settings-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .settings-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .settings-card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
@endpush


@section('content')
    @stack('styles')

    @include('components.navigation.top-nav')

    <div class="settings-container">
        <!-- Left Sidebar -->
        <aside class="settings-sidebar" id="settingsSidebar">
            <div class="settings-sidebar-header">
                <h1 class="settings-title">Settings</h1>
                <p class="settings-subtitle">Manage your account preferences</p>
            </div>

            <!-- Search -->
            <div class="settings-search">
                <div class="settings-search-wrapper">
                    <i class="fas fa-search settings-search-icon"></i>
                    <input type="search" class="settings-search-input" placeholder="Search settings..." id="settingsSearch"
                        autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                        aria-autocomplete="none" inputmode="text">
                </div>
            </div>

            <!-- Navigation -->
            <nav class="settings-nav">
                <div class="settings-nav-section">
                    <div class="settings-nav-section-title">Personal</div>
                    <ul class="settings-nav-list">
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.account', $username) }}"
                                class="settings-nav-link 
                                {{ $activeSection === 'account' ? 'active' : '' }}">
                                <i class="fas fa-user"></i>
                                <span>Account</span>
                            </a>
                        </li>
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.security', $username) }}"
                                class="settings-nav-link 
                                {{ $activeSection === 'security' ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>
                                <span>Security</span>
                            </a>
                        </li>
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.privacy', $username) }}"
                                class="settings-nav-link
                                 {{ $activeSection === 'privacy' ? 'active' : '' }}">
                                <i class="fas fa-lock"></i>
                                <span>Privacy & Visibility</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="settings-nav-section">
                    <div class="settings-nav-section-title">Preferences</div>
                    <ul class="settings-nav-list">
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.notifications', $username) }}"
                                class="settings-nav-link 
                                {{ $activeSection === 'notifications' ? 'active' : '' }}">
                                <i class="fas fa-bell"></i>
                                <span>Notifications</span>
                            </a>
                        </li>
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.appearance', $username) }}"
                                class="settings-nav-link {{ $activeSection === 'appearance' ? 'active' : '' }}">
                                <i class="fas fa-palette"></i>
                                <span>Appearance</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="settings-nav-section">
                    <div class="settings-nav-section-title">Billing</div>
                    <ul class="settings-nav-list">
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.billing', $username) }}"
                                class="settings-nav-link {{ $activeSection === 'billing' ? 'active' : '' }}">
                                <i class="fas fa-credit-card"></i>
                                <span>Billing & Subscription</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="settings-nav-section">
                    <div class="settings-nav-section-title">Advanced</div>
                    <ul class="settings-nav-list">
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.data', $username) }}"
                                class="settings-nav-link {{ $activeSection === 'data' ? 'active' : '' }}">
                                <i class="fas fa-database"></i>
                                <span>Data & Apps</span>
                            </a>
                        </li>
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.advanced', $username) }}"
                                class="settings-nav-link {{ $activeSection === 'advanced' ? 'active' : '' }}">
                                <i class="fas fa-sliders-h"></i>
                                <span>Advanced</span>
                            </a>
                        </li>
                        <li class="settings-nav-item">
                            <a href="{{ route('tenant.settings.danger', $username) }}"
                                class="settings-nav-link {{ $activeSection === 'danger' ? 'active' : '' }}">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Danger Zone</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="settings-content">
            @yield('settings-content')
        </main>

        <!-- Right Sidebar (Optional Help) -->
        @if (!empty($showHelp))
            <aside class="settings-right-sidebar">
                @yield('settings-help')
            </aside>
        @endif
    </div>

    <!-- Mobile Toggle Button -->
    <button class="settings-toggle-btn" id="settingsToggleBtn" style="display: none;">
        <i class="fas fa-cog"></i>
    </button>
@endsection

@push('scripts')
    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('settingsSidebar');
        const toggleBtn = document.getElementById('settingsToggleBtn');

        if (window.innerWidth <= 992) {
            toggleBtn.style.display = 'flex';
        }

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 &&
                sidebar.classList.contains('active') &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });

        // Responsive check
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 992) {
                toggleBtn.style.display = 'flex';
            } else {
                toggleBtn.style.display = 'none';
                sidebar.classList.remove('active');
            }
        });
        // Settings search functionality
        const searchInput = document.getElementById('settingsSearch');
        const navLinks = document.querySelectorAll('.settings-nav-link');
        searchInput?.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            navLinks.forEach(link => {
                const text = link.textContent.toLowerCase();
                const item = link.closest('.settings-nav-item');
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
            // Hide sections if all items are hidden
            document.querySelectorAll('.settings-nav-section').forEach(section => {
                const visibleItems = section.querySelectorAll(
                    '.settings-nav-item:not([style*="display: none"])');
                if (visibleItems.length === 0) {
                    section.style.display = 'none';
                } else {
                    section.style.display = '';
                }
            });
        });
    </script>
@endpush
