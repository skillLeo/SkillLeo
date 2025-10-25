@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Reports & Analytics</span>
</div>

<!-- Page Header -->
<div class="reports-page-header">
    <div class="reports-header-left">
        <h1 class="project-page-title">Reports & Analytics</h1>
        <p class="project-page-subtitle">
            Track performance, velocity, and project health metrics
        </p>
    </div>

    <div class="reports-header-right">
        <select class="project-form-control project-select" style="min-width:160px;">
            @foreach($dateRanges as $value => $label)
                <option value="{{ $value }}" {{ (int)$value === $selectedRange ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button class="project-btn project-btn-secondary">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="reports-metrics-grid">

    <!-- Velocity -->
    <div class="metric-card metric-card-primary">
        <div class="metric-header">
            <div class="metric-icon" style="background: rgba(59,130,246,.1); color:#3b82f6;">
                <i class="fas fa-chart-line"></i>
            </div>
            <button class="metric-menu-btn">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <div class="metric-content">
            <div class="metric-value">{{ $metrics['velocity']['value'] }}</div>
            <div class="metric-label">{{ $metrics['velocity']['label'] }}</div>
            <div class="metric-trend {{ $metrics['velocity']['trend_class'] }}">
                <i class="fas {{ $metrics['velocity']['trend_icon'] }}"></i>
                <span>{{ $metrics['velocity']['trend_text'] }}</span>
            </div>
        </div>

        <div class="metric-chart">
            <svg width="100%" height="40" viewBox="0 0 200 40" preserveAspectRatio="none">
                <polyline
                    fill="none"
                    stroke="rgba(59,130,246,0.5)"
                    stroke-width="2"
                    points="{{ $metrics['velocity']['chart_points'] }}"
                />
            </svg>
        </div>
    </div>

    <!-- Completion Rate -->
    <div class="metric-card metric-card-success">
        <div class="metric-header">
            <div class="metric-icon" style="background: rgba(16,185,129,.1); color:#10b981;">
                <i class="fas fa-check-circle"></i>
            </div>
            <button class="metric-menu-btn">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <div class="metric-content">
            <div class="metric-value">{{ $metrics['sprint_completion']['value'] }}%</div>
            <div class="metric-label">{{ $metrics['sprint_completion']['label'] }}</div>
            <div class="metric-trend {{ $metrics['sprint_completion']['trend_class'] }}">
                <i class="fas {{ $metrics['sprint_completion']['trend_icon'] }}"></i>
                <span>{{ $metrics['sprint_completion']['trend_text'] }}</span>
            </div>
        </div>

        <div class="metric-chart">
            <svg width="100%" height="40" viewBox="0 0 200 40" preserveAspectRatio="none">
                <polyline
                    fill="none"
                    stroke="rgba(16,185,129,0.5)"
                    stroke-width="2"
                    points="{{ $metrics['sprint_completion']['chart_points'] }}"
                />
            </svg>
        </div>
    </div>

    <!-- Cycle Time -->
    <div class="metric-card metric-card-warning">
        <div class="metric-header">
            <div class="metric-icon" style="background: rgba(245,158,11,.1); color:#f59e0b;">
                <i class="fas fa-clock"></i>
            </div>
            <button class="metric-menu-btn">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <div class="metric-content">
            <div class="metric-value">{{ $metrics['cycle_time']['value'] }}h</div>
            <div class="metric-label">{{ $metrics['cycle_time']['label'] }}</div>
            <div class="metric-trend {{ $metrics['cycle_time']['trend_class'] }}">
                <i class="fas {{ $metrics['cycle_time']['trend_icon'] }}"></i>
                <span>{{ $metrics['cycle_time']['trend_text'] }}</span>
            </div>
        </div>

        <div class="metric-chart">
            <svg width="100%" height="40" viewBox="0 0 200 40" preserveAspectRatio="none">
                <polyline
                    fill="none"
                    stroke="rgba(245,158,11,0.5)"
                    stroke-width="2"
                    points="{{ $metrics['cycle_time']['chart_points'] }}"
                />
            </svg>
        </div>
    </div>

    <!-- Urgent Open Tasks -->
    <div class="metric-card metric-card-danger">
        <div class="metric-header">
            <div class="metric-icon" style="background: rgba(239,68,68,.1); color:#ef4444;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <button class="metric-menu-btn">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <div class="metric-content">
            <div class="metric-value">{{ $metrics['active_bugs']['value'] }}</div>
            <div class="metric-label">{{ $metrics['active_bugs']['label'] }}</div>
            <div class="metric-trend {{ $metrics['active_bugs']['trend_class'] }}">
                <i class="fas {{ $metrics['active_bugs']['trend_icon'] }}"></i>
                <span>{{ $metrics['active_bugs']['trend_text'] }}</span>
            </div>
        </div>

        <div class="metric-chart">
            <svg width="100%" height="40" viewBox="0 0 200 40" preserveAspectRatio="none">
                <polyline
                    fill="none"
                    stroke="rgba(239,68,68,0.5)"
                    stroke-width="2"
                    points="{{ $metrics['active_bugs']['chart_points'] }}"
                />
            </svg>
        </div>
    </div>
</div>

<!-- Quick Report Links -->
<div class="quick-reports-grid">
    <a href="{{ route('tenant.manage.projects.reports.velocity', $username) }}" class="quick-report-card">
        <div class="quick-report-icon" style="background: rgba(59,130,246,.1); color:#3b82f6;">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div class="quick-report-content">
            <h4 class="quick-report-title">Velocity</h4>
            <p class="quick-report-desc">Weekly delivery (last 10 weeks)</p>
        </div>
        <div class="quick-report-arrow">
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    <a href="{{ route('tenant.manage.projects.reports.burndown', $username) }}" class="quick-report-card">
        <div class="quick-report-icon" style="background: rgba(16,185,129,.1); color:#10b981;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="quick-report-content">
            <h4 class="quick-report-title">Burndown</h4>
            <p class="quick-report-desc">14-day scope vs completion</p>
        </div>
        <div class="quick-report-arrow">
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    <a href="{{ route('tenant.manage.projects.reports.time-tracking', $username) }}" class="quick-report-card">
        <div class="quick-report-icon" style="background: rgba(139,92,246,.1); color:#8b5cf6;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="quick-report-content">
            <h4 class="quick-report-title">Time / Workload</h4>
            <p class="quick-report-desc">Hours & allocation by team</p>
        </div>
        <div class="quick-report-arrow">
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>

    <a href="{{ route('tenant.manage.projects.reports.velocity', $username) }}" class="quick-report-card">
        <div class="quick-report-icon" style="background: rgba(245,158,11,.1); color:#f59e0b;">
            <i class="fas fa-stream"></i>
        </div>
        <div class="quick-report-content">
            <h4 class="quick-report-title">Throughput</h4>
            <p class="quick-report-desc">Team output trend</p>
        </div>
        <div class="quick-report-arrow">
            <i class="fas fa-arrow-right"></i>
        </div>
    </a>
</div>

<!-- Team Performance -->
<div class="reports-section">
    <div class="reports-section-header">
        <h2 class="reports-section-title">
            <i class="fas fa-users"></i>
            Team Performance
        </h2>

        <button class="project-btn project-btn-ghost">
            <span>View Full Report</span>
            <i class="fas fa-external-link-alt"></i>
        </button>
    </div>

    <div class="team-performance-grid">
        @foreach($teamPerformance as $member)
            <div class="team-member-performance">
                <div class="performance-header">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($member['name']) }}&background={{ $member['avatar_bg'] }}&color=fff"
                        alt="{{ $member['name'] }}"
                        class="performance-avatar">

                    <div class="performance-info">
                        <h4 class="performance-name">{{ $member['name'] }}</h4>
                        <p class="performance-role">{{ $member['role'] }}</p>
                    </div>
                </div>

                <div class="performance-stats">
                    <div class="performance-stat">
                        <span class="stat-label">Completed</span>
                        <span class="stat-value">{{ $member['completed'] }}</span>
                    </div>

                    <div class="performance-stat">
                        <span class="stat-label">In Progress</span>
                        <span class="stat-value">{{ $member['in_progress'] }}</span>
                    </div>
                </div>

                <div class="performance-bar-container">
                    <div class="performance-bar">
                        <div
                            class="performance-bar-fill"
                            style="width: {{ $member['completion_pct'] }}%;">
                        </div>
                    </div>
                    <span class="performance-percentage">{{ $member['completion_pct'] }}%</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Project Health -->
<div class="reports-section">
    <div class="reports-section-header">
        <h2 class="reports-section-title">
            <i class="fas fa-heartbeat"></i>
            Project Health
        </h2>
    </div>

    <div class="project-health-grid">
        @foreach($projectHealth as $p)
            <div class="project-health-card">

                <div class="health-header">
                    <h4 class="health-project-name">{{ $p['project_name'] }}</h4>

                    <span class="health-badge health-{{ $p['health'] }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($p['health']) }}
                    </span>
                </div>

                <div class="health-metrics">

                    <div class="health-metric">
                        <span class="health-metric-label">Progress</span>

                        <div class="health-progress">
                            <div class="health-progress-bar">
                                <div
                                    class="health-progress-fill"
                                    style="width: {{ $p['progress_pct'] }}%; background: {{ $p['color'] }};">
                                </div>
                            </div>

                            <span class="health-progress-text">{{ $p['progress_pct'] }}%</span>
                        </div>
                    </div>

                    <div class="health-indicators">
                        <div class="health-indicator">
                            <i class="fas fa-tasks"></i>
                            <span>{{ $p['tasks_done'] }}/{{ $p['tasks_total'] }} tasks</span>
                        </div>

                        <div class="health-indicator">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $p['days_left_label'] }}</span>
                        </div>

                        <div class="health-indicator">
                            <i class="fas fa-users"></i>
                            <span>{{ $p['members'] }} members</span>
                        </div>
                    </div>

                </div>

            </div>
        @endforeach
    </div>
</div>





<style>
    /* ===================================== 
       REPORTS PAGE STYLES
    ===================================== */

    /* Page Header */
    .reports-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .reports-header-left {
        flex: 1;
        min-width: 300px;
    }

    .reports-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Metrics Grid */
    .reports-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .metric-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .metric-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .metric-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .metric-card-primary .metric-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .metric-card-success .metric-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .metric-card-warning .metric-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .metric-card-danger .metric-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .metric-menu-btn {
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
        transition: all 0.15s ease;
    }

    .metric-menu-btn:hover {
        background: var(--bg);
    }

    .metric-value {
        font-size: 36px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .metric-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
        margin-bottom: 12px;
    }

    .metric-trend-up {
        color: #10b981;
    }

    .metric-trend-down {
        color: #ef4444;
    }

    .metric-trend-neutral {
        color: var(--text-muted);
    }

    .metric-chart {
        margin-top: 12px;
    }

    /* Quick Reports */
    .quick-reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .quick-report-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .quick-report-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(4px);
        border-color: var(--accent);
    }

    .quick-report-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .quick-report-content {
        flex: 1;
        min-width: 0;
    }

    .quick-report-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .quick-report-desc {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .quick-report-arrow {
        color: var(--text-muted);
        font-size: 18px;
        transition: transform 0.2s ease;
    }

    .quick-report-card:hover .quick-report-arrow {
        transform: translateX(4px);
        color: var(--accent);
    }

    /* Reports Section */
    .reports-section {
        margin-bottom: 32px;
    }

    .reports-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .reports-section-title {
        font-size: var(--fs-h2);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Team Performance */
    .team-performance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }

    .team-member-performance {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .team-member-performance:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .performance-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .performance-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
    }

    .performance-name {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 2px 0;
    }

    .performance-role {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin: 0;
    }

    .performance-stats {
        display: grid;
        grid-template-columnsgrid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .performance-stat {
        text-align: center;
        padding: 10px;
        background: var(--bg);
        border-radius: 8px;
    }

    .performance-stat .stat-label {
        display: block;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .performance-stat .stat-value {
        display: block;
        font-size: 20px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
    }

    .performance-bar-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .performance-bar {
        flex: 1;
        height: 8px;
        background: var(--bg);
        border-radius: 4px;
        overflow: hidden;
    }

    .performance-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .performance-percentage {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        white-space: nowrap;
    }

    /* Project Health */
    .project-health-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
    }

    .project-health-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .project-health-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .health-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        gap: 12px;
    }

    .health-project-name {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .health-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .health-badge i {
        font-size: 8px;
    }

    .health-excellent {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .health-good {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .health-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .health-critical {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .health-metrics {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .health-metric-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .health-progress {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .health-progress-bar {
        flex: 1;
        height: 8px;
        background: var(--bg);
        border-radius: 4px;
        overflow: hidden;
    }

    .health-progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .health-progress-text {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        white-space: nowrap;
    }

    .health-indicators {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    .health-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .health-indicator i {
        font-size: var(--ic-sm);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .reports-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .reports-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .quick-reports-grid {
            grid-template-columns: 1fr;
        }

        .team-performance-grid {
            grid-template-columns: 1fr;
        }

        .project-health-grid {
            grid-template-columns: 1fr;
        }

        .metric-value {
            font-size: 28px;
        }
    }
</style>

<script>
    // ===================================== 
    // REPORTS PAGE FUNCTIONALITY
    // ===================================== 

    function exportReport() {
        console.log('Export Report');
        alert('Export Report - Coming Soon!');
    }

    function viewFullReport(type) {
        console.log('View full report:', type);
        alert(`Full ${type} Report - Coming Soon!`);
    }

    console.log('✅ Reports Dashboard Initialized');
</script>

@endsection