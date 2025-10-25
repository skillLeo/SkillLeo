{{-- resources/views/tenant/projects/dashboard.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Page Header -->
<div class="dashboard-header">
    <div class="dashboard-header-left">
        <h1 class="dashboard-title">Project Dashboard</h1>
        <p class="dashboard-subtitle">Overview of all your projects and work</p>
    </div>
    <div class="dashboard-header-right">
        <button class="project-btn project-btn-secondary" onclick="window.location.href='{{ route('tenant.manage.projects.list', $username) }}'">
            <i class="fas fa-th"></i>
            <span>All Projects</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="alert('Create Project - Coming Soon!')">
            <i class="fas fa-plus"></i>
            <span>New Project</span>
        </button>
    </div>
</div>

<!-- Quick Stats -->
<div class="dashboard-stats-grid">
    <div class="dashboard-stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-project-diagram"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">12</div>
            <div class="stat-label">Active Projects</div>
        </div>
        <div class="stat-trend">
            <i class="fas fa-arrow-up" style="color: #10b981;"></i>
            <span style="color: #10b981;">+2 this week</span>
        </div>
    </div>

    <div class="dashboard-stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">89</div>
            <div class="stat-label">Open Tasks</div>
        </div>
        <div class="stat-trend">
            <i class="fas fa-minus" style="color: #6b7280;"></i>
            <span style="color: #6b7280;">No change</span>
        </div>
    </div>

    <div class="dashboard-stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">18</div>
            <div class="stat-label">Due This Week</div>
        </div>
        <div class="stat-trend">
            <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
            <span style="color: #f59e0b;">Needs attention</span>
        </div>
    </div>

    <div class="dashboard-stat-card">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">15</div>
            <div class="stat-label">Team Members</div>
        </div>
        <div class="stat-trend">
            <i class="fas fa-check" style="color: #10b981;"></i>
            <span style="color: #10b981;">All active</span>
        </div>
    </div>
</div>

<!-- Active Sprint Section -->
<div class="dashboard-section">
    <div class="dashboard-section-header">
        <div>
            <h2 class="section-title">Active Sprint</h2>
            <p class="section-subtitle">Current sprint progress and status</p>
        </div>
        <button class="project-btn project-btn-ghost" onclick="window.location.href='{{ route('tenant.manage.projects.sprints.active', $username) }}'">
            <span>View Sprint Board</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <div class="active-sprint-dashboard">
        <div class="sprint-info-bar">
            <div class="sprint-info-item">
                <i class="fas fa-flag"></i>
                <div>
                    <div class="sprint-label">Sprint 5</div>
                    <div class="sprint-meta">Jan 15 - Jan 29</div>
                </div>
            </div>
            <div class="sprint-info-item">
                <i class="fas fa-calendar"></i>
                <div>
                    <div class="sprint-label">5 Days Left</div>
                    <div class="sprint-meta">Ends Jan 29</div>
                </div>
            </div>
            <div class="sprint-info-item">
                <i class="fas fa-chart-line"></i>
                <div>
                    <div class="sprint-label">52 Points</div>
                    <div class="sprint-meta">34 completed</div>
                </div>
            </div>
        </div>

        <div class="sprint-progress-dashboard">
            <div class="progress-label-row">
                <span>Sprint Progress</span>
                <span class="progress-percentage">65%</span>
            </div>
            <div class="progress-bar-large">
                <div class="progress-fill-large" style="width: 65%;"></div>
            </div>
            <div class="progress-breakdown">
                <div class="breakdown-item">
                    <span class="breakdown-dot" style="background: #10b981;"></span>
                    <span>Done: 22</span>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-dot" style="background: #3b82f6;"></span>
                    <span>In Progress: 8</span>
                </div>
                <div class="breakdown-item">
                    <span class="breakdown-dot" style="background: #6b7280;"></span>
                    <span>To Do: 4</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Projects -->
<div class="dashboard-section">
    <div class="dashboard-section-header">
        <div>
            <h2 class="section-title">Recent Projects</h2>
            <p class="section-subtitle">Your most recently updated projects</p>
        </div>
        <button class="project-btn project-btn-ghost" onclick="window.location.href='{{ route('tenant.manage.projects.list', $username) }}'">
            <span>View All</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <div class="projects-grid-dashboard">
        @for($i = 1; $i <= 6; $i++)
            @php
                $progress = rand(40, 95);
                $health = $progress > 80 ? 'good' : ($progress > 60 ? 'warning' : 'critical');
            @endphp
            <div class="project-card-dashboard">
                <div class="project-card-header-dashboard">
                    <div class="project-avatar-dash" style="background: linear-gradient(135deg, {{ ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#ffd500'][$i % 6] }} 0%, {{ ['#764ba2', '#f5576c', '#00f2fe', '#4facfe', '#f5576c', '#ff6b6b'][$i % 6] }} 100%);">
                        <span>{{ ['WR', 'MA', 'AI', 'CR', 'DB', 'PM'][$i - 1] }}</span>
                    </div>
                    <button class="project-menu-btn-dash">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>

                <h3 class="project-name-dash">{{ ['Website Redesign', 'Mobile App', 'API Integration', 'CRM System', 'Database Migration', 'Project Manager'][$i - 1] }}</h3>
                <p class="project-desc-dash">{{ ['Modern UI/UX redesign', 'iOS & Android app', 'REST API development', 'Custom CRM solution', 'Database optimization', 'Team management tool'][$i - 1] }}</p>

                <div class="project-progress-dash">
                    <div class="progress-header-dash">
                        <span class="progress-label-dash">Progress</span>
                        <span class="progress-value-dash">{{ $progress }}%</span>
                    </div>
                    <div class="progress-bar-dash">
                        <div class="progress-fill-dash" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>

                <div class="project-meta-dash">
                    <div class="meta-item-dash">
                        <i class="fas fa-tasks"></i>
                        <span>{{ rand(10, 50) }}/{{ rand(50, 100) }}</span>
                    </div>
                    <div class="meta-item-dash">
                        <i class="fas fa-users"></i>
                        <span>{{ rand(3, 8) }}</span>
                    </div>
                    <div class="meta-item-dash">
                        <i class="fas fa-calendar"></i>
                        <span>{{ rand(1, 30) }}d left</span>
                    </div>
                </div>

                <div class="project-footer-dash">
                    <span class="health-badge health-{{ $health }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($health) }}
                    </span>
                    <button class="btn-view-project" onclick="window.location.href='#'">
                        View <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        @endfor
    </div>
</div>

<!-- My Tasks -->
<div class="dashboard-section">
    <div class="dashboard-section-header">
        <div>
            <h2 class="section-title">My Tasks</h2>
            <p class="section-subtitle">Tasks assigned to you</p>
        </div>
        <button class="project-btn project-btn-ghost" onclick="window.location.href='{{ route('tenant.manage.projects.issues.index', $username) }}'">
            <span>View All Issues</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <div class="tasks-list-dashboard">
        @for($i = 1; $i <= 5; $i++)
            @php
                $priorities = ['highest', 'high', 'medium', 'low', 'lowest'];
                $statuses = ['todo', 'progress', 'review'];
                $priority = $priorities[$i % 5];
                $status = $statuses[$i % 3];
            @endphp
            <div class="task-item-dashboard">
                <div class="task-check">
                    <input type="checkbox">
                </div>
                <div class="task-content">
                    <div class="task-header-dash">
                        @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['story', 'task', 'bug'][$i % 3]])
                        <span class="task-key">PROJ-{{ 100 + $i }}</span>
                        @include('tenant.manage.projects.components.priority-icon', ['priority' => $priority])
                    </div>
                    <h4 class="task-title-dash">{{ ['Implement user authentication', 'Design homepage layout', 'Fix mobile responsive bug', 'Update database schema', 'Create API documentation'][$i - 1] }}</h4>
                    <div class="task-meta-dash">
                        <span class="status-badge status-{{ $status }}">
                            {{ ['To Do', 'In Progress', 'In Review'][$i % 3] }}
                        </span>
                        <span class="task-project">Website Redesign</span>
                        <span class="task-due">Due in {{ rand(1, 7) }} days</span>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<style>
    /* ===================================== 
       DASHBOARD STYLES
    ===================================== */

    .dashboard-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 32px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .dashboard-title {
        font-size: 32px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
        letter-spacing: -0.02em;
    }

    .dashboard-subtitle {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .dashboard-header-right {
        display: flex;
        gap: 8px;
    }

    /* Stats Grid */
    .dashboard-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .dashboard-stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        transition: all 0.2s ease;
    }

    .dashboard-stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 36px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
    }

    /* Section */
    .dashboard-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 24px;
    }

    .dashboard-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 16px;
    }

    .section-title {
        font-size: var(--fs-h2);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .section-subtitle {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin: 0;
    }

    /* Active Sprint Dashboard */
    .active-sprint-dashboard {
        background: var(--bg);
        border-radius: 8px;
        padding: 20px;
    }

    .sprint-info-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .sprint-info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--card);
        border-radius: 8px;
    }

    .sprint-info-item i {
        font-size: 24px;
        color: var(--accent);
    }

    .sprint-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .sprint-meta {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .sprint-progress-dashboard {
        background: var(--card);
        padding: 20px;
        border-radius: 8px;
    }

    .progress-label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-weight: var(--fw-semibold);
    }

    .progress-percentage {
        font-size: 20px;
        color: var(--accent);
    }

    .progress-bar-large {
        height: 12px;
        background: var(--bg);
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .progress-fill-large {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 6px;
        transition: width 0.6s ease;
    }

    .progress-breakdown {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .breakdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .breakdown-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Projects Grid */
    .projects-grid-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .project-card-dashboard {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 20px;
        transition: all 0.2s ease;
    }

    .project-card-dashboard:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .project-card-header-dashboard {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .project-avatar-dash {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: var(--fw-bold);
        color: white;
    }

    .project-menu-btn-dash {
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

    .project-menu-btn-dash:hover {
        background: var(--card);
    }

    .project-name-dash {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 8px 0;
    }

    .project-desc-dash {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0 0 16px 0;
    }

    .project-progress-dash {
        margin-bottom: 16px;
    }

    .progress-header-dash {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .progress-label-dash {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .progress-value-dash {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
    }

    .progress-bar-dash {
        height: 8px;
        background: var(--card);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill-dash {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .project-meta-dash {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }

    .meta-item-dash {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .project-footer-dash {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    .health-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
    }

    .health-badge i {
        font-size: 8px;
    }

    .health-good {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .health-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .health-critical {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .btn-view-project {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--accent);
        color: var(--btn-text-primary);
        border: none;
        border-radius: 6px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-view-project:hover {
        background: var(--accent-dark);
    }

    /* Tasks List */
    .tasks-list-dashboard {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .task-item-dashboard {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: var(--bg);
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .task-item-dashboard:hover {
        background: var(--card);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .task-check input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .task-content {
        flex: 1;
    }

    .task-header-dash {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .task-key {
        font-family: monospace;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--accent);
    }

    .task-title-dash {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-heading);
        margin: 0 0 8px 0;
    }

    .task-meta-dash {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .task-project {
        font-weight: var(--fw-medium);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: stretch;
        }

        .dashboard-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .projects-grid-dashboard {
            grid-template-columns: 1fr;
        }

        .sprint-info-bar {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection