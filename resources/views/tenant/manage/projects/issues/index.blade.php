{{-- resources/views/tenant/projects/issues/index.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">All Issues</span>
</div>

<!-- Page Header -->
<div class="issues-page-header">
    <div class="issues-header-left">
        <h1 class="project-page-title">All Issues</h1>
        <p class="project-page-subtitle">Track and manage all tasks, bugs, and stories across projects</p>
    </div>
    <div class="issues-header-right">
        <button class="project-btn project-btn-secondary" onclick="exportIssues()">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="openCreateIssueModal()">
            <i class="fas fa-plus"></i>
            <span>Create Issue</span>
        </button>
    </div>
</div>

<!-- Filters & Views Bar -->
<div class="issues-toolbar">
    <div class="issues-toolbar-left">
        <!-- Search -->
        <div class="project-search-box" style="max-width: 350px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search issues by ID, title, or description..." id="issuesSearch">
        </div>

        <!-- Quick Filters -->
        <div class="quick-filters">
            <button class="quick-filter-btn active" data-filter="all">
                All Issues
                <span class="filter-count">156</span>
            </button>
            <button class="quick-filter-btn" data-filter="my">
                My Issues
                <span class="filter-count">24</span>
            </button>
            <button class="quick-filter-btn" data-filter="open">
                Open
                <span class="filter-count">89</span>
            </button>
            <button class="quick-filter-btn" data-filter="resolved">
                Resolved
                <span class="filter-count">67</span>
            </button>
        </div>
    </div>

    <div class="issues-toolbar-right">
        <!-- Advanced Filters -->
        <button class="project-btn project-btn-ghost" onclick="toggleAdvancedFilters()">
            <i class="fas fa-sliders-h"></i>
            <span>Filters</span>
        </button>

        <!-- View Switcher -->
        <div class="view-switcher">
            <button class="view-btn active" data-view="list" title="List View">
                <i class="fas fa-list"></i>
            </button>
            <button class="view-btn" data-view="board" title="Board View">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="calendar" title="Calendar View">
                <i class="fas fa-calendar"></i>
            </button>
        </div>

        <!-- Sort Dropdown -->
        <select class="project-form-control project-select" style="width: auto; min-width: 160px;">
            <option value="recent">Recently Updated</option>
            <option value="created">Recently Created</option>
            <option value="priority">Priority (High to Low)</option>
            <option value="due-date">Due Date</option>
            <option value="assignee">Assignee</option>
        </select>
    </div>
</div>

<!-- Advanced Filters Panel (Collapsible) -->
<div class="advanced-filters-panel" id="advancedFiltersPanel">
    <div class="advanced-filters-grid">
        <div class="filter-group">
            <label class="filter-label">Project</label>
            <select class="project-form-control project-select">
                <option value="">All Projects</option>
                <option value="proj-1">Website Redesign</option>
                <option value="proj-2">Mobile App</option>
                <option value="proj-3">API Integration</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Type</label>
            <div class="filter-checkboxes">
                <label class="filter-checkbox">
                    <input type="checkbox" checked>
                    <span><i class="fas fa-bookmark" style="color: #10b981;"></i> Story</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" checked>
                    <span><i class="fas fa-check-square" style="color: #3b82f6;"></i> Task</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" checked>
                    <span><i class="fas fa-bug" style="color: #ef4444;"></i> Bug</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" checked>
                    <span><i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Spike</span>
                </label>
            </div>
        </div>

        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select class="project-form-control project-select">
                <option value="">All Statuses</option>
                <option value="todo">To Do</option>
                <option value="progress">In Progress</option>
                <option value="review">In Review</option>
                <option value="done">Done</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Priority</label>
            <select class="project-form-control project-select">
                <option value="">All Priorities</option>
                <option value="highest">Highest</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
                <option value="lowest">Lowest</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Assignee</label>
            <select class="project-form-control project-select">
                <option value="">All Assignees</option>
                <option value="me">Assigned to me</option>
                <option value="unassigned">Unassigned</option>
                <option value="user-1">Hassan Mehmood</option>
                <option value="user-2">Ali Khan</option>
                <option value="user-3">Sara Ahmed</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Sprint</label>
            <select class="project-form-control project-select">
                <option value="">All Sprints</option>
                <option value="current">Current Sprint</option>
                <option value="sprint-5">Sprint 5</option>
                <option value="sprint-4">Sprint 4</option>
                <option value="backlog">Backlog</option>
            </select>
        </div>
    </div>

    <div class="advanced-filters-actions">
        <button class="project-btn project-btn-ghost" onclick="clearFilters()">
            <i class="fas fa-times"></i>
            <span>Clear All</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="applyFilters()">
            <i class="fas fa-check"></i>
            <span>Apply Filters</span>
        </button>
    </div>
</div>

<!-- Issues Stats Cards -->
<div class="issues-stats-grid">
    <div class="issues-stat-card">
        <div class="issues-stat-header">
            <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
            <span class="issues-stat-label">Critical</span>
        </div>
        <div class="issues-stat-value">12</div>
        <div class="issues-stat-change">
            <i class="fas fa-arrow-up"></i>
            <span class="stat-increase">+3 this week</span>
        </div>
    </div>

    <div class="issues-stat-card">
        <div class="issues-stat-header">
            <i class="fas fa-play-circle" style="color: #3b82f6;"></i>
            <span class="issues-stat-label">In Progress</span>
        </div>
        <div class="issues-stat-value">34</div>
        <div class="issues-stat-change">
            <i class="fas fa-minus"></i>
            <span class="stat-neutral">No change</span>
        </div>
    </div>

    <div class="issues-stat-card">
        <div class="issues-stat-header">
            <i class="fas fa-clock" style="color: #f59e0b;"></i>
            <span class="issues-stat-label">Due Soon</span>
        </div>
        <div class="issues-stat-value">18</div>
        <div class="issues-stat-change">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="stat-warning">Within 3 days</span>
        </div>
    </div>

    <div class="issues-stat-card">
        <div class="issues-stat-header">
            <i class="fas fa-check-circle" style="color: #10b981;"></i>
            <span class="issues-stat-label">Resolved</span>
        </div>
        <div class="issues-stat-value">92</div>
        <div class="issues-stat-change">
            <i class="fas fa-arrow-up"></i>
            <span class="stat-increase">+15 this week</span>
        </div>
    </div>
</div>

<!-- Issues Table -->
<div class="issues-table-container">
    <table class="issues-table">
        <thead>
            <tr>
                <th class="th-checkbox">
                    <input type="checkbox" id="selectAll">
                </th>
                <th class="th-type">Type</th>
                <th class="th-key">Key</th>
                <th class="th-summary">Summary</th>
                <th class="th-priority">Priority</th>
                <th class="th-status">Status</th>
                <th class="th-assignee">Assignee</th>
                <th class="th-reporter">Reporter</th>
                <th class="th-created">Created</th>
                <th class="th-updated">Updated</th>
                <th class="th-actions"></th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 20; $i++)
                @php
                    $types = ['story', 'task', 'bug', 'spike'];
                    $priorities = ['highest', 'high', 'medium', 'low', 'lowest'];
                    $statuses = ['todo', 'progress', 'review', 'done'];
                    $type = $types[$i % 4];
                    $priority = $priorities[$i % 5];
                    $status = $statuses[$i % 4];
                @endphp
                <tr class="issue-row {{ $status === 'done' ? 'issue-row-done' : '' }}" onclick="openIssueDetail('PROJ-{{ 100 + $i }}')">
                    <td class="td-checkbox" onclick="event.stopPropagation()">
                        <input type="checkbox">
                    </td>
                    <td class="td-type">
                        @include('tenant.manage.projects.components.issue-type-icon', ['type' => $type])
                    </td>
                    <td class="td-key">
                        <span class="issue-key-link">PROJ-{{ 100 + $i }}</span>
                    </td>
                    <td class="td-summary">
                        <span class="issue-summary">{{ ['Implement user authentication', 'Design homepage layout', 'Fix mobile responsive bug', 'Research payment gateway options', 'Update database schema', 'Create API documentation', 'Optimize image loading', 'Add email notifications', 'Setup CI/CD pipeline', 'Write unit tests', 'Refactor legacy code', 'Improve error handling', 'Add logging system', 'Create admin panel', 'Integrate analytics', 'Setup monitoring', 'Performance optimization', 'Security audit', 'Code review', 'Deploy to production'][$i - 1] }}</span>
                    </td>
                    <td class="td-priority">
                        @include('tenant.manage.projects.components.priority-icon', ['priority' => $priority])
                    </td>
                    <td class="td-status">
                        <span class="status-badge status-{{ $status }}">
                            {{ ['todo' => 'To Do', 'progress' => 'In Progress', 'review' => 'In Review', 'done' => 'Done'][$status] }}
                        </span>
                    </td>
                    <td class="td-assignee">
                        @if($i % 4 !== 0)
                            <div class="assignee-avatar" title="{{ ['Hassan M', 'Ali K', 'Sara A'][$i % 3] }}">
                                <img src="https://ui-avatars.com/api/?name={{ ['Hassan+M', 'Ali+K', 'Sara+A'][$i % 3] }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" alt="Avatar">
                            </div>
                        @else
                            <button class="assign-btn-small" onclick="event.stopPropagation();" title="Assign">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        @endif
                    </td>
                    <td class="td-reporter">
                        <div class="reporter-info">
                            <img src="https://ui-avatars.com/api/?name=Hassan+M&background=667eea&color=fff" alt="Reporter" class="reporter-avatar-small">
                        </div>
                    </td>
                    <td class="td-created">
                        <span class="date-text">{{ now()->subDays(rand(1, 30))->format('M d') }}</span>
                    </td>
                    <td class="td-updated">
                        <span class="date-text">{{ now()->subHours(rand(1, 48))->diffForHumans() }}</span>
                    </td>
                    <td class="td-actions" onclick="event.stopPropagation()">
                        <button class="issue-action-btn" title="More actions">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="issues-pagination">
    <div class="pagination-info">
        Showing <strong>1-20</strong> of <strong>156</strong> issues
    </div>
    <div class="pagination-controls">
        <button class="pagination-btn" disabled>
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="pagination-btn active">1</button>
        <button class="pagination-btn">2</button>
        <button class="pagination-btn">3</button>
        <button class="pagination-btn">4</button>
        <span class="pagination-dots">...</span>
        <button class="pagination-btn">8</button>
        <button class="pagination-btn">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="pagination-per-page">
        <select class="project-form-control project-select" style="width: auto;">
            <option value="20">20 per page</option>
            <option value="50">50 per page</option>
            <option value="100">100 per page</option>
        </select>
    </div>
</div>

<style>
    /* ===================================== 
       ISSUES PAGE STYLES
    ===================================== */

    /* Page Header */
    .issues-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .issues-header-left {
        flex: 1;
        min-width: 300px;
    }

    .issues-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Toolbar */
    .issues-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .issues-toolbar-left,
    .issues-toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Quick Filters */
    .quick-filters {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .quick-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .quick-filter-btn:hover {
        border-color: var(--accent);
        background: var(--accent-light);
    }

    .quick-filter-btn.active {
        background: var(--accent);
        color: var(--btn-text-primary);
        border-color: var(--accent);
    }

    .filter-count {
        padding: 2px 8px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-bold);
    }

    .quick-filter-btn.active .filter-count {
        background: rgba(255, 255, 255, 0.2);
    }

    /* View Switcher */
    .view-switcher {
        display: flex;
        gap: 4px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 2px;
    }

    .view-btn {
        width: 36px;
        height: 36px;
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

    .view-btn:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    .view-btn.active {
        background: var(--accent);
        color: var(--btn-text-primary);
    }

    /* Advanced Filters Panel */
    .advanced-filters-panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0;
        margin-bottom: 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }

    .advanced-filters-panel.active {
        max-height: 600px;
        padding: 20px;
    }

    .advanced-filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-body);
    }

    .filter-checkboxes {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .filter-checkbox:hover {
        background: var(--bg);
    }

    .filter-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .filter-checkbox span {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .advanced-filters-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    /* Stats Cards */
    .issues-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .issues-stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .issues-stat-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .issues-stat-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .issues-stat-header i {
        font-size: 18px;
    }

    .issues-stat-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-muted);
    }

    .issues-stat-value {
        font-size: 32px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 8px;
    }

    .issues-stat-change {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
    }

    .stat-increase {
        color: #10b981;
        font-weight: var(--fw-medium);
    }

    .stat-decrease {
        color: #ef4444;
        font-weight: var(--fw-medium);
    }

    .stat-neutral {
        color: var(--text-muted);
        font-weight: var(--fw-medium);
    }

    .stat-warning {
        color: #f59e0b;
        font-weight: var(--fw-medium);
    }

    /* Issues Table */
    .issues-table-container {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .issues-table {
        width: 100%;
        border-collapse: collapse;
    }

    .issues-table thead {
        background: var(--bg);
        border-bottom: 2px solid var(--border);
    }

    .issues-table th {
        padding: 14px 16px;
        text-align: left;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .th-checkbox {
        width: 50px;
        text-align: center;
    }

    .th-type {
        width: 60px;
    }

    .th-key {
        width: 120px;
    }

    .th-summary {
        width: auto;
        min-width: 300px;
    }

    .th-priority {
        width: 80px;
    }

    .th-status {
        width: 140px;
    }

    .th-assignee {
        width: 80px;
    }

    .th-reporter {
        width: 80px;
    }

    .th-created {
        width: 100px;
    }

    .th-updated {
        width: 120px;
    }

    .th-actions {
        width: 60px;
    }

    .issues-table tbody tr {
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .issues-table tbody tr:hover {
        background: var(--bg);
    }

    .issues-table tbody tr.issue-row-done {
        opacity: 0.6;
    }

    .issues-table tbody tr.issue-row-done .issue-summary {
        text-decoration: line-through;
    }

    .issues-table td {
        padding: 14px 16px;
        font-size: var(--fs-body);
        color: var(--text-body);
        vertical-align: middle;
    }

    .td-checkbox {
        text-align: center;
    }

    .td-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .issue-key-link {
        font-family: monospace;
        font-weight: var(--fw-semibold);
        color: var(--accent);
    }

    .issue-summary {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .status-todo {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .status-progress {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .status-review {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .status-done {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    /* Assignee Avatar */
    .assignee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
    }

    .assignee-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .assign-btn-small {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 50%;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .assign-btn-small:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .reporter-avatar-small {
        width: 24px;
        height: 24px;
        border-radius: 50%;
    }

    .date-text {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .issue-action-btn {
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

    .issue-action-btn:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    /* Pagination */
    .issues-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .pagination-info {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .pagination-controls {
        display: flex;
        gap: 4px;
    }

    .pagination-btn {
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .pagination-btn:hover:not(:disabled) {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .pagination-btn.active {
        background: var(--accent);
        color: var(--btn-text-primary);
        border-color: var(--accent);
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    ..pagination-dots {
        display: flex;
        align-items: center;
        padding: 0 8px;
        color: var(--text-muted);
    }

    .pagination-per-page {
        display: flex;
        align-items: center;
    }

    /* Responsive */
    @media (max-width: 1400px) {
        .issues-table {
            font-size: var(--fs-subtle);
        }

        .th-reporter,
        .th-created {
            display: none;
        }

        .td-reporter,
        .td-created {
            display: none;
        }
    }

    @media (max-width: 1200px) {
        .quick-filters {
            width: 100%;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .quick-filters::-webkit-scrollbar {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .issues-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .issues-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .issues-toolbar-left,
        .issues-toolbar-right {
            width: 100%;
        }

        .project-search-box {
            max-width: 100%;
        }

        .advanced-filters-grid {
            grid-template-columns: 1fr;
        }

        .issues-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Simplified mobile table */
        .th-type,
        .th-priority,
        .th-assignee,
        .th-reporter,
        .th-created,
        .th-updated {
            display: none;
        }

        .td-type,
        .td-priority,
        .td-assignee,
        .td-reporter,
        .td-created,
        .td-updated {
            display: none;
        }

        .issue-summary {
            max-width: 200px;
        }

        .issues-pagination {
            flex-direction: column;
            align-items: stretch;
        }

        .pagination-controls {
            justify-content: center;
        }
    }
</style>

<script>
    // ===================================== 
    // ISSUES PAGE FUNCTIONALITY
    // ===================================== 

    function toggleAdvancedFilters() {
        const panel = document.getElementById('advancedFiltersPanel');
        panel.classList.toggle('active');
    }

    function clearFilters() {
        console.log('Clear all filters');
        // Reset all filter inputs
        document.querySelectorAll('.advanced-filters-panel select').forEach(select => {
            select.selectedIndex = 0;
        });
        document.querySelectorAll('.filter-checkbox input').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function applyFilters() {
        console.log('Apply filters');
        // Apply filter logic here
        toggleAdvancedFilters();
    }

    function exportIssues() {
        console.log('Export issues');
        alert('Export Issues - Coming Soon!');
    }

    function openCreateIssueModal() {
        console.log('Open create issue modal');
        alert('Create Issue Modal - Coming Soon!');
    }

    function openIssueDetail(key) {
        console.log('Open issue detail:', key);
        window.location.href = `?issue=${key}`;
    }

    // Quick filter buttons
    document.querySelectorAll('.quick-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // View switcher
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const view = this.getAttribute('data-view');
            console.log('Switch to view:', view);
        });
    });

    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.issues-table tbody input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    console.log('✅ Issues Page Initialized');
</script>

@endsection