@extends('layouts.app')

@section('title', $user->name . ' | SkillLeo')

@section('content')

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

            --gradient-border: linear-gradient(135deg,
                    #667eea 0%,
                    #764ba2 35%,
                    #f093fb 70%,
                    #4facfe 100%);
            --gradient-button: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);


            --nav-height-mobile: 107px;
            --nav-height-desktop: 64px;
        }






        body {
            font-family: "Inter", -apple-system, system-ui, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;

            background: linear-gradient(180deg,
                    rgba(19, 81, 216, 0.04) 0%,
                    rgba(19, 81, 216, 0.02) 150px,
                    rgba(255, 255, 255, 0) 300px);
            background-color: var(--bg);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-body);
            font-size: var(--fs-body);
            line-height: var(--lh-normal);
            font-weight: var(--fw-regular);

            padding-top: var(--nav-height-desktop);



            -webkit-text-size-adjust: 100%;
            -moz-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }



        /* ============================================
                       CONTENT AREA - PROFESSIONAL SPACING
                       ============================================ */

        .content-area {
            flex: 1;
            padding: var(--space-md);
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .page-title {
            font-size: var(--fs-display);
            font-weight: var(--fw-bold);
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        /* ============================================
                       STATS GRID - LINKEDIN STYLE
                       ============================================ */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--mb-sections);
            margin-bottom: var(--mb-sections);
            ;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: var(--space-lg);
            transition: all var(--transition-base);
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent);
            transform: scaleY(0);
            transition: transform var(--transition-base);
        }

        .stat-card:hover::before {
            transform: scaleY(1);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--accent);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--mb-sections);
        }

        .stat-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: var(--fw-semibold);
        }

        .stat-value {
            font-size: 28px;
            font-weight: var(--fw-bold);
            margin: var(--space-xs) 0;
            color: var(--text-heading);
            line-height: 1;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            background: var(--bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 20px;
        }

        .stat-change {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change .positive {
            color: #10b981;
            font-weight: var(--fw-semibold);
        }

        .stat-change .negative {
            color: #ef4444;
            font-weight: var(--fw-semibold);
        }

        /* ============================================
                       CHART CONTAINER - PROFESSIONAL
                       ============================================ */

        .chart-container {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: var(--space-xl);
            margin-bottom: var(--mb-sections);
            ;
            box-shadow: var(--shadow-sm);
            transition: box-shadow var(--transition-base);
        }

        .chart-container:hover {
            box-shadow: var(--shadow-md);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--mb-sections);
            flex-wrap: wrap;
            gap: var(--space-md);
        }

        .chart-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
        }

        .chart-subtitle {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin-top: 4px;
        }

        .time-filters {
            display: flex;
            gap: var(--space-xs);
            background: var(--bg);
            padding: 4px;
            border-radius: 8px;
        }

        .time-filter {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: var(--fs-subtle);
            cursor: pointer;
            transition: all var(--transition-fast);
            background: transparent;
            border: none;
            font-weight: var(--fw-medium);
            color: var(--text-body);
        }

        .time-filter:hover {
            background: var(--card);
            transform: translateY(-1px);
        }

        .time-filter.active {
            background: var(--accent);
            color: var(--btn-text-primary);
            box-shadow: var(--shadow-sm);
        }

        .chart-area {
            height: 240px;
            background: var(--bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .chart-legend {
            display: flex;
            justify-content: flex-end;
            gap: var(--space-lg);
            margin-top: var(--space-lg);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: var(--fs-subtle);
            color: var(--text-body);
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: var(--shadow-xs);
        }

        /* ============================================
                       ROW CARDS - CONVERSION & ORDERS
                       ============================================ */

        .row-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--mb-sections);
            ;
            margin-bottom: var(--mb-sections);
            ;
        }

        .funnel-card,
        .orders-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: var(--space-xl);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-base);
        }

        .funnel-card:hover,
        .orders-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            text-align: center;
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
        }

        .card-subtitle {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            text-align: center;
            margin-bottom: var(--mb-sections);
        }

        .funnel-item {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--mb-sections);
        }

        .funnel-label {
            min-width: 130px;
            font-size: var(--fs-body);
            color: var(--text-body);
            font-weight: var(--fw-medium);
        }

        .funnel-bar-container {
            flex: 1;
            height: 38px;
            background: var(--accent-light);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .funnel-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--accent) 0%, #0d3393 100%);
            border-radius: 8px;
            transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .funnel-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% {
                left: -100%;
            }

            50%,
            100% {
                left: 100%;
            }
        }

        .funnel-percent {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            color: var(--ink);
        }

        .funnel-note {
            text-align: center;
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin-top: var(--space-lg);
        }

        /* Pie Chart */
        .pie-chart-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-xl);
            margin: var(--space-xl) 0;
        }

        .pie-chart {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: conic-gradient(var(--accent) 0deg 72deg,
                    #4a90e2 72deg 190.8deg,
                    #7eb8f5 190.8deg 234deg,
                    #b3d9ff 234deg 360deg);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .pie-chart-inner {
            width: 130px;
            height: 130px;
            background: var(--card);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .pie-chart-value {
            font-size: 32px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
        }

        .pie-chart-label {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .pie-legend {
            display: flex;
            flex-direction: column;
            gap: var(--space-md);
        }

        .pie-legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pie-legend-dot {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            box-shadow: var(--shadow-xs);
        }

        .pie-legend-text {
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
        }

        .pie-legend-count {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            display: block;
            margin-top: 2px;
        }

        /* ============================================
                       DUE SOON SECTION - FACEBOOK STYLE
                       ============================================ */

        .due-soon-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: var(--space-xl);
            margin-bottom: var(--mb-sections);
            ;
            box-shadow: var(--shadow-sm);
        }

        .due-soon-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
        }

        .due-soon-subtitle {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin-bottom: var(--mb-sections);
        }

        .due-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-lg) 0;
            border-bottom: 1px solid var(--border);
            transition: all var(--transition-fast);
        }

        .due-item:hover {
            background: var(--bg);
            padding-left: var(--space-md);
            padding-right: var(--space-md);
            border-radius: 8px;
            margin-left: calc(var(--space-md) * -1);
            margin-right: calc(var(--space-md) * -1);
        }

        .due-item:last-child {
            border-bottom: none;
        }

        .due-item-content h6 {
            font-size: var(--fs-title);
            font-weight: var(--fw-medium);
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
        }

        .due-item-meta {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .due-item-meta .overdue {
            color: #ef4444;
            font-weight: var(--fw-semibold);
        }

        .due-item-action {
            font-size: var(--fs-subtle);
            color: var(--accent);
            cursor: pointer;
            font-weight: var(--fw-medium);
            padding: 6px 12px;
            border-radius: 6px;
            transition: all var(--transition-fast);
        }

        .due-item-action:hover {
            background: var(--accent-light);
        }

        /* ============================================
                       ACTIVITY SECTION - ATLASSIAN STYLE
                       ============================================ */

        .activity-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: var(--space-xl);
            margin-bottom: var(--mb-sections);
            ;
            box-shadow: var(--shadow-sm);
        }

        .activity-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: var(--space-lg);
        }

        .activity-item {
            display: flex;
            gap: var(--space-md);
            padding: var(--space-lg);
            background: var(--bg);
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all var(--transition-base);
            cursor: pointer;
        }

        .activity-item:hover {
            background: var(--card);
            box-shadow: var(--shadow-md);
            border-color: var(--border);
            transform: translateY(-2px);
        }

        .activity-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: var(--shadow-sm);
            transition: transform var(--transition-base);
        }

        .activity-item:hover .activity-avatar {
            transform: scale(1.1);
        }

        .activity-content {
            flex: 1;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--mb-sections);
        }

        .activity-name {
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
        }

        .activity-time {
            font-size: var(--fs-micro);
            color: var(--text-muted);
        }

        .activity-desc {
            font-size: var(--fs-subtle);
            color: var(--text-body);
            margin-bottom: 4px;
        }

        .activity-meta {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }





























































































        /* ============================================
                       RIGHT SIDEBAR - PROFESSIONAL
                       ============================================ */

        .right-sidebar {
            width: var(--right-sidebar-width);
            flex-shrink: 0;
            padding: var(--space-2xl) var(--space-md);
            min-height: 100vh;
            transition: transform var(--transition-slow);
        }

        .right-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: var(--space-lg);
            margin-bottom: var(--mb-sections);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-base);
        }

        .right-card:hover {
            box-shadow: var(--shadow-md);
        }

        .right-card h6 {
            font-size: var(--fs-title);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
        }

        .tutorial-placeholder {
            width: 100%;
            height: 160px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-white);
            font-size: var(--fs-body);
            box-shadow: var(--shadow-md);
            transition: transform var(--transition-base);
            cursor: pointer;
        }

        .tutorial-placeholder:hover {
            transform: scale(1.02);
        }

        .refiner-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .refiner-card h6 {
            font-size: var(--fs-body);
            margin: 0;
        }

        .refine-button {
            background: var(--card);
            border: 2px solid transparent;
            background-clip: padding-box;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            cursor: pointer;
            position: relative;
            background: linear-gradient(var(--card), var(--card)) padding-box,
                linear-gradient(90deg, #667eea, #764ba2) border-box;
            color: #667eea;
            transition: all var(--transition-base);
        }

        .refine-button:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .pinned-section {
            position: relative;
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: var(--space-lg) var(--space-md) var(--space-md);
        }

        .pinned-title {
            position: absolute;
            top: -12px;
            left: 15px;
            background: var(--card);
            padding: 0 10px;
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
        }

        .pinned-edit {
            position: absolute;
            top: -12px;
            right: 15px;
            background: var(--card);
            padding: 0 6px;
            color: var(--accent);
            cursor: pointer;
            transition: transform var(--transition-fast);
        }

        .pinned-edit:hover {
            transform: scale(1.1);
        }

        .pinned-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pinned-links li {
            padding: var(--space-sm) 0;
            font-size: var(--fs-body);
            color: var(--text-body);
            cursor: pointer;
            transition: all var(--transition-fast);
            border-radius: 6px;
            padding-left: var(--space-xs);
        }

        .pinned-links li:hover {
            color: var(--accent);
            background: var(--accent-light);
            padding-left: var(--space-sm);
        }



        @keyframes stickyEnhanced {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            50% {
                transform: translateY(5px) scale(1.05);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1.02);
            }
        }


        @keyframes stickyPulseEnhanced {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
                filter: blur(2px);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.08);
                filter: blur(4px);
            }
        }


        .sidebar-toggle {
            left: 20px;
        }

        .right-sidebar-toggle {
            right: 20px;
        }




        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .chart-container,
        .funnel-card,
        .orders-card,
        .due-soon-section,
        .activity-section,
        .right-card {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) backwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        .stat-card:nth-child(6) {
            animation-delay: 0.3s;
        }

        /* ============================================
                       RESPONSIVE DESIGN
                       ============================================ */

        @media (max-width: 1400px) {
            .content-area {
                padding: var(--space-md);
            }
        }

        @media (max-width: 1280px) {
            .content-area {
                padding: var(--space-lg);
            }

            .row-cards {
                grid-template-columns: 1fr;
                gap: var(--space-lg);
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: var(--mb-sections);
            }



            .right-sidebar.active {
                transform: translateX(0);
            }

            .right-sidebar-toggle {
                display: flex;
            }
        }

        @media (max-width: 992px) {
            .content-area {
                padding: var(--space-md);
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }



            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .content-area {
                padding: var(--space-sm);
            }

            .page-title {
                font-size: var(--fs-h2);
                margin-bottom: var(--mb-sections);
            }

            .stats-grid {
                gap: var(--mb-sections);
            }

            .chart-container,
            .funnel-card,
            .orders-card,
            .due-soon-section,
            .activity-section {
                padding: var(--space-lg);
                margin-bottom: var(--mb-sections);
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-toggle {
                bottom: 90px;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .pie-chart-container {
                flex-direction: column;
                gap: var(--space-lg);
            }
        }

        /* ============================================
                       ACCESSIBILITY
                       ============================================ */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        .time-filter:focus,
        .due-item-action:focus,
        button:focus {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }




        .main-container {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            max-width: 1920px;
            margin: 0 auto;
            /* display: block; */


        }

        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            transition: transform var(--transition-base);
            /* background: var(--bg); */
            overflow-y: auto;
            /* Enable scrolling */
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }

        .sidebar-content {
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 var(--space-sm);
            flex: 1;
            padding-top: 50px;

            scrollbar-width: 2px;
            scrollbar-height: 10px;
            scrollbar-color: black
                /* scrollbar-color: rgba(0, 0, 0, 0.2) transparent; */
        }

        .sidebar-content::-webkit-scrollbar {
            width: 2px;
            height: 10px !important;
            scrollbar-color: black
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            border-radius: 3px;
            height: 10px !important;

        }


        

















        /* ============================================
   PROFILE CARD - LINKEDIN/ATLASSIAN STYLE
   ============================================ */

.profile-card1 {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0;
    margin-bottom: var(--mb-sections);
    overflow: hidden;
}

/* Cover Background */
.profile-cover {
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

/* Avatar Container */
.profile-avatar-wrapper1 {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: -32px;
    padding: 0 var(--space-lg);
}

.profile-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    border: 4px solid var(--card);
    background: var(--card);
    position: relative;
}

.profile-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #10b981;
    border: 2px solid var(--card);
    border-radius: 50%;
}

/* Profile Info */
.profile-info {
    text-align: center;
    padding: var(--space-md) var(--space-lg);
}

.profile-name {
    font-size: 1rem;
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.profile-headline {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
    margin: 0 0 var(--space-sm) 0;
    line-height: 1.4;
}

.profile-location {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: var(--fs-subtle);
    color: var(--text-muted);
    margin-bottom: var(--space-md);
}

.profile-location i {
    font-size: 12px;
}

/* Divider */
.profile-divider {
    height: 1px;
    background: var(--border);
    margin: 0 var(--space-lg) var(--space-md);
}

/* Stats Row */
.profile-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
    padding: 0 var(--space-lg) var(--space-md);
}

.profile-stat {
    text-align: center;
}

.profile-stat-value {
    font-size: 1.125rem;
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    display: block;
    line-height: 1;
    margin-bottom: 4px;
}

.profile-stat-label {
    font-size: var(--fs-micro);
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Action Button */
.profile-action {
    /* padding: 0 var(--space-lg) var(--space-lg); */
    display: flex;align-items: center; justify-content: center
}
.profile-action a{
    /* padding: 0 var(--space-lg) var(--space-lg); */
    display: flex;align-items: center; justify-content: center
}

.btn-view-profile {
    width: 100%;
    background: transparent;
    color: var(--accent);
    border: 1px solid var(--border);
    padding: 9px 16px;
    border-radius: 6px;
    font-size: var(--fs-body);
    cursor: pointer;
    font-weight: var(--fw-medium);
    transition: all 0.15s ease;
}

.btn-view-profile:hover {
    background: var(--bg);
    border-color: var(--accent);
}

.btn-view-profile:active {
    transform: scale(0.98);
}

        .nav-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: var(--space-sm);
            margin-bottom: var(--mb-sections);
            box-shadow: var(--shadow-sm);
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-body);
            transition: all var(--transition-base);
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: var(--accent-light);
            color: var(--accent);
        }

        .upgrade-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: var(--space-xl);
            text-align: center;
            color: var(--text-white);
            box-shadow: var(--shadow-lg);
            margin-bottom: var(--mb-sections);
        }

        .btn-upgrade {
            background: var(--card);
            color: #667eea;
            border: none;
            padding: 10px 24px;
            border-radius: 20px;
            font-weight: var(--fw-semibold);
            cursor: pointer;
            transition: all var(--transition-base);
        }

        /* UNIFIED SCROLL WRAPPER */
        .unified-scroll-wrapper {
            flex: 1;
            display: flex;
            overflow-y: auto;
            overflow-x: hidden;
            height: 100vh;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        .unified-scroll-wrapper::-webkit-scrollbar {
            width: 10px;

        }

        .unified-scroll-wrapper::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }



        .page-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-bold);
            margin-bottom: var(--mb-sections);
            ;
            color: var(--text-heading);
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--mb-sections);
            margin-bottom: var(--mb-sections);
            ;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: var(--space-lg);
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all var(--transition-base);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent);
            transform: scaleY(0);
            transition: transform var(--transition-base);
        }

        .stat-card:hover::before {
            transform: scaleY(1);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .stat-value {
            font-size: 28px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
        }

        /* RIGHT SIDEBAR */
        .right-sidebar {
            width: var(--right-sidebar-width);
            flex-shrink: 0;
            padding: var(--space-2xl) var(--space-md);
            min-height: 100vh;
            transition: transform var(--transition-base);
        }

        .right-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: var(--space-lg);
            margin-bottom: var(--mb-sections);
            box-shadow: var(--shadow-sm);
        }

        /* STICKY SCROLL */
        .sticky-on-scroll {
            position: relative;
            transition: all var(--transition-base);
        }

        .sticky-on-scroll.is-sticky {
            position: fixed !important;
            top: 100px !important;
            z-index: 999;
            box-shadow: var(--shadow-2xl);
            transform: scale(1.02);
        }

        .sticky-placeholder {
            display: none;
            visibility: hidden;
        }

        /* TOGGLE BUTTONS */
        .sidebar-toggle,
        .right-sidebar-toggle {
            position: fixed;
            bottom: 20px;
            z-index: 1001;
            background: var(--accent);
            color: var(--btn-text-primary);
            border: none;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            box-shadow: var(--shadow-xl);
            display: none;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle {
            left: 20px;
        }

        .right-sidebar-toggle {
            right: 20px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
            opacity: 0;
            transition: opacity var(--transition-base);
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .right-sidebar {
                position: fixed;
                right: 0;
                top: 50px;
                height: 100vh;
                overflow-y: auto;
                background: var(--bg);
                transform: translateX(100%);
                z-index: 1;
            }

            .right-sidebar.active {
                transform: translateX(0);
            }

            .right-sidebar-toggle {
                display: flex;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 50px;
                background: var(--bg);
                transform: translateX(-100%);
                z-index: 1;
                height: calc(100vh - 50px);
                /* Set proper height for scrolling */
                max-height: calc(100vh - 50px);
                /* Ensure it doesn't exceed viewport */
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: flex;
            }
        }
    </style>

    <x-navigation.top-nav :user="$user" :username="$username" />

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Toggle Buttons -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <button class="right-sidebar-toggle" id="rightSidebarToggle" aria-label="Toggle right sidebar">
        <i class="fas fa-sliders-h"></i>
    </button>

    <!-- Navigation Component -->

    <!-- Main Container -->
    <div class="main-container">
        <!-- Left Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <!-- Profile Card -->
                <div class="profile-card1">
                    <!-- Cover Photo / Banner -->
                    <div class="profile-cover" 
                         @if($user->banner_url)
                         style="background-image: url('{{ $user->banner_url }}'); 
                                background-size: {{ $user->banner_fit }}; 
                                background-position: {{ $user->banner_position }};"
                         @endif>
                    </div>
                    
                    <!-- Avatar -->
                    <div class="profile-avatar-wrapper1">
                        <div class="profile-avatar">
                            <img src="{{ $user->avatar_url }}" 
                                 alt="{{ $user->name }}" 
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"
                                 referrerpolicy="no-referrer"
                                 crossorigin="anonymous"
                                 onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';"
                                 
                                 >
                            
                            @if($user->is_online)
                                <span class="profile-status" title="Online"></span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Profile Info -->
                    <div class="profile-info">
                        <h6 class="profile-name">{{ $user->name }}</h6>
                        
                        @if($user->headline)
                            <p class="profile-headline">{{ $user->headline }}</p>
                        @endif
                        
                        @if($user->location)
                            <div class="profile-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $user->location }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Divider -->
                    <div class="profile-divider"></div>
                    
                    <!-- Stats -->
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="profile-stat-value">{{ $owner->connections_count ?? 0 }}</span>
                            <span class="profile-stat-label">Clicks</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-value">{{ $owner->profile_views_count ?? 0 }}</span>
                            <span class="profile-stat-label">Profile Views</span>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="profile-action">
                        <a href="{{ route('tenant.profile', $owner->username) }}" class="btn-view-profile">
                            View public profile
                        </a>
                    </div>
                </div>
                <!-- Manage Profile -->
                <div class="nav-section">
                    <div class="nav-section-title">Manage Profile</div>
                    <ul class="nav-menu">
                        <li><a href="#" class="active"><i class="fas fa-user"></i> Personal Info</a></li>
                        <li><a href="#"><i class="fas fa-code"></i> Skills</a></li>
                        <li><a href="#"><i class="fas fa-briefcase"></i> Portfolio</a></li>
                        <li><a href="#"><i class="fas fa-history"></i> Experience</a></li>
                        <li><a href="#"><i class="fas fa-graduation-cap"></i> Education</a></li>
                    </ul>
                </div>

                <!-- Manage Projects -->
                <div class="nav-section">
                    <div class="nav-section-title">Manage Projects</div>
                    <ul class="nav-menu">
                        <li><a href="#"><i class="fas fa-project-diagram"></i> Projects</a></li>
                        <li><a href="#"><i class="fas fa-clipboard-list"></i> Orders</a></li>
                        <li><a href="#"><i class="fas fa-users"></i> Team Members</a></li>
                        <li><a href="#"><i class="fas fa-user-tie"></i> Clients</a></li>
                    </ul>
                </div>

                <!-- Legal & Finance -->
                <div class="nav-section">
                    <div class="nav-section-title">Legal & Finance</div>
                    <ul class="nav-menu">
                        <li><a href="#"><i class="fas fa-file-invoice"></i> Invoices</a></li>
                        <li><a href="#"><i class="fas fa-file-contract"></i> Contracts</a></li>
                        <li><a href="#"><i class="fas fa-balance-scale"></i> Legal Docs</a></li>
                    </ul>
                </div>

                <!-- Account -->
                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <ul class="nav-menu">
                        <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><a href="#"><i class="fas fa-user-circle"></i> Profile</a></li>
                        <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>

                <!-- Upgrade Card -->
                <div class="upgrade-card">
                    <h6>Upgrade to Pro</h6>
                    <div class="icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <p style="font-size: 12px; margin-bottom: 16px; opacity: 0.9;">Unlock premium features for smooth
                        workflow</p>
                    <button class="btn-upgrade">See plans</button>
                </div>
            </div>
        </aside>

        <!-- Unified Scroll Wrapper: Main Content + Right Sidebar -->
        <div class="unified-scroll-wrapper">
            <!-- Main Content Area -->
            <main class="content-area">
                <h1 class="page-title">Dashboard</h1>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Visitors</div>
                                <div class="stat-value">12450</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i>
                            <span class="positive">9.2%</span>
                            <span>(Up from last 7 days)</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Impressions</div>
                                <div class="stat-value">28960</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i>
                            <span class="positive">5.7%</span>
                            <span>(Up from last 30 days)</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">CTA Clicks</div>
                                <div class="stat-value">1280</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-mouse-pointer"></i>
                            </div>
                        </div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-down"></i>
                            <span class="negative">3.4%</span>
                            <span>(Down from yesterday)</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Active Orders</div>
                                <div class="stat-value">16</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="stat-change">
                            <span>Currently active</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Revenue</div>
                                <div class="stat-value">24600</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i>
                            <span class="positive">11.5%</span>
                            <span>(Up from last 30 days)</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Overdue</div>
                                <div class="stat-value">3</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stat-change">
                            <span>3 invoices overdue</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Trend Overview</div>
                            <div class="chart-subtitle">Visitors vs Impressions</div>
                        </div>
                        <div class="time-filters">
                            <button class="time-filter">7d</button>
                            <button class="time-filter active">30d</button>
                            <button class="time-filter">90d</button>
                        </div>
                    </div>
                    <div class="chart-area">
                        <svg width="100%" height="100%" viewBox="0 0 800 200" preserveAspectRatio="none">
                            <line x1="0" y1="50" x2="800" y2="50" stroke="#e0e0e0"
                                stroke-width="1" />
                            <line x1="0" y1="100" x2="800" y2="100" stroke="#e0e0e0"
                                stroke-width="1" />
                            <line x1="0" y1="150" x2="800" y2="150" stroke="#e0e0e0"
                                stroke-width="1" />
                            <polyline fill="none" stroke="#1351d8" stroke-width="3"
                                points="0,120 100,80 200,100 300,60 400,90 500,70 600,85 700,65 800,75" />
                            <polyline fill="none" stroke="#9ca3af" stroke-width="3"
                                points="0,150 100,130 200,145 300,110 400,125 500,115 600,120 700,105 800,110" />
                        </svg>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #1351d8;"></span>
                            <span>Impressions</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #9ca3af;"></span>
                            <span>Visitors</span>
                        </div>
                    </div>
                </div>

                <!-- Row Cards -->
                <div class="row-cards">
                    <!-- Conversion Funnel -->
                    <div class="funnel-card">
                        <div class="card-title">Conversion Funnel</div>
                        <div class="card-subtitle">Profile views → Paid Orders</div>
                        <div style="margin: 20px 0;">
                            <div class="funnel-item">
                                <div class="funnel-label">Profile views</div>
                                <div class="funnel-bar-container">
                                    <div class="funnel-bar" style="width: 100%;"></div>
                                    <span class="funnel-percent">100%</span>
                                </div>
                            </div>
                            <div class="funnel-item">
                                <div class="funnel-label">Project clicks</div>
                                <div class="funnel-bar-container">
                                    <div class="funnel-bar" style="width: 62%;"></div>
                                    <span class="funnel-percent">62%</span>
                                </div>
                            </div>
                            <div class="funnel-item">
                                <div class="funnel-label">Contact requests</div>
                                <div class="funnel-bar-container">
                                    <div class="funnel-bar" style="width: 28%;"></div>
                                    <span class="funnel-percent">28%</span>
                                </div>
                            </div>
                            <div class="funnel-item">
                                <div class="funnel-label">Orders created</div>
                                <div class="funnel-bar-container">
                                    <div class="funnel-bar" style="width: 12%;"></div>
                                    <span class="funnel-percent">12%</span>
                                </div>
                            </div>
                            <div class="funnel-item">
                                <div class="funnel-label">Paid orders</div>
                                <div class="funnel-bar-container">
                                    <div class="funnel-bar" style="width: 9%;"></div>
                                    <span class="funnel-percent">9%</span>
                                </div>
                            </div>
                        </div>
                        <div class="funnel-note">Track lead drop-off at each stage</div>
                    </div>

                    <!-- Orders by Status -->
                    <div class="orders-card">
                        <div class="card-title">Orders by Status</div>
                        <div class="pie-chart-container">
                            <div class="pie-chart">
                                <div class="pie-chart-inner">
                                    <div class="pie-chart-value">120</div>
                                    <div class="pie-chart-label">Orders</div>
                                </div>
                            </div>
                            <div class="pie-legend">
                                <div class="pie-legend-item">
                                    <span class="pie-legend-dot" style="background: #1351d8;"></span>
                                    <div class="pie-legend-text">
                                        New
                                        <span class="pie-legend-count">25 (20%)</span>
                                    </div>
                                </div>
                                <div class="pie-legend-item">
                                    <span class="pie-legend-dot" style="background: #4a90e2;"></span>
                                    <div class="pie-legend-text">
                                        In progress
                                        <span class="pie-legend-count">40 (33%)</span>
                                    </div>
                                </div>
                                <div class="pie-legend-item">
                                    <span class="pie-legend-dot" style="background: #7eb8f5;"></span>
                                    <div class="pie-legend-text">
                                        Waiting client
                                        <span class="pie-legend-count">15 (12%)</span>
                                    </div>
                                </div>
                                <div class="pie-legend-item">
                                    <span class="pie-legend-dot" style="background: #b3d9ff;"></span>
                                    <div class="pie-legend-text">
                                        Completed
                                        <span class="pie-legend-count">40 (35%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Due Soon Section -->
                <div class="due-soon-section">
                    <h3 class="due-soon-title">Due Soon</h3>
                    <p class="due-soon-subtitle">Upcoming milestones, tasks, and invoices sorted by due date.</p>

                    <div class="due-item">
                        <div class="due-item-content">
                            <h6>Milestone "Backend API" - Acme Website</h6>
                            <div class="due-item-meta">Due in 2 days • $1,200</div>
                        </div>
                        <div class="due-item-action">Open →</div>
                    </div>

                    <div class="due-item">
                        <div class="due-item-content">
                            <h6>Invoice #1045 – Globex</h6>
                            <div class="due-item-meta"><span class="overdue">Overdue 5d</span> • $900</div>
                        </div>
                        <div class="due-item-action">Send Reminder</div>
                    </div>

                    <div class="due-item">
                        <div class="due-item-content">
                            <h6>Task "UI polish" – RAG Assistant</h6>
                            <div class="due-item-meta">Due today • Assignee: Hassan</div>
                        </div>
                        <div class="due-item-action">Mark Done</div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="activity-section">
                    <h3 class="activity-title">Recent Activity</h3>
                    <div class="activity-grid">
                        <div class="activity-item">
                            <img src="https://ui-avatars.com/api/?name=Hassan+Mehmood&background=0072d2&color=fff"
                                alt="Hassan" class="activity-avatar">
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-name">Hassan Mehmood</div>
                                    <div class="activity-time">10m ago</div>
                                </div>
                                <div class="activity-desc">approved Order #1021</div>
                                <div class="activity-meta">Milestone: Backend API</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <img src="https://ui-avatars.com/api/?name=Ali+Khan&background=28a745&color=fff"
                                alt="Ali" class="activity-avatar">
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-name">Ali Khan</div>
                                    <div class="activity-time">2h ago</div>
                                </div>
                                <div class="activity-desc">uploaded Invoice #1045</div>
                                <div class="activity-meta">Globex Project</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <img src="https://ui-avatars.com/api/?name=Sana+Riaz&background=dc3545&color=fff"
                                alt="Sana" class="activity-avatar">
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-name">Sana Riaz</div>
                                    <div class="activity-time">5h ago</div>
                                </div>
                                <div class="activity-desc">left a comment on Order #1021</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <img src="https://ui-avatars.com/api/?name=Hamza+Malik&background=ffc107&color=000"
                                alt="Hamza" class="activity-avatar">
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-name">Hamza Malik</div>
                                    <div class="activity-time">yesterday</div>
                                </div>
                                <div class="activity-desc">created new Order #1030</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <img src="https://ui-avatars.com/api/?name=Zara+Ahmed&background=17a2b8&color=fff"
                                alt="Zara" class="activity-avatar">
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-name">Zara Ahmed</div>
                                    <div class="activity-time">2d ago</div>
                                </div>
                                <div class="activity-desc">completed Milestone: UI Mockups</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <img src="https://ui-avatars.com/api/?name=Asad+Khan&background=6f42c1&color=fff"
                                alt="Asad" class="activity-avatar">
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-name">Asad Khan</div>
                                    <div class="activity-time">3d ago</div>
                                </div>
                                <div class="activity-desc">added New Client Proposal</div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="right-sidebar" id="rightSidebar">
                <!-- Tutorial Video -->
                <div class="right-card">
                    <h6>Tutorial Video</h6>
                    <div class="tutorial-placeholder">
                        <i class="fas fa-play-circle" style="font-size: 56px;"></i>
                    </div>
                </div>

                <!-- AI Message Refiner -->
                <div class="right-card refiner-card">
                    <h6>AI Message Refiner</h6>
                    <button class="refine-button">
                        Refine <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- Pinned Links -->
                <div class="right-card">
                    <div class="pinned-section pinned-links">
                        <span class="pinned-title">Pinned links</span>
                        <i class="fas fa-pen pinned-edit"></i>
                        <ul>
                            <li>Proposal template</li>
                            <li>Contract (e-sign)</li>
                            <li>Invoice template</li>
                            <li>Brand assets</li>
                        </ul>
                    </div>
                </div>

                <!-- Help & Learn -->
                <div class="right-card">
                    <div class="pinned-section pinned-links">
                        <span class="pinned-title">Help & learn</span>
                        <i class="fas fa-pen pinned-edit"></i>
                        <ul>
                            <li>How orders work</li>
                            <li>Connect Stripe</li>
                            <li>About milestones</li>
                            <li>Keyboard shortcuts</li>
                        </ul>
                    </div>
                </div>

                <!-- Sticky Upgrade Card -->
                <div class="upgrade-card sticky-on-scroll" id="upgradeCardRight">
                    <h6>Go Pro — Unlock Everything</h6>
                    <div class="icon"><i class="fas fa-rocket"></i></div>
                    <p style="margin:10px 0 16px; position: relative; z-index: 1;">
                        Advanced analytics, AI helpers & priority support.
                    </p>
                    <button class="btn-upgrade" type="button">Upgrade Now</button>
                </div>
            </aside>
        </div>
    </div>

    <style>
        /* keep size identical when sticky */
        .sticky-on-scroll {
            box-sizing: border-box;
        }

        .sticky-on-scroll.is-sticky {
            /* was: transform: scale(1.02);  <-- remove this to avoid growth */
            transform: none;
        }
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        'use strict';

        // DASHBOARD CONTROLLER
        class DashboardController {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.rightSidebar = document.getElementById('rightSidebar');
                this.sidebarToggle = document.getElementById('sidebarToggle');
                this.rightSidebarToggle = document.getElementById('rightSidebarToggle');
                this.overlay = document.getElementById('sidebarOverlay');
                this.init();
            }

            init() {
                this.setupToggles();
                this.setupOverlay();
                this.setupClickOutside();
                this.setupEscape();
                this.setupResize();
                console.log('✅ Dashboard Initialized');
            }

            setupToggles() {
                this.sidebarToggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.sidebar?.classList.toggle('active');
                    this.overlay?.classList.toggle('active');
                    this.rightSidebar?.classList.remove('active');
                });

                this.rightSidebarToggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.rightSidebar?.classList.toggle('active');
                    this.overlay?.classList.toggle('active');
                    this.sidebar?.classList.remove('active');
                });
            }

            setupOverlay() {
                this.overlay?.addEventListener('click', () => this.closeAll());
            }

            setupClickOutside() {
                document.addEventListener('click', (e) => {
                    const clickedOutside = !this.sidebar?.contains(e.target) &&
                        !this.rightSidebar?.contains(e.target) &&
                        !this.sidebarToggle?.contains(e.target) &&
                        !this.rightSidebarToggle?.contains(e.target);

                    if (clickedOutside) {
                        if (window.innerWidth <= 992 && this.sidebar?.classList.contains('active')) {
                            this.closeAll();
                        }
                        if (window.innerWidth <= 1200 && this.rightSidebar?.classList.contains('active')) {
                            this.closeAll();
                        }
                    }
                });

                this.sidebar?.addEventListener('click', (e) => e.stopPropagation());
                this.rightSidebar?.addEventListener('click', (e) => e.stopPropagation());
            }

            setupEscape() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') this.closeAll();
                });
            }

            setupResize() {
                let timer;
                window.addEventListener('resize', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        if (window.innerWidth > 992) {
                            this.sidebar?.classList.remove('active');
                            this.overlay?.classList.remove('active');
                        }
                        if (window.innerWidth > 1200) {
                            this.rightSidebar?.classList.remove('active');
                            this.overlay?.classList.remove('active');
                        }
                    }, 250);
                });
            }

            closeAll() {
                this.sidebar?.classList.remove('active');
                this.rightSidebar?.classList.remove('active');
                this.overlay?.classList.remove('active');
            }
        }

        // STICKY SCROLL HANDLER
        class StickyScrollHandler {
            constructor(selector, options = {}) {
                this.element = document.querySelector(selector);
                this.container = document.querySelector('.unified-scroll-wrapper');
                this.offset = options.offset || 100;
                this.isSticky = false;
                this.originalPos = null;
                this.placeholder = null;

                if (this.element && this.container) {
                    this.init();
                }
            }

            init() {
                this.storePosition();
                this.createPlaceholder();
                this.setupScroll();
                this.setupResize();
                this.check();
                console.log('✅ Sticky Scroll Initialized');
            }

            storePosition() {
                const rect = this.element.getBoundingClientRect();
                const scrollTop = this.container.scrollTop;
                this.originalPos = {
                    top: rect.top + scrollTop,
                    width: rect.width,
                    height: rect.height
                };
            }

            createPlaceholder() {
                this.placeholder = document.createElement('div');
                this.placeholder.className = 'sticky-placeholder';
                this.placeholder.style.display = 'none';
                this.element.parentNode.insertBefore(this.placeholder, this.element);
            }

            setupScroll() {
                let ticking = false;
                this.container.addEventListener('scroll', () => {
                    if (!ticking) {
                        requestAnimationFrame(() => {
                            this.check();
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, {
                    passive: true
                });
            }

            // setupResize() {
            //     if (window.ResizeObserver) {
            //         new ResizeObserver(() => {
            //             if (!this.isSticky) this.storePosition();
            //             this.updatePosition();
            //         }).observe(this.element);
            //     }
            // }

            check() {
                const scrollTop = this.container.scrollTop;
                const trigger = this.originalPos.top - this.offset;

                if (scrollTop >= trigger && !this.isSticky) {
                    this.makeSticky();
                } else if (scrollTop < trigger && this.isSticky) {
                    this.removeSticky();
                }

                // if (this.isSticky) this.updatePosition();
            }

            makeSticky() {
                this.isSticky = true;
                this.placeholder.style.display = 'block';
                this.placeholder.style.height = this.originalPos.height + 'px';
                this.placeholder.style.width = this.originalPos.width + 'px';

                this.element.style.position = 'fixed';
                this.element.style.top = this.offset + 'px';
                this.element.style.width = this.originalPos.width + 'px';
                this.element.style.zIndex = '999';
                this.element.classList.add('is-sticky');
            }

            removeSticky() {
                this.isSticky = false;
                this.placeholder.style.display = 'none';

                this.element.style.position = '';
                this.element.style.top = '';
                this.element.style.width = '';
                this.element.style.zIndex = '';
                this.element.style.left = '';
                this.element.classList.remove('is-sticky');
            }

            updatePosition() {
                if (!this.isSticky) return;
                const sidebar = this.element.closest('.right-sidebar');
                if (sidebar) {
                    const rect = sidebar.getBoundingClientRect();
                    this.element.style.left = rect.left + 'px';
                }
            }
        }

        // INITIALIZE
        document.addEventListener('DOMContentLoaded', () => {
            new DashboardController();

            const sticky = new StickyScrollHandler('#upgradeCardRight', {
                offset: 100
            });

            // Handle resize for sticky
            let timer;
            window.addEventListener('resize', () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    if (sticky) {
                        sticky.storePosition();
                        sticky.check();
                    }
                }, 250);
            });

            console.log('🎨 Dashboard Ready');
        });
    </script>

@endsection
