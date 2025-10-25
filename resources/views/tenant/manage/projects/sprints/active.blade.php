{{-- resources/views/tenant/projects/sprints/active.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.sprints.index', $username) }}" class="project-breadcrumb-item">
        Sprints
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Sprint 5</span>
</div>

<!-- Sprint Header -->
<div class="sprint-board-header">
    <div class="sprint-board-header-left">
        <div class="sprint-title-group">
            <h1 class="sprint-board-title">Sprint 5</h1>
            <span class="sprint-badge sprint-badge-active">
                <i class="fas fa-play-circle"></i>
                Active
            </span>
        </div>
        <p class="sprint-board-subtitle">Jan 15 - Jan 29, 2025 • 5 days remaining</p>
    </div>
    <div class="sprint-board-header-right">
        <button class="project-btn project-btn-secondary" onclick="alert('Sprint Settings')">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="alert('Complete Sprint')">
            <i class="fas fa-check-circle"></i>
            <span>Complete Sprint</span>
        </button>
    </div>
</div>

<!-- Sprint Stats Bar -->
<div class="sprint-stats-bar">
    <div class="sprint-stat-item">
        <div class="sprint-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">21</div>
            <div class="sprint-stat-label">Done</div>
        </div>
    </div>

    <div class="sprint-stat-item">
        <div class="sprint-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-play-circle"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">8</div>
            <div class="sprint-stat-label">In Progress</div>
        </div>
    </div>

    <div class="sprint-stat-item">
        <div class="sprint-stat-icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <i class="fas fa-circle"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">5</div>
            <div class="sprint-stat-label">To Do</div>
        </div>
    </div>

    <div class="sprint-stat-item">
        <div class="sprint-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">52</div>
            <div class="sprint-stat-label">Story Points</div>
        </div>
    </div>

    <div class="sprint-progress-inline">
        <div class="progress-label-inline">Progress</div>
        <div class="progress-bar-inline">
            <div class="progress-fill-inline" style="width: 62%;"></div>
        </div>
        <div class="progress-percentage-inline">62%</div>
    </div>
</div>

<!-- Board Toolbar -->
<div class="board-toolbar">
    <div class="board-toolbar-left">
        <div class="project-search-box" style="max-width: 300px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search issues..." id="boardSearch">
        </div>

        <button class="board-filter-btn">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
        </button>

        <button class="board-group-btn">
            <i class="fas fa-users"></i>
            <span>Group by: None</span>
        </button>
    </div>

    <div class="board-toolbar-right">
        <button class="board-view-btn active">
            <i class="fas fa-th"></i>
            <span>Board</span>
        </button>
        <button class="board-view-btn" onclick="window.location.href='{{ route('tenant.manage.projects.issues.index', $username) }}'">
            <i class="fas fa-list"></i>
            <span>List</span>
        </button>
        <button class="board-view-btn" onclick="alert('Reports')">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </button>
    </div>
</div>

<!-- Kanban Board -->
<div class="kanban-board">
    <!-- To Do Column -->
    <div class="kanban-column">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <span class="kanban-column-name">To Do</span>
                <span class="kanban-column-count">5</span>
            </div>
            <button class="kanban-column-menu">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>

        <div class="kanban-column-content">
            @for($i = 1; $i <= 5; $i++)
                <div class="kanban-card" draggable="true">
                    <div class="kanban-card-header">
                        <div class="kanban-card-type">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['story', 'task', 'bug'][$i % 3]])
                        </div>
                        <button class="kanban-card-menu">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>

                    <h4 class="kanban-card-title">{{ ['Setup authentication flow', 'Design user dashboard', 'Fix header alignment', 'Create API endpoints', 'Write test cases'][$i - 1] }}</h4>

                    <div class="kanban-card-meta">
                        <span class="kanban-card-key">PROJ-{{ 100 + $i }}</span>
                        @include('tenant.manage.projects.components.priority-icon', ['priority' => ['high', 'medium', 'low', 'highest', 'lowest'][$i % 5]])
                    </div>

                    <div class="kanban-card-footer">
                        <div class="kanban-card-labels">
                            @if($i % 2 === 0)
                                <span class="kanban-label" style="background: #3b82f6;">Frontend</span>
                            @endif
                            @if($i % 3 === 0)
                                <span class="kanban-label" style="background: #10b981;">Backend</span>
                            @endif
                        </div>
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="Assignee" 
                             class="kanban-card-avatar">
                    </div>
                </div>
            @endfor
        </div>

        <button class="kanban-add-card">
            <i class="fas fa-plus"></i>
            <span>Create issue</span>
        </button>
    </div>

    <!-- In Progress Column -->
    <div class="kanban-column">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <span class="kanban-column-name">In Progress</span>
                <span class="kanban-column-count">8</span>
            </div>
            <button class="kanban-column-menu">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>

        <div class="kanban-column-content">
            @for($i = 6; $i <= 13; $i++)
                <div class="kanban-card" draggable="true">
                    <div class="kanban-card-header">
                        <div class="kanban-card-type">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['task', 'story', 'bug'][$i % 3]])
                        </div>
                        <button class="kanban-card-menu">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>

                    <h4 class="kanban-card-title">{{ ['Implement login page', 'Build registration form', 'Add password validation', 'Create user profile', 'Setup email service', 'Integrate payment gateway', 'Add error handling', 'Optimize database queries'][$i - 6] }}</h4>

                    <div class="kanban-card-meta">
                        <span class="kanban-card-key">PROJ-{{ 100 + $i }}</span>
                        @include('tenant.manage.projects.components.priority-icon', ['priority' => ['medium', 'high', 'low', 'highest', 'medium'][$i % 5]])
                    </div>

                    <div class="kanban-card-progress">
                        <div class="card-progress-bar">
                            <div class="card-progress-fill" style="width: {{ rand(30, 80) }}%;"></div>
                        </div>
                        <span class="card-progress-text">{{ rand(30, 80) }}%</span>
                    </div>

                    <div class="kanban-card-footer">
                        <div class="kanban-card-labels">
                            @if($i % 2 === 0)
                                <span class="kanban-label" style="background: #8b5cf6;">UI</span>
                            @endif
                        </div>
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="Assignee" 
                             class="kanban-card-avatar">
                    </div>
                </div>
            @endfor
        </div>

        <button class="kanban-add-card">
            <i class="fas fa-plus"></i>
            <span>Create issue</span>
        </button>
    </div>

    <!-- In Review Column -->
    <div class="kanban-column">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <span class="kanban-column-name">In Review</span>
                <span class="kanban-column-count">3</span>
            </div>
            <button class="kanban-column-menu">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>

        <div class="kanban-column-content">
            @for($i = 14; $i <= 16; $i++)
                <div class="kanban-card" draggable="true">
                    <div class="kanban-card-header">
                        <div class="kanban-card-type">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['story', 'task', 'bug'][$i % 3]])
                        </div>
                        <button class="kanban-card-menu">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>

                    <h4 class="kanban-card-title">{{ ['Complete user settings page', 'Add notification system', 'Fix mobile responsive issues'][$i - 14] }}</h4>

                    <div class="kanban-card-meta">
                        <span class="kanban-card-key">PROJ-{{ 100 + $i }}</span>
                        @include('tenant.manage.projects.components.priority-icon', ['priority' => ['high', 'medium', 'highest'][$i % 3]])
                    </div>

                    <div class="kanban-card-footer">
                        <div class="kanban-card-labels">
                            <span class="kanban-label" style="background: #f59e0b;">Review</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="Assignee" 
                             class="kanban-card-avatar">
                    </div>
                </div>
            @endfor
        </div>

        <button class="kanban-add-card">
            <i class="fas fa-plus"></i>
            <span>Create issue</span>
        </button>
    </div>

    <!-- Done Column -->
    <div class="kanban-column kanban-column-done">
        <div class="kanban-column-header">
            <div class="kanban-column-title">
                <span class="kanban-column-name">Done</span>
                <span class="kanban-column-count">21</span>
            </div>
            <button class="kanban-column-menu">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>

        <div class="kanban-column-content">
            @for($i = 17; $i <= 22; $i++)
                <div class="kanban-card kanban-card-done" draggable="true">
                    <div class="kanban-card-header">
                        <div class="kanban-card-type">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['task', 'story', 'bug'][$i % 3]])
                        </div>
                        <div class="kanban-card-check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>

                    <h4 class="kanban-card-title">{{ ['Setup project structure', 'Create database schema', 'Design homepage mockup', 'Write API documentation', 'Setup CI/CD pipeline', 'Configure environment'][$i - 17] }}</h4>

                    <div class="kanban-card-meta">
                        <span class="kanban-card-key">PROJ-{{ 100 + $i }}</span>
                        @include('tenant.manage.projects.components.priority-icon', ['priority' => ['medium', 'high', 'low', 'medium', 'low', 'high'][$i % 6]])
                    </div>

                    <div class="kanban-card-footer">
                        <div class="kanban-card-labels">
                            <span class="kanban-label" style="background: #10b981;">Done</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="Assignee" 
                             class="kanban-card-avatar">
                    </div>
                </div>
            @endfor
        </div>

        <button class="kanban-add-card">
            <i class="fas fa-plus"></i>
            <span>Create issue</span>
        </button>
    </div>
</div>

<style>
    /* ===================================== 
       ACTIVE SPRINT BOARD STYLES
    ===================================== */

    /* Sprint Board Header */
    .sprint-board-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .sprint-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .sprint-board-title {
        font-size: 28px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0;
    }

    .sprint-badge-active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
    }

    .sprint-board-subtitle {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .sprint-board-header-right {
        display: flex;
        gap: 8px;
    }

    /* Sprint Stats Bar */
    .sprint-stats-bar {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 24px;
        flex-wrap: wrap;
        align-items: center;
    }

    .sprint-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sprint-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .sprint-stat-content {
        display: flex;
        flex-direction: column;
    }

    .sprint-stat-value {
        font-size: 24px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
    }

    .sprint-stat-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .sprint-progress-inline {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
        min-width: 300px;
    }

    .progress-label-inline {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-body);
        white-space: nowrap;
    }

    .progress-bar-inline {
        flex: 1;
        height: 8px;
        background: var(--bg);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill-inline {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .progress-percentage-inline {
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        white-space: nowrap;
    }

    /* Board Toolbar */
    .board-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .board-toolbar-left,
    .board-toolbar-right {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .board-filter-btn,
    .board-group-btn,
    .board-view-btn {
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

    .board-filter-btn:hover,
    .board-group-btn:hover,
    .board-view-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .board-view-btn.active {
        background: var(--accent);
        color: var(--btn-text-primary);
        border-color: var(--accent);
    }

    /* Kanban Board */
    .kanban-board {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 20px;
    }

    .kanban-column {
        flex: 0 0 320px;
        background: var(--bg);
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 400px);
    }

    .kanban-column-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid var(--border);
    }

    .kanban-column-title {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kanban-column-name {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .kanban-column-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 20px;
        padding: 0 6px;
        background: var(--card);
        border-radius: 10px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-bold);
        color: var(--text-muted);
    }

    .kanban-column-menu {
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

    .kanban-column-menu:hover {
        background: var(--card);
        color: var(--text-body);
    }

    .kanban-column-content {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Kanban Card */
    .kanban-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .kanban-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .kanban-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .kanban-card-type {
        display: flex;
    }

    .kanban-card-menu {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.15s ease;
        opacity: 0;
    }

    .kanban-card:hover .kanban-card-menu {
        opacity: 1;
    }

    .kanban-card-menu:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    .kanban-card-check {
        color: #10b981;
        font-size: 18px;
    }

    .kanban-card-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-heading);
        margin: 0 0 8px 0;
        line-height: 1.4;
    }

    .kanban-card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .kanban-card-key {
        font-family: monospace;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
    }

    .kanban-card-progress {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .card-progress-bar {
        flex: 1;
        height: 4px;
        background: var(--bg);
        border-radius: 2px;
        overflow: hidden;
    }

    .card-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 2px;
        transition: width 0.6s ease;
    }

    .card-progress-text {
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
    }

    .kanban-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .kanban-card-labels {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .kanban-label {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        color: white;
    }

    .kanban-card-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .kanban-card-done {
        opacity: 0.7;
    }

    .kanban-card-done .kanban-card-title {
        text-decoration: line-through;
    }

    .kanban-add-card {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        margin: 12px;
        background: none;
        border: 1px dashed var(--border);
        border-radius: 8px;
        color: var(--text-muted);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .kanban-add-card:hover {
        background: var(--card);
        border-color: var(--accent);
        color: var(--accent);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sprint-board-header {
            flex-direction: column;
            align-items: stretch;
        }

        .sprint-stats-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .sprint-progress-inline {
            margin-left: 0;
            min-width: 100%;
        }

        .board-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .kanban-board {
            flex-direction: column;
        }

        .kanban-column {
            flex: 1;
            max-height: none;
        }
    }
</style>

<script>
    // ===================================== 
    // KANBAN BOARD FUNCTIONALITY
    // ===================================== 

    // Drag and Drop
    document.querySelectorAll('.kanban-card').forEach(card => {
        card.addEventListener('dragstart', function(e) {
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            this.style.opacity = '0.4';
        });

        card.addEventListener('dragend', function() {
            this.style.opacity = '1';
        });
    });

    document.querySelectorAll('.kanban-column-content').forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.style.background = 'rgba(59, 130, 246, 0.05)';
        });

        column.addEventListener('dragleave', function() {
            this.style.background = '';
        });

        column.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '';
            console.log('Card moved to column');
            // Here you would update the backend
        });
    });

    console.log('✅ Active Sprint Board Initialized');
</script>

@endsection