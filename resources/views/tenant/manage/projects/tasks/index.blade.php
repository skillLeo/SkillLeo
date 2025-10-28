{{-- resources/views/tenant/manage/projects/tasks/index.blade.php --}}
@extends('tenant.manage.app')

@section('main')
    @php
        $allTasks = $tasks;

        $overdueCount = $allTasks->filter(fn($t) => $t->is_overdue)->count();
        $todayCount = $allTasks->filter(fn($t) => $t->due_date?->isToday())->count();
        $upcomingCount = $allTasks
            ->filter(fn($t) => $t->due_date && $t->due_date->isFuture() && !$t->due_date->isToday())
            ->count();
        $urgentCount = $allTasks->where('priority', 'urgent')->count();
        $highCount = $allTasks->where('priority', 'high')->count();
        $inProgressCount = $allTasks->where('status', 'in-progress')->count();
        $reviewCount = $allTasks->where('status', 'review')->count();
        $blockedCount = $allTasks->where('status', 'blocked')->count();

        $trackingCount = $allTasks->count(); // total tasks visible

        // -------- NEW COMPLETION MATH --------
        // Each task counts as 1 unit.
        // Each subtask counts as 1 unit.
        // Completed units:
        //   - task status == 'done' -> +1
        //   - each subtask completed == true -> +1
        $totalUnits = 0;
        $completedUnits = 0;

        foreach ($allTasks as $t) {
            // task itself
            $totalUnits += 1;
            if ($t->status === 'done') {
                $completedUnits += 1;
            }

            // subtasks
            $subtasksTotal = $t->subtasks->count();
            $subtasksCompleted = $t->subtasks->where('completed', true)->count();

            $totalUnits += $subtasksTotal;
            $completedUnits += $subtasksCompleted;
        }

        $completionRate = $totalUnits > 0 ? round(($completedUnits / $totalUnits) * 100) : 0;
    @endphp

    <div class="pm-workspace">
        <div class="pm-container">

            <!-- PREMIUM HEADER SECTION -->
            <div class="pm-header">
                <div class="pm-header-content">
                    <div class="pm-header-main">
                        <div class="pm-icon-badge pm-icon-badge--primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 11l3 3L22 4" />
                                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                            </svg>
                        </div>
                        @php
                            $titleText = $context === 'delegated' ? 'Assigned Out' : 'My Tasks';
                            $subtitle =
                                $context === 'delegated'
                                    ? "Tasks you created and assigned to others — you're tracking the outcome"
                                    : 'Tasks assigned to you across all projects';
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
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0118.8-4.3M22 12.5a10 10 0 01-18.8 4.2" />
                            </svg>
                            <span>Refresh</span>
                        </button>

                        <button class="pm-btn pm-btn--secondary" onclick="openFilters()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        <div class="pm-divider"></div>

                        <button class="pm-btn pm-btn--icon" onclick="openSettings()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="3" />
                                <path d="M12 1v6m0 6v6M1 12h6m6 0h6" />
                                <path d="M4.2 4.2l4.3 4.3m5 5l4.3 4.3M4.2 19.8l4.3-4.3m5-5l4.3-4.3" />
                            </svg>
                        </button>

                        <button class="pm-btn pm-btn--icon" onclick="openMenu()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="1" fill="currentColor" />
                                <circle cx="12" cy="5" r="1" fill="currentColor" />
                                <circle cx="12" cy="19" r="1" fill="currentColor" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- INSIGHT METRICS BAR -->
                <div class="pm-metrics">
                    <div class="pm-metric pm-metric--tracking">
                        <div class="pm-metric-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                                <path d="M22 4L12 14.01l-3-3" />
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M9 11l3 3 8-8" />
                                    <path d="M20 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 8v4l2 2" />
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                </svg>
                            </div>
                            <div class="pm-metric-content">
                                <div class="pm-metric-value">{{ $blockedCount }}</div>
                                <div class="pm-metric-label">Blocked</div>
                            </div>
                        </div>
                    @endif

                    <div class="pm-metric-spacer"></div>
                    <div class="pm-metric pm-metric--completion" id="pm-completion-wrapper"
                        data-total-units="{{ $totalUnits }}" data-completed-units="{{ $completedUnits }}">
                        <div class="pm-completion-ring">
                            <svg viewBox="0 0 36 36" class="pm-circular-chart">
                                <path class="pm-circle-bg"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />

                                <path class="pm-circle" stroke-dasharray="{{ $completionRate }}, 100"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />

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
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="4" y1="6" x2="20" y2="6" />
                                <line x1="4" y1="12" x2="20" y2="12" />
                                <line x1="4" y1="18" x2="20" y2="18" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">All Tasks</span>
                        @if ($trackingCount > 0)
                            <span class="pm-badge pm-badge--neutral">{{ $trackingCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=review"
                        class="pm-nav-item {{ $activeFilter === 'review' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 11l3 3 8-8" />
                                <path d="M20 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">In Review</span>
                        @if ($reviewCount > 0)
                            <span class="pm-badge pm-badge--success">{{ $reviewCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=overdue"
                        class="pm-nav-item {{ $activeFilter === 'overdue' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">Overdue</span>
                        @if ($overdueCount > 0)
                            <span class="pm-badge pm-badge--danger">{{ $overdueCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=today"
                        class="pm-nav-item {{ $activeFilter === 'today' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">Due Today</span>
                        @if ($todayCount > 0)
                            <span class="pm-badge pm-badge--warning">{{ $todayCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=upcoming"
                        class="pm-nav-item {{ $activeFilter === 'upcoming' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">Upcoming</span>
                        @if ($upcomingCount > 0)
                            <span class="pm-badge pm-badge--info">{{ $upcomingCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=in-progress"
                        class="pm-nav-item {{ $activeFilter === 'in-progress' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">In Progress</span>
                        @if ($inProgressCount > 0)
                            <span class="pm-badge pm-badge--primary">{{ $inProgressCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=blocked"
                        class="pm-nav-item {{ $activeFilter === 'blocked' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">Blocked</span>
                        @if ($blockedCount > 0)
                            <span class="pm-badge pm-badge--danger">{{ $blockedCount }}</span>
                        @endif
                    </a>

                    <span class="pm-nav-divider"></span>

                    <a href="?filter=urgent"
                        class="pm-nav-item {{ $activeFilter === 'urgent' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">Urgent</span>
                        @if ($urgentCount > 0)
                            <span class="pm-badge pm-badge--danger">{{ $urgentCount }}</span>
                        @endif
                    </a>

                    <a href="?filter=high"
                        class="pm-nav-item {{ $activeFilter === 'high' ? 'pm-nav-item--active' : '' }}">
                        <span class="pm-nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </span>
                        <span class="pm-nav-label">High Priority</span>
                        @if ($highCount > 0)
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
                                <circle cx="60" cy="60" r="50" stroke="#DFE1E6" stroke-width="2"
                                    fill="none" opacity="0.5" />
                                <circle cx="60" cy="60" r="40" stroke="#DFE1E6" stroke-width="2"
                                    fill="none" opacity="0.3" />
                                <path d="M60 35v25M60 75v5" stroke="#6B778C" stroke-width="3" stroke-linecap="round" />
                                <circle cx="60" cy="60" r="4" fill="#6B778C" />
                            </svg>
                        </div>
                        <h3 class="pm-empty-title">No tasks to display</h3>
                        <p class="pm-empty-description">
                            You don't have any tasks in this category yet.<br>
                            Tasks will appear here once they're assigned to you.
                        </p>
                        <button class="pm-btn pm-btn--primary pm-btn--lg" onclick="createTask()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            <span>Create Task</span>
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @include('tenant.manage.projects.tasks.components.status-modal')
    {{-- @include('tenant.manage.projects.tasks.components.task-action-modals', ['username' => $username ?? request()->segment(1)]) --}}


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
            --pm-font-size-xs: 0.6875rem;
            /* 11px */
            --pm-font-size-sm: 0.75rem;
            /* 12px */
            --pm-font-size-base: 0.875rem;
            /* 14px */
            --pm-font-size-md: 1rem;
            /* 16px */
            --pm-font-size-lg: 1.125rem;
            /* 18px */
            --pm-font-size-xl: 1.25rem;
            /* 20px */
            --pm-font-size-2xl: 1.5rem;
            /* 24px */
            --pm-font-size-3xl: 1.875rem;
            /* 30px */

            --pm-font-weight-normal: 400;
            --pm-font-weight-medium: 500;
            --pm-font-weight-semibold: 600;
            --pm-font-weight-bold: 700;

            --pm-line-height-tight: 1.2;
            --pm-line-height-normal: 1.4;
            --pm-line-height-relaxed: 1.6;

            /* Spacing Scale */
            --pm-space-xs: 0.25rem;
            /* 4px */
            --pm-space-sm: 0.5rem;
            /* 8px */
            --pm-space-md: 0.75rem;
            /* 12px */
            --pm-space-base: 1rem;
            /* 16px */
            --pm-space-lg: 1.5rem;
            /* 24px */
            --pm-space-xl: 2rem;
            /* 32px */
            --pm-space-2xl: 2.5rem;
            /* 40px */
            --pm-space-3xl: 3rem;
            /* 48px */

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
        (function() {
    'use strict';

    // =========================================
    //  GLOBAL CONFIG
    //  make sure Blade sets this value:
    //  window.TENANT_USERNAME = "{{ $username ?? request()->segment(1) }}";
    // =========================================
    if (!window.TENANT_USERNAME) {
        window.TENANT_USERNAME = "{{ $username ?? request()->segment(1) }}";
    }

    // keep selected attachments between drag/drop and submit
    let selectedFiles = [];

    // =========================================
    // STATUS STYLE MAP (match backend statusMeta)
    // =========================================
    const STATUS_STYLE = {
        'todo': {
            label: 'To Do',
            bg: '#F4F5F7',
            color: '#6B778C'
        },
        'in-progress': {
            label: 'In Progress',
            bg: '#DEEBFF',
            color: '#0052CC'
        },
        'review': {
            label: 'Review',
            bg: '#FFFAE6',
            color: '#FF991F'
        },
        'done': {
            label: 'Done',
            bg: '#E3FCEF',
            color: '#00875A'
        },
        'blocked': {
            label: 'Blocked',
            bg: '#FFEBE6',
            color: '#DE350B'
        },
        'postponed': {
            label: 'Postponed',
            bg: '#EAE6FF',
            color: '#8777D9'
        },
        'cancelled': {
            label: 'Cancelled',
            bg: '#F4F5F7',
            color: '#6B778C'
        },
    };

    // =========================================
    // TOAST / NOTIFICATION
    // =========================================
    function showToast(message, type = 'info') {
        let box = document.getElementById('app-toast-container');
        if (!box) {
            box = document.createElement('div');
            box.id = 'app-toast-container';
            box.style.position = 'fixed';
            box.style.top = '16px';
            box.style.right = '16px';
            box.style.zIndex = '9999';
            box.style.display = 'flex';
            box.style.flexDirection = 'column';
            box.style.gap = '8px';
            document.body.appendChild(box);
        }

        const toast = document.createElement('div');
        toast.style.minWidth = '220px';
        toast.style.maxWidth = '320px';
        toast.style.padding = '12px 14px';
        toast.style.borderRadius = '6px';
        toast.style.boxShadow = '0 10px 24px rgba(0,0,0,0.12), 0 2px 4px rgba(0,0,0,0.08)';
        toast.style.display = 'flex';
        toast.style.alignItems = 'flex-start';
        toast.style.gap = '8px';
        toast.style.fontSize = '13px';
        toast.style.lineHeight = '1.4';
        toast.style.border = '1px solid transparent';
        toast.style.fontWeight = '500';
        toast.style.cursor = 'default';

        let iconSvg = '';
        let colorBg = '';
        let colorText = '';
        let colorBorder = '';

        if (type === 'success') {
            colorBg = '#E3FCEF';
            colorText = '#006644';
            colorBorder = '#36B37E33';
            iconSvg =
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#006644" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>';
        } else if (type === 'error') {
            colorBg = '#FFEBE6';
            colorText = '#BF2600';
            colorBorder = '#FF563033';
            iconSvg =
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#BF2600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        } else {
            colorBg = '#DEEBFF';
            colorText = '#0747A6';
            colorBorder = '#0052CC33';
            iconSvg =
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0747A6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        }

        toast.style.backgroundColor = colorBg;
        toast.style.color = colorText;
        toast.style.borderColor = colorBorder;

        toast.innerHTML = `
                <div style="flex-shrink:0;">${iconSvg}</div>
                <div style="flex:1;">${message}</div>
                <button style="
                    background:transparent;
                    border:none;
                    color:${colorText};
                    cursor:pointer;
                    line-height:1;
                    padding:0;
                    font-size:14px;
                    font-weight:600;
                " aria-label="Close">&times;</button>
            `;

        const closeBtn = toast.querySelector('button');
        closeBtn.addEventListener('click', () => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        });

        setTimeout(() => {
            toast.style.transition = 'opacity 200ms ease, transform 200ms ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-4px)';
            setTimeout(() => {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 220);
        }, 3000);

        box.appendChild(toast);
    }

    // =========================================
    // COMPLETION RING RECALC
    // Each task = 1 unit
    // Each subtask = 1 unit
    // done task/subtask = completed unit
    // =========================================
    function recalcGlobalCompletion() {
        const wrapper = document.getElementById('pm-completion-wrapper');
        if (!wrapper) return;

        const taskCards = document.querySelectorAll('.pro-task-card');

        let totalUnits = 0;
        let completedUnits = 0;

        taskCards.forEach(card => {
            // 1 unit for the task itself
            totalUnits += 1;
            const taskStatus = card.getAttribute('data-task-status');
            if (taskStatus === 'done') {
                completedUnits += 1;
            }

            // each subtask is also 1 unit
            card.querySelectorAll('.pro-subtask-item').forEach(row => {
                totalUnits += 1;
                const cb = row.querySelector('.pro-subtask-checkbox');
                if (cb && cb.checked) {
                    completedUnits += 1;
                }
            });
        });

        const pct = totalUnits > 0 ?
            Math.round((completedUnits / totalUnits) * 100) :
            0;

        wrapper.setAttribute('data-total-units', totalUnits);
        wrapper.setAttribute('data-completed-units', completedUnits);

        // update ring dash
        const circle = wrapper.querySelector('.pm-circle');
        if (circle) {
            circle.setAttribute('stroke-dasharray', pct + ', 100');
        }

        // update % text
        const pctText = wrapper.querySelector('.pm-percentage');
        if (pctText) {
            pctText.textContent = pct + '%';
        }
    }

    // =========================================
    // EXPAND/COLLAPSE SUBTASKS
    // called by onclick="toggleSubtasksExpand(taskId)"
    // =========================================
    function toggleSubtasksExpand(taskId) {
        const list = document.getElementById(`subtasks-list-${taskId}`);
        const btn = document.getElementById(`expand-btn-${taskId}`);
        if (!list || !btn) return;

        const hidden = (list.style.display === 'none' || list.style.display === '');
        list.style.display = hidden ? 'block' : 'none';
        btn.classList.toggle('is-expanded', hidden);
    }

    // =========================================
    // CLICK ON SUBTASK ROW
    // called by onclick="subtaskRowClick(event, taskId, subtaskId, totalSubtasks)"
    // =========================================
    function subtaskRowClick(e, taskId, subtaskId, totalSubtasks) {
        const cb = document.getElementById(`subtask-cb-${subtaskId}`);
        if (!cb) return;

        const willBeChecked = !cb.checked;

        // figure out if this click will complete ALL subtasks
        const checkboxes = document.querySelectorAll(
            `#subtasks-list-${taskId} .pro-subtask-checkbox`
        );

        let futureCompleted = 0;
        checkboxes.forEach(box => {
            if (box.id === `subtask-cb-${subtaskId}`) {
                if (willBeChecked) futureCompleted++;
            } else if (box.checked) {
                futureCompleted++;
            }
        });

        const isLastSubtask = (futureCompleted === totalSubtasks);

        // if this click finishes the final remaining subtask,
        // open modal to finalize whole task (requires remark)
        if (isLastSubtask && willBeChecked) {
            e.preventDefault();
            openStatusModal(taskId, 'done', subtaskId);
            return;
        }

        // normal flow: optimistic toggle
        cb.checked = willBeChecked;
        toggleSubtask(taskId, subtaskId, willBeChecked);
    }

    // =========================================
    // UPDATE SUBTASK + BADGE + BAR AFTER /toggle
    // THIS VERSION IS UPDATED TO HANDLE ALL STATES
    // =========================================
    function updateSubtaskUIAfterToggle(taskId, data) {
        // data from backend:
        // {
        //   success: true,
        //   subtask: { id, completed: true/false, ... },
        //   completed_subtasks_count,
        //   subtasks_count,
        //   task_status,
        //   task_status_label,
        //   task_status_bg,
        //   task_status_color
        // }

        const card = document.querySelector(`.pro-task-card[data-task-id="${taskId}"]`);
        if (!card) {
            recalcGlobalCompletion();
            return;
        }

        // 1. sync that specific subtask row (checkbox + strike-through)
        if (data.subtask && data.subtask.id) {
            const row = card.querySelector(`.pro-subtask-item[data-subtask-id="${data.subtask.id}"]`);
            if (row) {
                const cb = row.querySelector('.pro-subtask-checkbox');
                if (cb) {
                    cb.checked = !!data.subtask.completed;
                }

                if (data.subtask.completed) {
                    row.classList.add('is-completed');
                } else {
                    row.classList.remove('is-completed');
                }
            }
        }

        // 2. update "X/Y subtasks"
        const countEl = card.querySelector('.pro-subtasks-count');
        if (countEl) {
            countEl.textContent = `${data.completed_subtasks_count}/${data.subtasks_count}`;
        }

        // 3. update mini progress bar width + color
        const bar = card.querySelector('.pro-progress-mini-bar');
        if (bar) {
            const pct = data.subtasks_count > 0
                ? Math.round((data.completed_subtasks_count / data.subtasks_count) * 100)
                : 0;

            bar.style.width = pct + '%';

            const styleForStatus = STATUS_STYLE[data.task_status] || {};
            const barColor = data.task_status_color || styleForStatus.color || '#0052CC';
            bar.style.background = barColor;
        }

        // 4. update the status badge
        if (data.task_status) {
            // update the data-task-status attr so global % calc stays correct
            card.setAttribute('data-task-status', data.task_status);

            const badge = card.querySelector('.pro-status-badge');
            if (badge) {
                const fallbackStyle = STATUS_STYLE[data.task_status] || {};

                const label = data.task_status_label || fallbackStyle.label || data.task_status;
                const bg    = data.task_status_bg    || fallbackStyle.bg    || '#F4F5F7';
                const col   = data.task_status_color || fallbackStyle.color || '#6B778C';

                badge.textContent       = label;
                badge.style.background  = bg;
                badge.style.color       = col;
                badge.style.borderColor = col;
            }

            // if task flipped to "done", visually strike all subtasks, 100% bar, toast, etc.
            if (data.task_status === 'done') {
                card.querySelectorAll('.pro-subtask-item').forEach(r => {
                    r.classList.add('is-completed');
                    const cb = r.querySelector('.pro-subtask-checkbox');
                    if (cb) cb.checked = true;
                });

                const doneBar = card.querySelector('.pro-progress-mini-bar');
                if (doneBar) {
                    doneBar.style.width = '100%';
                }

                showToast('Task marked done ✅', 'success');
            } else if (data.task_status === 'blocked') {
                showToast('Task marked blocked', 'error');
            } else if (data.task_status === 'postponed') {
                showToast('Task postponed', 'info');
            } else if (data.task_status === 'cancelled') {
                showToast('Task cancelled', 'info');
            } else if (data.task_status === 'in-progress') {
                showToast('Task set In Progress', 'success');
            } else if (data.task_status === 'todo') {
                showToast('Task reopened', 'info');
            } else {
                showToast('Task updated', 'success');
            }
        }

        // 5. recalc global completion ring at top header
        recalcGlobalCompletion();
    }

    // =========================================
    // AJAX: TOGGLE SUBTASK
    // =========================================
    function toggleSubtask(taskId, subtaskId, isChecked) {
        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    completed: isChecked ? 1 : 0
                })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Update failed');
                }

                updateSubtaskUIAfterToggle(taskId, data);

                if (data.subtask.completed) {
                    showToast('Subtask marked complete', 'success');
                } else {
                    showToast('Subtask reopened', 'info');
                }
            })
            .catch(err => {
                console.error(err);
                // rollback checkbox
                const cb = document.getElementById(`subtask-cb-${subtaskId}`);
                if (cb) cb.checked = !isChecked;
                showToast('Could not update subtask', 'error');
            });
    }

    // =========================================
    // MODAL OPEN / CLOSE
    // action:
    //   'remark'      -> add comment only
    //   'done'        -> mark done
    //   'blocked'     -> mark blocked
    //   'postponed'   -> postpone
    //   'cancelled'   -> cancel
    // =========================================
    function openStatusModal(taskId, action, subtaskId = null) {
        const modal = document.getElementById('taskStatusModal');
        const form = document.getElementById('taskStatusForm');
        const modalTitle = document.getElementById('modalTitle');
        const modalTaskId = document.getElementById('modalTaskId');
        const modalSubId = document.getElementById('modalSubtaskId');
        const modalAction = document.getElementById('modalAction');
        const statusWrap = document.getElementById('statusSelection');
        const remarkLabel = document.getElementById('remarkLabel');
        const submitBtnText = document.getElementById('submitBtnText');

        const postponeField = document.getElementById('postponeDateField');
        const postponeInput = document.getElementById('postponed_until');
        const filePreview = document.getElementById('filePreview');

        // reset form
        form.reset();
        selectedFiles = [];
        filePreview.innerHTML = '';
        filePreview.style.display = 'none';

        // push ids
        modalTaskId.value = taskId;
        modalSubId.value = subtaskId || '';
        modalAction.value = action;

        if (action === 'remark') {
            // remark-only mode
            modalTitle.textContent = 'Add Remark';
            statusWrap.style.display = 'none';
            remarkLabel.textContent = 'Your Remark';
            submitBtnText.textContent = 'Add Remark';
            postponeField.style.display = 'none';
            postponeInput.required = false;
        } else {
            // status-change mode
            modalTitle.textContent = 'Update Task Status';
            statusWrap.style.display = 'block';
            remarkLabel.textContent = 'Describe your update';
            submitBtnText.textContent = 'Update Status';

            // preselect the chosen status radio
            if (action !== 'status') {
                const radio = document.querySelector(`input[name="status"][value="${action}"]`);
                if (radio) radio.checked = true;
            }

            updatePostponeDateVisibility();
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeStatusModal(evt) {
        // clicking overlay (target===currentTarget) OR ESC
        if (evt && evt.target && evt.currentTarget && evt.target !== evt.currentTarget) {
            return;
        }

        const modal = document.getElementById('taskStatusModal');
        if (modal) modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // =========================================
    // SHOW/HIDE POSTPONE DATE FIELD
    // =========================================
    function updatePostponeDateVisibility() {
        const checked = document.querySelector('input[name="status"]:checked');
        const val = checked ? checked.value : null;

        const postponeField = document.getElementById('postponeDateField');
        const postponeInput = document.getElementById('postponed_until');

        if (val === 'postponed') {
            postponeField.style.display = 'block';
            postponeInput.required = true;
        } else {
            postponeField.style.display = 'none';
            postponeInput.required = false;
        }
    }

    // =========================================
    // REMARK CHARACTER COUNTER
    // =========================================
    function handleRemarkInput() {
        const remark = document.getElementById('remark');
        const cc = document.getElementById('charCount');
        if (!remark || !cc) return;

        const len = remark.value.length;
        cc.textContent = len;
        cc.style.color = len > 1900 ? '#DE350B' : '#6B778C';
    }

    // =========================================
    // FILE UPLOAD HANDLING
    // drag/drop + preview list
    // =========================================
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }

    function handleFiles(fileList) {
        Array.from(fileList).forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                showToast(`"${file.name}" is too large (max 10MB)`, 'error');
                return;
            }
            selectedFiles.push(file);
            displayFilePreview(file);
        });
    }

    function displayFilePreview(file) {
        const container = document.getElementById('filePreview');
        container.style.display = 'grid';

        const item = document.createElement('div');
        item.className = 'file-preview-item';
        item.dataset.fileName = file.name;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                item.innerHTML = `
                        <img src="${ev.target.result}" alt="${file.name}" class="file-preview-image">
                        <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                            </svg>
                        </button>
                    `;
            };
            reader.readAsDataURL(file);
        } else {
            item.innerHTML = `
                    <div class="file-preview-file">
                        <svg class="file-preview-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                            <path d="M13 2v7h7"/>
                        </svg>
                        <div class="file-preview-name">${file.name}</div>
                    </div>
                    <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                        </svg>
                    </button>
                `;
        }

        container.appendChild(item);
    }

    function removeFile(fileName) {
        selectedFiles = selectedFiles.filter(f => f.name !== fileName);
        const item = document.querySelector(`.file-preview-item[data-file-name="${fileName}"]`);
        if (item) item.remove();

        if (selectedFiles.length === 0) {
            const container = document.getElementById('filePreview');
            container.style.display = 'none';
        }
    }

    // =========================================
    // UPDATE CARD UI AFTER STATUS CHANGE
    // called after we successfully POST status / remark
    // =========================================
    function updateTaskCardUIAfterStatus(taskId, data) {
        // expects:
        // data.task_status
        // data.task_status_label
        // data.completed_subtasks_count
        // data.subtasks_count
        // data.task_status_bg
        // data.task_status_color

        const card = document.querySelector(`.pro-task-card[data-task-id="${taskId}"]`);
        if (!card) {
            recalcGlobalCompletion();
            return;
        }

        // update data-task-status on card
        if (data.task_status) {
            card.setAttribute('data-task-status', data.task_status);
        }

        // update badge
        const badge = card.querySelector('.pro-status-badge');
        if (badge && data.task_status) {
            const styleMap = STATUS_STYLE[data.task_status] || {
                bg: '#F4F5F7',
                color: '#6B778C',
                fallback: data.task_status_label || 'Status',
            };

            const label = data.task_status_label || styleMap.fallback;
            const bg = data.task_status_bg || styleMap.bg;
            const col = data.task_status_color || styleMap.color;

            badge.textContent = label;
            badge.style.background = bg;
            badge.style.color = col;
            badge.style.borderColor = col;
        }

        // if task went "done", force all subtasks done visually
        if (data.task_status === 'done') {
            const subRows = card.querySelectorAll('.pro-subtask-item');
            subRows.forEach(r => {
                r.classList.add('is-completed');
                const cb = r.querySelector('.pro-subtask-checkbox');
                if (cb) cb.checked = true;
            });

            // x/y label
            const countEl = card.querySelector('.pro-subtasks-count');
            if (countEl &&
                data.subtasks_count !== undefined &&
                data.completed_subtasks_count !== undefined) {
                countEl.textContent = `${data.completed_subtasks_count}/${data.subtasks_count}`;
            }

            // progress bar -> 100%
            const bar = card.querySelector('.pro-progress-mini-bar');
            if (bar) {
                bar.style.width = '100%';
                bar.style.background = STATUS_STYLE['done'].color;
            }

            showToast('Task marked done ✅', 'success');
        } else if (data.task_status === 'blocked') {
            showToast('Task marked blocked', 'error');
        } else if (data.task_status === 'postponed') {
            showToast('Task postponed', 'info');
        } else if (data.task_status === 'cancelled') {
            showToast('Task cancelled', 'info');
        } else if (data.task_status === 'in-progress') {
            showToast('Task set In Progress', 'success');
        } else if (data.task_status === 'todo') {
            showToast('Task reopened', 'info');
        } else if (data.task_status) {
            showToast('Task updated', 'success');
        }

        // update global completion ring
        recalcGlobalCompletion();
    }

    // =========================================
    // SUBMIT MODAL FORM
    // onsubmit="submitTaskStatus(event)"
    // =========================================
    function submitTaskStatus(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const prevHTML = submitBtn.innerHTML;

        // lock button + spinner
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" class="spinning">
                    <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" fill="none" opacity="0.25"/>
                    <path d="M8 1a7 7 0 017 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
                <span>Processing...</span>
            `;

        const formEl = document.getElementById('taskStatusForm');
        const taskId = document.getElementById('modalTaskId').value;
        const subtaskId = document.getElementById('modalSubtaskId').value;
        const actionValue = document.getElementById('modalAction').value;

        const fd = new FormData(formEl);

        // push our selectedFiles (drag/drop)
        selectedFiles.forEach(file => {
            fd.append('attachments[]', file);
        });

        // decide endpoint:
        //  - remark only
        //  - completing last subtask (complete-final)
        //  - regular task status update
        let url = '';
        if (actionValue === 'remark') {
            url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/remark`;
        } else if (subtaskId) {
            url =
                `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/complete-final`;
        } else {
            url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/status`;
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    // do NOT set Content-Type here, browser sets multipart/form-data boundary
                },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Request failed');
                }

                // close modal
                closeStatusModal();

                // update UI
                if (data.task_status) {
                    updateTaskCardUIAfterStatus(taskId, data);
                } else {
                    // remark-only path
                    showToast(data.message || 'Remark added successfully', 'success');
                }

                // restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = prevHTML;
            })
            .catch(err => {
                console.error(err);
                showToast('Error updating task', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = prevHTML;
            });
    }

    // =========================================
    // PAGE-LEVEL SHORTCUTS / HELPERS
    // =========================================
    function refreshTasks() {
        window.location.reload();
    }

    function openFilters() {
        console.log('openFilters()');
    }

    function openSettings() {
        console.log('openSettings()');
    }

    function openMenu() {
        console.log('openMenu()');
    }

    function createTask() {
        console.log('createTask()');
    }

    function handleKeyCommands(e) {
        // Cmd/Ctrl + R = refresh (override browser)
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshTasks();
        }

        // Cmd/Ctrl + F = filter
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            openFilters();
        }

        // Cmd/Ctrl + N = new task
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            createTask();
        }

        // ESC closes modal
        if (e.key === 'Escape') {
            closeStatusModal();
        }
    }

    // =========================================
    // INIT
    // =========================================
    document.addEventListener('DOMContentLoaded', function() {

        // scroll active tab into view
        const navScroll = document.querySelector('.pm-nav-scroll');
        if (navScroll) {
            const activeItem = navScroll.querySelector('.pm-nav-item--active');
            if (activeItem) {
                activeItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }

        // listen to radio changes (postpone toggle)
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', updatePostponeDateVisibility);
        });

        // remark char counter
        const remark = document.getElementById('remark');
        if (remark) {
            remark.addEventListener('input', handleRemarkInput);
        }

        // drag+drop area
        const uploadArea = document.getElementById('uploadArea');
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evtName => {
                uploadArea.addEventListener(evtName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(evtName => {
                uploadArea.addEventListener(evtName, () => {
                    uploadArea.classList.add('drag-over');
                }, false);
            });

            ['dragleave', 'drop'].forEach(evtName => {
                uploadArea.addEventListener(evtName, () => {
                    uploadArea.classList.remove('drag-over');
                }, false);
            });

            uploadArea.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }, false);
        }

        // global keyboard shortcuts
        document.addEventListener('keydown', handleKeyCommands);

        // ensure completion ring is accurate on load
        recalcGlobalCompletion();
    });

    // =========================================
    // expose to window so Blade inline onclick="" works
    // =========================================
    window.showToast = showToast;
    window.recalcGlobalCompletion = recalcGlobalCompletion;
    window.toggleSubtasksExpand = toggleSubtasksExpand;
    window.subtaskRowClick = subtaskRowClick;
    window.toggleSubtask = toggleSubtask;
    window.openStatusModal = openStatusModal;
    window.closeStatusModal = closeStatusModal;
    window.submitTaskStatus = submitTaskStatus;
    window.handleFileSelect = handleFileSelect;
    window.removeFile = removeFile;
    window.refreshTasks = refreshTasks;
    window.openFilters = openFilters;
    window.openSettings = openSettings;
    window.openMenu = openMenu;
    window.createTask = createTask;

    // spinner CSS injected once
    const spinStyle = document.createElement('style');
    spinStyle.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to   { transform: rotate(360deg); }
            }
            .spinning {
                animation: spin 1s linear infinite;
            }
            .drag-over {
                outline: 2px dashed #0052CC;
                outline-offset: 4px;
                background: rgba(0,82,204,.05);
            }
            .file-preview-image {
                max-width: 64px;
                max-height: 64px;
                border-radius: 4px;
                object-fit: cover;
                box-shadow: 0 2px 8px rgba(0,0,0,.15);
            }
            .file-preview-file {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 12px;
                background:#F4F5F7;
                color:#172B4D;
                border-radius:4px;
                padding:6px 8px;
            }
            .file-preview-icon {
                width:16px;
                height:16px;
            }
            .file-preview-remove {
                background:transparent;
                border:none;
                cursor:pointer;
                color:#6B778C;
                margin-left:4px;
            }
            #filePreview {
                display:none;
                grid-template-columns:repeat(auto-fill,minmax(80px,1fr));
                gap:8px;
                margin-top:8px;
            }
        `;
    document.head.appendChild(spinStyle);

})();
    </script>
@endsection
