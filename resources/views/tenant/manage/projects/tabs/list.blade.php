{{-- resources/views/tenant/projects/tabs/list.blade.php --}}

<div class="project-list-view">
    <div class="project-list-header">
        <div class="project-list-filters">
            <button class="project-btn project-btn-secondary">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <button class="project-btn project-btn-secondary">
                <i class="fas fa-sort"></i>
                <span>Sort</span>
            </button>
        </div>
        <button class="project-btn project-btn-primary">
            <i class="fas fa-plus"></i>
            <span>Add Task</span>
        </button>
    </div>

    @if($project->tasks->count() > 0)
        <div class="project-list-table">
            <div class="project-list-table-header">
                <div class="project-list-col-task">Task</div>
                <div class="project-list-col-status">Status</div>
                <div class="project-list-col-priority">Priority</div>
                <div class="project-list-col-assignee">Assignee</div>
                <div class="project-list-col-due">Due Date</div>
                <div class="project-list-col-actions"></div>
            </div>

            @foreach($project->tasks as $task)
                <div class="project-list-table-row">
                    <div class="project-list-col-task">
                        <div class="project-list-task-info">
                            <h4 class="project-list-task-title">{{ $task->title }}</h4>
                            @if($task->subtasks->count() > 0)
                                @php
                                    $completedSubtasks = $task->subtasks->where('completed', true)->count();
                                @endphp
                                <span class="project-list-task-subtasks">
                                    <i class="fas fa-check-square"></i>
                                    {{ $completedSubtasks }}/{{ $task->subtasks->count() }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="project-list-col-status">
                        <span class="project-list-status status-{{ $task->status }}">
                            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                        </span>
                    </div>
                    
                    <div class="project-list-col-priority">
                        <span class="project-list-priority priority-{{ $task->priority }}">
                            <i class="fas fa-{{ 
                                $task->priority === 'urgent' ? 'exclamation-circle' :
                                ($task->priority === 'high' ? 'arrow-up' :
                                ($task->priority === 'medium' ? 'minus' : 'arrow-down'))
                            }}"></i>
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    
                    <div class="project-list-col-assignee">
                        @if($task->assignedTo)
                            <div class="project-list-assignee">
                                <img src="{{ $task->assignedTo->avatar_url }}" 
                                     alt="{{ $task->assignedTo->name }}"
                                     class="project-list-avatar">
                                <span>{{ $task->assignedTo->name }}</span>
                            </div>
                        @else
                            <span class="project-list-unassigned">Unassigned</span>
                        @endif
                    </div>
                    
                    <div class="project-list-col-due">
                        @if($task->due_date)
                            <span class="project-list-due-date {{ $task->due_date->isPast() ? 'overdue' : '' }}">
                                {{ $task->due_date->format('M d, Y') }}
                            </span>
                        @else
                            <span class="project-list-no-date">No date</span>
                        @endif
                    </div>
                    
                    <div class="project-list-col-actions">
                        <button class="project-icon-btn">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="project-empty-state">
            <div class="project-empty-state-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <h3 class="project-empty-state-title">No tasks yet</h3>
            <p class="project-empty-state-desc">Create your first task to get started</p>
            <button class="project-btn project-btn-primary">
                <i class="fas fa-plus"></i>
                <span>Add Task</span>
            </button>
        </div>
    @endif
</div>

<style>
    .project-list-view {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .project-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid var(--border);
    }

    .project-list-filters {
        display: flex;
        gap: 8px;
    }

    .project-list-table {
        overflow-x: auto;
    }

    .project-list-table-header,
    .project-list-table-row {
        display: grid;
        grid-template-columns: 2fr 120px 120px 180px 140px 60px;
        gap: 16px;
        padding: 16px 20px;
        align-items: center;
    }

    .project-list-table-header {
        background: var(--bg);
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .project-list-table-row {
        border-bottom: 1px solid var(--border);
        transition: background 0.2s;
    }

    .project-list-table-row:hover {
        background: var(--bg);
    }

    .project-list-task-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .project-list-task-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-heading);
        margin: 0;
    }

    .project-list-task-subtasks {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: var(--fs-micro);
        color: var(--text-muted);
        padding: 2px 8px;
        background: var(--bg);
        border-radius: 10px;
    }

    .project-list-status {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
    }

    .project-list-status.status-todo {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .project-list-status.status-in-progress {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .project-list-status.status-review {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .project-list-status.status-done {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .project-list-priority {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
    }

    .project-list-priority.priority-urgent {
        color: #ef4444;
    }

    .project-list-priority.priority-high {
        color: #f59e0b;
    }

    .project-list-priority.priority-medium {
        color: #3b82f6;
    }

    .project-list-priority.priority-low {
        color: #6b7280;
    }

    .project-list-assignee {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .project-list-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
    }

    .project-list-unassigned {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        font-style: italic;
    }

    .project-list-due-date {
        font-size: var(--fs-subtle);
        color: var(--text-body);
    }

    .project-list-due-date.overdue {
        color: #ef4444;
        font-weight: var(--fw-semibold);
    }

    .project-list-no-date {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        font-style: italic;
    }

    @media (max-width: 1200px) {
        .project-list-table-header,
        .project-list-table-row {
            grid-template-columns: 2fr 100px 100px 150px 120px 50px;
        }
    }

    @media (max-width: 768px) {
        .project-list-table-header,
        .project-list-table-row {
            grid-template-columns: 1fr;
        }

        .project-list-col-status,
        .project-list-col-priority,
        .project-list-col-assignee,
        .project-list-col-due {
            display: none;
        }
    }
</style>