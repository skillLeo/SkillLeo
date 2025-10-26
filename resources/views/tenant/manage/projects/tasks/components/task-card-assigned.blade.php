{{-- resources/views/tenant/manage/projects/tasks/components/task-card-assigned.blade.php --}}

@php
    // Props: $task
    $isOverdue = $task->is_overdue ?? false;
    $dueLabel = $task->due_date ? $task->due_date->format('M d, Y') : 'No due date';
    $keyLabel = ($task->project->key ?? 'TASK') . '-' . $task->id;
    $creatorName = $task->reporter->name ?? 'Unknown';
    $creatorAvatar = $task->reporter->avatar_url ?? asset('images/avatar-fallback.png');
    
    // Subtask progress
    $totalSubtasks = $task->subtasks->count();
    $completedSubtasks = $task->subtasks->where('completed', true)->count();
    $subtaskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
    
    // Priority styling
    $priorityColors = [
        'urgent' => ['bg' => '#FFEBE6', 'text' => '#DE350B', 'icon' => 'fa-exclamation-circle'],
        'high' => ['bg' => '#FFFAE6', 'text' => '#FF991F', 'icon' => 'fa-arrow-up'],
        'medium' => ['bg' => '#DEEBFF', 'text' => '#0052CC', 'icon' => 'fa-equals'],
        'low' => ['bg' => '#E3FCEF', 'text' => '#00875A', 'icon' => 'fa-arrow-down'],
    ];
    $priority = $priorityColors[$task->priority] ?? $priorityColors['medium'];
@endphp

<div class="task-card-assigned" onclick="openTaskDetail({{ $task->id }})">
    <!-- Header -->
    <div class="task-card-header">
        <div class="task-card-key">{{ $keyLabel }}</div>
        <div class="task-card-priority" 
             style="background: {{ $priority['bg'] }}; color: {{ $priority['text'] }};">
            <i class="fas {{ $priority['icon'] }}"></i>
        </div>
    </div>

    <!-- Title -->
    <h3 class="task-card-title">{{ $task->title }}</h3>

    <!-- Meta Info -->
    <div class="task-card-meta">
        <span class="task-card-project">
            <i class="fas fa-folder"></i>
            {{ $task->project->name }}
        </span>
        
        @if($isOverdue)
            <span class="task-card-due overdue">
                <i class="fas fa-exclamation-triangle"></i>
                Overdue
            </span>
        @else
            <span class="task-card-due">
                <i class="fas fa-calendar"></i>
                {{ $dueLabel }}
            </span>
        @endif
    </div>

    <!-- Subtasks Progress -->
    @if($totalSubtasks > 0)
        <div class="task-card-subtasks">
            <div class="task-card-subtasks-label">
                <i class="fas fa-check-square"></i>
                {{ $completedSubtasks }}/{{ $totalSubtasks }} subtasks
            </div>
            <div class="task-card-progress-bar">
                <div class="task-card-progress-fill" style="width: {{ $subtaskProgress }}%;"></div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="task-card-footer">
        <div class="task-card-creator">
            <img src="{{ $creatorAvatar }}" alt="{{ $creatorName }}" class="task-card-avatar">
            <span class="task-card-creator-label">Created by {{ $creatorName }}</span>
        </div>

        <div class="task-card-actions" onclick="event.stopPropagation()">
            @if($task->status === 'in-progress' || $task->status === 'todo')
                <button class="task-card-btn primary" 
                        onclick="openCompleteModal({{ $task->id }})">
                    <i class="fas fa-check"></i>
                    <span>Complete</span>
                </button>
            @elseif($task->status === 'review')
                <div class="task-card-badge waiting">
                    <i class="fas fa-clock"></i>
                    <span>Waiting Approval</span>
                </div>
            @elseif($task->status === 'blocked')
                <div class="task-card-badge blocked">
                    <i class="fas fa-ban"></i>
                    <span>Blocked</span>
                </div>
            @endif

            <button class="task-card-btn-icon" 
                    onclick="openTaskActions({{ $task->id }})"
                    title="More actions">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
    </div>
</div>

<style>
.task-card-assigned {
    background: white;
    border: 1px solid var(--task-gray-300);
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.task-card-assigned:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-color: var(--task-primary);
    transform: translateY(-2px);
}

/* Header */
.task-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.task-card-key {
    font-size: 12px;
    font-weight: 700;
    color: var(--task-gray-600);
    font-family: monospace;
    background: var(--task-gray-100);
    padding: 4px 8px;
    border-radius: 4px;
}

.task-card-priority {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

/* Title */
.task-card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--task-gray-900);
    margin: 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Meta */
.task-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 12px;
}

.task-card-project,
.task-card-due {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: var(--task-gray-100);
    border-radius: 4px;
    color: var(--task-gray-700);
}

.task-card-due.overdue {
    background: #FFEBE6;
    color: var(--task-danger);
    font-weight: 600;
}

/* Subtasks */
.task-card-subtasks {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.task-card-subtasks-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--task-gray-600);
    font-weight: 600;
}

.task-card-progress-bar {
    height: 6px;
    background: var(--task-gray-200);
    border-radius: 3px;
    overflow: hidden;
}

.task-card-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--task-primary) 0%, #0065FF 100%);
    border-radius: 3px;
    transition: width 0.3s;
}

/* Footer */
.task-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid var(--task-gray-200);
}

.task-card-creator {
    display: flex;
    align-items: center;
    gap: 8px;
}

.task-card-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1px solid var(--task-gray-300);
}

.task-card-creator-label {
    font-size: 12px;
    color: var(--task-gray-600);
}

.task-card-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.task-card-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border: 1px solid var(--task-gray-300);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
    color: var(--task-gray-800);
}

.task-card-btn:hover {
    background: var(--task-gray-50);
    border-color: var(--task-gray-400);
}

.task-card-btn.primary {
    background: var(--task-primary);
    border-color: var(--task-primary);
    color: white;
}

.task-card-btn.primary:hover {
    background: #0065FF;
    border-color: #0065FF;
}

.task-card-btn-icon {
    width: 28px;
    height: 28px;
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

.task-card-btn-icon:hover {
    background: var(--task-gray-50);
    border-color: var(--task-gray-400);
    color: var(--task-gray-900);
}

.task-card-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.task-card-badge.waiting {
    background: #FFFAE6;
    color: var(--task-warning);
}

.task-card-badge.blocked {
    background: #FFEBE6;
    color: var(--task-danger);
}
</style>

<script>
function openTaskDetail(taskId) {
    window.location.href = `{{ route('tenant.manage.projects.tasks.show', [$username, 'TASK_ID']) }}`.replace('TASK_ID', taskId);
}

function openCompleteModal(taskId) {
    console.log('Open complete modal for task:', taskId);
    // This would open a modal for submitting work
}

function openTaskActions(taskId) {
    console.log('Open task actions for:', taskId);
    // This would open a dropdown menu
}
</script>