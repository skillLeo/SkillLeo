@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<nav class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i>
        <span>Projects</span>
    </a>
    <i class="fas fa-chevron-right project-breadcrumb-separator"></i>
    <a href="{{ route('tenant.manage.projects.list', $username) }}" class="project-breadcrumb-item">
        All Projects
    </a>
    <i class="fas fa-chevron-right project-breadcrumb-separator"></i>
    <span class="project-breadcrumb-item active">Time Tracking</span>
</nav>

<!-- Page Header -->
<header class="project-page-header">
    <div class="project-page-header-content">
        <h1 class="project-page-title">Time Tracking Report</h1>
        <p class="project-page-subtitle">Track team hours and productivity</p>
    </div>

    <div class="project-page-actions">
        <button class="project-btn project-btn-primary">
            <i class="fas fa-play"></i>
            <span>Start Timer</span>
        </button>

        <button class="project-btn project-btn-secondary">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>
</header>

<!-- Filters -->
<div class="report-filters">

    <div class="report-filter-group">
        <label class="report-filter-label">Project</label>
        <select class="project-form-control project-select">
            @foreach($projectFilterOptions as $opt)
                <option
                    value="{{ $opt['id'] ?? 'all' }}"
                    {{ $opt['selected'] ? 'selected' : '' }}>
                    {{ $opt['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="report-filter-group">
        <label class="report-filter-label">Team Member</label>
        <select class="project-form-control project-select">
            @foreach($memberFilterOptions as $opt)
                <option
                    value="{{ $opt['id'] ?? 'all' }}"
                    {{ $opt['selected'] ? 'selected' : '' }}>
                    {{ $opt['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="report-filter-group">
        <label class="report-filter-label">Date Range</label>
        <select class="project-form-control project-select">
            @foreach($dateRangeOptionsTT as $opt)
                <option value="{{ $opt['value'] }}" {{ $opt['selected'] ? 'selected' : '' }}>
                    {{ $opt['label'] }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<!-- High-level Stats -->
<div class="report-stats-grid">

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(0,82,204,.1); color:#0052cc;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="report-stat-content">
            <div class="report-stat-label">Total Hours</div>
            <div class="report-stat-value">{{ $highLevel['total_hours'] }}h</div>
            <div class="report-stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $highLevel['total_trend'] }}</span>
            </div>
        </div>
    </div>

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(16,185,129,.1); color:#10b981;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="report-stat-content">
            <div class="report-stat-label">Billable Hours</div>
            <div class="report-stat-value">{{ $highLevel['billable_hours'] }}h</div>
            <div class="report-stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $highLevel['billable_ratio'] }} billable</span>
            </div>
        </div>
    </div>

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(139,92,246,.1); color:#8b5cf6;">
            <i class="fas fa-users"></i>
        </div>
        <div class="report-stat-content">
            <div class="report-stat-label">Active Members</div>
            <div class="report-stat-value">{{ $highLevel['active_members'] }}</div>
            <div class="report-stat-trend neutral">
                <i class="fas fa-minus"></i>
                <span>{{ $highLevel['active_members_text'] }}</span>
            </div>
        </div>
    </div>

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(245,158,11,.1); color:#f59e0b;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="report-stat-content">
            <div class="report-stat-label">Avg Hours/Day</div>
            <div class="report-stat-value">{{ $highLevel['avg_hours_per_day'] }}h</div>
            <div class="report-stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $highLevel['avg_hours_comment'] }}</span>
            </div>
        </div>
    </div>

</div>

<!-- Time by Project -->
<div class="report-chart-container">
    <div class="report-chart-header">
        <h2 class="report-chart-title">Time by Project</h2>

        <div class="report-chart-legend">
            <div class="report-legend-item">
                <span class="report-legend-dot" style="background:#0052cc;"></span>
                <span>Billable</span>
            </div>
            <div class="report-legend-item">
                <span class="report-legend-dot" style="background:#dfe1e6;"></span>
                <span>Non-billable</span>
            </div>
        </div>
    </div>

    <div class="time-chart-bars">
        @foreach($projectTimeBreakdown as $row)
            <div class="time-chart-bar-row">
                <div class="time-chart-bar-label">{{ $row['project_name'] }}</div>

                <div class="time-chart-bar-container">
                    <div
                        class="time-chart-bar-billable"
                        style="width: {{ $row['billable_pct'] }}%; background: {{ $row['color'] }};">
                        <span class="time-chart-bar-value">
                            {{ $row['billable_hours'] }}h
                        </span>
                    </div>

                    <div
                        class="time-chart-bar-nonbillable"
                        style="width: {{ $row['nonbillable_pct'] }}%;">
                        <span class="time-chart-bar-value">
                            {{ $row['nonbillable_hours'] }}h
                        </span>
                    </div>
                </div>

                <div class="time-chart-bar-total">
                    {{ $row['total_hours'] }}h
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Detailed Log -->
<div class="report-table-container">
    <div class="report-table-header">
        <h2 class="report-section-title">Team Time Log</h2>

        <div class="report-table-actions">
            <div class="project-search-box" style="max-width:250px;">
                <i class="fas fa-search project-search-icon"></i>
                <input type="text" placeholder="Search members...">
            </div>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Member</th>
                <th>Project</th>
                <th>Task</th>
                <th>Date</th>
                <th>Time Logged</th>
                <th>Type</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($timeLogRows as $log)
                <tr>
                    <td>
                        <div class="time-log-member">
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode($log['member_name']) }}&background={{ $log['avatar_bg'] }}&color=fff"
                                alt="{{ $log['member_name'] }}"
                                class="time-log-avatar">
                            <span>{{ $log['member_name'] }}</span>
                        </div>
                    </td>

                    <td>{{ $log['project_name'] }}</td>
                    <td>{{ $log['task_title'] }}</td>
                    <td>{{ $log['date_label'] }}</td>

                    <td><strong>{{ $log['hours'] }}h</strong></td>

                    <td>
                        <span class="time-type-badge time-type-{{ $log['type_key'] }}">
                            {{ $log['type_label'] }}
                        </span>
                    </td>

                    <td>
                        <span class="report-status-badge report-status-{{ $log['status_key'] }}">
                            {{ $log['status_label'] }}
                        </span>
                    </td>

                    <td>
                        <button class="project-icon-btn" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Today's Timeline -->
<div class="time-timeline-container">
    <h2 class="report-section-title">Today's Timeline</h2>

    <div class="time-timeline">
        @foreach ($timelineEntries as $block)
            <div class="time-timeline-entry">
                <div class="time-timeline-time">{{ $block['start_time'] }}</div>

                <div class="time-timeline-line"></div>

                <div class="time-timeline-content">
                    <div class="time-timeline-card">
                        <div class="time-timeline-header">
                            <h4 class="time-timeline-task">{{ $block['task_title'] }}</h4>
                            <span class="time-timeline-duration">{{ $block['duration_label'] }}</span>
                        </div>

                        <div class="time-timeline-meta">
                            <span class="time-timeline-project">
                                <i class="fas fa-folder"></i>
                                {{ $block['project_name'] }}
                            </span>
                            <span class="time-timeline-member">
                                <i class="fas fa-user"></i>
                                {{ $block['user_short'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    <style>
        /* Time Chart Bars */
        .time-chart-bars {
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: var(--manage-bg);
            padding: 20px;
            border-radius: 6px;
        }

        .time-chart-bar-row {
            display: grid;
            grid-template-columns: 180px 1fr 80px;
            gap: 16px;
            align-items: center;
        }

        .time-chart-bar-label {
            font-size: var(--manage-fs-sm);
            font-weight: var(--manage-fw-semibold);
            color: var(--manage-text-primary);
        }

        .time-chart-bar-container {
            display: flex;
            height: 32px;
            background: var(--manage-card);
            border-radius: 6px;
            overflow: hidden;
        }

        .time-chart-bar-billable,
        .time-chart-bar-nonbillable {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: width 0.6s ease;
        }

        .time-chart-bar-billable {
            background: #0052cc;
        }

        .time-chart-bar-nonbillable {
            background: #dfe1e6;
        }

        .time-chart-bar-value {
            font-size: var(--manage-fs-xs);
            font-weight: var(--manage-fw-bold);
            color: white;
        }

        .time-chart-bar-nonbillable .time-chart-bar-value {
            color: var(--manage-text-secondary);
        }

        .time-chart-bar-total {
            font-size: var(--manage-fs-sm);
            font-weight: var(--manage-fw-bold);
            color: var(--manage-text-primary);
            text-align: right;
        }

        /* Report Table Header */
        .report-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .report-table-actions {
            display: flex;
            gap: 12px;
        }

        /* Time Log Member */
        .time-log-member {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .time-log-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid var(--manage-card);
        }

        .time-type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: var(--manage-fs-xs);
            font-weight: var(--manage-fw-semibold);
        }

        .time-type-billable {
            background: rgba(0, 82, 204, 0.1);
            color: #0052cc;
        }

        .time-type-non-billable {
            background: rgba(94, 108, 132, 0.1);
            color: #5e6c84;
        }

        .report-status-approved {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .report-status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        /* Timeline */
        .time-timeline-container {
            background: var(--manage-card);
            border: 1px solid var(--manage-border);
            border-radius: 8px;
            padding: 24px;
            margin-top: 32px;
        }

        .time-timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .time-timeline-entry {
            display: grid;
            grid-template-columns: 100px 40px 1fr;
            gap: 16px;
            position: relative;
        }

        .time-timeline-time {
            font-size: var(--manage-fs-sm);
            font-weight: var(--manage-fw-semibold);
            color: var(--manage-text-secondary);
            padding-top: 8px;
        }

        .time-timeline-line {
            position: relative;
            display: flex;
            justify-content: center;
            padding: 8px 0;
        }

        .time-timeline-line::before {
            content: '';
            position: absolute;
            width: 2px;
            top: 0;
            bottom: -20px;
            background: var(--manage-border);
        }

        .time-timeline-line::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--manage-accent);
            border: 3px solid var(--manage-card);
            box-shadow: 0 0 0 2px var(--manage-border);
            top: 12px;
            z-index: 1;
        }

        .time-timeline-entry:last-child .time-timeline-line::before {
            display: none;
        }

        .time-timeline-content {
            padding-bottom: 20px;
        }

        .time-timeline-card {
            background: var(--manage-bg);
            border: 1px solid var(--manage-border);
            border-radius: 8px;
            padding: 16px;
            transition: all 0.2s ease;
        }

        .time-timeline-card:hover {
            box-shadow: var(--manage-shadow-sm);
            border-color: var(--manage-accent);
        }

        .time-timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .time-timeline-task {
            font-size: var(--manage-fs-md);
            font-weight: var(--manage-fw-semibold);
            color: var(--manage-text-primary);
            margin: 0;
        }

        .time-timeline-duration {
            font-size: var(--manage-fs-sm);
            font-weight: var(--manage-fw-bold);
            color: var(--manage-accent);
        }

        .time-timeline-meta {
            display: flex;
            gap: 16px;
            font-size: var(--manage-fs-sm);
            color: var(--manage-text-secondary);
        }

        .time-timeline-project,
        .time-timeline-member {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 768px) {
            .time-chart-bar-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .time-chart-bar-total {
                text-align: left;
            }

            .time-timeline-entry {
                grid-template-columns: 80px 30px 1fr;
                gap: 12px;
            }
        }
    </style>
@endsection
