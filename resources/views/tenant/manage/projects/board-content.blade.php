{{-- resources/views/tenant/projects/tabs/board.blade.php --}}

@php
    $statuses = ['todo', 'in-progress', 'review', 'done'];
    $statusLabels = [
        'todo' => 'To Do',
        'in-progress' => 'In Progress',
        'review' => 'Review',
        'done' => 'Done'
    ];
    $statusColors = [
        'todo' => '#6b7280',
        'in-progress' => '#3b82f6',
        'review' => '#f59e0b',
        'done' => '#10b981'
    ];
@endphp

<div class="project-board">
    @foreach($statuses as $status)
        @php
            $tasks = $project->tasks->where('status', $status);
        @endphp
        
        <div class="project-board-column">
            <div class="project-board-column-header">
                <div class="project-board-column-title">
                    <div class="project-board-status-dot" style="background: {{ $statusColors[$status] }};"></div>
                    <span>{{ $statusLabels[$status] }}</span>
                    <span class="project-board-count">{{ $tasks->count() }}</span>
                </div>
                <button class="project-icon-btn">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            
            <div class="project-board-column-body">
                @forelse($tasks as $task)
                    <div class="project-board-task-card">
                        <div class="project-board-task-header">
                            <span class="project-board-task-priority priority-{{ $task->priority }}">
                                <i class="fas fa-{{ 
                                    $task->priority === 'urgent' ? 'exclamation-circle' :
                                    ($task->priority === 'high' ? 'arrow-up' :
                                    ($task->priority === 'medium' ? 'minus' : 'arrow-down'))
                                }}"></i>
                            </span>
                            <button class="project-icon-btn">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        
                        <h4 class="project-board-task-title">{{ $task->title }}</h4>
                        
                        @if($task->notes)
                            <p class="project-board-task-notes">{{ Str::limit($task->notes, 100) }}</p>
                        @endif
                        
                        @if($task->subtasks->count() > 0)
                            @php
                                $completedSubtasks = $task->subtasks->where('completed', true)->count();
                            @endphp
                            <div class="project-board-task-subtasks">
                                <i class="fas fa-check-square"></i>
                                <span>{{ $completedSubtasks }}/{{ $task->subtasks->count() }}</span>
                            </div>
                        @endif
                        
                        <div class="project-board-task-footer">
                            <div class="project-board-task-meta">
                                @if($task->due_date)
                                    <span class="project-board-task-date">
                                        <i class="fas fa-calendar"></i>
                                        {{ $task->due_date->format('M d') }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($task->assignedTo)
                                <img src="{{ $task->assignedTo->avatar_url }}" 
                                     alt="{{ $task->assignedTo->name }}"
                                     class="project-board-task-avatar"
                                     title="{{ $task->assignedTo->name }}">
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="project-board-empty">
                        <i class="fas fa-inbox"></i>
                        <span>No tasks</span>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<style>
    .project-board {
        display: grid;
        grid-template-columns: repeat(4, minmax(280px, 1fr));
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 20px;
    }

    .project-board-column {
        background: var(--bg);
        border-radius: var(--radius);
        min-height: 600px;
    }

    .project-board-column-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid var(--border);
    }

    .project-board-column-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .project-board-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .project-board-count {
        padding: 2px 8px;
        background: var(--card);
        border-radius: 10px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
    }

    .project-board-column-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .project-board-task-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .project-board-task-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .project-board-task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .project-board-task-priority {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .project-board-task-priority.priority-urgent {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .project-board-task-priority.priority-high {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .project-board-task-priority.priority-medium {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .project-board-task-priority.priority-low {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .project-board-task-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 8px 0;
        line-height: 1.4;
    }

    .project-board-task-notes {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin: 0 0 12px 0;
        line-height: 1.5;
    }

    .project-board-task-subtasks {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .project-board-task-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .project-board-task-meta {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .project-board-task-date {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: var(--fs-micro);
        color: var(--text-muted);
    }

    .project-board-task-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--card);
    }

    .project-board-empty {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
        font-size: var(--fs-subtle);
    }

    .project-board-empty i {
        display: block;
        font-size: 32px;
        margin-bottom: 8px;
        opacity: 0.5;
    }

    @media (max-width: 1200px) {
        .project-board {
            grid-template-columns: repeat(2, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .project-board {
            grid-template-columns: 1fr;
        }
    }
</style>