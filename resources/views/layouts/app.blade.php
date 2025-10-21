{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SkillLeo')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/fav/fav7.png') }}">
    
    @stack('styles')

    <link href="{{ asset('css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">

    <x-timezone-detector />


    @section('styles')


        <style>
            .ui-pro :root {
                --pro-shadow: 0 10px 30px rgba(0, 0, 0, .12);
                --pro-shadow-sm: 0 2px 10px rgba(0, 0, 0, .08);
                --pro-ring: 0 0 0 3px rgba(19, 81, 216, .14);
            }

            /* -- ICON BUTTONS / BADGES -------------------------------------------- */
            .ui-pro .icon-btn {
                width: 36px;
                height: 36px;
                border-radius: 12px;
                transition: transform .15s ease, background .15s ease, box-shadow .15s ease;
            }

            .ui-pro .icon-btn:hover {
                background: var(--hover-bg);
                box-shadow: var(--pro-shadow-sm);
            }

            .ui-pro .icon-btn i {
                font-size: 18px;
            }

            /* Compact, classy counters (no “dots on icons” crowding) */
            .ui-pro .icon-btn .badge {
                min-width: 16px;
                height: 16px;
                font-size: 10px;
                font-weight: 700;
                top: -4px;
                right: -4px;
                padding: 0 4px;
                border-radius: 8px;
                border: none;
                box-shadow: 0 0 0 2px var(--nav-bg), 0 2px 6px rgba(0, 0, 0, .18);
            }

            .ui-pro .icon-btn .badge.badge-dot {
                width: 10px;
                height: 10px;
                top: -3px;
                right: -3px;
                padding: 0;
                border-radius: 50%;
            }

            /* -- SHARE BUTTON: smaller, minimal, premium -------------------------- */
            .ui-pro .share-btn {
                height: 36px;
                padding: 0 12px;
                border-radius: 12px;
                background: #fff;
                color: var(--accent);
                border: 1px solid rgba(19, 81, 216, .25);
                box-shadow: 0 1px 0 rgba(0, 0, 0, .04);
                letter-spacing: .1px;
            }

            .ui-pro [data-theme="dark"] .share-btn {
                background: #161a1f;
                color: #fff;
                border-color: rgba(255, 255, 255, .12);
            }

            .ui-pro .share-btn i {
                font-size: 16px;
            }

            .ui-pro .share-btn:hover {
                background: var(--accent);
                color: #fff;
                box-shadow: var(--pro-shadow);
                transform: translateY(-1px);
            }

            /* -- DROPDOWNS: shell -------------------------------------------------- */
            .ui-pro .dropdown {
                border: 1px solid var(--border);
                backdrop-filter: saturate(140%) blur(8px);
                border-radius: 14px;
                box-shadow: var(--pro-shadow);
                transform: translateY(6px) scale(.98);
            }

            .ui-pro .dropdown.active {
                transform: translateY(0) scale(1);
            }

            .ui-pro .messages-dropdown {
                width: 420px;
            }

            .ui-pro .notifications-dropdown {
                width: 380px;
            }

            /* Headers/Tabs: tighter & professional */
            .ui-pro .dropdown-header {
                padding: 14px 16px;
            }

            .ui-pro .dropdown-title {
                font-size: 15px;
                letter-spacing: .2px;
            }

            .ui-pro .dropdown-tabs {
                padding: 0 16px;
                top: 52px;
            }

            .ui-pro .dropdown-tab {
                padding: 10px 6px;
                font-size: 13.5px;
            }

            .ui-pro .dropdown-tab.active {
                font-weight: 700;
            }

            .ui-pro .dropdown-tab.active::after {
                height: 2px;
                border-radius: 2px;
            }

            /* Scroll polish */
            .ui-pro .dropdown-content {
                padding: 6px 0;
            }

            .ui-pro .dropdown-content::-webkit-scrollbar {
                width: 6px;
            }

            .ui-pro .dropdown-content::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, .12);
                border-radius: 3px;
            }

            /* -- MESSAGES: premium list items ------------------------------------- */
            .ui-pro .message-item {
                margin: 4px 10px;
                padding: 12px 14px;
                border-radius: 12px;
                transition: background .12s ease, box-shadow .12s ease, transform .12s ease;
            }

            .ui-pro .message-item:hover {
                background: var(--hover-bg);
                box-shadow: 0 1px 0 rgba(0, 0, 0, .05);
            }

            .ui-pro .message-item.unread {
                background: rgba(19, 81, 216, .06);
                box-shadow: inset 0 0 0 1px rgba(19, 81, 216, .16);
            }

            [data-theme="dark"] .ui-pro .message-item.unread {
                background: rgba(74, 143, 255, .10);
                box-shadow: inset 0 0 0 1px rgba(74, 143, 255, .25);
            }

            /* Avatar + presence (clean, smaller dot with subtle ring) */
            .ui-pro .message-avatar {
                border-radius: 50%;
                overflow: hidden;
                position: relative;
                over
            }

            .ui-pro .message-avatar img {
                width: 48px;
                height: 48px;
                object-fit: cover;
                display: block;
                border-radius: 50%;
            }

            .ui-pro .message-status {
                bottom: 2px;
                right: 5px;
                width: 10px;
                height: 10px;
                border: 2px solid var(--card);
                box-shadow: 0 0 0 1px rgba(0, 0, 0, .06);
            }

            .ui-pro [data-theme="dark"] .message-status {
                border-color: #1e2226;
            }

            /* Names/time/preview tweaks */
            .ui-pro .message-name {
                font-size: 14.5px;
                color: var(--text-heading);
                letter-spacing: .15px;
            }

            .ui-pro .message-time {
                font-size: 12px;
                color: var(--text-subtle);
                font-variant-numeric: tabular-nums;
            }

            .ui-pro .message-preview {
                color: var(--text-muted);
                -webkit-line-clamp: 2;
            }

            /* Typing indicator: smaller + calmer */
            .ui-pro .typing-indicator {
                margin-top: 6px;
                opacity: .85;
            }

            .ui-pro .typing-dot {
                width: 3px;
                height: 3px;
                opacity: .65;
                animation-duration: 1s;
            }

            /* -- NOTIFICATIONS: refined cards ------------------------------------- */
            .ui-pro .notification-item {
                margin: 2px 10px;
                border-radius: 12px;
                padding: 12px 14px;
            }

            .ui-pro .notification-item:hover {
                background: var(--hover-bg);
            }

            .ui-pro .notification-item.unread {
                background: rgba(19, 81, 216, .06);
                border-left: 0;
                box-shadow: inset 0 0 0 1px rgba(19, 81, 216, .16);
            }

            .ui-pro .notification-icon {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }

            /* Footer links */
            .ui-pro .dropdown-footer {
                padding: 10px 14px;
            }

            .ui-pro .dropdown-footer-link {
                border-radius: 10px;
                font-size: 13.5px;
            }

            /* Profile dropdown harmony */
            .ui-pro .profile-dropdown {
                border: 1px solid var(--border);
                box-shadow: var(--pro-shadow);
                border-radius: 14px;
            }

            .ui-pro .profile-card {
                padding: 16px;
            }

            .ui-pro .profile-menu-link {
                padding: 10px 16px;
                border-radius: 10px;
            }

            .ui-pro .profile-menu-link:hover {
                background: var(--hover-bg);
            }
        </style>

        <style>
 
            .ui-pro .message-avatar {
                position: relative;
                overflow: visible;
                /* let the dot sit outside */
            }

            .ui-pro .message-status {
                position: absolute;
                right: 0;
                /* push it out of the circle */
                bottom: -1px;
                width: 12px;
                height: 12px;
                border: none;
                /* we'll use a halo instead */
                box-shadow: 0 0 0 2px var(--card), 0 2px 6px rgba(0, 0, 0, .18);
                border-radius: 50%;
            }

            [data-theme="dark"] .ui-pro .message-status {
                box-shadow: 0 0 0 2px #1e2226, 0 2px 6px rgba(0, 0, 0, .5);
            }

            /* 2) “Typing …” dots to the RIGHT of the name (header), premium + simple */
            .ui-pro .message-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
            }

            .ui-pro .message-name {
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            /* show dots next to name only when that message has a typing indicator */
            .ui-pro .message-item:has(.typing-indicator) .message-name::after {
                content: "";
                width: 4px;
                height: 4px;
                border-radius: 50%;
                display: inline-block;
                vertical-align: middle;
                color: var(--text-subtle);
                background: currentColor;
                /* three dots from one element */
                box-shadow: 7px 0 0 currentColor, 14px 0 0 currentColor;
                opacity: .75;
                animation: pro-typing-pulse 1.2s infinite ease-in-out;
            }

            /* a touch of emphasis when unread + typing */
            .ui-pro .message-item.unread:has(.typing-indicator) .message-name::after {
                color: var(--accent);
                opacity: .9;
            }

            /* hide the old 3-dot row under the preview */
            .ui-pro .message-item:has(.typing-indicator) .typing-indicator {
                display: none;
            }

            @keyframes pro-typing-pulse {

                0%,
                80%,
                100% {
                    transform: translateY(0);
                    opacity: .35;
                }

                40% {
                    transform: translateY(-1px);
                    opacity: .95;
                }
            }
        </style>


    <body class="ui-pro">
        @yield('content')


        <script src="{{ asset('js/profile.js') }}"></script>
        @stack('scripts')
        @section('scripts')

        <script>
            document.querySelectorAll('.edit-card').forEach(button => {
                // Skip if button already has onclick attribute (hero section buttons)
                if (button.hasAttribute('onclick')) {
                    return;
                }

                button.addEventListener('click', function() {
                    const section = this.closest('section');

                    if (section.classList.contains('hero-merged')) {
                        openModal('editProfileModal');
                    } else if (section.classList.contains('experience-section')) {
                        openModal('editExperienceModal');
                    } else if (section.classList.contains('portfolios-section')) {
                        openModal('editPortfolioModal');
                    } else if (section.classList.contains('skills-showcase')) {
                        openModal('editSkillsModal');
                    }
                    // Add more conditions for other sections
                });
            });
        </script>
    </body>

    </html>
