@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.reports.index', $username) }}" class="project-breadcrumb-item">
        Reports
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Velocity</span>
</div>

<!-- Header -->
<div class="report-header">
    <div class="report-header-left">
        <h1 class="report-title">Velocity (Weekly Delivery)</h1>
        <p class="report-subtitle">
            Story points completed per week (last 10 weeks)
        </p>
    </div>

    <div class="report-header-right">
        <select class="project-form-control project-select">
            <option value="10" selected>Last 10 weeks</option>
            <option value="20">Last 20 weeks</option>
        </select>

        <button class="project-btn project-btn-secondary">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>
</div>

<!-- Key Metrics -->
<div class="velocity-metrics-grid">

    <div class="velocity-metric-card">
        <div class="velocity-metric-icon" style="background: rgba(59,130,246,.1); color:#3b82f6;">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div class="velocity-metric-content">
            <div class="velocity-metric-label">Average Velocity</div>
            <div class="velocity-metric-value">
                {{ $velocitySummary['avg_velocity'] }}
                <span class="metric-unit">pts/week</span>
            </div>
            <div class="velocity-metric-trend">
                <i class="fas fa-arrow-up" style="color:#10b981;"></i>
                <span style="color:#10b981;">
                    {{ $velocitySummary['avg_velocity_trend'] }}
                </span>
            </div>
        </div>
    </div>

    <div class="velocity-metric-card">
        <div class="velocity-metric-icon" style="background: rgba(16,185,129,.1); color:#10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="velocity-metric-content">
            <div class="velocity-metric-label">Avg Tasks Closed</div>
            <div class="velocity-metric-value">
                {{ $velocitySummary['avg_tasks_per_week'] }}
                <span class="metric-unit">tasks/week</span>
            </div>
            <div class="velocity-metric-info">
                Consistently shipped
            </div>
        </div>
    </div>

    <div class="velocity-metric-card">
        <div class="velocity-metric-icon" style="background: rgba(139,92,246,.1); color:#8b5cf6;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="velocity-metric-content">
            <div class="velocity-metric-label">Highest Week</div>
            <div class="velocity-metric-value">
                {{ $velocitySummary['highest_week_points'] }}
                <span class="metric-unit">pts</span>
            </div>
            <div class="velocity-metric-info">
                {{ $velocitySummary['highest_week_label'] }}
            </div>
        </div>
    </div>

    <div class="velocity-metric-card">
        <div class="velocity-metric-icon" style="background: rgba(245,158,11,.1); color:#f59e0b;">
            <i class="fas fa-sync-alt"></i>
        </div>
        <div class="velocity-metric-content">
            <div class="velocity-metric-label">Consistency</div>
            <div class="velocity-metric-value">
                {{ $velocitySummary['consistency_pct'] }}%
            </div>
            <div class="velocity-metric-info">
                Std dev: {{ $velocitySummary['std_dev'] }}
            </div>
        </div>
    </div>
</div>

<!-- Velocity Chart -->
<div class="velocity-chart-section">
    <div class="velocity-chart-header">
        <h3 class="velocity-chart-title">Weekly Velocity Trend</h3>

        <div class="chart-legend">
            <div class="legend-item">
                <span class="legend-dot" style="background:#10b981;"></span>
                <span>Completed Points</span>
            </div>
            <div class="legend-item">
                <span class="legend-line"></span>
                <span>Average</span>
            </div>
        </div>
    </div>

    <div class="velocity-chart-container">

        <!-- Y axis labels -->
        <div class="chart-y-axis">
            @foreach($chartMeta['y_ticks'] as $tick)
                <div class="y-axis-label">{{ $tick }}</div>
            @endforeach
        </div>

        <!-- Bars -->
        <div class="chart-area">
            <div class="chart-grid">
                @foreach($chartMeta['y_ticks'] as $tick)
                    <div class="grid-line"></div>
                @endforeach
            </div>

            <div class="chart-bars">
                @foreach($weeklyRows as $row)
                    <div class="bar-group">
                        <div class="bar-pair">
                            <div
                                class="bar bar-completed"
                                style="height: {{ $row['completed_pct_height'] }}%;
                                       background: linear-gradient(180deg,#10b981 0%,#059669 100%);"
                                title="Completed: {{ $row['completed_points'] }} pts">
                                <span class="bar-value">{{ $row['completed_points'] }}</span>
                            </div>
                        </div>

                        <div class="bar-label">{{ $row['sprint_name'] }}</div>
                    </div>
                @endforeach
            </div>

            <div
                class="chart-average-line"
                style="bottom: {{ $chartMeta['average_line_pct'] }}%;">
                <span class="average-label">
                    Average: {{ $velocitySummary['avg_velocity'] }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Breakdown Table -->
<div class="sprint-breakdown-section">
    <div class="breakdown-header">
        <h3 class="breakdown-title">Weekly Delivery Details</h3>

        <div class="breakdown-filters">
            <button class="filter-chip active">All Weeks</button>
            <button class="filter-chip">Active</button>
            <button class="filter-chip">Completed</button>
        </div>
    </div>

    <div class="breakdown-table-container">
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Date Range</th>
                    <th>Completed Points</th>
                    <th>Tasks Completed</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($weeklyRows as $row)
                    <tr>
                        <td>
                            <div class="sprint-name-cell">
                                <i class="fas fa-flag"></i>
                                <span>{{ $row['sprint_name'] }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="date-range">{{ $row['date_range'] }}</span>
                        </td>

                        <td>
                            <span class="points-badge points-completed">
                                {{ $row['completed_points'] }}
                            </span>
                        </td>

                        <td>
                            <span class="points-badge points-committed">
                                {{ $row['tasks_completed'] }} tasks
                            </span>
                        </td>

                        <td>
                            <span class="status-badge status-{{ $row['status_key'] }}">
                                {{ $row['status_label'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<!-- Insights -->
<div class="velocity-insights-section">
    <h3 class="insights-title">
        <i class="fas fa-lightbulb"></i>
        Insights & Recommendations
    </h3>

    <div class="insights-grid">
        @foreach($velocityInsights as $insight)
            <div class="insight-card {{ $insight['type_class'] }}">
                <div class="insight-icon"
                     style="background: {{ $insight['icon_bg'] }}; color: {{ $insight['icon_color'] }};">
                    <i class="fas {{ $insight['icon'] }}"></i>
                </div>
                <div class="insight-content">
                    <h4 class="insight-heading">{{ $insight['heading'] }}</h4>
                    <p class="insight-text">{{ $insight['text'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>



<style>
    /* ===================================== 
       VELOCITY REPORT STYLES
    ===================================== */

    /* Report Header */
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .report-title {
        font-size: 28px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .report-subtitle {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .report-header-right {
        display: flex;
        gap: 8px;
    }

    /* Velocity Metrics */
    .velocity-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .velocity-metric-card {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all 0.2s ease;
    }

    .velocity-metric-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .velocity-metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .velocity-metric-content {
        flex: 1;
    }

    .velocity-metric-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .velocity-metric-value {
        font-size: 32px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 8px;
    }

    .metric-unit {
        font-size: var(--fs-body);
        font-weight: var(--fw-normal);
        color: var(--text-muted);
    }

    .velocity-metric-trend,
    .velocity-metric-info {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
    }

    .velocity-metric-info {
        color: var(--text-muted);
    }

    /* Velocity Chart */
    .velocity-chart-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 24px;
    }

    .velocity-chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .velocity-chart-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .chart-legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .legend-line {
        width: 24px;
        height: 2px;
        background: #6b7280;
        border-top: 2px dashed #6b7280;
    }

    .velocity-chart-container {
        display: flex;
        gap: 16px;
        min-height: 400px;
        position: relative;
    }

    .chart-y-axis {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px 0;
        width: 40px;
    }

    .y-axis-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        text-align: right;
    }

    .chart-area {
        flex: 1;
        position: relative;
        padding: 20px 0;
    }

    .chart-grid {
        position: absolute;
        inset: 20px 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .grid-line {
        height: 1px;
        background: var(--border);
    }

    .chart-bars {
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        height: 100%;
        position: relative;
        z-index: 1;
    }

    .bar-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        max-width: 80px;
    }

    .bar-pair {
        display: flex;
        gap: 4px;
        align-items: flex-end;
        width: 100%;
        justify-content: center;
        height: 100%;
    }

    .bar {
        width: 24px;
        border-radius: 4px 4px 0 0;
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .bar:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .bar-committed {
        background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
    }

    .bar-completed {
        background: linear-gradient(180deg, #10b981 0%, #059669 100%);
    }

    .bar-value {
        position: absolute;
        top: -24px;
        left: 50%;
        transform: translateX(-50%);
        font-size: var(--fs-micro);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .bar:hover .bar-value {
        opacity: 1;
    }

    .bar-label {
        margin-top: 12px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        text-align: center;
        transform: rotate(-45deg);
        transform-origin: center;
        white-space: nowrap;
    }

    .chart-average-line {
        position: absolute;
        left: 0;
        right: 0;
        height: 2px;
        background: transparent;
        border-top: 2px dashed #6b7280;
        z-index: 2;
        pointer-events: none;
    }

    .average-label {
        position: absolute;
        right: 0;
        top: -24px;
        padding: 4px 8px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        white-space: nowrap;
    }

    /* Breakdown Table */
    .sprint-breakdown-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 24px;
    }

    .breakdown-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .breakdown-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .breakdown-filters {
        display: flex;
        gap: 8px;
    }

    .filter-chip {
        padding: 6px 12px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
        color: var(--text-body);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .filter-chip:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .filter-chip.active {
        background: var(--accent);
        color: var(--btn-text-primary);
        border-color: var(--accent);
    }

    .breakdown-table-container {
        overflow-x: auto;
    }

    .breakdown-table {
        width: 100%;
        border-collapse: collapse;
    }

    .breakdown-table thead {
        background: var(--bg);
    }

    .breakdown-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
    }

    .breakdown-table td {
        padding: 16px;
        font-size: var(--fs-body);
        color: var(--text-body);
        border-bottom: 1px solid var(--border);
    }

    .breakdown-table tbody tr:hover {
        background: var(--bg);
    }

    .sprint-name-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .date-range {
        color: var(--text-muted);
    }

    .points-badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
    }

    .points-committed {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .points-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .completion-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .completion-bar-mini {
        flex: 1;
        height: 6px;
        background: var(--bg);
        border-radius: 3px;
        overflow: hidden;
        min-width: 80px;
    }

    .completion-fill-mini {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }

    .completion-percentage {
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .variance-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
    }

    .variance-positive {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .variance-negative {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .status-badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
    }

    .status-active {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    /* Insights */
    .velocity-insights-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
    }

    .insights-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .insights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }

    .insight-card {
        display: flex;
        gap: 16px;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .insight-positive {
        background: rgba(16, 185, 129, 0.05);
        border-color: #10b981;
    }

    .insight-warning {
        background: rgba(245, 158, 11, 0.05);
        border-color: #f59e0b;
    }

    .insight-info {
        background: rgba(59, 130, 246, 0.05);
        border-color: #3b82f6;
    }

    .insight-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .insight-positive .insight-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .insight-warning .insight-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .insight-info .insight-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .insight-content {
        flex: 1;
    }

    .insight-heading {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 8px 0;
    }

    .insight-text {
        font-size: var(--fs-body);
        color: var(--text-body);
        line-height: 1.5;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .velocity-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .report-header {
            flex-direction: column;
            align-items: stretch;
        }

        .velocity-metrics-grid {
            grid-template-columns: 1fr;
        }

        .chart-y-axis {
            width: 30px;
        }

        .bar-label {
            font-size: 10px;
        }

        .breakdown-table {
            font-size: var(--fs-subtle);
        }

        .insights-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    console.log('✅ Velocity Chart Initialized');
</script>

@endsection
