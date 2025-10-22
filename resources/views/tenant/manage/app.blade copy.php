{{-- resources/views/tenant/manage/app.blade.php --}}
@extends('layouts.app')

@section('title', $user->name . ' | SkillLeo')

@push('styles')
    <style>
        /* ============================================
   COMPLETE PROFESSIONAL DASHBOARD CSS
   Full Code - No Missing Styles
   ============================================ */

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
}

/* ============================================
   BODY & BASE STYLES
   ============================================ */

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
   MAIN CONTAINER & LAYOUT
   ============================================ */

.main-container {
    display: flex;
    height: 100vh;
    width: 100%;
    overflow: hidden;
    max-width: 1400px;
    margin: 2vw auto;
}

/* ============================================
   LEFT SIDEBAR
   ============================================ */

.sidebar {
    width: var(--sidebar-width);
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    transition: transform var(--transition-base);
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar-content {
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0 var(--space-sm);
    flex: 1;
    padding-top: 20px;
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
}

.sidebar-content::-webkit-scrollbar {
    width: 3px;
}

.sidebar-content::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 3px;
}

.sidebar-content::-webkit-scrollbar-track {
    background: transparent;
}

/* ============================================
   PROFILE CARD IN SIDEBAR
   ============================================ */

.profile-card1 {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0;
    margin-bottom: var(--mb-sections);
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}

.profile-card1:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.profile-cover {
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

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
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.profile-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background: #10b981;
    border: 3px solid var(--card);
    border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.profile-info {
    text-align: center;
    padding: var(--space-md) var(--space-lg);
}

.profile-name {
    font-size: 1rem;
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
    background: linear-gradient(var(--card), var(--card)) padding-box, linear-gradient(90deg, #667eea, #764ba2) border-box;
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
    transition: transform var(--transition-base);
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
    transition: all var(--transition-base);
    border-radius: 6px;
    padding-left: var(--space-xs);
}

.pinned-links li:hover {
    color: var(--accent);
    background: var(--accent-light);
    padding-left: var(--space-sm);
}

/* ============================================
   STICKY SCROLL
   ============================================ */

.sticky-on-scroll {
    position: relative;
    transition: all var(--transition-base);
    box-sizing: border-box;
}

.sticky-on-scroll.is-sticky {
    position: fixed !important;
    top: 100px !important;
    z-index: 999;
    box-shadow: var(--shadow-2xl);
    transform: none;
}

.sticky-placeholder {
    display: none;
    visibility: hidden;
}

/* ============================================
   SIDEBAR OVERLAY
   ============================================ */

.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(3px);
    z-index: 1001;
    opacity: 0;
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
}

.sidebar-overlay.active {
    display: block;
    opacity: 1;
    pointer-events: auto;
}

/* ============================================
   TOGGLE BUTTONS
   ============================================ */



.sidebar-toggle:hover,
.right-sidebar-toggle:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-2xl);
}

.sidebar-toggle:active,
.right-sidebar-toggle:active {
    transform: scale(0.9) rotate(90deg);
}

.sidebar-toggle {
    left: 20px;
}

.right-sidebar-toggle {
    right: 20px;
}

.sidebar-toggle:focus,
.right-sidebar-toggle:focus {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

/* ============================================
   ALERTS & NOTIFICATIONS
   ============================================ */

.alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    animation: slideIn 0.3s ease;
}

.alert svg {
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================================
   PAGE HEADER
   ============================================ */



.page-actions {
    display: flex;
    gap: 8px;
}

/* ============================================
   BUTTONS
   ============================================ */

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 32px;
    padding: 0 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
}

.btn svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(19, 81, 216, 0.3);
}

.btn-secondary {
    background: var(--card);
    color: var(--text-body);
    border: 1px solid var(--border);
}

.btn-secondary:hover {
    background: var(--bg);
    border-color: var(--text-muted);
}

button:focus {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

/* ============================================
   TABS
   ============================================ */

.tabs-container {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    border-bottom: 2px solid var(--border);
}

.tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    color: var(--text-muted);
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
}

.tab-btn svg {
    opacity: 0.6;
}

.tab-btn:hover {
    color: var(--text-body);
}

.tab-btn.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}

.tab-btn.active svg {
    opacity: 1;
}

.tab-count {
    padding: 2px 8px;
    background: var(--bg);
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* ============================================
   CONTENT SECTION
   ============================================ */

.content-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 32px;
    margin-bottom: var(--mb-sections);
    transition: border-color 0.2s ease;
}

.content-section:hover {
    border-color: rgba(19, 81, 216, 0.3);
}

/* ============================================
   TOOLBAR
   ============================================ */

.section-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    border-radius: 6px;
    flex: 1;
    max-width: 300px;
}

.search-box svg {
    flex-shrink: 0;
    color: var(--text-muted);
}

.search-box input {
    border: none;
    background: none;
    outline: none;
    width: 100%;
    font-size: 14px;
    color: var(--input-text);
    font-family: inherit;
}

.search-box input::placeholder {
    color: var(--text-muted);
    opacity: 0.6;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--input-bg);
    color: var(--input-text);
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
}

.filter-select:hover {
    border-color: rgba(19, 81, 216, 0.4);
}

.filter-select:focus {
    border-color: var(--accent);
}

/* ============================================
   INSPECTOR PANEL
   ============================================ */

.inspector-panel {
    display: flex;
    flex-direction: column;
    gap: var(--mb-sections);
}

.inspector-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.inspector-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
}

.inspector-desc {
    font-size: 13px;
    color: var(--text-muted);
    margin: 4px 0 0 0;
}

.inspector-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s;
}

.inspector-close:hover {
    background: var(--bg);
    color: var(--text-body);
}

.inspector-body {
    display: flex;
    flex-direction: column;
    gap: var(    --mb-sections);
}

/* ============================================
   FORM ELEMENTS
   ============================================ */

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-body);
}

.required {
    color: #ff5630;
}

.form-control {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    background: var(--input-bg);
    color: var(--input-text);
    transition: all 0.15s ease;
}

.form-control:hover {
    border-color: rgba(19, 81, 216, 0.4);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(19, 81, 216, 0.1);
}

.form-control::placeholder {
    color: var(--text-muted);
    opacity: 0.6;
}

/* ============================================
   LEVEL SELECTOR
   ============================================ */

.level-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--mb-sections);
}

.level-btn {
    padding: 12px;
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: left;
}

.level-btn:hover {
    border-color: var(--accent);
}

.level-btn.active {
    border-color: var(--accent);
    background: rgba(19, 81, 216, 0.05);
}

.level-label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: var(--text-heading);
    margin-bottom: 4px;
}

.level-desc {
    display: block;
    font-size: 11px;
    color: var(--text-muted);
}

.form-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    padding-top: 8px;
    border-top: 1px solid var(--border);
}

/* ============================================
   HELP CARDS
   ============================================ */

.help-card {
    padding: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 16px;
}

.help-card h4 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: var(--text-heading);
}

.help-card.accent {
    background: linear-gradient(135deg, rgba(19, 81, 216, 0.1), rgba(19, 81, 216, 0.05));
    border-color: var(--accent);
}

.help-card p {
    margin: 0;
    font-size: 13px;
    color: var(--text-body);
    line-height: 1.5;
}

.shortcut-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.shortcut-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: var(--text-body);
}

kbd {
    padding: 4px 8px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    min-width: 28px;
    text-align: center;
    font-family: monospace;
}

.help-list {
    margin: 0;
    padding-left: 20px;
}

.help-list li {
    font-size: 13px;
    margin-bottom: 8px;
    color: var(--text-body);
    line-height: 1.5;
}

/* ============================================
   EMPTY STATE
   ============================================ */

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state svg {
    color: var(--text-muted);
    opacity: 0.3;
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 8px 0;
}

.empty-state p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0 0 20px 0;
}

/* ============================================
   ANIMATIONS
   ============================================ */

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

.stat-card:nth-child(1) { animation-delay: 0.05s; }
.stat-card:nth-child(2) { animation-delay: 0.1s; }
.stat-card:nth-child(3) { animation-delay: 0.15s; }
.stat-card:nth-child(4) { animation-delay: 0.2s; }
.stat-card:nth-child(5) { animation-delay: 0.25s; }
.stat-card:nth-child(6) { animation-delay: 0.3s; }

.sidebar.active {
    animation: slideInLeft 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.right-sidebar.active {
    animation: slideInRight 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideInLeft {
    from {
        transform: translateX(-100%);
        opacity: 0.8;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0.8;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* ============================================
   PROFESSIONAL FULL-SCREEN SIDEBAR OVERLAY
   ============================================ */

nav,
.top-nav,
[class*="nav"] {
    position: relative;
    z-index: 50;
}

body:has(.sidebar.active) nav,
body:has(.right-sidebar.active) nav {
    pointer-events: none;
    user-select: none;
}

.sidebar-toggle,
.right-sidebar-toggle {
    pointer-events: auto !important;
}

.sidebar.active,
.right-sidebar.active {
    box-shadow: 0 0 0 1px rgba(19, 81, 216, 0.1), 0 8px 32px rgba(0, 0, 0, 0.15);
}

body:has(.sidebar.active),
body:has(.right-sidebar.active) {
    overflow: hidden;
}

/* ============================================
   RESPONSIVE BREAKPOINTS
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

@media (max-width: 1200px) {
    .right-sidebar {
        position: fixed;
        right: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        background: var(--bg);
        transform: translateX(100%);
        z-index: 1002;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
        /* width: 85vw; */
        max-width: 380px;
        padding-top: 20px;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }
 
    .right-sidebar.active {
        transform: translateX(0);
    }

    .unified-scroll-wrapper {
        width: 100%;
    }

    .content-area {
        max-width: 100%;
        width: 100%;
        padding: var(--space-lg);
    }

    .right-sidebar-toggle {
        display: flex;
    }

 

    .right-sidebar::-webkit-scrollbar {
        width: 3px;
    }

    .right-sidebar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }





 
        #upgradeCardRight {
            display: none
        }
       
}

@media (max-width: 992px) {
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        background: var(--bg);
        transform: translateX(-100%);
        z-index: 1002;
        height: 100vh;
        max-height: 100vh;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        padding-top: 20px;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        width: 85vw;
        max-width: 280px;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .unified-scroll-wrapper {
        margin-left: 0;
    }

    .content-area {
        padding: var(--space-md);
        max-width: 100%;
    }

    .sidebar-toggle {
        display: flex;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .content-area {
        padding: var(--space-sm);
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

    .row-cards {
        grid-template-columns: 1fr;
    }

    .sidebar-toggle {
        bottom: 90px;
    }

    .profile-cover {
        height: 70px;
    }

    .profile-avatar-wrapper1 {
        margin-top: -28px;
    }

    .profile-avatar {
        width: 64px;
        height: 64px;
    }

    .profile-name {
        font-size: 0.9375rem;
    }

    .btn-view-profile {
        padding: 9px 14px;
        font-size: 13px;
    }



    .section-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        max-width: none;
    }

    .level-selector {
        grid-template-columns: 1fr;
    }



 
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }


    
        .skills-grid {
            flex-direction: column;
        }




        .soft-skills-grid {
            grid-template-columns: 1fr;
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

    body {
        padding-top: var(--nav-height-mobile);
    }

    .sidebar-toggle,
    .right-sidebar-toggle {
        width: 48px;
        height: 48px;
        font-size: 18px;
        bottom: 16px;
    }

    .sidebar-toggle {
        left: 16px;
    }

    .right-sidebar-toggle {
        right: 16px;
    }

    .sidebar,
    .right-sidebar {
        width: 90vw;
        max-width: 100%;
    }


 
}

/* ============================================
   ACCESSIBILITY
   ============================================ */

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

@media (prefers-color-scheme: dark) {
    [data-theme="dark"] .profile-card1:hover {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .profile-avatar {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
    }

    [data-theme="dark"] .btn-view-profile:hover {
        box-shadow: 0 4px 16px rgba(112, 181, 249, 0.3);
    }
    .level-1 {
            background: rgba(253, 230, 138, 0.2);
            color: #fbbf24;
        }

        .level-2 {
            background: rgba(147, 197, 253, 0.2);
            color: #60a5fa;
        }

        .level-3 {
            background: rgba(167, 243, 208, 0.2);
            color: #34d399;
        }
}

/* ============================================
   SMOOTH TRANSITIONS
   ============================================ */

.main-container,
.unified-scroll-wrapper,
.content-area,
.sidebar,
.right-sidebar {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.content-area {
    transition-property: padding, max-width;
}

.unified-scroll-wrapper {
    transition-property: margin, width;
}




.profile-headline {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
    margin: 0 0 var(--space-sm) 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

.profile-divider {
    height: 1px;
    background: var(--border);
    margin: 0 var(--space-lg) var(--space-md);
}

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
    font-weight: 500;
}

.profile-action {
    padding: 0 var(--space-lg) var(--space-lg);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-view-profile {
    width: 70%;
    background: var(--accent);
    color: #ffffff;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.2px;
}

.btn-view-profile:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(10, 102, 194, 0.2);
}

.btn-view-profile:active {
    transform: translateY(0);
    box-shadow: none;
}

.btn-view-profile i {
    font-size: 12px;
    transition: transform 0.2s ease;
}

.btn-view-profile:hover i {
    transform: translateX(3px);
}

/* ============================================
   NAVIGATION SECTION IN SIDEBAR
   ============================================ */

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
    margin: 0;
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
    margin: 3px 0;
}

.nav-menu a:hover,
.nav-menu a.active {
    background: var(--accent-light);
    color: var(--accent);
}

/* ============================================
   UPGRADE CARD IN SIDEBAR
   ============================================ */

.upgrade-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: var(--space-xl);
    text-align: center;
    color: var(--text-white);
    box-shadow: var(--shadow-lg);
    margin-bottom: var(--mb-sections);
    margin-top: var(--mb-sections);
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

/* ============================================
   UNIFIED SCROLL WRAPPER
   ============================================ */

/* .unified-scroll-wrapper {
    flex: 1;
    display: flex;
    overflow-y: auto;
    overflow-x: hidden;
    height: calc(100vh - var(--nav-height-desktop));
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    width: 100%;
    scrollbar-width: none;
    -ms-overflow-style: none;
} */

.unified-scroll-wrapper::-webkit-scrollbar {
    display: none;
}

/* ============================================
   CONTENT AREA
   ============================================ */

.content-area {
    flex: 1;
    padding: var(--space-md);
    min-height: 100vh;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}



/* ============================================
   STATS GRID
   ============================================ */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: var(--mb-sections);
    margin-bottom: var(--mb-sections);
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
   CHART CONTAINER
   ============================================ */

.chart-container {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: var(--space-xl);
    margin-bottom: var(--mb-sections);
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
    transition: all var(--transition-base);
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

.time-filter:focus {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
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
    box-shadow: var(--shadow-sm);
}

/* ============================================
   ROW CARDS - FUNNEL & PIE CHART
   ============================================ */

.row-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--mb-sections);
    margin-bottom: var(--mb-sections);
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
    50%, 100% {
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
    background: conic-gradient(var(--accent) 0deg 72deg, #4a90e2 72deg 190.8deg, #7eb8f5 190.8deg 234deg, #b3d9ff 234deg 360deg);
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
    box-shadow: var(--shadow-sm);
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
   DUE SOON SECTION
   ============================================ */

.due-soon-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: var(--space-xl);
    margin-bottom: var(--mb-sections);
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
    transition: all var(--transition-base);
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
    transition: all var(--transition-base);
}

.due-item-action:hover {
    background: var(--accent-light);
}

.due-item-action:focus {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

/* ============================================
   ACTIVITY SECTION
   ============================================ */

.activity-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: var(--space-xl);
    margin-bottom: var(--mb-sections);
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
   RIGHT SIDEBAR
   ============================================ */

.right-sidebar {
    width: var(--right-sidebar-width);
    flex-shrink: 0;
    padding: var(--space-2xl) var(--space-md);
    min-height: 100vh;
    transition: all var(--transition-base);
    background: transparent;
    position: relative;
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
}
    </style>
@endpush




@stack('styles')

@section('content')

    @include('components.navigation.top-nav')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <button class="right-sidebar-toggle" id="rightSidebarToggle" aria-label="Toggle right sidebar">
        <i class="fas fa-sliders-h"></i>
    </button>

    <div class="main-container">
        @include('tenant.manage.sidebar')


        <div class="unified-scroll-wrapper">
            <main class="content-area">


                {{-- Rest of your content --}}
                @yield('main')
            </main>

            <aside class="right-sidebar" id="rightSidebar">
                @yield('right')
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
        .sticky-on-scroll {
            box-sizing: border-box;
        }

        .sticky-on-scroll.is-sticky {
            transform: none;
        }
    </style>

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

    <script>
        'use strict';

        // ============================================
        // PROFESSIONAL DASHBOARD CONTROLLER
        // ============================================

        class DashboardController {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.rightSidebar = document.getElementById('rightSidebar');
                this.sidebarToggle = document.getElementById('sidebarToggle');
                this.rightSidebarToggle = document.getElementById('rightSidebarToggle');
                this.overlay = document.getElementById('sidebarOverlay');
                this.scrollWrapper = document.querySelector('.unified-scroll-wrapper');

                this.init();
            }

            init() {
                this.setupToggles();
                this.setupOverlay();
                this.setupClickOutside();
                this.setupEscape();
                this.setupResize();
                this.preventBodyScroll();
                console.log('✅ Professional Dashboard Initialized');
            }

            setupToggles() {
                // Left Sidebar Toggle
                this.sidebarToggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isActive = this.sidebar?.classList.toggle('active');

                    if (isActive) {
                        this.overlay?.classList.add('active');
                        this.rightSidebar?.classList.remove('active');
                        this.lockBodyScroll();
                    } else {
                        this.overlay?.classList.remove('active');
                        this.unlockBodyScroll();
                    }
                });

                // Right Sidebar Toggle
                this.rightSidebarToggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isActive = this.rightSidebar?.classList.toggle('active');

                    if (isActive) {
                        this.overlay?.classList.add('active');
                        this.sidebar?.classList.remove('active');
                        this.lockBodyScroll();
                    } else {
                        this.overlay?.classList.remove('active');
                        this.unlockBodyScroll();
                    }
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
                        // Check if sidebars should be closed based on screen size
                        if (window.innerWidth <= 992 && this.sidebar?.classList.contains('active')) {
                            this.closeAll();
                        }
                        if (window.innerWidth <= 1200 && this.rightSidebar?.classList.contains('active')) {
                            this.closeAll();
                        }
                    }
                });

                // Prevent clicks inside sidebars from propagating
                this.sidebar?.addEventListener('click', (e) => e.stopPropagation());
                this.rightSidebar?.addEventListener('click', (e) => e.stopPropagation());
            }

            setupEscape() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.closeAll();
                    }
                });
            }

            setupResize() {
                let resizeTimer;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        // Auto-close sidebars when resizing to desktop view
                        if (window.innerWidth > 992) {
                            this.sidebar?.classList.remove('active');
                        }
                        if (window.innerWidth > 1200) {
                            this.rightSidebar?.classList.remove('active');
                        }

                        // Remove overlay if both sidebars are closed
                        if (!this.sidebar?.classList.contains('active') &&
                            !this.rightSidebar?.classList.contains('active')) {
                            this.overlay?.classList.remove('active');
                            this.unlockBodyScroll();
                        }
                    }, 250);
                });
            }

            preventBodyScroll() {
                // Prevent body scroll when sidebar is open on mobile
                if (this.sidebar || this.rightSidebar) {
                    const preventScroll = (e) => {
                        if (window.innerWidth <= 992 && this.sidebar?.classList.contains('active')) {
                            if (!this.sidebar.contains(e.target)) {
                                e.preventDefault();
                            }
                        }
                        if (window.innerWidth <= 1200 && this.rightSidebar?.classList.contains('active')) {
                            if (!this.rightSidebar.contains(e.target)) {
                                e.preventDefault();
                            }
                        }
                    };

                    document.addEventListener('touchmove', preventScroll, {
                        passive: false
                    });
                    document.addEventListener('wheel', preventScroll, {
                        passive: false
                    });
                }
            }

            lockBodyScroll() {
                document.body.style.overflow = 'hidden';
                if (this.scrollWrapper) {
                    this.scrollWrapper.style.overflow = 'hidden';
                }
            }

            unlockBodyScroll() {
                document.body.style.overflow = '';
                if (this.scrollWrapper) {
                    this.scrollWrapper.style.overflow = '';
                }
            }

            closeAll() {
                this.sidebar?.classList.remove('active');
                this.rightSidebar?.classList.remove('active');
                this.overlay?.classList.remove('active');
                this.unlockBodyScroll();
            }
        }

        // ============================================
        // STICKY SCROLL HANDLER (OPTIONAL - FOR UPGRADE CARD)
        // ============================================

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

            setupResize() {
                let timer;
                window.addEventListener('resize', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        this.storePosition();
                        this.check();
                    }, 250);
                });
            }

            check() {
                const scrollTop = this.container.scrollTop;
                const trigger = this.originalPos.top - this.offset;

                if (scrollTop >= trigger && !this.isSticky) {
                    this.makeSticky();
                } else if (scrollTop < trigger && this.isSticky) {
                    this.removeSticky();
                }
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
                this.element.classList.remove('is-sticky');
            }
        }

        // ============================================
        // INITIALIZE EVERYTHING
        // ============================================

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Dashboard Controller
            const dashboard = new DashboardController();

            // Initialize Sticky Scroll (optional for upgrade card)
            const sticky = new StickyScrollHandler('#upgradeCardRight', {
                offset: 100
            });

            console.log('🎨 Professional Dashboard Ready');
        });
    </script>

<style>


    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============ PAGE HEADER ============ */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .page-title {
        font-size: var(--fs-h2);
        font-weight: 600;
        color: var(--text-heading);
        margin-bottom: var(--mb-sections);
        letter-spacing: -0.02em;
        line-height: 1.2;

    }






   
 



    .tab-content {
        display: none;
    }

 



    .help-list {
        margin: 0;
        padding-left: 20px;
    }

    .help-list li {
        font-size: 13px;
        margin-bottom: 8px;
        color: var(--text-body);
        line-height: 1.5;
    }

 



    /* ============ SMOOTH ANIMATIONS ============ */
    @media (prefers-reduced-motion: no-preference) {

        .btn,
        .form-control,
        .tab-btn {
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }
    }















 
    .unified-scroll-wrapper {
        flex: 1;
        display: flex;
        overflow-y: auto;
        overflow-x: hidden;
        height: 100vh;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

     .content-area {
        flex: 1;
        padding: var(--space-md);
        min-height: 100vh;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

 
    
 



  


    .sidebar-toggle,
    .right-sidebar-toggle {
        position: fixed;
        bottom: 20px;
        z-index: 1003;
        /* Above everything including sidebars */
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
        transition: all var(--transition-base);
    }

    .sidebar-toggle:hover,
    .right-sidebar-toggle:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-2xl);
    }

    .sidebar-toggle {
        left: 20px;
    }

    .right-sidebar-toggle {
        right: 20px;
    }

    /* Show toggle buttons at appropriate breakpoints */
    @media (max-width: 1200px) {

    }

    @media (max-width: 992px) {
        .sidebar-toggle {
            display: flex;
        }
    }

 
</style>

@endsection
