{{-- resources/views/tenant/manage/projects/tasks/components/task-card.blade.php --}}

@php
    // Priority colors and icons
    $priorityConfig = [
        'urgent' => ['color' => '#DE350B', 'bg' => 'rgba(222, 53, 11, 0.08)', 'icon' => 'fa-exclamation-circle', 'label' => 'Urgent'],
        'high' => ['color' => '#FF991F', 'bg' => 'rgba(255, 153, 31, 0.08)', 'icon' => 'fa-arrow-up', 'label' => 'High'],
        'medium' => ['color' => '#0065FF', 'bg' => 'rgba(0, 101, 255, 0.08)', 'icon' => 'fa-equals', 'label' => 'Medium'],
        'low' => ['color' => '#00875A', 'bg' => 'rgba(0, 135, 90, 0.08)', 'icon' => 'fa-arrow-down', 'label' => 'Low'],
    ];
    
    // Status colors
    $statusConfig = [
        'todo' => ['color' => '#6B778C', 'bg' => '#F4F5F7', 'label' => 'To Do'],
        'in-progress' => ['color' => '#0052CC', 'bg' => '#DEEBFF', 'label' => 'In Progress'],
        'review' => ['color' => '#FF991F', 'bg' => '#FFFAE6', 'label' => 'In Review'],
        'done' => ['color' => '#00875A', 'bg' => '#E3FCEF', 'label' => 'Done'],
        'blocked' => ['color' => '#DE350B', 'bg' => '#FFEBE6', 'label' => 'Blocked'],
        'postponed' => ['color' => '#8777D9', 'bg' => '#EAE6FF', 'label' => 'Postponed'],
    ];

    $priority = $priorityConfig[$task->priority] ?? $priorityConfig['medium'];
    $status = $statusConfig[$task->status] ?? $statusConfig['todo'];
    
    // Check relationships
    $isAssignedToMe = $task->assigned_to === $viewer->id;
    $isCreatedByMe = $task->reporter_id === $viewer->id;
    $isOverdue = $task->is_overdue;
    
    // Subtask progress
    $totalSubtasks = $task->subtasks->count();
    $completedSubtasks = $task->subtasks->where('completed', true)->count();
    $subtaskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
@endphp

<div class="task-card" 
     data-task-id="{{ $task->id }}" 
     onclick="openTaskDrawer({{ $task->id }})"
     style="cursor: pointer;">
    
    <!-- Task Header -->
    <div class="task-card-header">
        <!-- Priority Indicator -->
        <div class="task-priority" 
             style="background: {{ $priority['bg'] }}; color: {{ $priority['color'] }};"
             title="{{ $priority['label'] }} Priority">
            <i class="fas {{ $priority['icon'] }}"></i>
        </div>

        <!-- Task Key -->
        <span class="task-key">{{ $task->project->key }}-{{ $task->id }}</span>

        <!-- Assignment Badge -->
        @if($isAssignedToMe)
            <span class="task-assignment-badge my-tasks">
                <i class="fas fa-user"></i>
                Assigned to me
            </span>
        @endif

        @if($isCreatedByMe && !$isAssignedToMe)
            <span class="task-assignment-badge created-by-me">
                <i class="fas fa-pencil-alt"></i>
                Created by me
            </span>
        @endif

        <!-- Actions Menu -->
        <button class="task-card-menu" onclick="event.stopPropagation(); openTaskActions({{ $task->id }})">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </div>

    <!-- Task Title -->
    <h3 class="task-card-title">{{ $task->title }}</h3>

    <!-- Task Meta -->
    <div class="task-card-meta">
        <!-- Project Name -->
        <div class="task-meta-item">
            <i class="fas fa-folder"></i>
            <span>{{ $task->project->name }}</span>
        </div>

        <!-- Status Badge -->
        <div class="task-status-badge" 
             style="background: {{ $status['bg'] }}; color: {{ $status['color'] }};">
            {{ $status['label'] }}
        </div>

        <!-- Due Date -->
        @if($task->due_date)
            <div class="task-meta-item {{ $isOverdue ? 'overdue' : '' }}">
                <i class="fas fa-calendar"></i>
                <span>{{ $task->due_date->format('M d') }}</span>
                @if($isOverdue)
                    <span class="overdue-label">Overdue</span>
                @endif
            </div>
        @endif
    </div>

    <!-- Subtask Progress -->
    @if($totalSubtasks > 0)
        <div class="task-subtasks">
            <div class="task-subtasks-header">
                <i class="fas fa-check-square"></i>
                <span>{{ $completedSubtasks }}/{{ $totalSubtasks }} subtasks</span>
                <span class="task-subtasks-percent">{{ $subtaskProgress }}%</span>
            </div>
            <div class="task-subtasks-bar">
                <div class="task-subtasks-fill" style="width: {{ $subtaskProgress }}%;"></div>
            </div>
        </div>
    @endif

    <!-- Task Footer -->
    <div class="task-card-footer">
        <!-- Reporter (Who created it) -->
        {{-- <div class="task-footer-section">
            <span class="task-footer-label">Reporter:</span>
            <div class="task-user-mini">
                <img src="{{ $task->reporter->avatar_url }}" 
                     alt="{{ $task->reporter->name }}"
                     class="task-avatar-mini">
                <span>{{ $task->reporter->name }}</span>
            </div>
        </div>

        <!-- Assignee (Who's working on it) -->
        <div class="task-footer-section">
            <span class="task-footer-label">Assignee:</span>
            @if($task->assignee)
                <div class="task-user-mini">
                    <img src="{{ $task->assignee->avatar_url }}" 
                         alt="{{ $task->assignee->name }}"
                         class="task-avatar-mini">
                    <span>{{ $task->assignee->name }}</span>
                </div>
            @else
                <span class="task-unassigned">Unassigned</span>
            @endif
        </div> --}}

        <!-- Attachments Count -->
        @if($task->attachments->count() > 0)
            <div class="task-footer-icon">
                <i class="fas fa-paperclip"></i>
                <span>{{ $task->attachments->count() }}</span>
            </div>
        @endif
    </div>

    <!-- Overdue Banner -->
    @if($isOverdue)
        <div class="task-overdue-banner">
            <i class="fas fa-exclamation-triangle"></i>
            Overdue by {{ $task->due_date->diffForHumans() }}
        </div>
    @endif
</div>

<style>
/* ===== PROFESSIONAL TASK CARD ===== */

.task-card {
    background: #FFFFFF;
    border: 1px solid #DFE1E6;
    border-radius: 8px;
    padding: 16px;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.task-card:hover {
    box-shadow: 0 4px 12px rgba(9, 30, 66, 0.15);
    border-color: #0052CC;
    transform: translateY(-2px);
}

/* Task Header */
.task-card-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.task-priority {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.task-key {
    font-size: 12px;
    font-weight: 600;
    color: #6B778C;
    font-family: monospace;
    background: #F4F5F7;
    padding: 2px 6px;
    border-radius: 3px;
}

.task-assignment-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-left: auto;
}

.task-assignment-badge.my-tasks {
    background: #DEEBFF;
    color: #0052CC;
}

.task-assignment-badge.created-by-me {
    background: #E3FCEF;
    color: #00875A;
}

.task-card-menu {
    width: 24px;
    height: 24px;
    border: none;
    background: none;
    color: #6B778C;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}

.task-card-menu:hover {
    background: #F4F5F7;
    color: #172B4D;
}

/* Task Title */
.task-card-title {
    font-size: 14px;
    font-weight: 600;
    color: #172B4D;
    margin: 0 0 12px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Task Meta */
.task-card-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

.task-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: #6B778C;
}

.task-meta-item i {
    font-size: 11px;
}

.task-meta-item.overdue {
    color: #DE350B;
    font-weight: 600;
}

.overdue-label {
    background: #FFEBE6;
    color: #DE350B;
    padding: 1px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 700;
}

.task-status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

/* Subtasks */
.task-subtasks {
    margin-bottom: 12px;
    padding: 8px;
    background: #F4F5F7;
    border-radius: 6px;
}

.task-subtasks-header {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6B778C;
    margin-bottom: 6px;
}

.task-subtasks-percent {
    margin-left: auto;
    font-weight: 600;
    color: #0052CC;
}

.task-subtasks-bar {
    height: 4px;
    background: #DFE1E6;
    border-radius: 2px;
    overflow: hidden;
}

.task-subtasks-fill {
    height: 100%;
    background: linear-gradient(90deg, #0052CC 0%, #0065FF 100%);
    border-radius: 2px;
    transition: width 0.3s ease;
}

/* Task Footer */
.task-card-footer {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 12px;
    border-top: 1px solid #F4F5F7;
    font-size: 12px;
}

.task-footer-section {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
}

.task-footer-label {
    color: #6B778C;
    font-size: 11px;
}

.task-user-mini {
    display: flex;
    align-items: center;
    gap: 4px;
}

.task-avatar-mini {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid #DFE1E6;
}

.task-user-mini span {
    color: #172B4D;
    font-weight: 500;
    font-size: 12px;
}

.task-unassigned {
    color: #6B778C;
    font-style: italic;
}

.task-footer-icon {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #6B778C;
}

/* Overdue Banner */
.task-overdue-banner {
    background: #FFEBE6;
    color: #DE350B;
    padding: 6px 12px;
    margin: 12px -16px -16px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    border-top: 1px solid #FFBDAD;
}

/* Responsive */
@media (max-width: 768px) {
    .task-card-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .task-footer-section {
        width: 100%;
    }
}
</style>