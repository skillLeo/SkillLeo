{{-- resources/views/tenant/manage/projects/tasks/components/simple-task-card.blade.php --}}

@php
    // Props: $task, $mode ('assigned' or 'tracking')
    $isOverdue = $task->is_overdue ?? false;
    $dueLabel = $task->due_date ? $task->due_date->format('M d') : null;
    $taskKey = $task->project->key . '-' . $task->id;
    
    // For "assigned" mode - show who assigned it to me
    // For "tracking" mode - show who it's assigned to
    if ($mode === 'assigned') {
        $personName = $task->reporter->name ?? 'Unknown';
        $personAvatar = $task->reporter->avatar_url ?? asset('images/avatar-fallback.png');
        $personLabel = 'Created by';
    } else {
        $personName = $task->assignee->name ?? 'Unassigned';
        $personAvatar = $task->assignee->avatar_url ?? asset('images/avatar-fallback.png');
        $personLabel = 'Assigned to';
    }
    
    // Subtasks
    $totalSubs = $task->subtasks->count();
    $doneSubs = $task->subtasks->where('completed', true)->count();
    $subsProgress = $totalSubs > 0 ? round(($doneSubs / $totalSubs) * 100) : 0;
    
    // Priority icon
    $priorityIcons = [
        'urgent' => 'fa-exclamation-circle',
        'high' => 'fa-arrow-up',
        'medium' => 'fa-equals',
        'low' => 'fa-arrow-down'
    ];
    $priorityIcon = $priorityIcons[$task->priority] ?? 'fa-circle';
@endphp

<div class="simple-task-card {{ $mode }}" onclick="openTaskDetail({{ $task->id }})">
    
    <!-- Task Header -->
    <div class="stask-header">
        <span class="stask-key">{{ $taskKey }}</span>
        <span class="stask-priority priority-{{ $task->priority }}">
            <i class="fas {{ $priorityIcon }}"></i>
        </span>
    </div>

    <!-- Task Title -->
    <h3 class="stask-title">{{ $task->title }}</h3>

    <!-- Task Meta -->
    <div class="stask-meta">
        <span class="stask-project">
            <i class="fas fa-folder"></i>
            {{ $task->project->name }}
        </span>
        
        @if($dueLabel)
            <span class="stask-due {{ $isOverdue ? 'overdue' : '' }}">
                <i class="fas fa-calendar"></i>
                {{ $dueLabel }}
                @if($isOverdue)
                    <strong>OVERDUE</strong>
                @endif
            </span>
        @endif
    </div>

    <!-- Subtasks Progress (if any) -->
    @if($totalSubs > 0)
        <div class="stask-progress">
            <span class="stask-progress-label">
                {{ $doneSubs }}/{{ $totalSubs }} subtasks
            </span>
            <div class="stask-progress-bar">
                <div class="stask-progress-fill" style="width: {{ $subsProgress }}%;"></div>
            </div>
        </div>
    @endif

    <!-- Task Footer -->
    <div class="stask-footer">
        <div class="stask-person">
            <img src="{{ $personAvatar }}" alt="{{ $personName }}" class="stask-avatar">
            <div class="stask-person-info">
                <span class="stask-person-label">{{ $personLabel }}</span>
                <span class="stask-person-name">{{ $personName }}</span>
            </div>
        </div>

        @if($mode === 'assigned')
            <button class="stask-action-btn complete" 
                    onclick="event.stopPropagation(); completeTask({{ $task->id }})">
                <i class="fas fa-check"></i>
                Complete
            </button>
        @else
            <button class="stask-action-btn remind" 
                    onclick="event.stopPropagation(); remindAssignee({{ $task->id }})">
                <i class="fas fa-bell"></i>
                Remind
            </button>
        @endif
    </div>

</div>

<style>
.simple-task-card {
    background: white;
    border: 1px solid #DFE1E6;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.simple-task-card:hover {
    box-shadow: 0 4px 12px rgba(9, 30, 66, 0.15);
    border-color: #0052CC;
    transform: translateY(-1px);
}

/* Header */
.stask-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stask-key {
    font-family: monospace;
    font-size: 11px;
    font-weight: 700;
    color: #6B778C;
    background: #F4F5F7;
    padding: 4px 8px;
    border-radius: 4px;
}

.stask-priority {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.stask-priority.priority-urgent {
    background: #FFEBE6;
    color: #DE350B;
}

.stask-priority.priority-high {
    background: #FFFAE6;
    color: #FF991F;
}

.stask-priority.priority-medium {
    background: #DEEBFF;
    color: #0052CC;
}

.stask-priority.priority-low {
    background: #E3FCEF;
    color: #00875A;
}

/* Title */
.stask-title {
    font-size: 15px;
    font-weight: 600;
    color: #172B4D;
    margin: 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Meta */
.stask-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12px;
}

.stask-project,
.stask-due {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #6B778C;
}

.stask-due.overdue {
    color: #DE350B;
    font-weight: 700;
}

/* Progress */
.stask-progress {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.stask-progress-label {
    font-size: 11px;
    color: #6B778C;
    font-weight: 600;
}

.stask-progress-bar {
    height: 4px;
    background: #EBECF0;
    border-radius: 2px;
    overflow: hidden;
}

.stask-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #0052CC 0%, #0065FF 100%);
    border-radius: 2px;
    transition: width 0.3s;
}

/* Footer */
.stask-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #F4F5F7;
}

.stask-person {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.stask-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(9, 30, 66, 0.12);
}

.stask-person-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.stask-person-label {
    font-size: 10px;
    color: #6B778C;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.stask-person-name {
    font-size: 13px;
    color: #172B4D;
    font-weight: 600;
}

.stask-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.stask-action-btn.complete {
    background: #00875A;
    color: white;
}

.stask-action-btn.complete:hover {
    background: #00A572;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 135, 90, 0.3);
}

.stask-action-btn.remind {
    background: #0052CC;
    color: white;
}

.stask-action-btn.remind:hover {
    background: #0065FF;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 82, 204, 0.3);
}

@media (max-width: 640px) {
    .stask-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .stask-action-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function openTaskDetail(taskId) {
    console.log('Open task:', taskId);
    // window.location.href = `/tasks/${taskId}`;
}

function completeTask(taskId) {
    console.log('Complete task:', taskId);
    // Show modal to submit work
}

function remindAssignee(taskId) {
    console.log('Remind task:', taskId);
    if (confirm('Send a reminder to the assignee?')) {
        // Send reminder
    }
}
</script>