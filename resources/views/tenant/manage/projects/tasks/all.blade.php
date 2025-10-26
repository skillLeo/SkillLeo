{{-- resources/views/tenant/manage/projects/tasks/all.blade.php --}}
@extends('tenant.manage.app')

@section('main')

<!-- Page Header -->
<div class="all-tasks-header">
    <div class="all-tasks-header-left">
        <h1 class="all-tasks-title">
            <i class="fas fa-list"></i>
            All Tasks
        </h1>
        <p class="all-tasks-subtitle">Workspace-wide task management</p>
    </div>
    <div class="all-tasks-header-actions">
        <button class="btn-secondary" onclick="exportTasks()">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
    </div>
</div>

<!-- Filters -->
<div class="all-tasks-filters">
    <form method="GET" action="{{ route('tenant.manage.projects.tasks.all', $username) }}" class="filters-form">
        <div class="filter-group">
            <label class="filter-label">Project</label>
            <select name="project_id" class="filter-select" onchange="this.form.submit()">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" 
                            {{ request('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->key }} - {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Assignee</label>
            <select name="assigned_to" class="filter-select" onchange="this.form.submit()">
                <option value="">All Assignees</option>
                <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>
                    Unassigned
                </option>
                @foreach($teamMembers as $member)
                    <option value="{{ $member->id }}" 
                            {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                <option value="in-progress" {{ request('status') === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>In Review</option>
                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                <option value="postponed" {{ request('status') === 'postponed' ? 'selected' : '' }}>Postponed</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Priority</label>
            <select name="priority" class="filter-select" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>

        @if(array_filter($filters))
            <button type="button" class="btn-clear" onclick="clearFilters()">
                <i class="fas fa-times"></i>
                <span>Clear Filters</span>
            </button>
        @endif
    </form>
</div>

<!-- Tasks Table -->
<div class="all-tasks-table-wrapper">
    <table class="all-tasks-table">
        <thead>
            <tr>
                <th class="th-key">Key</th>
                <th class="th-title">Title</th>
                <th class="th-project">Project</th>
                <th class="th-assignee">Assignee</th>
                <th class="th-status">Status</th>
                <th class="th-priority">Priority</th>
                <th class="th-due">Due Date</th>
                <th class="th-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                @php
                    $isOverdue = $task->is_overdue;
                    $statusConfig = [
                        'todo' => ['bg' => '#F4F5F7', 'text' => '#6B778C'],
                        'in-progress' => ['bg' => '#DEEBFF', 'text' => '#0052CC'],
                        'review' => ['bg' => '#FFFAE6', 'text' => '#FF991F'],
                        'done' => ['bg' => '#E3FCEF', 'text' => '#00875A'],
                        'blocked' => ['bg' => '#FFEBE6', 'text' => '#DE350B'],
                        'postponed' => ['bg' => '#EAE6FF', 'text' => '#8777D9'],
                    ];
                    $status = $statusConfig[$task->status] ?? $statusConfig['todo'];
                    
                    $priorityConfig = [
                        'urgent' => ['text' => '#DE350B', 'icon' => 'fa-exclamation-circle'],
                        'high' => ['text' => '#FF991F', 'icon' => 'fa-arrow-up'],
                        'medium' => ['text' => '#0052CC', 'icon' => 'fa-equals'],
                        'low' => ['text' => '#00875A', 'icon' => 'fa-arrow-down'],
                    ];
                    $priority = $priorityConfig[$task->priority] ?? $priorityConfig['medium'];
                @endphp

                <tr class="task-row {{ $isOverdue ? 'overdue' : '' }}" 
                    onclick="openTaskDetail({{ $task->id }})">
                    <td class="td-key">
                        <span class="task-key">{{ $task->project->key }}-{{ $task->id }}</span>
                    </td>
                    <td class="td-title">
                        <div class="task-title-cell">
                            <span class="task-title-text">{{ $task->title }}</span>
                            @if($task->subtasks->count() > 0)
                                <span class="task-subtasks-badge">
                                    <i class="fas fa-check-square"></i>
                                    {{ $task->subtasks->where('completed', true)->count() }}/{{ $task->subtasks->count() }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="td-project">
                        <span class="task-project-badge">{{ $task->project->name }}</span>
                    </td>
                    <td class="td-assignee">
                        @if($task->assignee)
                            <div class="task-assignee-cell">
                                <img src="{{ $task->assignee->avatar_url }}" 
                                     alt="{{ $task->assignee->name }}" 
                                     class="task-assignee-avatar">
                                <span>{{ $task->assignee->name }}</span>
                            </div>
                        @else
                            <span class="task-unassigned">Unassigned</span>
                        @endif
                    </td>
                    <td class="td-status">
                        <span class="task-status-badge" 
                              style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};">
                            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="td-priority">
                        <span class="task-priority-badge" style="color: {{ $priority['text'] }};">
                            <i class="fas {{ $priority['icon'] }}"></i>
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="td-due">
                        @if($task->due_date)
                            <span class="task-due {{ $isOverdue ? 'overdue' : '' }}">
                                {{ $task->due_date->format('M d, Y') }}
                                @if($isOverdue)
                                    <i class="fas fa-exclamation-triangle"></i>
                                @endif
                            </span>
                        @else
                            <span class="task-no-due">—</span>
                        @endif
                    </td>
                    <td class="td-actions">
                        <div class="task-actions" onclick="event.stopPropagation()">
                            <button class="task-action-btn" 
                                    onclick="sendReminder({{ $task->id }})"
                                    title="Send reminder">
                                <i class="fas fa-bell"></i>
                            </button>
                            <button class="task-action-btn" 
                                    onclick="editTask({{ $task->id }})"
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty-row">
                        <div class="empty-state-small">
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
@if($tasks->hasPages())
    <div class="all-tasks-pagination">
        {{ $tasks->links() }}
    </div>
@endif

<style>
:root {
    --task-primary: #0052CC;
    --task-success: #00875A;
    --task-warning: #FF991F;
    --task-danger: #DE350B;
    --task-gray-50: #FAFBFC;
    --task-gray-100: #F4F5F7;
    --task-gray-200: #EBECF0;
    --task-gray-300: #DFE1E6;
    --task-gray-400: #B3BAC5;
    --task-gray-500: #8993A4;
    --task-gray-600: #6B778C;
    --task-gray-700: #5E6C84;
    --task-gray-800: #42526E;
    --task-gray-900: #172B4D;
}

/* Header */
.all-tasks-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.all-tasks-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--task-gray-900);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.all-tasks-subtitle {
    font-size: 14px;
    color: var(--task-gray-600);
    margin: 0;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 1px solid var(--task-gray-300);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    color: var(--task-gray-800);
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: var(--task-gray-50);
    border-color: var(--task-gray-400);
}

/* Filters */
.all-tasks-filters {
    background: white;
    border: 1px solid var(--task-gray-300);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 24px;
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--task-gray-700);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-select {
    height: 40px;
    padding: 0 36px 0 12px;
    border: 1px solid var(--task-gray-300);
    border-radius: 6px;
    font-size: 14px;
    color: var(--task-gray-900);
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235E6C84' d='M6 9L1 4h10z'/%3E%3C/svg%3E") no-repeat right 12px center;
    appearance: none;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-select:hover {
    border-color: var(--task-gray-400);
}

.filter-select:focus {
    outline: none;
    border-color: var(--task-primary);
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.btn-clear {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: var(--task-gray-100);
    border: 1px solid var(--task-gray-300);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    color: var(--task-gray-700);
    cursor: pointer;
    transition: all 0.2s;
    height: 40px;
}

.btn-clear:hover {
    background: var(--task-gray-200);
    border-color: var(--task-gray-400);
}

/* Table */
.all-tasks-table-wrapper {
    background: white;
    border: 1px solid var(--task-gray-300);
    border-radius: 8px;
    overflow: hidden;
}

.all-tasks-table {
    width: 100%;
    border-collapse: collapse;
}

.all-tasks-table thead {
    background: var(--task-gray-50);
    border-bottom: 2px solid var(--task-gray-300);
}

.all-tasks-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    color: var(--task-gray-700);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.th-key { width: 120px; }
.th-title { width: auto; }
.th-project { width: 180px; }
.th-assignee { width: 160px; }
.th-status { width: 120px; }
.th-priority { width: 110px; }
.th-due { width: 130px; }
.th-actions { width: 100px; }

.task-row {
    border-bottom: 1px solid var(--task-gray-200);
    cursor: pointer;
    transition: background 0.15s;
}

.task-row:hover {
    background: var(--task-gray-50);
}

.task-row.overdue {
    background: rgba(255, 235, 230, 0.3);
}

.task-row.overdue:hover {
    background: rgba(255, 235, 230, 0.5);
}

.all-tasks-table td {
    padding: 14px 16px;
    font-size: 14px;
    vertical-align: middle;
}

/* Cell Styles */
.task-key {
    font-family: monospace;
    font-weight: 600;
    color: var(--task-primary);
    background: #DEEBFF;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.task-title-cell {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.task-title-text {
    color: var(--task-gray-900);
    font-weight: 500;
}

.task-subtasks-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: var(--task-gray-100);
    border-radius: 10px;
    font-size: 11px;
    color: var(--task-gray-600);
    font-weight: 600;
    width: fit-content;
}

.task-project-badge {
    display: inline-block;
    padding: 4px 10px;
    background: var(--task-gray-100);
    border-radius: 6px;
    font-size: 13px;
    color: var(--task-gray-800);
    font-weight: 500;
}

.task-assignee-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.task-assignee-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.task-unassigned {
    color: var(--task-gray-500);
    font-style: italic;
    font-size: 13px;
}

.task-status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.task-priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
}

.task-due {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--task-gray-800);
    font-size: 13px;
}

.task-due.overdue {
    color: var(--task-danger);
    font-weight: 600;
}

.task-no-due {
    color: var(--task-gray-500);
}

.task-actions {
    display: flex;
    gap: 6px;
}

.task-action-btn {
    width: 32px;
    height: 32px;
    border: 1px solid var(--task-gray-300);
    border-radius: 6px;
    background: white;
    color: var(--task-gray-600);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.task-action-btn:hover {
    background: var(--task-gray-50);
    border-color: var(--task-gray-400);
    color: var(--task-gray-900);
}

.empty-row {
    padding: 60px 20px !important;
}

.empty-state-small {
    text-align: center;
    color: var(--task-gray-600);
}

.empty-state-small i {
    font-size: 48px;
    color: var(--task-gray-400);
    margin-bottom: 16px;
}

.empty-state-small p {
    margin: 0;
    font-size: 14px;
}

/* Pagination */
.all-tasks-pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}

@media (max-width: 1200px) {
    .all-tasks-table-wrapper {
        overflow-x: auto;
    }

    .all-tasks-table {
        min-width: 1000px;
    }
}

@media (max-width: 768px) {
    .all-tasks-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .filters-form {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function openTaskDetail(taskId) {
    window.location.href = `{{ route('tenant.manage.projects.tasks.show', [$username, 'TASK_ID']) }}`.replace('TASK_ID', taskId);
}

function sendReminder(taskId) {
    if (confirm('Send a reminder to the assignee?')) {
        fetch(`{{ route('tenant.manage.projects.tasks.remind', [$username, 'TASK_ID']) }}`.replace('TASK_ID', taskId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            alert('Reminder sent successfully!');
        })
        .catch(error => {
            alert('Failed to send reminder');
        });
    }
}

function editTask(taskId) {
    window.location.href = `{{ route('tenant.manage.projects.tasks.show', [$username, 'TASK_ID']) }}`.replace('TASK_ID', taskId);
}

function clearFilters() {
    window.location.href = '{{ route('tenant.manage.projects.tasks.all', $username) }}';
}

function exportTasks() {
    alert('Export functionality coming soon!');
}
</script>

@endsection