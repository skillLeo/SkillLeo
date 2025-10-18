@extends('layouts.app')

@section('title', $user->name . ' | SkillLeo')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg: #f3f2ee;
            --card: #fff;
            --ink: #1a1a1a;
            --muted: #000000af;
            --muted2: #999;
            --border: #e5e5e5;
            --accent: #1351d8;
            --accent-dark: #0d3393;
            --accent-light: #1351d818;
            --radius: 10px;
            --apc-g1: #a855f7;
            --apc-g2: #2dd4ea;
            --apc-bg: #f5f6f8;
            --fw-regular: 400;
            --fw-medium: 500;
            --fw-semibold: 600;
            --fw-bold: 700;
            --fw-extrabold: 800;
            --lh-compact: 1.2;
            --lh-tight: 1.3;
            --lh-normal: 1.5;
            --lh-relaxed: 1.65;
            --fs-display: clamp(1.75rem, 1.2vw + 1rem, 2.25rem);
            --fs-h1: clamp(1.375rem, 0.8vw + 1rem, 1.75rem);
            --fs-h2: 1.25rem;
            --fs-h3: 1.125rem;
            --fs-title: 1rem;
            --fs-body: 0.875rem;
            --fs-subtle: 0.8125rem;
            --fs-micro: 0.75rem;
            --ic-xxs: 0.625rem;
            --ic-xs: 0.75rem;
            --ic-sm: 0.875rem;
            --ic-md: 1rem;
            --ic-lg: 1.125rem;
            --mb-sections: 9px;
            --sticky-offset: 72px;
            --gradient-border: linear-gradient(135deg, #667eea 0%, #764ba2 35%, #f093fb 70%, #4facfe 100%);
            --gradient-button: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
            --text-primary: #1a1a1a;
            --text-heading: #1a1a1a;
            --text-body: #000000af;
            --text-muted: #667085;
            --text-subtle: #98a2b3;
            --text-disabled: #c0c0c0;
            --text-link: #0b63ff;
            --text-accent: #1351d8;
            --nav-bg: #fff;
            --nav-border: #e6e8eb;
            --nav-text: #000000af;
            --nav-icon: #444;
            --input-bg: #fff;
            --input-border: #e6e8eb;
            --input-text: #000000af;
            --input-placeholder: #9aa1a9;
            --card-title: #1a1a1a;
            --card-subtitle: #667085;
            --card-desc: #475569;
            --card-meta: #98a2b3;
            --btn-text-primary: #fff;
            --btn-text-secondary: #000000af;
            --tag-text: #000000af;
            --tag-bg: #fff;
            --tag-border: #1a1a1a;
            --badge-bg: #111;
            --badge-text: #fff;
            --section-title: #1e293b;
            --section-text: #323130;
            --nav-height-mobile: 107px;
            --nav-height-desktop: 64px;
            --text-white: #ffffff;
            --sidebar-width: 280px;
            --right-sidebar-width: 320px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg);
            overflow: hidden;
            height: 100vh;
        }

        /* Main Layout - Fixed Height with Single Scroll */
        .main-container {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            max-width: 1400px;
            margin: auto;
        }

        /* Sidebar Styles - Sticky with Internal Scroll */
        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-content {
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 15px;
            flex: 1;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background-color: var(--border);
            border-radius: 3px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background-color: var(--muted2);
        }

        /* Profile Card */
        .profile-card1 {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
            margin-bottom: var(--mb-sections);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .profile-card1 .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: var(--mb-sections);
        }

        .profile-card1 .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-card1 .status-badge {
            position: absolute;
            bottom: 0;
            right: -5px;
            background: #d4edda;
            color: #28a745;
            font-size: var(--fs-micro);
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: var(--fw-semibold);
        }

        .profile-card1 h6 {
            margin: 10px 0 5px;
            font-weight: var(--fw-semibold);
            font-size: var(--fs-title);
            color: var(--text-heading);
        }

        .profile-card1 .skills {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            font-style: italic;
            margin: 10px 0;
            line-height: var(--lh-tight);
        }

        .profile-card1 .location {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin: 10px 0;
        }

        .btn-view-profile {
            background: var(--accent);
            color: var(--btn-text-primary);
            border: none;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: var(--fs-subtle);
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: var(--fw-medium);
        }

        .btn-view-profile:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }

        /* Navigation Menu */
        .nav-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 10px;
            margin-bottom: var(--mb-sections);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .nav-section-title {
            font-size: var(--fs-micro);
            text-transform: uppercase;
            color: var(--text-muted);
            margin: 10px 10px 5px;
            font-weight: var(--fw-semibold);
            letter-spacing: 0.5px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-menu li {
            margin-bottom: 2px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-body);
            font-size: var(--fs-body);
            transition: all 0.2s ease;
            font-weight: var(--fw-regular);
        }

        .nav-menu a:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        .nav-menu a.active {
            background: var(--accent-light);
            color: var(--accent);
            font-weight: var(--fw-medium);
        }

        .nav-menu i {
            width: 20px;
            text-align: center;
            color: var(--nav-icon);
        }

        /* Upgrade Card */
        .upgrade-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: var(--radius);
            padding: 25px;
            text-align: center;
            color: var(--text-white);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            margin-bottom: var(--mb-sections);
        }

        .upgrade-card h6 {
            font-size: var(--fs-title);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--mb-sections);
        }

        .upgrade-card .icon {
            font-size: 48px;
            margin: 15px 0;
            opacity: 0.9;
        }

        .btn-upgrade {
            background: var(--card);
            color: #667eea;
            border: none;
            padding: 8px 24px;
            border-radius: 20px;
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-upgrade:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* UNIFIED SCROLL WRAPPER - Main + Right Sidebar */
        .unified-scroll-wrapper {
            flex: 1;
            display: flex;
            overflow-y: auto;
            overflow-x: hidden;
            height: 100vh;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .unified-scroll-wrapper::-webkit-scrollbar {
            width: 8px;
        }

        .unified-scroll-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .unified-scroll-wrapper::-webkit-scrollbar-thumb {
            background-color: var(--border);
            border-radius: 4px;
        }

        .unified-scroll-wrapper::-webkit-scrollbar-thumb:hover {
            background-color: var(--muted2);
        }

        /* Content Area - NO Individual Scroll */
        .content-area {
            flex: 1;
            padding: 25px;
            min-height: 100vh;
        }

        .page-title {
            font-size: var(--fs-display);
            font-weight: var(--fw-bold);
            margin-bottom: var(--mb-sections);
            color: var(--text-heading);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: var(--mb-sections);
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 15px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--mb-sections);
        }

        .stat-label {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: var(--fw-semibold);
        }

        .stat-value {
            font-size: 24px;
            font-weight: var(--fw-bold);
            margin: 5px 0;
            color: var(--text-heading);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            background: var(--bg);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 18px;
        }

        .stat-change {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change .positive {
            color: #28a745;
            font-weight: var(--fw-semibold);
        }

        .stat-change .negative {
            color: #dc3545;
            font-weight: var(--fw-semibold);
        }

        /* Chart Container */
        .chart-container {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: var(--mb-sections);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--mb-sections);
            flex-wrap: wrap;
            gap: 10px;
        }

        .chart-title {
            font-size: var(--fs-h3);
            font-weight: var(--fw-semibold);
            color: var(--card-title);
        }

        .chart-subtitle {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin-top: 2px;
        }

        .time-filters {
            display: flex;
            gap: 8px;
        }

        .time-filter {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: var(--fs-subtle);
            cursor: pointer;
            transition: all 0.2s ease;
            background: transparent;
            border: 1px solid var(--border);
            font-weight: var(--fw-medium);
            color: var(--text-body);
        }

        .time-filter:hover {
            background: var(--bg);
        }

        .time-filter.active {
            background: var(--accent);
            color: var(--btn-text-primary);
            border-color: var(--accent);
        }

        .chart-area {
            height: 220px;
            background: var(--bg);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .chart-legend {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-top: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: var(--fs-subtle);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        /* Row Cards */
        .row-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: var(--mb-sections);
        }

        .funnel-card,
        .orders-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .card-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            text-align: center;
            margin-bottom: 5px;
            color: var(--card-title);
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
            gap: 15px;
            margin-bottom: var(--mb-sections);
        }

        .funnel-label {
            min-width: 120px;
            font-size: var(--fs-subtle);
            color: var(--text-body);
        }

        .funnel-bar-container {
            flex: 1;
            height: 32px;
            background: var(--accent-light);
            border-radius: 6px;
            position: relative;
            overflow: hidden;
        }

        .funnel-bar {
            height: 100%;
            background: var(--accent);
            border-radius: 6px;
            transition: width 0.5s ease;
        }

        .funnel-percent {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            color: var(--ink);
        }

        .funnel-note {
            text-align: center;
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin-top: 15px;
        }

        /* Pie Chart */
        .pie-chart-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin: 20px 0;
        }

        .pie-chart {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: conic-gradient(var(--accent) 0deg 72deg,
                    #4a90e2 72deg 190.8deg,
                    #7eb8f5 190.8deg 234deg,
                    #b3d9ff 234deg 360deg);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pie-chart-inner {
            width: 110px;
            height: 110px;
            background: var(--card);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .pie-chart-value {
            font-size: 24px;
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
            gap: 10px;
        }

        .pie-legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pie-legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 3px;
        }

        .pie-legend-text {
            font-size: var(--fs-subtle);
        }

        .pie-legend-count {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            display: block;
        }

        /* Due Soon Section */
        .due-soon-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: var(--mb-sections);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .due-soon-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            margin-bottom: 5px;
            color: var(--card-title);
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
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
        }

        .due-item:last-child {
            border-bottom: none;
        }

        .due-item-content h6 {
            font-size: var(--fs-title);
            font-weight: var(--fw-medium);
            margin-bottom: 5px;
            color: var(--text-heading);
        }

        .due-item-meta {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .due-item-meta .overdue {
            color: #dc3545;
            font-weight: var(--fw-semibold);
        }

        .due-item-action {
            font-size: var(--fs-subtle);
            color: var(--accent);
            cursor: pointer;
            font-weight: var(--fw-medium);
        }

        .due-item-action:hover {
            text-decoration: underline;
        }

        /* Activity Feed */
        .activity-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: var(--mb-sections);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .activity-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--mb-sections);
            color: var(--card-title);
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }

        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: var(--bg);
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            background: var(--card);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .activity-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
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
            margin-bottom: 2px;
        }

        .activity-meta {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        /* Right Sidebar - NO Individual Scroll */
        .right-sidebar {
            width: var(--right-sidebar-width);
            flex-shrink: 0;
            padding: 20px 15px;
            min-height: 100vh;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .right-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: var(--mb-sections);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .right-card h6 {
            font-size: var(--fs-title);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--mb-sections);
            color: var(--card-title);
        }

        .tutorial-placeholder {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-white);
            font-size: var(--fs-body);
        }

        .ai-profile-creator {
            text-align: center;
        }

        .ai-profile-creator p {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin: 10px 0;
        }

        .gradient-border-button {
            position: relative;
            display: inline-block;
            width: 100%;
            margin: 15px 0 10px;
        }

        .gradient-border-button button {
            background: var(--card);
            border: 2px solid transparent;
            background-clip: padding-box;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            font-size: var(--fs-subtle);
            color: var(--text-body);
            cursor: pointer;
            position: relative;
            font-weight: var(--fw-medium);
        }

        .gradient-border-button::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: -1;
            margin: -2px;
            border-radius: 8px;
            background: var(--gradient-button);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            padding: 0 10px;
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .textarea-gradient-border {
            position: relative;
            padding: 2px;
            background: var(--gradient-button);
            border-radius: 8px;
            margin: 10px 0;
        }

        .textarea-gradient-border textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: none;
            border-radius: 6px;
            resize: none;
            font-size: var(--fs-subtle);
            background: var(--card);
            color: var(--text-body);
        }

        .textarea-gradient-border textarea:focus {
            outline: none;
        }

        .gradient-button {
            background: var(--gradient-button);
            color: var(--btn-text-primary);
            border: none;
            padding: 8px 24px;
            border-radius: 25px;
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 10px 0;
        }

        .gradient-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(91, 134, 229, 0.3);
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
            transition: all 0.2s ease;
        }

        .refine-button:hover {
            transform: translateY(-1px);
        }

        .pinned-section {
            position: relative;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 15px 15px;
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
            padding: 0 5px;
            color: var(--accent);
            cursor: pointer;
        }

        .pinned-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pinned-links li {
            padding: 8px 0;
            font-size: var(--fs-body);
            color: var(--text-body);
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .pinned-links li:hover {
            color: var(--accent);
        }

        /* Toggle Buttons */
        .sidebar-toggle {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--accent);
            color: var(--btn-text-primary);
            border: none;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(19, 81, 216, 0.4);
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.05);
        }

        .right-sidebar-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1001;
            background: var(--accent);
            color: var(--btn-text-primary);
            border: none;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(19, 81, 216, 0.4);
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .right-sidebar-toggle:hover {
            transform: scale(1.05);
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .right-sidebar {
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                overflow-y: auto;
                background: var(--bg);
                transform: translateX(100%);
                z-index: 1000;
                box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .right-sidebar.active {
                transform: translateX(0);
            }

            .right-sidebar-toggle {
                display: flex;
            }

            /* Unified wrapper takes full width when right sidebar is hidden */
            .unified-scroll-wrapper {
                width: 100%;
            }
        }

        @media (max-width: 1200px) {
            .row-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                background: var(--bg);
                transform: translateX(-100%);
                z-index: 1000;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: flex;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .content-area {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 15px;
            }

            .page-title {
                font-size: var(--fs-h1);
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-value {
                font-size: 20px;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
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
                gap: 20px;
            }

            .funnel-label {
                min-width: 100px;
                font-size: var(--fs-subtle);
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
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
        .activity-section {
            animation: fadeIn 0.5s ease-out;
        }

        /* Smooth Scroll */
        .unified-scroll-wrapper,
        .sidebar-content {
            scroll-behavior: smooth;
        }
    </style>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Toggle Buttons -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <button class="right-sidebar-toggle" id="rightSidebarToggle">
        <i class="fas fa-sliders-h"></i>
    </button>




    <x-navigation.top-nav :user="$user"  :username="$username"/>


    <!-- Main Container -->
    <div class="main-container">
        <!-- Left Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <!-- Profile Card -->
                <div class="profile-card1">
                    <div class="avatar-container">
                        <img src="https://ui-avatars.com/api/?name=Hassam+Mehmood&background=0072d2&color=fff"
                            alt="Avatar" class="avatar">
                        <span class="status-badge">Available</span>
                    </div>
                    <h6>Hassam Mehmood</h6>
                    <div class="skills">PHP · Laravel · React Js · Node Js · Express Js · Mongo DB · Tailwind CSS</div>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Sargodha, Pakistan</span>
                    </div>
                    <button class="btn-view-profile">View public profile</button>
                </div>


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

                <!-- Manage Projects/Orders Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Manage Projects</div>
                    <ul class="nav-menu">
                        <li><a href="#"><i class="fas fa-project-diagram"></i> Projects</a></li>
                        <li><a href="#"><i class="fas fa-clipboard-list"></i> Orders</a></li>
                        <li><a href="#"><i class="fas fa-users"></i> Team Members</a></li>
                        <li><a href="#"><i class="fas fa-user-tie"></i> Clients</a></li>
                    </ul>
                </div>

                <!-- Legal Docs Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Legal & Finance</div>
                    <ul class="nav-menu">
                        <li><a href="#"><i class="fas fa-file-invoice"></i> Invoices</a></li>
                        <li><a href="#"><i class="fas fa-file-contract"></i> Contracts</a></li>
                        <li><a href="#"><i class="fas fa-balance-scale"></i> Legal Docs</a></li>
                    </ul>
                </div>

                <!-- Auth Section -->
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
                    <p style="font-size: 12px; margin-bottom: var(--mb-sections); opacity: 0.9;">Unlock premium features for
                        smooth workflow</p>
                    <button class="btn-upgrade">See plans</button>
                </div>
            </div>
        </aside>

        <!-- UNIFIED SCROLL WRAPPER: Main Content + Right Sidebar -->
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
                                <div class="stat-value">12,450</div>
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
                                <div class="stat-value">28,960</div>
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
                                <div class="stat-value">1,280</div>
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
                                <div class="stat-value">$24,600</div>
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
                            <div class="time-filter">7d</div>
                            <div class="time-filter active">30d</div>
                            <div class="time-filter">90d</div>
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

            <style>
                .sticky-on-scroll {
                    position: sticky !important;
                    top: 100px !important;
                    z-index: 100;
                    box-sizing: border-box;
                }


                @media (min-width: 1201px) {
                    .right-sidebar-content {
                        overflow: visible !important;
                    }
                }
            </style>
            <!-- Right Sidebar - Part of Unified Scroll -->
            <aside class="right-sidebar" id="rightSidebar">
                <div class="right-sidebar-content">
                    <!-- Tutorial Video -->
                    <div class="right-card">
                        <h6>Tutorial Video</h6>
                        <div class="tutorial-placeholder">
                            <i class="fas fa-play-circle" style="font-size: 48px;"></i>
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

                    <!-- Upgrade (sticks at 20px when it reaches the top) -->
                    <div class="upgrade-card sticky-on-scroll" id="upgradeCardRight">
                        <h6>Go Pro — Unlock Everything</h6>
                        <div class="icon"><i class="fas fa-rocket"></i></div>
                        <p style="margin:10px 0 16px">
                            Advanced analytics, AI helpers & priority support.
                        </p>
                        <button class="btn-upgrade" type="button">Upgrade Now</button>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <style>
        /* ============================================
       STICKY SCROLL PROFESSIONAL STYLES
       ============================================ */

        /* Base sticky element */
        .sticky-on-scroll {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        /* When element becomes sticky */
        .sticky-on-scroll.is-sticky {
            position: fixed !important;
            top: 100px !important;
            z-index: 999;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
            transform: scale(1.02);
        }

        /* Placeholder to prevent layout shift */
        .sticky-placeholder {
            display: none;
            visibility: hidden;
        }

        /* Smooth transitions */
        .sticky-on-scroll.is-sticky {
            animation: stickySlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes stickySlideIn {
            from {
                opacity: 0.8;
                transform: translateY(-10px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1.02);
            }
        }

        /* ============================================
       RESPONSIVE BEHAVIOR
       ============================================ */

        /* Disable sticky on mobile for better UX */
        @media (max-width: 1200px) {
            .sticky-on-scroll.is-sticky {
                position: relative !important;
                top: auto !important;
                transform: none !important;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            }
        }

        /* ============================================
       ENHANCED STICKY STYLES (OPTIONAL)
       ============================================ */

        /* Add pulse animation when sticky */
        .sticky-on-scroll.is-sticky::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: calc(var(--radius) + 2px);
            z-index: -1;
            opacity: 0.3;
            animation: stickyPulse 2s ease-in-out infinite;
        }

        @keyframes stickyPulse {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.05);
            }
        }

        /* ============================================
       SCROLL CONTAINER OPTIMIZATION
       ============================================ */

        /* Ensure smooth scrolling */
        .unified-scroll-wrapper {
            scroll-behavior: smooth;
            position: relative;
        }

        /* Hardware acceleration for better performance */
        .sticky-on-scroll,
        .sticky-on-scroll.is-sticky {
            will-change: transform, position;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        /* ============================================
       ACCESSIBILITY
       ============================================ */

        /* Respect reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {

            .sticky-on-scroll,
            .sticky-on-scroll.is-sticky {
                transition: none;
                animation: none;
            }

            .sticky-on-scroll.is-sticky::before {
                animation: none;
            }
        }

        /* ============================================
       RIGHT SIDEBAR SPECIFIC ADJUSTMENTS
       ============================================ */

        /* Ensure right sidebar content flows properly */
        .right-sidebar-content {
            position: relative;
        }

        /* Fix for right sidebar when sticky card is active */
        @media (min-width: 1201px) {
            .right-sidebar {
                position: relative;
            }

            .right-sidebar-content {
                overflow: visible !important;
            }
        }

        /* ============================================
       DEBUGGING (Remove in production)
       ============================================ */

        /* Uncomment to visualize sticky trigger points */
        /*
    .sticky-sentinel {
        background: rgba(255, 0, 0, 0.2) !important;
        height: 2px !important;
    }

    .sticky-placeholder {
        background: rgba(0, 255, 0, 0.2) !important;
        border: 2px dashed green !important;
    }
    */
    </style>

    <script>
 
        class StickyScrollHandler {
            constructor(elementSelector, options = {}) {
                this.element = document.querySelector(elementSelector);
                this.scrollContainer = document.querySelector('.unified-scroll-wrapper');

                // Configuration
                this.config = {
                    stickyOffset: options.stickyOffset || 100, // Distance from top when sticky
                    threshold: options.threshold || 0,
                    rootMargin: options.rootMargin || '0px'
                };

                // State
                this.isSticky = false;
                this.originalPosition = null;
                this.placeholder = null;

                if (this.element && this.scrollContainer) {
                    this.init();
                }
            }

            init() {
                // Store original position
                this.storeOriginalPosition();

                // Create placeholder to prevent layout shift
                this.createPlaceholder();

                // Setup scroll listener
                this.setupScrollListener();

                // Setup resize observer
                this.setupResizeObserver();

                // Initial check
                this.checkPosition();
            }

            storeOriginalPosition() {
                const rect = this.element.getBoundingClientRect();
                const scrollTop = this.scrollContainer.scrollTop;

                this.originalPosition = {
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

            setupScrollListener() {
                let ticking = false;

                this.scrollContainer.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(() => {
                            this.checkPosition();
                            ticking = false;
                        });
                        ticking = true;
                    }
                });
            }

            setupResizeObserver() {
                const resizeObserver = new ResizeObserver(() => {
                    if (!this.isSticky) {
                        this.storeOriginalPosition();
                    }
                });

                resizeObserver.observe(this.element);
            }

            checkPosition() {
                const scrollTop = this.scrollContainer.scrollTop;
                const containerRect = this.scrollContainer.getBoundingClientRect();
                const triggerPoint = this.originalPosition.top - this.config.stickyOffset;

                if (scrollTop >= triggerPoint && !this.isSticky) {
                    this.makeSticky();
                } else if (scrollTop < triggerPoint && this.isSticky) {
                    this.removeSticky();
                }

                // Update sticky position while scrolling
                if (this.isSticky) {
                    this.updateStickyPosition();
                }
            }

            makeSticky() {
                this.isSticky = true;

                // Show placeholder to prevent layout shift
                this.placeholder.style.display = 'block';
                this.placeholder.style.height = `${this.originalPosition.height}px`;
                this.placeholder.style.width = `${this.originalPosition.width}px`;

                // Apply sticky styles
                this.element.style.position = 'fixed';
                this.element.style.top = `${this.config.stickyOffset}px`;
                this.element.style.width = `${this.originalPosition.width}px`;
                this.element.style.zIndex = '999';
                this.element.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';

                // Add sticky class for additional styling
                this.element.classList.add('is-sticky');
            }

            removeSticky() {
                this.isSticky = false;

                // Hide placeholder
                this.placeholder.style.display = 'none';

                // Remove sticky styles
                this.element.style.position = '';
                this.element.style.top = '';
                this.element.style.width = '';
                this.element.style.zIndex = '';

                // Remove sticky class
                this.element.classList.remove('is-sticky');
            }

            updateStickyPosition() {
                const rightSidebarRect = this.element.closest('.right-sidebar').getBoundingClientRect();
                this.element.style.left = `${rightSidebarRect.left}px`;
            }

            destroy() {
                this.removeSticky();
                if (this.placeholder && this.placeholder.parentNode) {
                    this.placeholder.parentNode.removeChild(this.placeholder);
                }
            }
        }

        // ============================================
        // INITIALIZE ON DOM READY
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sticky scroll handler
            const stickyHandler = new StickyScrollHandler('#upgradeCardRight', {
                stickyOffset: 100, // Distance from top when sticky
                threshold: 0
            });

            // Handle window resize - recalculate positions
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    // Reinitialize on significant resize
                    if (stickyHandler) {
                        stickyHandler.storeOriginalPosition();
                        stickyHandler.checkPosition();
                    }
                }, 250);
            });

            // Cleanup on page unload (optional)
            window.addEventListener('beforeunload', function() {
                if (stickyHandler) {
                    stickyHandler.destroy();
                }
            });

            console.log('✅ Sticky Scroll Handler Initialized');
        });

 
        function initStickyWithIntersectionObserver() {
            const upgradeCard = document.querySelector('#upgradeCardRight');
            const scrollContainer = document.querySelector('.unified-scroll-wrapper');

            if (!upgradeCard || !scrollContainer) return;

            // Create a sentinel element at the trigger point
            const sentinel = document.createElement('div');
            sentinel.className = 'sticky-sentinel';
            sentinel.style.position = 'absolute';
            sentinel.style.height = '1px';
            sentinel.style.width = '100%';
            sentinel.style.top = '0';
            sentinel.style.pointerEvents = 'none';

            upgradeCard.parentNode.insertBefore(sentinel, upgradeCard);

            // Create observer
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (!entry.isIntersecting) {
                            upgradeCard.classList.add('is-sticky');
                        } else {
                            upgradeCard.classList.remove('is-sticky');
                        }
                    });
                }, {
                    root: scrollContainer,
                    threshold: 0,
                    rootMargin: '-20px 0px 0px 0px'
                }
            );

            observer.observe(sentinel);
        }

        // Uncomment to use Intersection Observer method instead:
        // document.addEventListener('DOMContentLoaded', initStickyWithIntersectionObserver);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Functionality
        const sidebar = document.getElementById('sidebar');
        const rightSidebar = document.getElementById('rightSidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const rightSidebarToggle = document.getElementById('rightSidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');

        // Left Sidebar Toggle
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            // Close right sidebar if open
            if (rightSidebar.classList.contains('active')) {
                rightSidebar.classList.remove('active');
            }
        });

        // Right Sidebar Toggle
        rightSidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            rightSidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            // Close left sidebar if open
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });

        // Overlay Click - Close All Sidebars
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            rightSidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Close sidebars when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isMobile = window.innerWidth <= 992;
            const isTablet = window.innerWidth <= 1200;

            // Check if click is outside sidebars and toggles
            const clickedOutside = !sidebar.contains(event.target) &&
                !rightSidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target) &&
                !rightSidebarToggle.contains(event.target);

            if (clickedOutside) {
                if (isMobile && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }

                if (isTablet && rightSidebar.classList.contains('active')) {
                    rightSidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            }
        });

        // Prevent sidebar content clicks from closing sidebar
        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        rightSidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const width = window.innerWidth;

                // Reset left sidebar on desktop
                if (width > 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }

                // Reset right sidebar on large desktop
                if (width > 1200) {
                    rightSidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            }, 250);
        });

        // Time filter functionality
        document.querySelectorAll('.time-filter').forEach(filter => {
            filter.addEventListener('click', function() {
                document.querySelectorAll('.time-filter').forEach(f => f.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Animate funnel bars on load
        window.addEventListener('load', function() {
            document.querySelectorAll('.funnel-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });

        // Active navigation link handling
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active from all links in the same section
                const parentMenu = this.closest('.nav-menu');
                parentMenu.querySelectorAll('a').forEach(a => a.classList.remove('active'));

                // Add active to clicked link
                this.classList.add('active');

                // Close sidebar on mobile after selection
                if (window.innerWidth <= 992) {
                    setTimeout(() => {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }, 300);
                }
            });
        });

        // Escape key to close sidebars
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                sidebar.classList.remove('active');
                rightSidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // Add hover effect to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.borderColor = 'var(--accent-light)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.borderColor = 'var(--border)';
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe animated elements
        document.querySelectorAll(
            '.stat-card, .chart-container, .funnel-card, .orders-card, .due-soon-section, .activity-section').forEach(
            el => {
                observer.observe(el);
            });

        // Handle activity item interactions
        document.querySelectorAll('.activity-item').forEach(item => {
            item.addEventListener('click', function() {
                console.log('Activity item clicked');
            });
        });

        // Handle due item actions
        document.querySelectorAll('.due-item-action').forEach(action => {
            action.addEventListener('click', function(e) {
                e.stopPropagation();
                console.log('Due item action clicked:', this.textContent);
            });
        });

        // Pinned links edit functionality
        document.querySelectorAll('.pinned-edit').forEach(edit => {
            edit.addEventListener('click', function() {
                console.log('Edit pinned links clicked');
            });
        });

        // Console log for debugging
        console.log('Dashboard initialized successfully');
        console.log('Unified scroll enabled - Main content and right sidebar scroll together');
        console.log('Left Sidebar:', sidebar);
        console.log('Right Sidebar:', rightSidebar);
        console.log('Viewport width:', window.innerWidth);
    </script>











<style>
    
</style>
@endsection <!-- Manage Profile Section -->
