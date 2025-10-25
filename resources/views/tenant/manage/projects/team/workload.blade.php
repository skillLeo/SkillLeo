 
{{-- resources/views/tenant/projects/team/workload.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.team.index', $username) }}" class="project-breadcrumb-item">
        Team
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Workload</span>
</div>

<!-- Workload Header -->
<div class="workload-header">
    <div class="workload-header-left">
        <h1 class="workload-title">Team Workload</h1>
        <p class="workload-subtitle">Monitor team capacity and task distribution</p>
    </div>
    <div class="workload-header-right">
        <select class="project-form-control project-select" style="width: auto;">
            <option value="week">This Week</option>
            <option value="month" selected>This Month</option>
            <option value="quarter">This Quarter</option>
        </select>
        <button class="project-btn project-btn-secondary" onclick="alert('Export Report')">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>
</div>

<!-- Team Overview Cards -->
<div class="workload-stats-grid">
    <div class="workload-stat-card">
        <div class="workload-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-users"></i>
        </div>
        <div class="workload-stat-content">
            <div class="workload-stat-value">15</div>
            <div class="workload-stat-label">Team Members</div>
        </div>
    </div>

    <div class="workload-stat-card">
        <div class="workload-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="workload-stat-content">
            <div class="workload-stat-value">89</div>
            <div class="workload-stat-label">Active Tasks</div>
        </div>
    </div>

    <div class="workload-stat-card">
        <div class="workload-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div class="workload-stat-content">
            <div class="workload-stat-value">73%</div>
            <div class="workload-stat-label">Avg. Capacity</div>
        </div>
    </div>

    <div class="workload-stat-card">
        <div class="workload-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="workload-stat-content">
            <div class="workload-stat-value">328h</div>
            <div class="workload-stat-label">Total Hours</div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="workload-filters-bar">
    <div class="workload-filters-left">
        <div class="project-search-box" style="max-width: 300px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search team members..." id="workloadSearch">
        </div>

        <select class="project-form-control project-select" style="width: auto;">
            <option value="all">All Projects</option>
            <option value="web">Website Redesign</option>
            <option value="mobile">Mobile App</option>
            <option value="api">API Integration</option>
        </select>

        <select class="project-form-control project-select" style="width: auto;">
            <option value="all">All Roles</option>
            <option value="dev">Developers</option>
            <option value="design">Designers</option>
            <option value="qa">QA Engineers</option>
        </select>
    </div>

    <div class="workload-filters-right">
        <button class="workload-view-btn active">
            <i class="fas fa-chart-bar"></i>
            <span>Chart</span>
        </button>
        <button class="workload-view-btn">
            <i class="fas fa-list"></i>
            <span>List</span>
        </button>
    </div>
</div>

<!-- Capacity Overview Chart -->
<div class="capacity-chart-section">
    <div class="capacity-chart-header">
        <h3 class="capacity-chart-title">Team Capacity Overview</h3>
        <div class="capacity-legend">
            <div class="legend-item">
                <span class="legend-dot" style="background: #10b981;"></span>
                <span>Optimal (60-80%)</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #f59e0b;"></span>
                <span>High (80-100%)</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #ef4444;"></span>
                <span>Overloaded (>100%)</span>
            </div>
        </div>
    </div>

    <div class="capacity-chart-content">
        @for($i = 1; $i <= 10; $i++)
            @php
                $capacity = rand(45, 120);
                $status = $capacity > 100 ? 'overloaded' : ($capacity > 80 ? 'high' : 'optimal');
                $color = ['optimal' => '#10b981', 'high' => '#f59e0b', 'overloaded' => '#ef4444'][$status];
            @endphp
            <div class="capacity-bar-item">
                <div class="capacity-bar-info">
                    <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                         alt="Member" 
                         class="capacity-member-avatar">
                    <div class="capacity-member-details">
                        <div class="capacity-member-name">{{ ['Hassan Mehmood', 'Ali Khan', 'Sara Ahmed', 'Zain Malik', 'Ayesha Raza', 'Hamza Sheikh', 'Fatima Noor', 'Omar Farooq', 'Amina Tariq', 'Bilal Hussain'][$i - 1] }}</div>
                        <div class="capacity-member-role">{{ ['Senior Developer', 'UI/UX Designer', 'QA Engineer', 'Full Stack Dev', 'Product Manager', 'DevOps Engineer', 'Frontend Dev', 'Backend Dev', 'Scrum Master', 'Data Analyst'][$i - 1] }}</div>
                    </div>
                </div>
                <div class="capacity-bar-visual">
                    <div class="capacity-bar-track">
                        <div class="capacity-bar-fill-workload" style="width: {{ min($capacity, 100) }}%; background: {{ $color }};"></div>
                        @if($capacity > 100)
                            <div class="capacity-bar-overflow" style="width: {{ $capacity - 100 }}%;"></div>
                        @endif
                    </div>
                    <div class="capacity-bar-labels">
                        <span class="capacity-percentage" style="color: {{ $color }};">{{ $capacity }}%</span>
                        <span class="capacity-hours">{{ rand(20, 45) }}h / {{ rand(35, 40) }}h</span>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<!-- Team Members Grid -->
<div class="team-workload-grid">
    @for($i = 1; $i <= 6; $i++)
        @php
            $capacity = rand(45, 120);
            $tasksCount = rand(5, 15);
        @endphp
        <div class="team-workload-card">
            <div class="workload-card-header">
                <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                     alt="Member" 
                     class="workload-card-avatar">
                <div class="workload-card-info">
                    <h4 class="workload-card-name">{{ ['Hassan Mehmood', 'Ali Khan', 'Sara Ahmed', 'Zain Malik', 'Ayesha Raza', 'Hamza Sheikh'][$i - 1] }}</h4>
                    <p class="workload-card-role">{{ ['Senior Developer', 'UI/UX Designer', 'QA Engineer', 'Full Stack Dev', 'Product Manager', 'DevOps Engineer'][$i - 1] }}</p>
                </div>
                <button class="workload-card-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <div class="workload-card-capacity">
                <div class="capacity-header-row">
                    <span class="capacity-label">Capacity</span>
                    <span class="capacity-value-display">{{ $capacity }}%</span>
                </div>
                <div class="capacity-bar-mini">
                    <div class="capacity-bar-fill-mini" style="width: {{ min($capacity, 100) }}%; background: {{ $capacity > 100 ? '#ef4444' : ($capacity > 80 ? '#f59e0b' : '#10b981') }};"></div>
                </div>
            </div>

            <div class="workload-card-stats">
                <div class="workload-stat-mini">
                    <i class="fas fa-tasks"></i>
                    <span>{{ $tasksCount }} tasks</span>
                </div>
                <div class="workload-stat-mini">
                    <i class="fas fa-clock"></i>
                    <span>{{ rand(20, 45) }}h this week</span>
                </div>
                <div class="workload-stat-mini">
                    <i class="fas fa-project-diagram"></i>
                    <span>{{ rand(2, 5) }} projects</span>
                </div>
            </div>

            <div class="workload-card-tasks">
                <div class="workload-tasks-header">
                    <span class="workload-tasks-title">Current Tasks</span>
                    <span class="workload-tasks-count">{{ $tasksCount }}</span>
                </div>
                <div class="workload-tasks-preview">
                    @for($j = 1; $j <= 3; $j++)
                        <div class="workload-task-mini">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['task', 'story', 'bug'][$j % 3]])
                            <span class="task-mini-title">{{ ['Implement authentication', 'Design homepage', 'Fix mobile bug'][$j - 1] }}</span>
                        </div>
                    @endfor
                    @if($tasksCount > 3)
                        <button class="view-all-tasks-mini">
                            <span>+{{ $tasksCount - 3 }} more</span>
                        </button>
                    @endif
                </div>
            </div>

            <button class="workload-card-action" onclick="alert('View {{ ['Hassan', 'Ali', 'Sara', 'Zain', 'Ayesha', 'Hamza'][$i - 1] }} Details')">
                <span>View Details</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    @endfor
</div>

<!-- Workload Distribution -->
<div class="workload-distribution-section">
    <div class="distribution-header">
        <h3 class="distribution-title">Workload Distribution by Role</h3>
        <button class="project-btn project-btn-ghost project-btn-sm">
            <i class="fas fa-expand"></i>
            <span>View Details</span>
        </button>
    </div>

    <div class="distribution-grid">
        @foreach(['Developers' => 45, 'Designers' => 25, 'QA Engineers' => 15, 'Project Managers' => 10, 'DevOps' => 5] as $role => $percentage)
            <div class="distribution-card">
                <div class="distribution-role">{{ $role }}</div>
                <div class="distribution-bar">
                    <div class="distribution-bar-fill" style="width: {{ $percentage }}%; background: {{ ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444'][array_search($role, array_keys(['Developers' => 45, 'Designers' => 25, 'QA Engineers' => 15, 'Project Managers' => 10, 'DevOps' => 5]))] }};"></div>
                </div>
                <div class="distribution-stats">
                    <span class="distribution-percentage">{{ $percentage }}%</span>
                    <span class="distribution-count">{{ rand(2, 6) }} members</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    /* ===================================== 
       TEAM WORKLOAD STYLES
    ===================================== */

    /* Workload Header */
    .workload-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .workload-title {
        font-size: 28px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .workload-subtitle {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .workload-header-right {
        display: flex;
        gap: 8px;
    }

    /* Stats Grid */
    .workload-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .workload-stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all 0.2s ease;
    }

    .workload-stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .workload-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .workload-stat-content {
        flex: 1;
    }

    .workload-stat-value {
        font-size: 32px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .workload-stat-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    /* Filters Bar */
    .workload-filters-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .workload-filters-left,
    .workload-filters-right {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .workload-view-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .workload-view-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .workload-view-btn.active {
        background: var(--accent);
        color: var(--btn-text-primary);
        border-color: var(--accent);
    }

    /* Capacity Chart Section */
    .capacity-chart-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 24px;
    }

    .capacity-chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .capacity-chart-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .capacity-legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Capacity Bars */
    .capacity-chart-content {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .capacity-bar-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
    }

    .capacity-bar-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 200px;
    }

    .capacity-member-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .capacity-member-name {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .capacity-member-role {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .capacity-bar-visual {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .capacity-bar-track {
        flex: 1;
        height: 12px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        overflow: visible;
        position: relative;
        display: flex;
    }

    .capacity-bar-fill-workload {
        height: 100%;
        border-radius: 6px;
        transition: width 0.6s ease;
    }

    .capacity-bar-overflow {
        height: 100%;
        background: repeating-linear-gradient(
            45deg,
            #ef4444,
            #ef4444 10px,
            #dc2626 10px,
            #dc2626 20px
        );
        border-radius: 0 6px 6px 0;
    }

    .capacity-bar-labels {
        display: flex;
        gap: 12px;
        white-space: nowrap;
    }

    .capacity-percentage {
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
    }

    .capacity-hours {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Team Workload Grid */
    .team-workload-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .team-workload-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .team-workload-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .workload-card-header {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
    }

    .workload-card-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .workload-card-info {
        flex: 1;
        min-width: 0;
    }

    .workload-card-name {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .workload-card-role {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .workload-card-menu {
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

    .workload-card-menu:hover {
        background: var(--bg);
    }

    .workload-card-capacity {
        margin-bottom: 16px;
    }

    .capacity-header-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .capacity-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .capacity-value-display {
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
    }

    .capacity-bar-mini {
        height: 8px;
        background: var(--bg);
        border-radius: 4px;
        overflow: hidden;
    }

    .capacity-bar-fill-mini {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .workload-card-stats {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .workload-stat-mini {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .workload-stat-mini i {
        width: 16px;
        color: var(--text-muted);
    }

    .workload-card-tasks {
        margin-bottom: 16px;
    }

    .workload-tasks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .workload-tasks-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .workload-tasks-count {
        padding: 2px 8px;
        background: var(--bg);
        border-radius: 10px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
        color: var(--text-muted);
    }

    .workload-tasks-preview {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .workload-task-mini {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .task-mini-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .view-all-tasks-mini {
        width: 100%;
        padding: 6px;
        background: none;
        border: 1px dashed var(--border);
        border-radius: 6px;
        color: var(--text-muted);
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .view-all-tasks-mini:hover {
        background: var(--bg);
        border-color: var(--accent);
        color: var(--accent);
    }

    .workload-card-action {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        background: var(--accent);
        color: var(--btn-text-primary);
        border: none;
        border-radius: 8px;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .workload-card-action:hover {
        background: var(--accent-dark);
    }

    /* Distribution Section */
    .workload-distribution-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
    }

    .distribution-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .distribution-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .distribution-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .distribution-card {
        padding: 16px;
        background: var(--bg);
        border-radius: 8px;
    }

    .distribution-role {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin-bottom: 8px;
    }

    .distribution-bar {
        height: 8px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .distribution-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .distribution-stats {
        display: flex;
        justify-content: space-between;
        font-size: var(--fs-body);
    }.distribution-percentage {
    font-weight: var(--fw-bold);
    color: var(--text-heading);
}

.distribution-count {
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 1200px) {
    .workload-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .capacity-bar-info {
        min-width: 150px;
    }
}

@media (max-width: 768px) {
    .workload-header {
        flex-direction: column;
        align-items: stretch;
    }

    .workload-stats-grid {
        grid-template-columns: 1fr;
    }

    .workload-filters-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .capacity-chart-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .capacity-bar-item {
        flex-direction: column;
        align-items: stretch;
    }

    .capacity-bar-info {
        min-width: 100%;
    }

    .team-workload-grid {
        grid-template-columns: 1fr;
    }
}</style>
<script>
    console.log('✅ Team Workload Initialized');
</script>
@endsection