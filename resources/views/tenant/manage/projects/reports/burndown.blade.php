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
    <span class="project-breadcrumb-item active">Burndown</span>
</nav>

<!-- Page Header -->
<header class="project-page-header">
    <div class="project-page-header-content">
        <h1 class="project-page-title">Burndown (Last 14 Days)</h1>
        <p class="project-page-subtitle">Scope vs completed work across active projects</p>
    </div>

    <div class="project-page-actions">
        <button class="project-btn project-btn-ghost">
            <i class="fas fa-download"></i>
            <span>Export PDF</span>
        </button>

        <button class="project-btn project-btn-secondary">
            <i class="fas fa-share-alt"></i>
            <span>Share Report</span>
        </button>
    </div>
</header>

<!-- Filters -->
<div class="report-filters">
    <div class="report-filter-group">
        <label class="report-filter-label">Project</label>
        <select class="project-form-control project-select">
            @foreach($projectFilterOptions as $opt)
                <option value="{{ $opt['id'] }}" {{ $opt['selected'] ? 'selected' : '' }}>
                    {{ $opt['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="report-filter-group">
        <label class="report-filter-label">Date Range</label>
        <select class="project-form-control project-select">
            <option selected>Last 14 Days</option>
        </select>
    </div>
</div>

<!-- Summary Cards -->
<div class="report-stats-grid">

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(0,82,204,.1); color:#0052cc;">
            <i class="fas fa-tasks"></i>
        </div>

        <div class="report-stat-content">
            <div class="report-stat-label">Total Story Points</div>
            <div class="report-stat-value">{{ $iterationSummary['total_points'] }}</div>
            <div class="report-stat-trend {{ $iterationSummary['total_points_trend_class'] }}">
                <i class="fas {{ $iterationSummary['total_points_trend_icon'] }}"></i>
                <span>{{ $iterationSummary['total_points_trend_text'] }}</span>
            </div>
        </div>
    </div>

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(16,185,129,.1); color:#10b981;">
            <i class="fas fa-check-circle"></i>
        </div>

        <div class="report-stat-content">
            <div class="report-stat-label">Completed Points</div>
            <div class="report-stat-value">{{ $iterationSummary['completed_points'] }}</div>
            <div class="report-stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $iterationSummary['completion_rate'] }}% completion rate</span>
            </div>
        </div>
    </div>

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(245,158,11,.1); color:#f59e0b;">
            <i class="fas fa-clock"></i>
        </div>

        <div class="report-stat-content">
            <div class="report-stat-label">Days Remaining</div>
            <div class="report-stat-value">{{ $iterationSummary['days_remaining'] }}</div>
            <div class="report-stat-trend neutral">
                <i class="fas fa-minus"></i>
                <span>{{ $iterationSummary['days_remaining_text'] }}</span>
            </div>
        </div>
    </div>

    <div class="report-stat-card">
        <div class="report-stat-icon" style="background: rgba(239,68,68,.1); color:#ef4444;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <div class="report-stat-content">
            <div class="report-stat-label">At Risk</div>
            <div class="report-stat-value">{{ $iterationSummary['at_risk_tasks'] }}</div>
            <div class="report-stat-trend negative">
                <i class="fas fa-arrow-down"></i>
                <span>{{ $iterationSummary['at_risk_text'] }}</span>
            </div>
        </div>
    </div>

</div>

<!-- Burndown Chart -->
<div class="report-chart-container">
    <div class="report-chart-header">
        <h2 class="report-chart-title">Burndown Trend</h2>

        <div class="report-chart-legend">
            <div class="report-legend-item">
                <span class="report-legend-dot" style="background:#0052cc;"></span>
                <span>Ideal Burndown</span>
            </div>

            <div class="report-legend-item">
                <span class="report-legend-dot" style="background:#10b981;"></span>
                <span>Actual Burndown</span>
            </div>

            <div class="report-legend-item">
                <span class="report-legend-dot" style="background:#f59e0b;"></span>
                <span>Projection</span>
            </div>
        </div>
    </div>

    <div class="report-chart-body">
        <svg viewBox="0 0 800 400" style="width:100%; height:400px;">

            <!-- Axes -->
            <g class="grid-lines">
                <line x1="50" y1="50"  x2="50"  y2="350" stroke="#dfe1e6" stroke-width="2"/>
                <line x1="50" y1="350" x2="750" y2="350" stroke="#dfe1e6" stroke-width="2"/>

                @foreach($chart['y_guides'] as $g)
                    <line x1="50" y1="{{ $g['y'] }}" x2="750" y2="{{ $g['y'] }}" stroke="#f3f4f6" stroke-width="1"/>
                    <text x="30" y="{{ $g['y'] + 5 }}" fill="#5e6c84" font-size="12">
                        {{ $g['label'] }}
                    </text>
                @endforeach
            </g>

            <!-- X labels -->
            <g class="x-axis-labels">
                @foreach($chart['x_labels'] as $lbl)
                    <text
                        x="{{ $lbl['x'] }}"
                        y="370"
                        fill="#5e6c84"
                        font-size="11"
                        text-anchor="middle">
                        {{ $lbl['text'] }}
                    </text>
                @endforeach
            </g>

            <!-- Ideal -->
            <polyline
                points="{{ $chart['ideal_line'] }}"
                fill="none"
                stroke="#0052cc"
                stroke-width="2"
                stroke-dasharray="5,5"
                opacity="0.5"
            />

            <!-- Actual -->
            <polyline
                points="{{ $chart['actual_line'] }}"
                fill="none"
                stroke="#10b981"
                stroke-width="3"
            />

            <!-- Projection -->
            <polyline
                points="{{ $chart['projection_line'] }}"
                fill="none"
                stroke="#f59e0b"
                stroke-width="2"
                stroke-dasharray="3,3"
                opacity="0.7"
            />

            <!-- Actual dots -->
            @foreach($chart['actual_points'] as $pt)
                <circle
                    cx="{{ $pt['x'] }}"
                    cy="{{ $pt['y'] }}"
                    r="4"
                    fill="#10b981"
                    stroke="#fff"
                    stroke-width="2"/>
            @endforeach
        </svg>
    </div>
</div>

<!-- Insights -->
<div class="report-insights">
    <h2 class="report-section-title">Iteration Insights</h2>

    <div class="report-insights-grid">
        @foreach($insights as $insight)
            <div class="report-insight-card {{ $insight['type_class'] }}">
                <div class="report-insight-icon">
                    <i class="fas {{ $insight['icon'] }}"></i>
                </div>
                <div class="report-insight-content">
                    <h3 class="report-insight-title">{{ $insight['title'] }}</h3>
                    <p class="report-insight-desc">{{ $insight['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Daily Progress Table -->
<div class="report-table-container">
    <h2 class="report-section-title">Daily Progress</h2>

    <table class="report-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                <th>Ideal Remaining</th>
                <th>Actual Remaining</th>
                <th>Completed Today</th>
                <th>Added Today</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($dailyProgress as $row)
                <tr>
                    <td><strong>Day {{ $row['day_index'] }}</strong></td>
                    <td>{{ $row['date_label'] }}</td>
                    <td>{{ $row['ideal_remaining'] }} pts</td>
                    <td>{{ $row['actual_remaining'] }} pts</td>
                    <td>
                        <span class="report-badge report-badge-success">
                            {{ $row['completed_today'] }} pts
                        </span>
                    </td>
                    <td>{{ $row['added_today'] }} pts</td>
                    <td>
                        <span class="report-status-badge report-status-{{ $row['status_key'] }}">
                            {{ $row['status_label'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>



<!-- Daily Progress Table -->

<style>
    /* Report Styles */
    .report-filters {
        display: flex;
        align-items: flex-end;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .report-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 200px;
    }

    .report-filter-label {
        font-size: var(--manage-fs-sm);
        font-weight: var(--manage-fw-semibold);
        color: var(--manage-text-secondary);
    }

    .report-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .report-stat-card {
        display: flex;
        gap: 16px;
        background: var(--manage-card);
        border: 1px solid var(--manage-border);
        border-radius: 8px;
        padding: 20px;
        transition: all 0.2s ease;
    }

    .report-stat-card:hover {
        box-shadow: var(--manage-shadow-md);
        transform: translateY(-2px);
    }

    .report-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .report-stat-content {
        flex: 1;
    }

    .report-stat-label {
        font-size: var(--manage-fs-sm);
        color: var(--manage-text-secondary);
        margin-bottom: 4px;
    }

    .report-stat-value {
        font-size: 28px;
        font-weight: var(--manage-fw-bold);
        color: var(--manage-text-primary);
        line-height: 1;
        margin-bottom: 6px;
    }

    .report-stat-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: var(--manage-fs-xs);
        font-weight: var(--manage-fw-medium);
    }

    .report-stat-trend.positive {
        color: #10b981;
    }

    .report-stat-trend.negative {
        color: #ef4444;
    }

    .report-stat-trend.neutral {
        color: var(--manage-text-muted);
    }

    .report-chart-container {
        background: var(--manage-card);
        border: 1px solid var(--manage-border);
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .report-chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .report-chart-title {
        font-size: var(--manage-fs-lg);
        font-weight: var(--manage-fw-semibold);
        color: var(--manage-text-primary);
        margin: 0;
    }

    .report-chart-legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .report-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--manage-fs-sm);
        color: var(--manage-text-secondary);
    }

    .report-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .report-chart-body {
        background: var(--manage-bg);
        border-radius: 6px;
        padding: 20px;
    }

    .report-section-title {
        font-size: var(--manage-fs-lg);
        font-weight: var(--manage-fw-semibold);
        color: var(--manage-text-primary);
        margin: 0 0 20px 0;
    }

    .report-insights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .report-insight-card {
        display: flex;
        gap: 16px;
        background: var(--manage-card);
        border: 1px solid var(--manage-border);
        border-radius: 8px;
        padding: 20px;
        border-left-width: 4px;
    }

    .report-insight-card.insight-success {
        border-left-color: #10b981;
    }

    .report-insight-card.insight-warning {
        border-left-color: #f59e0b;
    }

    .report-insight-card.insight-info {
        border-left-color: #0052cc;
    }

    .report-insight-icon {
        font-size: 24px;
        flex-shrink: 0;
    }

    .insight-success .report-insight-icon {
        color: #10b981;
    }

    .insight-warning .report-insight-icon {
        color: #f59e0b;
    }

    .insight-info .report-insight-icon {
        color: #0052cc;
    }

    .report-insight-title {
        font-size: var(--manage-fs-md);
        font-weight: var(--manage-fw-semibold);
        color: var(--manage-text-primary);
        margin: 0 0 6px 0;
    }

    .report-insight-desc {
        font-size: var(--manage-fs-sm);
        color: var(--manage-text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    .report-table-container {
        background: var(--manage-card);
        border: 1px solid var(--manage-border);
        border-radius: 8px;
        padding: 24px;
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table thead {
        background: var(--manage-bg);
    }

    .report-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: var(--manage-fs-sm);
        font-weight: var(--manage-fw-semibold);
        color: var(--manage-text-secondary);
        border-bottom: 2px solid var(--manage-border);
    }

    .report-table td {
        padding: 12px 16px;
        font-size: var(--manage-fs-sm);
        color: var(--manage-text-primary);
        border-bottom: 1px solid var(--manage-border);
    }

    .report-table tbody tr:hover {
        background: var(--manage-bg);
    }

    .report-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--manage-fs-xs);
        font-weight: var(--manage-fw-semibold);
    }

    .report-badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .report-status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: var(--manage-fs-xs);
        font-weight: var(--manage-fw-semibold);
    }

    .report-status-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .report-status-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    @media (max-width: 768px) {
        .report-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .report-filter-group {
            min-width: 100%;
        }

        .report-insights-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection