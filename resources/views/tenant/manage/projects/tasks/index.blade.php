{{-- resources/views/tenant/manage/projects/tasks/index.blade.php --}}
@extends('tenant.manage.app')

@section('main')

@php
    // Task analytics and counts
    $allTasks        = $tasks;
    $overdueCount    = $allTasks->filter(fn($t) => $t->is_overdue)->count();
    $todayCount      = $allTasks->filter(fn($t) => $t->due_date?->isToday())->count();
    $upcomingCount   = $allTasks->filter(fn($t) => $t->due_date && $t->due_date->isFuture() && !$t->due_date->isToday())->count();
    $urgentCount     = $allTasks->where('priority', 'urgent')->count();
    $highCount       = $allTasks->where('priority', 'high')->count();
    $inProgressCount = $allTasks->where('status', 'in-progress')->count();
    $reviewCount     = $allTasks->where('status', 'review')->count();
    $blockedCount    = $allTasks->where('status', 'blocked')->count();
    $trackingCount   = $allTasks->count();
    
    // Calculate completion percentage
    $completedCount  = $allTasks->where('status', 'completed')->count();
    $completionRate  = $trackingCount > 0 ? round(($completedCount / $trackingCount) * 100) : 0;
@endphp

<div class="pm-workspace">
    <div class="pm-container">

        <!-- PREMIUM HEADER SECTION -->
        <div class="pm-header">
            <div class="pm-header-content">
                <div class="pm-header-main">
                    <div class="pm-icon-badge pm-icon-badge--primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                    </div>
                    @php
                    $titleText = $context === 'delegated' ? 'Assigned Out' : 'My Tasks';
                    $subtitle  = $context === 'delegated'
                        ? "Tasks you created and assigned to others — you're tracking the outcome"
                        : "Tasks assigned to you across all projects";
                    $countLabel = $context === 'delegated' ? 'active' : 'items';
                @endphp
                
                <div class="pm-header-text">
                    <h1 class="pm-title">
                        <span class="pm-title-text">{{ $titleText }}</span>
                        <span class="pm-title-meta">{{ $trackingCount }} {{ $countLabel }}</span>
                    </h1>
                    <p class="pm-subtitle">{{ $subtitle }}</p>
                </div>
                
                </div>

                <div class="pm-header-actions">
                    <button class="pm-btn pm-btn--secondary" onclick="refreshTasks()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0118.8-4.3M22 12.5a10 10 0 01-18.8 4.2"/>
                        </svg>
                        <span>Refresh</span>
                    </button>
                    
                    <button class="pm-btn pm-btn--secondary" onclick="openFilters()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                        </svg>
                        <span>Filter</span>
                    </button>

                    <div class="pm-divider"></div>

                    <button class="pm-btn pm-btn--icon" onclick="openSettings()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 1v6m0 6v6M1 12h6m6 0h6"/>
                            <path d="M4.2 4.2l4.3 4.3m5 5l4.3 4.3M4.2 19.8l4.3-4.3m5-5l4.3-4.3"/>
                        </svg>
                    </button>

                    <button class="pm-btn pm-btn--icon" onclick="openMenu()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="1" fill="currentColor"/>
                            <circle cx="12" cy="5" r="1" fill="currentColor"/>
                            <circle cx="12" cy="19" r="1" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- INSIGHT METRICS BAR -->
            <div class="pm-metrics">
                <div class="pm-metric pm-metric--tracking">
                    <div class="pm-metric-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                            <path d="M22 4L12 14.01l-3-3"/>
                        </svg>
                    </div>
                    <div class="pm-metric-content">
                        <div class="pm-metric-value">{{ $trackingCount }}</div>
                        <div class="pm-metric-label">Tracking</div>
                    </div>
                </div>

                @if ($inProgressCount > 0)
                <div class="pm-metric pm-metric--active">
                    <div class="pm-metric-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div class="pm-metric-content">
                        <div class="pm-metric-value">{{ $inProgressCount }}</div>
                        <div class="pm-metric-label">In Progress</div>
                    </div>
                </div>
                @endif

                @if ($reviewCount > 0)
                <div class="pm-metric pm-metric--review">
                    <div class="pm-metric-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3 8-8"/>
                            <path d="M20 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                    </div>
                    <div class="pm-metric-content">
                        <div class="pm-metric-value">{{ $reviewCount }}</div>
                        <div class="pm-metric-label">In Review</div>
                    </div>
                </div>
                @endif

                @if ($overdueCount > 0)
                <div class="pm-metric pm-metric--overdue">
                    <div class="pm-metric-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4l2 2"/>
                        </svg>
                    </div>
                    <div class="pm-metric-content">
                        <div class="pm-metric-value">{{ $overdueCount }}</div>
                        <div class="pm-metric-label">Overdue</div>
                    </div>
                </div>
                @endif

                @if ($blockedCount > 0)
                <div class="pm-metric pm-metric--blocked">
                    <div class="pm-metric-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                        </svg>
                    </div>
                    <div class="pm-metric-content">
                        <div class="pm-metric-value">{{ $blockedCount }}</div>
                        <div class="pm-metric-label">Blocked</div>
                    </div>
                </div>
                @endif

                <div class="pm-metric-spacer"></div>

                <div class="pm-metric pm-metric--completion">
                    <div class="pm-completion-ring">
                        <svg viewBox="0 0 36 36" class="pm-circular-chart">
                            <path class="pm-circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="pm-circle" stroke-dasharray="{{ $completionRate }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="20.35" class="pm-percentage">{{ $completionRate }}%</text>
                        </svg>
                    </div>
                    <div class="pm-metric-label">Completion</div>
                </div>
            </div>
        </div>

        <!-- ADVANCED FILTER NAVIGATION -->
        <div class="pm-nav">
            <div class="pm-nav-scroll">
                <a href="?filter=all" class="pm-nav-item {{ $activeFilter === 'all' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="4" y1="6" x2="20" y2="6"/>
                            <line x1="4" y1="12" x2="20" y2="12"/>
                            <line x1="4" y1="18" x2="20" y2="18"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">All Tasks</span>
                    @if($trackingCount > 0)
                        <span class="pm-badge pm-badge--neutral">{{ $trackingCount }}</span>
                    @endif
                </a>

                <a href="?filter=review" class="pm-nav-item {{ $activeFilter === 'review' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3 8-8"/>
                            <path d="M20 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">In Review</span>
                    @if($reviewCount > 0)
                        <span class="pm-badge pm-badge--success">{{ $reviewCount }}</span>
                    @endif
                </a>

                <a href="?filter=overdue" class="pm-nav-item {{ $activeFilter === 'overdue' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">Overdue</span>
                    @if($overdueCount > 0)
                        <span class="pm-badge pm-badge--danger">{{ $overdueCount }}</span>
                    @endif
                </a>

                <a href="?filter=today" class="pm-nav-item {{ $activeFilter === 'today' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">Due Today</span>
                    @if($todayCount > 0)
                        <span class="pm-badge pm-badge--warning">{{ $todayCount }}</span>
                    @endif
                </a>

                <a href="?filter=upcoming" class="pm-nav-item {{ $activeFilter === 'upcoming' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">Upcoming</span>
                    @if($upcomingCount > 0)
                        <span class="pm-badge pm-badge--info">{{ $upcomingCount }}</span>
                    @endif
                </a>

                <a href="?filter=in-progress" class="pm-nav-item {{ $activeFilter === 'in-progress' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">In Progress</span>
                    @if($inProgressCount > 0)
                        <span class="pm-badge pm-badge--primary">{{ $inProgressCount }}</span>
                    @endif
                </a>

                <a href="?filter=blocked" class="pm-nav-item {{ $activeFilter === 'blocked' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">Blocked</span>
                    @if($blockedCount > 0)
                        <span class="pm-badge pm-badge--danger">{{ $blockedCount }}</span>
                    @endif
                </a>

                <span class="pm-nav-divider"></span>

                <a href="?filter=urgent" class="pm-nav-item {{ $activeFilter === 'urgent' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">Urgent</span>
                    @if($urgentCount > 0)
                        <span class="pm-badge pm-badge--danger">{{ $urgentCount }}</span>
                    @endif
                </a>

                <a href="?filter=high" class="pm-nav-item {{ $activeFilter === 'high' ? 'pm-nav-item--active' : '' }}">
                    <span class="pm-nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </span>
                    <span class="pm-nav-label">High Priority</span>
                    @if($highCount > 0)
                        <span class="pm-badge pm-badge--warning">{{ $highCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="pm-content">
            @if ($tasks->count() > 0)
                @include('tenant.manage.projects.tasks.tabs.' . $activeFilter, ['tasks' => $tasks])
            @else
                <div class="pm-empty">
                    <div class="pm-empty-illustration">
                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
                            <circle cx="60" cy="60" r="50" stroke="#DFE1E6" stroke-width="2" fill="none" opacity="0.5"/>
                            <circle cx="60" cy="60" r="40" stroke="#DFE1E6" stroke-width="2" fill="none" opacity="0.3"/>
                            <path d="M60 35v25M60 75v5" stroke="#6B778C" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="60" cy="60" r="4" fill="#6B778C"/>
                        </svg>
                    </div>
                    <h3 class="pm-empty-title">No tasks to display</h3>
                    <p class="pm-empty-description">
                        You don't have any tasks in this category yet.<br>
                        Tasks will appear here once they're assigned to you.
                    </p>
                    <button class="pm-btn pm-btn--primary pm-btn--lg" onclick="createTask()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <span>Create Task</span>
                    </button>
                </div>
            @endif
        </div>

    </div>
</div>


<style>
/* ============================================
   DESIGN SYSTEM FOUNDATION
   Professional-grade CSS Variables
   ============================================ */

:root {
    /* Neutral Palette - Atlassian-inspired */
    --pm-n0: #FFFFFF;
    --pm-n10: #FAFBFC;
    --pm-n20: #F4F5F7;
    --pm-n30: #EBECF0;
    --pm-n40: #DFE1E6;
    --pm-n50: #C1C7D0;
    --pm-n60: #B3BAC5;
    --pm-n70: #A5ADBA;
    --pm-n80: #97A0AF;
    --pm-n90: #8993A4;
    --pm-n100: #7A869A;
    --pm-n200: #6B778C;
    --pm-n300: #5E6C84;
    --pm-n400: #505F79;
    --pm-n500: #42526E;
    --pm-n600: #344563;
    --pm-n700: #253858;
    --pm-n800: #172B4D;
    --pm-n900: #091E42;

    /* Brand Colors */
    --pm-primary-50: #DEEBFF;
    --pm-primary-100: #B3D4FF;
    --pm-primary-200: #4C9AFF;
    --pm-primary-300: #2684FF;
    --pm-primary-400: #0065FF;
    --pm-primary-500: #0052CC;
    --pm-primary-600: #0747A6;
    --pm-primary-700: #053D8C;

    /* Success Colors */
    --pm-success-50: #E3FCEF;
    --pm-success-100: #ABF5D1;
    --pm-success-200: #79F2C0;
    --pm-success-300: #57D9A3;
    --pm-success-400: #36B37E;
    --pm-success-500: #00875A;
    --pm-success-600: #006644;

    /* Warning Colors */
    --pm-warning-50: #FFFAE6;
    --pm-warning-100: #FFF0B3;
    --pm-warning-200: #FFE380;
    --pm-warning-300: #FFC400;
    --pm-warning-400: #FFAB00;
    --pm-warning-500: #FF991F;
    --pm-warning-600: #FF8B00;

    /* Danger Colors */
    --pm-danger-50: #FFEBE6;
    --pm-danger-100: #FFBDAD;
    --pm-danger-200: #FF8F73;
    --pm-danger-300: #FF7452;
    --pm-danger-400: #FF5630;
    --pm-danger-500: #DE350B;
    --pm-danger-600: #BF2600;

    /* Info Colors */
    --pm-info-50: #E6FCFF;
    --pm-info-100: #B3F5FF;
    --pm-info-200: #79E2F2;
    --pm-info-300: #00C7E6;
    --pm-info-400: #00B8D9;
    --pm-info-500: #00A3BF;

    /* Typography Scale */
    --pm-font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
    --pm-font-size-xs: 0.6875rem;   /* 11px */
    --pm-font-size-sm: 0.75rem;     /* 12px */
    --pm-font-size-base: 0.875rem;  /* 14px */
    --pm-font-size-md: 1rem;        /* 16px */
    --pm-font-size-lg: 1.125rem;    /* 18px */
    --pm-font-size-xl: 1.25rem;     /* 20px */
    --pm-font-size-2xl: 1.5rem;     /* 24px */
    --pm-font-size-3xl: 1.875rem;   /* 30px */

    --pm-font-weight-normal: 400;
    --pm-font-weight-medium: 500;
    --pm-font-weight-semibold: 600;
    --pm-font-weight-bold: 700;

    --pm-line-height-tight: 1.2;
    --pm-line-height-normal: 1.4;
    --pm-line-height-relaxed: 1.6;

    /* Spacing Scale */
    --pm-space-xs: 0.25rem;   /* 4px */
    --pm-space-sm: 0.5rem;    /* 8px */
    --pm-space-md: 0.75rem;   /* 12px */
    --pm-space-base: 1rem;    /* 16px */
    --pm-space-lg: 1.5rem;    /* 24px */
    --pm-space-xl: 2rem;      /* 32px */
    --pm-space-2xl: 2.5rem;   /* 40px */
    --pm-space-3xl: 3rem;     /* 48px */

    /* Border Radius */
    --pm-radius-sm: 3px;
    --pm-radius-base: 6px;
    --pm-radius-md: 8px;
    --pm-radius-lg: 12px;
    --pm-radius-xl: 16px;
    --pm-radius-full: 9999px;

    /* Shadows - Layered elevation system */
    --pm-shadow-xs: 0 1px 2px 0 rgba(9, 30, 66, 0.08);
    --pm-shadow-sm: 0 1px 3px 0 rgba(9, 30, 66, 0.12), 0 1px 2px 0 rgba(9, 30, 66, 0.06);
    --pm-shadow-base: 0 4px 6px -1px rgba(9, 30, 66, 0.12), 0 2px 4px -1px rgba(9, 30, 66, 0.06);
    --pm-shadow-md: 0 8px 12px -2px rgba(9, 30, 66, 0.16), 0 4px 6px -2px rgba(9, 30, 66, 0.08);
    --pm-shadow-lg: 0 12px 24px -4px rgba(9, 30, 66, 0.20), 0 6px 12px -4px rgba(9, 30, 66, 0.10);
    --pm-shadow-xl: 0 20px 40px -8px rgba(9, 30, 66, 0.24), 0 10px 20px -8px rgba(9, 30, 66, 0.12);

    /* Transitions */
    --pm-transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
    --pm-transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
    --pm-transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);

    /* Z-Index Scale */
    --pm-z-base: 1;
    --pm-z-dropdown: 1000;
    --pm-z-sticky: 1020;
    --pm-z-fixed: 1030;
    --pm-z-modal-backdrop: 1040;
    --pm-z-modal: 1050;
    --pm-z-popover: 1060;
    --pm-z-tooltip: 1070;
}

/* ============================================
   BASE & RESET
   ============================================ */

.pm-workspace {
    min-height: 100vh;
    /* background: var(--pm-n20); */
    font-family: var(--pm-font-family);
    font-size: var(--pm-font-size-base);
    color: var(--pm-n800);
    line-height: var(--pm-line-height-normal);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.pm-container {
    /* max-width: 1400px; */
    margin: 0 auto;
    /* padding: var(--pm-space-lg); */
    display: flex;
    flex-direction: column;
    gap: var(--pm-space-base);
}

/* ============================================
   HEADER SECTION - Premium Design
   ============================================ */

.pm-header {
    background: var(--pm-n0);
    border: 1px solid var(--pm-n40);
    border-radius: var(--pm-radius-md);
    padding: var(--pm-space-lg);
    box-shadow: var(--pm-shadow-sm);
    transition: box-shadow var(--pm-transition-base);
}

.pm-header:hover {
    box-shadow: var(--pm-shadow-base);
}

.pm-header-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--pm-space-lg);
    margin-bottom: var(--pm-space-lg);
    flex-wrap: wrap;
}

.pm-header-main {
    display: flex;
    align-items: flex-start;
    gap: var(--pm-space-base);
    flex: 1;
    min-width: 0;
}

.pm-icon-badge {
    width: 48px;
    height: 48px;
    border-radius: var(--pm-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    transition: all var(--pm-transition-base);
}

.pm-icon-badge--primary {
    background: linear-gradient(135deg, var(--pm-primary-500) 0%, var(--pm-primary-600) 100%);
    color: var(--pm-n0);
    box-shadow: 0 4px 12px rgba(0, 82, 204, 0.25);
}

.pm-icon-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 82, 204, 0.35);
}

.pm-header-text {
    flex: 1;
    min-width: 0;
}

.pm-title {
    margin: 0 0 var(--pm-space-xs) 0;
    display: flex;
    align-items: center;
    gap: var(--pm-space-md);
    flex-wrap: wrap;
}

.pm-title-text {
    font-size: var(--pm-font-size-2xl);
    font-weight: var(--pm-font-weight-bold);
    color: var(--pm-n800);
    line-height: var(--pm-line-height-tight);
}

.pm-title-meta {
    font-size: var(--pm-font-size-sm);
    font-weight: var(--pm-font-weight-medium);
    color: var(--pm-n200);
    background: var(--pm-n30);
    padding: var(--pm-space-xs) var(--pm-space-sm);
    border-radius: var(--pm-radius-base);
    border: 1px solid var(--pm-n40);
}

.pm-subtitle {
    margin: 0;
    font-size: var(--pm-font-size-base);
    color: var(--pm-n300);
    line-height: var(--pm-line-height-relaxed);
}

.pm-header-actions {
    display: flex;
    align-items: center;
    gap: var(--pm-space-sm);
    flex-wrap: wrap;
}

/* ============================================
   BUTTONS - Professional System
   ============================================ */

.pm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--pm-space-sm);
    padding: var(--pm-space-sm) var(--pm-space-base);
    border-radius: var(--pm-radius-base);
    font-size: var(--pm-font-size-base);
    font-weight: var(--pm-font-weight-medium);
    line-height: var(--pm-line-height-tight);
    cursor: pointer;
    transition: all var(--pm-transition-fast);
    border: 1px solid transparent;
    white-space: nowrap;
    user-select: none;
    position: relative;
    overflow: hidden;
}

.pm-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width var(--pm-transition-base), height var(--pm-transition-base);
}

.pm-btn:active::before {
    width: 300px;
    height: 300px;
}

.pm-btn--primary {
    background: linear-gradient(135deg, var(--pm-primary-500) 0%, var(--pm-primary-600) 100%);
    color: var(--pm-n0);
    border-color: var(--pm-primary-600);
    box-shadow: var(--pm-shadow-xs);
}

.pm-btn--primary:hover {
    background: linear-gradient(135deg, var(--pm-primary-600) 0%, var(--pm-primary-700) 100%);
    box-shadow: var(--pm-shadow-base);
    transform: translateY(-1px);
}

.pm-btn--primary:active {
    transform: translateY(0);
}

.pm-btn--secondary {
    background: var(--pm-n0);
    color: var(--pm-n500);
    border-color: var(--pm-n40);
    box-shadow: var(--pm-shadow-xs);
}

.pm-btn--secondary:hover {
    background: var(--pm-n10);
    border-color: var(--pm-n60);
    color: var(--pm-n700);
    box-shadow: var(--pm-shadow-sm);
}

.pm-btn--icon {
    width: 36px;
    height: 36px;
    padding: 0;
    background: var(--pm-n0);
    color: var(--pm-n300);
    border-color: var(--pm-n40);
}

.pm-btn--icon:hover {
    background: var(--pm-n10);
    color: var(--pm-primary-500);
    border-color: var(--pm-primary-200);
}

.pm-btn--lg {
    padding: var(--pm-space-md) var(--pm-space-lg);
    font-size: var(--pm-font-size-md);
}

.pm-divider {
    width: 1px;
    height: 24px;
    background: var(--pm-n40);
}

/* ============================================
   METRICS BAR - Data Visualization
   ============================================ */

.pm-metrics {
    display: flex;
    align-items: center;
    gap: var(--pm-space-base);
    padding-top: var(--pm-space-lg);
    border-top: 1px solid var(--pm-n30);
    flex-wrap: wrap;
}

.pm-metric {
    display: flex;
    align-items: center;
    gap: var(--pm-space-md);
    padding: var(--pm-space-md);
    border-radius: var(--pm-radius-base);
    background: var(--pm-n10);
    border: 1px solid var(--pm-n30);
    transition: all var(--pm-transition-base);
}

.pm-metric:hover {
    background: var(--pm-n0);
    border-color: var(--pm-n50);
    box-shadow: var(--pm-shadow-xs);
    transform: translateY(-2px);
}

.pm-metric-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--pm-radius-base);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.pm-metric--tracking .pm-metric-icon {
    background: var(--pm-primary-50);
    color: var(--pm-primary-500);
}

.pm-metric--active .pm-metric-icon {
    background: var(--pm-info-50);
    color: var(--pm-info-500);
}

.pm-metric--review .pm-metric-icon {
    background: var(--pm-success-50);
    color: var(--pm-success-500);
}

.pm-metric--overdue .pm-metric-icon {
    background: var(--pm-danger-50);
    color: var(--pm-danger-500);
}

.pm-metric--blocked .pm-metric-icon {
    background: var(--pm-n50);
    color: var(--pm-n500);
}

.pm-metric-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.pm-metric-value {
    font-size: var(--pm-font-size-xl);
    font-weight: var(--pm-font-weight-bold);
    color: var(--pm-n800);
    line-height: 1;
}

.pm-metric-label {
    font-size: var(--pm-font-size-sm);
    font-weight: var(--pm-font-weight-medium);
    color: var(--pm-n300);
    line-height: 1;
}

.pm-metric-spacer {
    flex: 1;
    min-width: var(--pm-space-base);
}

.pm-metric--completion {
    flex-direction: column;
    align-items: center;
    gap: var(--pm-space-sm);
    padding: var(--pm-space-md);
}

.pm-completion-ring {
    width: 48px;
    height: 48px;
}

.pm-circular-chart {
    display: block;
    max-width: 100%;
    max-height: 100%;
}

.pm-circle-bg {
    fill: none;
    stroke: var(--pm-n30);
    stroke-width: 3;
}

.pm-circle {
    fill: none;
    stroke: var(--pm-success-500);
    stroke-width: 3;
    stroke-linecap: round;
    animation: pm-progress 1s ease-out forwards;
}

@keyframes pm-progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

.pm-percentage {
    fill: var(--pm-n800);
    font-family: var(--pm-font-family);
    font-size: 0.5rem;
    font-weight: var(--pm-font-weight-bold);
    text-anchor: middle;
}

/* ============================================
   NAVIGATION TABS - Premium Filter System
   ============================================ */

.pm-nav {
    background: var(--pm-n0);
    border: 1px solid var(--pm-n40);
    border-radius: var(--pm-radius-md);
    box-shadow: var(--pm-shadow-sm);
    overflow: hidden;
}

.pm-nav-scroll {
    display: flex;
    align-items: center;
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: thin;
    scrollbar-color: var(--pm-n60) transparent;
    padding: var(--pm-space-xs) var(--pm-space-sm);
    gap: var(--pm-space-xs);
}

.pm-nav-scroll::-webkit-scrollbar {
    height: 4px;
}

.pm-nav-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.pm-nav-scroll::-webkit-scrollbar-thumb {
    background: var(--pm-n60);
    border-radius: var(--pm-radius-full);
}

.pm-nav-scroll::-webkit-scrollbar-thumb:hover {
    background: var(--pm-n100);
}

.pm-nav-item {
    display: flex;
    align-items: center;
    gap: var(--pm-space-sm);
    padding: var(--pm-space-sm) var(--pm-space-md);
    border-radius: var(--pm-radius-base);
    font-size: var(--pm-font-size-base);
    font-weight: var(--pm-font-weight-medium);
    color: var(--pm-n400);
    text-decoration: none;
    white-space: nowrap;
    transition: all var(--pm-transition-fast);
    position: relative;
    border: 1px solid transparent;
}

.pm-nav-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--pm-primary-500);
    transform: scaleX(0);
    transition: transform var(--pm-transition-base);
    border-radius: var(--pm-radius-full);
}

.pm-nav-item:hover {
    background: var(--pm-n10);
    color: var(--pm-n600);
}

.pm-nav-item--active {
    /* background: var(--pm-primary-50); */
    color: var(--pm-primary-600);
    font-weight: var(--pm-font-weight-semibold);
    /* border-color: var(--pm-primary-200); */
}

.pm-nav-item--active::before {
    transform: scaleX(1);
}

.pm-nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: currentColor;
    opacity: 0.8;
}

.pm-nav-item--active .pm-nav-icon {
    opacity: 1;
}

.pm-nav-label {
    font-size: var(--pm-font-size-base);
}

.pm-nav-divider {
    width: 1px;
    height: 24px;
    background: var(--pm-n40);
    margin: 0 var(--pm-space-xs);
}

/* ============================================
   BADGES - Status Indicators
   ============================================ */

.pm-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 var(--pm-space-sm);
    border-radius: var(--pm-radius-full);
    font-size: var(--pm-font-size-xs);
    font-weight: var(--pm-font-weight-bold);
    line-height: 1;
    border: 1px solid transparent;
}

.pm-badge--neutral {
    background: var(--pm-n30);
    color: var(--pm-n600);
    border-color: var(--pm-n40);
}

.pm-badge--primary {
    background: var(--pm-primary-50);
    color: var(--pm-primary-600);
    border-color: var(--pm-primary-100);
}

.pm-badge--success {
    background: var(--pm-success-50);
    color: var(--pm-success-600);
    border-color: var(--pm-success-100);
}

.pm-badge--warning {
    background: var(--pm-warning-50);
    color: var(--pm-warning-600);
    border-color: var(--pm-warning-100);
}

.pm-badge--danger {
    background: var(--pm-danger-50);
    color: var(--pm-danger-600);
    border-color: var(--pm-danger-100);
}

.pm-badge--info {
    background: var(--pm-info-50);
    color: var(--pm-info-500);
    border-color: var(--pm-info-100);
}

/* ============================================
   CONTENT AREA
   ============================================ */

.pm-content {
    background: var(--pm-n0);
    border: 1px solid var(--pm-n40);
    border-radius: var(--pm-radius-md);
    min-height: 500px;
    padding: var(--pm-space-lg);
    box-shadow: var(--pm-shadow-sm);
}

/* ============================================
   EMPTY STATE - Elegant Design
   ============================================ */

.pm-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--pm-space-3xl) var(--pm-space-lg);
    text-align: center;
    min-height: 400px;
}

.pm-empty-illustration {
    margin-bottom: var(--pm-space-lg);
    opacity: 0;
    animation: pm-fade-in 0.6s ease-out 0.2s forwards;
}

@keyframes pm-fade-in {
    to {
        opacity: 1;
    }
}

.pm-empty-title {
    margin: 0 0 var(--pm-space-sm) 0;
    font-size: var(--pm-font-size-xl);
    font-weight: var(--pm-font-weight-bold);
    color: var(--pm-n700);
    opacity: 0;
    animation: pm-fade-in 0.6s ease-out 0.4s forwards;
}

.pm-empty-description {
    margin: 0 0 var(--pm-space-xl) 0;
    font-size: var(--pm-font-size-base);
    color: var(--pm-n300);
    line-height: var(--pm-line-height-relaxed);
    max-width: 420px;
    opacity: 0;
    animation: pm-fade-in 0.6s ease-out 0.6s forwards;
}

.pm-empty .pm-btn {
    opacity: 0;
    animation: pm-fade-in 0.6s ease-out 0.8s forwards;
}

/* ============================================
   RESPONSIVE DESIGN - Mobile First
   ============================================ */

@media (max-width: 1024px) {
    .pm-container {
        padding: var(--pm-space-base);
    }

    .pm-metrics {
        overflow-x: auto;
        scrollbar-width: thin;
        padding-bottom: var(--pm-space-sm);
    }
}

@media (max-width: 768px) {
    .pm-container {
        padding: var(--pm-space-md);
        gap: var(--pm-space-md);
    }

    .pm-header {
        padding: var(--pm-space-base);
    }

    .pm-header-content {
        flex-direction: column;
        align-items: stretch;
        gap: var(--pm-space-base);
    }

    .pm-header-main {
        gap: var(--pm-space-md);
    }

    .pm-icon-badge {
        width: 40px;
        height: 40px;
    }

    .pm-title-text {
        font-size: var(--pm-font-size-xl);
    }

    .pm-header-actions {
        justify-content: flex-start;
    }

    .pm-metrics {
        gap: var(--pm-space-sm);
        padding-top: var(--pm-space-base);
    }

    .pm-metric {
        padding: var(--pm-space-sm);
    }

    .pm-metric-value {
        font-size: var(--pm-font-size-lg);
    }

    .pm-nav-scroll {
        padding: var(--pm-space-xs);
    }

    .pm-nav-item {
        padding: var(--pm-space-sm);
        font-size: var(--pm-font-size-sm);
    }

    .pm-content {
        padding: var(--pm-space-base);
    }

    .pm-empty {
        padding: var(--pm-space-xl) var(--pm-space-base);
        min-height: 300px;
    }

    .pm-empty-illustration svg {
        width: 80px;
        height: 80px;
    }
}

@media (max-width: 480px) {
    .pm-title {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--pm-space-sm);
    }

    .pm-title-text {
        font-size: var(--pm-font-size-lg);
    }

    .pm-btn {
        width: 100%;
        justify-content: center;
    }

    .pm-btn--icon {
        width: 36px;
    }

    .pm-header-actions {
        flex-direction: column;
        width: 100%;
    }

    .pm-divider {
        display: none;
    }
}

/* ============================================
   ACCESSIBILITY ENHANCEMENTS
   ============================================ */

.pm-btn:focus-visible,
.pm-nav-item:focus-visible {
    outline: 2px solid var(--pm-primary-500);
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* ============================================
   PRINT STYLES
   ============================================ */

@media print {
    .pm-header-actions,
    .pm-nav {
        display: none;
    }

    .pm-workspace {
        background: white;
    }

    .pm-header,
    .pm-content {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}
</style>

<script>
// Professional JavaScript Handlers
(function() {
    'use strict';

    // Task Management Functions
    window.refreshTasks = function() {
        console.log('Refreshing tasks...');
        // Add your refresh logic here
        window.location.reload();
    };

    window.openFilters = function() {
        console.log('Opening filter panel...');
        // Implement filter modal/panel
    };

    window.openSettings = function() {
        console.log('Opening settings...');
        // Implement settings panel
    };

    window.openMenu = function() {
        console.log('Opening menu...');
        // Implement dropdown menu
    };

    window.createTask = function() {
        console.log('Creating new task...');
        // Implement task creation modal
    };

    window.openTaskDetails = function(taskId) {
        console.log('Opening task details:', taskId);
        // Implement task details view
    };

    window.openTaskMenu = function(taskId) {
        console.log('Opening task menu:', taskId);
        // Implement task context menu
    };

    // Smooth scroll for navigation
    document.addEventListener('DOMContentLoaded', function() {
        const navScroll = document.querySelector('.pm-nav-scroll');
        if (navScroll) {
            const activeItem = navScroll.querySelector('.pm-nav-item--active');
            if (activeItem) {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + R: Refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshTasks();
        }
        
        // Ctrl/Cmd + F: Open filters
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            openFilters();
        }
        
        // Ctrl/Cmd + N: New task
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            createTask();
        }
    });

})();
</script>

@endsection