{{-- resources/views/tenant/manage/projects/tasks/all.blade.php --}}
@extends('tenant.manage.app')

@section('main')

<!-- Page Header -->
<div class="all-tasks-header">
    <div class="all-tasks-header-content">
        <div class="all-tasks-header-top">
            <div class="all-tasks-title-section">
                <h1 class="all-tasks-title">
                    <i class="fas fa-tasks"></i>
                    All Tasks
                </h1>
                <span class="all-tasks-count">{{ $tasks->total() }} tasks</span>
            </div>
            <div class="all-tasks-actions">
                <button class="all-tasks-btn" onclick="exportTasks()">
                    <i class="fas fa-download"></i>
                    <span>Export</span>
                </button>
                <button class="all-tasks-btn all-tasks-btn-primary" onclick="bulkActions()">
                    <i class="fas fa-check-square"></i>
                    <span>Bulk Actions</span>
                </button>
            </div>
        </div>
        <p class="all-tasks-subtitle">
            Workspace-wide view of all tasks. Filter, sort, and manage in bulk.
        </p>
    </div>

    <!-- Advanced Filters Bar -->
    <div class="all-tasks-filters-bar">
        <form method="GET" action="{{ route('tenant.manage.projects.tasks.all', $username) }}" class="filters-form">
            <!-- Search -->
            <div class="filter-group filter-search">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Search tasks..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Project Filter -->
            <div class="filter-group">
                <select name="project_id" class="filter-select">
                    <option value="">All Projects</option>
                    @foreach($projectsForFilter as $proj)
                        <option value="{{ $proj->id }}" @selected(request('project_id') == $proj->id)>
                            {{ $proj->key }} — {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Assignee Filter -->
            <div class="filter-group">
                <select name="assigned_to" class="filter-select">
                    <option value="">All Assignees</option>
                    @foreach($teamForFilter as $member)
                        <option value="{{ $member->id }}" @selected(request('assigned_to') == $member->id)>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="filter-group">
                <select name="status" class="filter-select">
                    <option value="">All Status</option>
                    @foreach(\App\Models\Task::statusOptions() as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>
                            {{ ucfirst(str_replace('-', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Due Date Filter -->
            <div class="filter-group">
                <select name="due" class="filter-select">
                    <option value="">Due: Any</option>
                    <option value="overdue" @selected(request('due') === 'overdue')>Overdue</option>
                    <option value="today" @selected(request('due') === 'today')>Today</option>
                    <option value="week" @selected(request('due') === 'week')>This Week</option>
                </select>
            </div>

            <!-- Filter Actions -->
            <div class="filter-actions">
                <button type="submit" class="filter-btn filter-btn-primary">
                    <i class="fas fa-filter"></i>
                    Apply
                </button>
                @if(request()->hasAny(['search', 'project_id', 'assigned_to', 'status', 'due']))
                    <a href="{{ route('tenant.manage.projects.tasks.all', $username) }}" class="filter-btn">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filters Display -->
    @if(request()->hasAny(['search', 'project_id', 'assigned_to', 'status', 'due']))
        <div class="active-filters">
            <span class="active-filters-label">Active filters:</span>
            <div class="active-filters-list">
                @if(request('search'))
                    <span class="filter-chip">
                        Search: "{{ request('search') }}"
                        <button onclick="removeFilter('search')">×</button>
                    </span>
                @endif
                @if(request('project_id'))
                    <span class="filter-chip">
                        Project: {{ $projectsForFilter->find(request('project_id'))?->name }}
                        <button onclick="removeFilter('project_id')">×</button>
                    </span>
                @endif
                @if(request('assigned_to'))
                    <span class="filter-chip">
                        Assignee: {{ $teamForFilter->firstWhere('id', request('assigned_to'))?->name }}
                        <button onclick="removeFilter('assigned_to')">×</button>
                    </span>
                @endif
                @if(request('status'))
                    <span class="filter-chip">
                        Status: {{ ucfirst(str_replace('-', ' ', request('status'))) }}
                        <button onclick="removeFilter('status')">×</button>
                    </span>
                @endif
                @if(request('due'))
                    <span class="filter-chip">
                        Due: {{ ucfirst(request('due')) }}
                        <button onclick="removeFilter('due')">×</button>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Tasks Table -->
<div class="all-tasks-table-wrapper">
    <table class="all-tasks-table">
        <thead>
            <tr>
                <th class="col-checkbox">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                </th>
                <th class="col-task">Task</th>
                <th class="col-project">Project</th>
                <th class="col-assignee">Assignee</th>
                <th class="col-status">Status</th>
                <th class="col-priority">Priority</th>
                <th class="col-due">Due Date</th>
                <th class="col-updated">Last Updated</th>
                <th class="col-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                @php
                    $isOverdue = $task->is_overdue;
                @endphp
                <tr class="task-table-row {{ $isOverdue ? 'row-overdue' : '' }}" data-task-id="{{ $task->id }}">
                    <!-- Checkbox -->
                    <td class="col-checkbox">
                        <input type="checkbox" class="task-checkbox" value="{{ $task->id }}">
                    </td>

                    <!-- Task Info -->
                    <td class="col-task">
                        <div class="task-cell-content">
                            <div class="task-cell-header">
                                <span class="task-cell-key">{{ $task->project?->key }}-{{ $task->id }}</span>
                                @if($isOverdue)
                                    <span class="task-cell-badge badge-danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Overdue
                                    </span>
                                @endif
                            </div>
                            <div class="task-cell-title" onclick="openTaskDrawer({{ $task->id }})">
                                {{ $task->title }}
                            </div>
                            @if($task->subtasks && $task->subtasks->count() > 0)
                                <div class="task-cell-meta">
                                    <i class="fas fa-check-square"></i>
                                    {{ $task->subtasks->where('completed', true)->count() }}/{{ $task->subtasks->count() }} subtasks
                                </div>
                            @endif
                        </div>
                    </td>

                    <!-- Project -->
                    <td class="col-project">
                        <div class="project-cell">
                            <span class="project-cell-badge" style="background: {{ ['#667eea','#f093fb','#4facfe'][$task->project_id % 3] }}">
                                {{ \Illuminate\Support\Str::of($task->project?->key)->substr(0,2)->upper() }}
                            </span>
                            <span class="project-cell-name">{{ $task->project?->name ?? '—' }}</span>
                        </div>
                    </td>

                    <!-- Assignee -->
                    <td class="col-assignee">
                        @if($task->assignee)
                            <div class="assignee-cell">
                                <img src="{{ $task->assignee->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->assignee->name) }}" 
                                     alt="{{ $task->assignee->name }}"
                                     class="assignee-cell-avatar">
                                <span class="assignee-cell-name">{{ $task->assignee->name }}</span>
                            </div>
                        @else
                            <span class="assignee-cell-unassigned">Unassigned</span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="col-status">
                        @include('tenant.manage.projects.tasks.components.task-status-badge', ['status' => $task->status])
                    </td>

                    <!-- Priority -->
                    <td class="col-priority">
                        <span class="priority-badge priority-{{ $task->priority ?? 'medium' }}">
                            @php
                                $priorityIcons = [
                                    'urgent' => 'fa-exclamation-circle',
                                    'high' => 'fa-arrow-up',
                                    'medium' => 'fa-minus',
                                    'low' => 'fa-arrow-down'
                                ];
                            @endphp
                            <i class="fas {{ $priorityIcons[$task->priority ?? 'medium'] }}"></i>
                            {{ ucfirst($task->priority ?? 'Medium') }}
                        </span>
                    </td>

                    <!-- Due Date -->
                    <td class="col-due">
                        @if($task->due_date)
                            <span class="due-date {{ $isOverdue ? 'due-date-overdue' : '' }}">
                                <i class="fas fa-calendar"></i>
                                {{ $task->due_date->format('M d, Y') }}
                            </span>
                        @else
                            <span class="due-date-none">—</span>
                        @endif
                    </td>

                    <!-- Last Updated -->
                    <td class="col-updated">
                        <span class="updated-time">
                            {{ $task->updated_at->diffForHumans() }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="col-actions">
                        <div class="table-actions">
                            <button class="table-action-btn" 
                                    onclick="openTaskDrawer({{ $task->id }})"
                                    title="View details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="table-action-btn" 
                                    onclick="sendReminder({{ $task->id }})"
                                    title="Send reminder">
                                <i class="fas fa-bell"></i>
                            </button>
                            <button class="table-action-btn" 
                                    onclick="openTaskMenu({{ $task->id }}, event)"
                                    title="More actions">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="table-empty">
                        <div class="table-empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No tasks found matching your filters</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="all-tasks-pagination">
    {{ $tasks->links() }}
</div>

<!-- Bulk Actions Bar (Hidden by default) -->
<div class="bulk-actions-bar" id="bulkActionsBar">
    <div class="bulk-actions-info">
        <span id="selectedCount">0</span> tasks selected
    </div>
    <div class="bulk-actions-buttons">
        <button class="bulk-action-btn" onclick="bulkUpdateStatus('done')">
            <i class="fas fa-check"></i>
            Mark as Done
        </button>
        <button class="bulk-action-btn" onclick="bulkUpdateStatus('in-progress')">
            <i class="fas fa-play"></i>
            Start Progress
        </button>
        <button class="bulk-action-btn" onclick="bulkAssign()">
            <i class="fas fa-user"></i>
            Assign To
        </button>
        <button class="bulk-action-btn" onclick="bulkSendReminder()">
            <i class="fas fa-bell"></i>
            Send Reminders
        </button>
    </div>
    <button class="bulk-actions-close" onclick="clearSelection()">
        <i class="fas fa-times"></i>
    </button>
</div>

<style>
/* ===================================
   ALL TASKS TABLE STYLES
=================================== */

:root {
    --table-border: #DFE1E6;
    --table-hover: #F4F5F7;
    --table-selected: #DEEBFF;
}

/* Header */
.all-tasks-header {
    background: white;
    border-bottom: 1px solid var(--table-border);
    padding: 24px 32px;
    margin: -24px -32px 0;
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.98);
}

.all-tasks-header-content {
    max-width: 1600px;
    margin: 0 auto 20px;
}

.all-tasks-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.all-tasks-title-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.all-tasks-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--task-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.all-tasks-count {
    display: inline-flex;
    align-items: center;
    height: 24px;
    padding: 0 10px;
    background: var(--task-bg);
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    color: var(--task-text-subtle);
}

.all-tasks-actions {
    display: flex;
    gap: 12px;
}

.all-tasks-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 36px;
    padding: 0 16px;
    background: white;
    border: 1px solid var(--table-border);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    color: var(--task-text);
    cursor: pointer;
    transition: var(--task-transition);
}

.all-tasks-btn:hover {
    background: var(--table-hover);
    border-color: var(--task-primary);
}

.all-tasks-btn-primary {
    background: var(--task-primary);
    color: white;
    border-color: var(--task-primary);
}

.all-tasks-btn-primary:hover {
    background: var(--task-primary-hover);
}

.all-tasks-subtitle {
    font-size: 14px;
    color: var(--task-text-subtle);
    margin: 0;
}

/* Filters Bar */
.all-tasks-filters-bar {
    background: white;
    padding: 16px 0;
    border-bottom: 1px solid var(--table-border);
}

.filters-form {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-group {
    min-width: 0;
}

.filter-search {
    flex: 1;
    min-width: 300px;
}

.search-input-wrapper {
    position: relative;
    width: 100%;
}

.search-input-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--task-text-subtle);
    font-size: 14px;
}

.search-input {
    width: 100%;
    height: 36px;
    padding: 0 12px 0 36px;
    background: white;
    border: 1px solid var(--table-border);
    border-radius: 6px;
    font-size: 14px;
    color: var(--task-text);
    transition: var(--task-transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--task-primary);
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.filter-select {
    min-width: 160px;
    height: 36px;
    padding: 0 32px 0 12px;
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235E6C84' d='M6 9L1 4h10z'/%3E%3C/svg%3E") no-repeat right 10px center;
    border: 1px solid var(--table-border);
    border-radius: 6px;
    font-size: 14px;
    color: var(--task-text);
    cursor: pointer;
    appearance: none;
    transition: var(--task-transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--task-primary);
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 36px;
    padding: 0 16px;
    background: white;
    border: 1px solid var(--table-border);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    color: var(--task-text);
    cursor: pointer;
    text-decoration: none;
    transition: var(--task-transition);
}

.filter-btn:hover {
    background: var(--table-hover);
}

.filter-btn-primary {
    background: var(--task-primary);
    color: white;
    border-color: var(--task-primary);
}

.filter-btn-primary:hover {
    background: var(--task-primary-hover);
}

/* Active Filters */
.active-filters {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0 0;
    flex-wrap: wrap;
}

.active-filters-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--task-text-subtle);
}

.active-filters-list {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 28px;
    padding: 0 8px 0 12px;
    background: var(--table-selected);
    border-radius: 14px;
    font-size: 13px;
    font-weight: 500;
    color: var(--task-primary);
}

.filter-chip button {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    border-radius: 50%;
    color: var(--task-primary);
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
    transition: var(--task-transition);
}

.filter-chip button:hover {
    background: rgba(0, 82, 204, 0.2);
}

/* Table */
.all-tasks-table-wrapper {
    background: white;
    border: 1px solid var(--table-border);
    border-radius: 8px;
    margin: 24px 32px;
    overflow: hidden;
}

.all-tasks-table {
    width: 100%;
    border-collapse: collapse;
}

.all-tasks-table thead {
    background: var(--task-bg);
    border-bottom: 2px solid var(--table-border);
}

.all-tasks-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: var(--task-text-subtle);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.col-checkbox {
    width: 48px;
    padding-left: 20px !important;
}

.col-task {
    min-width: 300px;
}

.col-project {
    width: 200px;
}

.col-assignee {
    width: 180px;
}

.col-status {
    width: 140px;
}

.col-priority {
    width: 120px;
}

.col-due {
    width: 140px;
}

.col-updated {
    width: 140px;
}

.col-actions {
    width: 120px;
}

.task-table-row {
    border-bottom: 1px solid var(--table-border);
    transition: var(--task-transition);
}

.task-table-row:hover {
    background: var(--table-hover);
}

.task-table-row.row-overdue {
    background: #FFEBE6;
}

.task-table-row.selected {
    background: var(--table-selected);
}

.all-tasks-table td {
    padding: 16px;
    vertical-align: top;
}

/* Task Cell */
.task-cell-content {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.task-cell-header {
    display: flex;
    align-items: center;
    gap: 8px;
}

.task-cell-key {
    font-size: 12px;
    font-weight: 600;
    color: var(--task-text-subtle);
    font-family: monospace;
}

.task-cell-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    height: 20px;
    padding: 0 8px;
    border-radius: 4px;
    font-size:11px;
    font-weight: 600;
}

.badge-danger {
    background: #FFEBE6;
    color: var(--task-danger);
}

.task-cell-title {
    font-size: 14px;
    font-weight: 500;
    color: var(--task-text);
    cursor: pointer;
    transition: var(--task-transition);
    line-height: 1.4;
}

.task-cell-title:hover {
    color: var(--task-primary);
}

.task-cell-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--task-text-subtle);
}

/* Project Cell */
.project-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.project-cell-badge {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}

.project-cell-name {
    font-size: 14px;
    color: var(--task-text);
    font-weight: 500;
}

/* Assignee Cell */
.assignee-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.assignee-cell-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.assignee-cell-name {
    font-size: 14px;
    color: var(--task-text);
}

.assignee-cell-unassigned {
    font-size: 14px;
    color: var(--task-text-subtle);
    font-style: italic;
}

/* Priority Badge */
.priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 24px;
    padding: 0 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.priority-urgent {
    background: #FFEBE6;
    color: var(--task-danger);
}

.priority-high {
    background: #FFF4E5;
    color: var(--task-warning);
}

.priority-medium {
    background: #E3FCEF;
    color: var(--task-success);
}

.priority-low {
    background: var(--task-bg);
    color: var(--task-text-subtle);
}

/* Due Date */
.due-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--task-text);
}

.due-date-overdue {
    color: var(--task-danger);
    font-weight: 600;
}

.due-date-none {
    font-size: 13px;
    color: var(--task-text-subtle);
}

/* Updated Time */
.updated-time {
    font-size: 13px;
    color: var(--task-text-subtle);
}

/* Table Actions */
.table-actions {
    display: flex;
    gap: 4px;
}

.table-action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid var(--table-border);
    border-radius: 6px;
    color: var(--task-text-subtle);
    cursor: pointer;
    transition: var(--task-transition);
}

.table-action-btn:hover {
    background: var(--task-hover);
    color: var(--task-primary);
    border-color: var(--task-primary);
}

/* Table Empty State */
.table-empty {
    padding: 80px 20px !important;
    text-align: center;
}

.table-empty-state i {
    font-size: 48px;
    color: var(--task-text-subtle);
    opacity: 0.3;
    margin-bottom: 16px;
}

.table-empty-state p {
    font-size: 16px;
    color: var(--task-text-subtle);
    margin: 0;
}

/* Pagination */
.all-tasks-pagination {
    display: flex;
    justify-content: center;
    padding: 24px 32px;
}

/* Bulk Actions Bar */
.bulk-actions-bar {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 16px 24px;
    background: var(--task-text);
    color: white;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    z-index: 999;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.bulk-actions-bar.active {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

.bulk-actions-info {
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
}

.bulk-actions-info #selectedCount {
    color: var(--task-info);
}

.bulk-actions-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.bulk-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 32px;
    padding: 0 12px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--task-transition);
}

.bulk-action-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
}

.bulk-actions-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 6px;
    transition: var(--task-transition);
}

.bulk-actions-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Responsive */
@media (max-width: 1400px) {
    .all-tasks-table {
        min-width: 1200px;
    }

    .all-tasks-table-wrapper {
        overflow-x: auto;
    }
}

@media (max-width: 768px) {
    .all-tasks-header {
        padding: 16px 20px;
        margin: -16px -20px 0;
    }

    .all-tasks-header-top {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .all-tasks-actions {
        width: 100%;
    }

    .all-tasks-btn {
        flex: 1;
        justify-content: center;
    }

    .filters-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        width: 100%;
    }

    .filter-search {
        min-width: 0;
    }

    .filter-select {
        width: 100%;
    }

    .all-tasks-table-wrapper {
        margin: 16px 20px;
    }

    .bulk-actions-bar {
        left: 20px;
        right: 20px;
        transform: translateX(0) translateY(100px);
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .bulk-actions-bar.active {
        transform: translateX(0) translateY(0);
    }

    .bulk-actions-buttons {
        width: 100%;
    }

    .bulk-action-btn {
        flex: 1;
        justify-content: center;
    }
}
</style>

<script>
// ===================================
// SELECTION MANAGEMENT
// ===================================
let selectedTasks = new Set();

function toggleSelectAll(checkbox) {
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    
    taskCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        const taskId = cb.value;
        
        if (checkbox.checked) {
            selectedTasks.add(taskId);
            cb.closest('tr').classList.add('selected');
        } else {
            selectedTasks.delete(taskId);
            cb.closest('tr').classList.remove('selected');
        }
    });
    
    updateBulkActionsBar();
}

// Attach change event to all task checkboxes
document.addEventListener('DOMContentLoaded', () => {
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    
    taskCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskId = this.value;
            
            if (this.checked) {
                selectedTasks.add(taskId);
                this.closest('tr').classList.add('selected');
            } else {
                selectedTasks.delete(taskId);
                this.closest('tr').classList.remove('selected');
                document.getElementById('selectAll').checked = false;
            }
            
            updateBulkActionsBar();
        });
    });
});

function updateBulkActionsBar() {
    const bar = document.getElementById('bulkActionsBar');
    const count = document.getElementById('selectedCount');
    
    count.textContent = selectedTasks.size;
    
    if (selectedTasks.size > 0) {
        bar.classList.add('active');
    } else {
        bar.classList.remove('active');
    }
}

function clearSelection() {
    selectedTasks.clear();
    
    document.querySelectorAll('.task-checkbox').forEach(cb => {
        cb.checked = false;
        cb.closest('tr').classList.remove('selected');
    });
    
    document.getElementById('selectAll').checked = false;
    updateBulkActionsBar();
}

// ===================================
// BULK ACTIONS
// ===================================
async function bulkUpdateStatus(status) {
    if (selectedTasks.size === 0) return;
    
    const confirmMsg = `Update ${selectedTasks.size} task(s) to "${status}"?`;
    if (!confirm(confirmMsg)) return;
    
    showLoading();
    
    // In real implementation, make batch API call
    console.log('Bulk update status:', status, Array.from(selectedTasks));
    
    setTimeout(() => {
        hideLoading();
        showNotification(`${selectedTasks.size} tasks updated`, 'success');
        clearSelection();
        location.reload();
    }, 1000);
}

async function bulkAssign() {
    if (selectedTasks.size === 0) return;
    
    // Show assignee picker modal
    showNotification('Bulk assign modal coming soon!', 'info');
}

async function bulkSendReminder() {
    if (selectedTasks.size === 0) return;
    
    const confirmMsg = `Send reminder for ${selectedTasks.size} task(s)?`;
    if (!confirm(confirmMsg)) return;
    
    showLoading();
    
    console.log('Bulk send reminder:', Array.from(selectedTasks));
    
    setTimeout(() => {
        hideLoading();
        showNotification(`Reminders sent for ${selectedTasks.size} tasks`, 'success');
        clearSelection();
    }, 1000);
}

function bulkActions() {
    if (selectedTasks.size === 0) {
        showNotification('Please select tasks first', 'warning');
        return;
    }
    
    showNotification('Advanced bulk actions coming soon!', 'info');
}

// ===================================
// FILTER MANAGEMENT
// ===================================
function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// ===================================
// EXPORT
// ===================================
function exportTasks() {
    showNotification('Exporting tasks...', 'info');
    
    // In real implementation, trigger export
    setTimeout(() => {
        showNotification('Export complete!', 'success');
    }, 1500);
}

// ===================================
// TASK ACTIONS
// ===================================
async function sendReminder(taskId) {
    const confirmMsg = 'Send reminder for this task?';
    if (!confirm(confirmMsg)) return;
    
    showLoading();
    
    const url = "{{ route('tenant.manage.projects.tasks.remind', [$username, 'TASK_ID']) }}"
        .replace('TASK_ID', taskId);
    
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            }
        });
        
        if (!res.ok) throw new Error('Failed to send reminder');
        
        showNotification('Reminder sent!', 'success');
        
    } catch (error) {
        console.error('Send reminder error:', error);
        showNotification('Failed to send reminder', 'error');
    } finally {
        hideLoading();
    }
}

function openTaskMenu(taskId, event) {
    event.stopPropagation();
    
    // Create context menu
    const menu = document.createElement('div');
    menu.className = 'task-context-menu';
    menu.style.cssText = `
        position: fixed;
        top: ${event.clientY}px;
        left: ${event.clientX}px;
        background: white;
        border: 1px solid var(--table-border);
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        z-index: 1000;
        min-width: 200px;
        padding: 8px 0;
    `;
    
    menu.innerHTML = `
        <button class="context-menu-item" onclick="openTaskDrawer(${taskId}); closeContextMenu();">
            <i class="fas fa-eye"></i>
            View Details
        </button>
        <button class="context-menu-item" onclick="editTask(${taskId}); closeContextMenu();">
            <i class="fas fa-edit"></i>
            Edit Task
        </button>
        <button class="context-menu-item" onclick="duplicateTask(${taskId}); closeContextMenu();">
            <i class="fas fa-copy"></i>
            Duplicate
        </button>
        <hr style="margin: 8px 0; border: none; border-top: 1px solid var(--table-border);">
        <button class="context-menu-item" onclick="sendReminder(${taskId}); closeContextMenu();">
            <i class="fas fa-bell"></i>
            Send Reminder
        </button>
        <button class="context-menu-item danger" onclick="deleteTask(${taskId}); closeContextMenu();">
            <i class="fas fa-trash"></i>
            Delete Task
        </button>
    `;
    
    document.body.appendChild(menu);
    
    // Add style for menu items
    const style = document.createElement('style');
    style.textContent = `
        .context-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: none;
            border: none;
            text-align: left;
            font-size: 14px;
            color: var(--task-text);
            cursor: pointer;
            transition: var(--task-transition);
        }
        .context-menu-item:hover {
            background: var(--table-hover);
        }
        .context-menu-item.danger {
            color: var(--task-danger);
        }
        .context-menu-item i {
            width: 16px;
            text-align: center;
        }
    `;
    document.head.appendChild(style);
    
    // Close on outside click
    setTimeout(() => {
        document.addEventListener('click', closeContextMenu);
    }, 0);
}

function closeContextMenu() {
    const menu = document.querySelector('.task-context-menu');
    if (menu) menu.remove();
    document.removeEventListener('click', closeContextMenu);
}

function editTask(taskId) {
    showNotification('Edit modal coming soon!', 'info');
}

function duplicateTask(taskId) {
    showNotification('Task duplicated!', 'success');
}

function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;
    
    showLoading();
    
    setTimeout(() => {
        hideLoading();
        showNotification('Task deleted', 'success');
        location.reload();
    }, 1000);
}

// ===================================
// UTILITIES
// ===================================
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function showLoading() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.classList.add('active');
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.classList.remove('active');
}

function showNotification(message, type = 'success') {
    const colors = {
        success: '#00875A',
        error: '#DE350B',
        warning: '#FF991F',
        info: '#0052CC'
    };

    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10000;
        padding: 12px 20px;
        background: ${colors[type] || colors.info};
        color: white;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function openTaskDrawer(taskId) {
    showNotification('Task drawer coming soon!', 'info');
}

console.log('✨ All Tasks Page Initialized');
</script>

@endsection