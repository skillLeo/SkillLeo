{{-- resources/views/tenant/manage/projects/tasks/mine.blade.php --}}
@extends('tenant.manage.app')

@section('main')

@php
    $colorPalette = ['#667eea','#f093fb','#4facfe','#43e97b','#fa709a','#feca57','#48dbfb','#ff9ff3'];
@endphp

<!-- Page Header -->
<div class="tasks-page-header">
    <div class="tasks-header-content">
        <div class="tasks-header-top">
            <h1 class="tasks-page-title">
                <i class="fas fa-check-circle"></i>
                My Tasks
            </h1>
            <div class="tasks-header-actions">
                <button class="task-header-btn" onclick="toggleTaskView()">
                    <i class="fas fa-th-large"></i>
                    <span>Change View</span>
                </button>
                <button class="task-header-btn task-header-btn-primary" onclick="openCreateTaskModal()">
                    <i class="fas fa-plus"></i>
                    <span>New Task</span>
                </button>
            </div>
        </div>
        <p class="tasks-page-subtitle">
            Manage your tasks across all projects. Drag to prioritize, check off to complete.
        </p>
    </div>

    <!-- Quick Filters -->
    <div class="tasks-quick-filters">
        <button class="quick-filter-btn active" data-filter="all">
            <span class="filter-label">All Tasks</span>
            <span class="filter-count">{{ $tasksByProject->flatten()->count() }}</span>
        </button>
        <button class="quick-filter-btn" data-filter="overdue">
            <span class="filter-label">Overdue</span>
            <span class="filter-count filter-count-danger">{{ $tasksByProject->flatten()->where('is_overdue', true)->count() }}</span>
        </button>
        <button class="quick-filter-btn" data-filter="today">
            <span class="filter-label">Due Today</span>
            <span class="filter-count">{{ $tasksByProject->flatten()->where('due_date', today())->count() }}</span>
        </button>
        <button class="quick-filter-btn" data-filter="in-progress">
            <span class="filter-label">In Progress</span>
            <span class="filter-count">{{ $tasksByProject->flatten()->where('status', 'in-progress')->count() }}</span>
        </button>
    </div>
</div>

<!-- Tasks Content -->
@forelse($tasksByProject as $projectId => $taskList)
    @php
        $meta = $projectMeta[$projectId] ?? null;
        $project = $meta['project'] ?? null;
        $projKey = $project?->key ?? 'PRJ';
        $projName = $project?->name ?? 'Untitled Project';
        $progressPct = min($meta['progressPct'] ?? 0, 100);
        $doneUnits = $meta['doneUnits'] ?? 0;
        $totalUnits = $meta['totalUnits'] ?? 0;
        $colorIndex = $project?->id ? $project->id % count($colorPalette) : 0;
        $projColor = $colorPalette[$colorIndex];
    @endphp

    <div class="project-section" data-project-id="{{ $projectId }}">
        <!-- Project Header -->
        <div class="project-section-header">
            <div class="project-section-left">
                <div class="project-avatar" style="background: {{ $projColor }}">
                    {{ \Illuminate\Support\Str::of($projKey)->substr(0,2)->upper() }}
                </div>
                <div class="project-info">
                    <div class="project-name-row">
                        <h3 class="project-name">{{ $projName }}</h3>
                        <span class="project-key-badge">{{ $projKey }}</span>
                    </div>
                    <div class="project-meta-row">
                        <span class="project-meta-item">
                            <i class="fas fa-tasks"></i>
                            {{ $taskList->count() }} tasks
                        </span>
                        <span class="project-meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ $meta['dueDate'] ?? 'No due date' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="project-section-right">
                <!-- Progress Card -->
                <div class="project-progress-card" id="progress-card-{{ $projectId }}">
                    <div class="progress-info">
                        <span class="progress-percentage" data-progress-value>{{ $progressPct }}%</span>
                        <span class="progress-text" data-progress-stats>{{ $doneUnits }}/{{ $totalUnits }}</span>
                    </div>
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" 
                                 id="progress-bar-{{ $projectId }}"
                                 style="width: {{ $progressPct }}%;">
                            </div>
                        </div>
                    </div>
                </div>

                <button class="project-menu-btn" onclick="toggleProjectMenu({{ $projectId }})">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="tasks-list-container">
            <ul class="tasks-list" 
                data-project-id="{{ $projectId }}"
                ondragover="taskDragOver(event)"
                ondrop="taskDrop(event)">
                
                @foreach($taskList as $task)
                    @php
                        $taskKey = ($task->project?->key ?? 'TASK') . '-' . $task->id;
                        $isOverdue = $task->is_overdue;
                        $subtasks = $task->subtasks ?? collect();
                        $hasSubs = $subtasks->count() > 0;
                        $subsCompleted = $subtasks->where('completed', true)->count();
                    @endphp

                    <li class="task-item" 
                        data-task-id="{{ $task->id }}"
                        data-project-id="{{ $projectId }}"
                        draggable="true"
                        ondragstart="taskDragStart(event)"
                        ondragend="taskDragEnd(event)">
                        
                        <!-- Task Main Content -->
                        <div class="task-content">
                            <!-- Left Section -->
                            <div class="task-left">
                                <!-- Drag Handle -->
                                <button class="task-drag-handle" title="Drag to reorder">
                                    <i class="fas fa-grip-vertical"></i>
                                </button>

                                <!-- Complete Checkbox -->
                                <div class="task-checkbox-wrapper">
                                    <input type="checkbox" 
                                           class="task-checkbox"
                                           id="task-{{ $task->id }}"
                                           @checked($task->status === 'done')
                                           onchange="handleTaskComplete({{ $task->id }}, {{ $projectId }}, this.checked, {{ $hasSubs ? 'true' : 'false' }})">
                                    <label for="task-{{ $task->id }}" class="task-checkbox-label"></label>
                                </div>

                                <!-- Task Info -->
                                <div class="task-info">
                                    <div class="task-header-row">
                                        <span class="task-key">{{ $taskKey }}</span>
                                        @include('tenant.manage.projects.tasks.components.task-status-badge', ['status' => $task->status])
                                        
                                        @if($isOverdue)
                                            <span class="task-badge task-badge-danger">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Overdue
                                            </span>
                                        @endif
                                    </div>

                                    <h4 class="task-title" onclick="openTaskDrawer({{ $task->id }})">
                                        {{ $task->title }}
                                    </h4>

                                    @if($task->notes)
                                        <p class="task-description">
                                            {{ \Illuminate\Support\Str::limit($task->notes, 120) }}
                                        </p>
                                    @endif

                                    <!-- Task Meta -->
                                    <div class="task-meta">
                                        @if($hasSubs)
                                            <button class="task-meta-item task-subtasks-toggle" 
                                                    onclick="toggleSubtasks({{ $task->id }})"
                                                    data-task-id="{{ $task->id }}">
                                                <i class="fas fa-chevron-right"></i>
                                                <span>{{ $subsCompleted }}/{{ $subtasks->count() }} subtasks</span>
                                            </button>
                                        @endif

                                        @if($task->due_date)
                                            <span class="task-meta-item task-due-date {{ $isOverdue ? 'overdue' : '' }}">
                                                <i class="fas fa-calendar"></i>
                                                {{ $task->due_date->format('M d') }}
                                            </span>
                                        @endif

                                        @if($task->assignee)
                                            <span class="task-meta-item">
                                                <img src="{{ $task->assignee->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->assignee->name) }}" 
                                                     alt="{{ $task->assignee->name }}"
                                                     class="task-assignee-avatar">
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Flags -->
                                    @if($task->status === \App\Models\Task::STATUS_BLOCKED && $task->blocked_reason)
                                        <div class="task-flag task-flag-blocked">
                                            <i class="fas fa-ban"></i>
                                            {{ $task->blocked_reason }}
                                        </div>
                                    @endif

                                    @if($task->status === \App\Models\Task::STATUS_POSTPONED && $task->postponed_until)
                                        <div class="task-flag task-flag-postponed">
                                            <i class="fas fa-clock"></i>
                                            Until {{ \Carbon\Carbon::parse($task->postponed_until)->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right Actions -->
                            <div class="task-actions">
                                <button class="task-action-btn task-action-postpone" 
                                        onclick="openPostponeModal({{ $task->id }}, {{ $projectId }})"
                                        title="Postpone">
                                    <i class="fas fa-clock"></i>
                                </button>
                                <button class="task-action-btn" 
                                        onclick="openTaskDrawer({{ $task->id }})"
                                        title="View details">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Subtasks Panel (Collapsible) -->
                        @if($hasSubs)
                            <div class="subtasks-container" id="subtasks-{{ $task->id }}">
                                <ul class="subtasks-list" 
                                    data-task-id="{{ $task->id }}"
                                    ondragover="subtaskDragOver(event)"
                                    ondrop="subtaskDrop(event)">
                                    @foreach($subtasks as $sub)
                                        <li class="subtask-item"
                                            data-subtask-id="{{ $sub->id }}"
                                            draggable="true"
                                            ondragstart="subtaskDragStart(event)"
                                            ondragend="subtaskDragEnd(event)">
                                            
                                            <button class="subtask-drag-handle">
                                                <i class="fas fa-grip-lines"></i>
                                            </button>

                                            <div class="subtask-checkbox-wrapper">
                                                <input type="checkbox" 
                                                       class="subtask-checkbox"
                                                       id="subtask-{{ $sub->id }}"
                                                       @checked($sub->completed)
                                                       onchange="handleSubtaskComplete({{ $task->id }}, {{ $sub->id }}, {{ $projectId }}, this.checked)">
                                                <label for="subtask-{{ $sub->id }}" class="subtask-checkbox-label"></label>
                                            </div>

                                            <span class="subtask-title">{{ $sub->title }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@empty
    <!-- Empty State -->
    <div class="tasks-empty-state">
        <div class="empty-state-illustration">
            <i class="fas fa-check-double"></i>
        </div>
        <h3 class="empty-state-title">All caught up! 🎉</h3>
        <p class="empty-state-description">
            No active tasks assigned to you right now. Time to celebrate or pick up something new!
        </p>
        <button class="empty-state-btn" onclick="openCreateTaskModal()">
            <i class="fas fa-plus"></i>
            Create New Task
        </button>
    </div>
@endforelse

<!-- Postpone Modal -->
<div class="modal-overlay" id="postponeModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-clock"></i>
                Postpone Task
            </h3>
            <button class="modal-close" onclick="closePostponeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Postpone until</label>
                <input type="date" class="form-input" id="postponeDate" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Reason (optional)</label>
                <textarea class="form-textarea" id="postponeReason" rows="3" placeholder="Why are you postponing this task?"></textarea>
            </div>
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" id="postponeSubtasks">
                    <span>Also postpone all subtasks</span>
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-secondary" onclick="closePostponeModal()">Cancel</button>
            <button class="modal-btn modal-btn-primary" onclick="confirmPostpone()">
                <i class="fas fa-clock"></i>
                Postpone Task
            </button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p>Updating...</p>
    </div>
</div>

<style>
/* ===================================
   MODERN TASK MANAGEMENT UI
   Inspired by Jira/Linear/Atlassian
=================================== */

:root {
    --task-primary: #0052CC;
    --task-primary-hover: #0747A6;
    --task-success: #00875A;
    --task-warning: #FF991F;
    --task-danger: #DE350B;
    --task-info: #00B8D9;
    --task-bg: #F4F5F7;
    --task-card: #FFFFFF;
    --task-border: #DFE1E6;
    --task-text: #172B4D;
    --task-text-subtle: #5E6C84;
    --task-hover: #EBECF0;
    --task-shadow: 0 1px 2px rgba(0,0,0,0.08);
    --task-shadow-lg: 0 4px 12px rgba(0,0,0,0.1);
    --task-radius: 8px;
    --task-transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Page Header */
.tasks-page-header {
    background: var(--task-card);
    border-bottom: 1px solid var(--task-border);
    padding: 24px 32px;
    margin: -24px -32px 32px;
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.95);
}

.tasks-header-content {
    max-width: 1400px;
    margin: 0 auto;
}

.tasks-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.tasks-page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--task-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.tasks-page-title i {
    color: var(--task-primary);
    font-size: 20px;
}

.tasks-header-actions {
    display: flex;
    gap: 12px;
}

.task-header-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 36px;
    padding: 0 16px;
    background: var(--task-card);
    border: 1px solid var(--task-border);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    color: var(--task-text);
    cursor: pointer;
    transition: var(--task-transition);
}

.task-header-btn:hover {
    background: var(--task-hover);
    border-color: var(--task-primary);
}

.task-header-btn-primary {
    background: var(--task-primary);
    color: white;
    border-color: var(--task-primary);
}

.task-header-btn-primary:hover {
    background: var(--task-primary-hover);
}

.tasks-page-subtitle {
    font-size: 14px;
    color: var(--task-text-subtle);
    margin: 0 0 20px 0;
}

/* Quick Filters */
.tasks-quick-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.quick-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 32px;
    padding: 0 12px;
    background: var(--task-card);
    border: 1px solid var(--task-border);
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    color: var(--task-text-subtle);
    cursor: pointer;
    transition: var(--task-transition);
}

.quick-filter-btn:hover {
    background: var(--task-hover);
    color: var(--task-text);
}

.quick-filter-btn.active {
    background: var(--task-primary);
    color: white;
    border-color: var(--task-primary);
}

.filter-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.quick-filter-btn.active .filter-count {
    background: rgba(255, 255, 255, 0.2);
}

.filter-count-danger {
    background: var(--task-danger);
    color: white;
}

/* Project Section */
.project-section {
    background: var(--task-card);
    border: 1px solid var(--task-border);
    border-radius: var(--task-radius);
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: var(--task-shadow);
    transition: var(--task-transition);
}

.project-section:hover {
    box-shadow: var(--task-shadow-lg);
}

/* Project Header */
.project-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--task-border);
    background: linear-gradient(to bottom, #FAFBFC 0%, #FFFFFF 100%);
}

.project-section-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
    min-width: 0;
}

.project-avatar {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.project-info {
    flex: 1;
    min-width: 0;
}

.project-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
}

.project-name {
    font-size: 16px;
    font-weight: 600;
    color: var(--task-text);
    margin: 0;
}

.project-key-badge {
    display: inline-flex;
    align-items: center;
    height: 20px;
    padding: 0 8px;
    background: var(--task-bg);
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    color: var(--task-text-subtle);
    font-family: monospace;
}

.project-meta-row {
    display: flex;
    align-items: center;
    gap: 16px;
}

.project-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--task-text-subtle);
}

.project-meta-item i {
    font-size: 12px;
}

.project-section-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Progress Card */
.project-progress-card {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 120px;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}

.progress-percentage {
    font-size: 20px;
    font-weight: 700;
    color: var(--task-primary);
}

.progress-text {
    font-size: 12px;
    color: var(--task-text-subtle);
}

.progress-bar-wrapper {
    width: 100%;
}

.progress-bar-track {
    height: 6px;
    background: var(--task-bg);
    border-radius: 3px;
    overflow: hidden;
    position: relative;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--task-primary) 0%, #0747A6 100%);
    border-radius: 3px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.progress-bar-fill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.project-menu-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: 1px solid var(--task-border);
    border-radius: 6px;
    color: var(--task-text-subtle);
    cursor: pointer;
    transition: var(--task-transition);
}

.project-menu-btn:hover {
    background: var(--task-hover);
    color: var(--task-text);
}

/* Tasks List */
.tasks-list-container {
    padding: 0;
}

.tasks-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

/* Task Item */
.task-item {
    border-bottom: 1px solid var(--task-border);
    transition: var(--task-transition);
    background: white;
}

.task-item:last-child {
    border-bottom: none;
}

.task-item:hover {
    background: #FAFBFC;
}

.task-item.dragging {
    opacity: 0.5;
    background: var(--task-primary);
    color: white;
}

.task-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px 24px;
    gap: 16px;
}

.task-left {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1;
    min-width: 0;
}

/* Drag Handle */
.task-drag-handle {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--task-text-subtle);
    cursor: grab;
    opacity: 0;
    transition: var(--task-transition);
    flex-shrink: 0;
}

.task-item:hover .task-drag-handle {
    opacity: 1;
}

.task-drag-handle:active {
    cursor: grabbing;
}

/* Custom Checkbox */
.task-checkbox-wrapper {
    position: relative;
    flex-shrink: 0;
}

.task-checkbox {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.task-checkbox-label {
    width: 20px;
    height: 20px;
    display: block;
    border: 2px solid var(--task-border);
    border-radius: 4px;
    cursor: pointer;
    transition: var(--task-transition);
    position: relative;
}

.task-checkbox-label::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    color: white;
    font-size: 10px;
    transition: transform 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.task-checkbox:checked + .task-checkbox-label {
    background: var(--task-success);
    border-color: var(--task-success);
}

.task-checkbox:checked + .task-checkbox-label::after {
    transform: translate(-50%, -50%) scale(1);
}

.task-checkbox:hover + .task-checkbox-label {
    border-color: var(--task-primary);
}

/* Task Info */
.task-info {
    flex: 1;
    min-width: 0;
}

.task-header-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.task-key {
    font-size: 12px;
    font-weight: 600;
    color: var(--task-text-subtle);
    font-family: monospace;
    user-select: text;
}

.task-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    height: 20px;
    padding: 0 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.task-badge-danger {
    background: #FFEBE6;
    color: var(--task-danger);
}

.task-title {
    font-size: 14px;
    font-weight: 500;
    color: var(--task-text);
    margin: 0 0 4px 0;
    line-height: 1.4;
    cursor: pointer;
    transition: var(--task-transition);
}

.task-title:hover {
    color: var(--task-primary);
}

.task-description {
    font-size: 13px;
    color: var(--task-text-subtle);
    line-height: 1.5;
    margin: 4px 0 8px 0;
}

.task-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.task-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--task-text-subtle);
}

.task-subtasks-toggle {
    background: none;
    border: none;
    cursor: pointer;
    transition: var(--task-transition);
    font-weight: 500;
}

.task-subtasks-toggle:hover {
    color: var(--task-primary);
}

.task-subtasks-toggle i {
    transition: transform 0.2s;
}

.task-subtasks-toggle.open i {
    transform: rotate(90deg);
}

.task-due-date.overdue {
    color: var(--task-danger);
    font-weight: 600;
}

.task-assignee-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Task Flags */
.task-flag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    margin-top: 8px;
}

.task-flag-blocked {
    background: #FFEBE6;
    color: var(--task-danger);
    border: 1px solid #FFBDAD;
}

.task-flag-postponed {
    background: #FFF4E5;
    color: var(--task-warning);
    border: 1px solid #FFE380;
}

/* Task Actions */
.task-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: var(--task-transition);
}

.task-item:hover .task-actions {
    opacity: 1;
}

.task-action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid var(--task-border);
    border-radius: 6px;
    color: var(--task-text-subtle);
    cursor: pointer;
    transition: var(--task-transition);
}

.task-action-btn:hover {
    background: var(--task-hover);
    color: var(--task-text);
    border-color: var(--task-primary);
}

.task-action-postpone:hover {
    background: #FFF4E5;
    color: var(--task-warning);
    border-color: var(--task-warning);
}

/* Subtasks Container */
.subtasks-container {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #FAFBFC;
    border-top: 1px solid var(--task-border);
}

.subtasks-container.open {
    max-height: 1000px;
}

.subtasks-list {
    list-style: none;
    margin: 0;
    padding: 16px 24px 16px 72px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Subtask Item */
.subtask-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: white;
    border: 1px solid var(--task-border);
    border-radius: 6px;
    transition: var(--task-transition);
}

.subtask-item:hover {
    border-color: var(--task-primary);
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}

.subtask-item.dragging {
    opacity: 0.5;
    background: var(--task-info);
}

.subtask-drag-handle {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--task-text-subtle);
    cursor: grab;
    opacity: 0;
    transition: var(--task-transition);
}

.subtask-item:hover .subtask-drag-handle {
    opacity: 1;
}

.subtask-drag-handle:active {
    cursor: grabbing;
}

.subtask-checkbox-wrapper {
    position: relative;
    flex-shrink: 0;
}

.subtask-checkbox {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.subtask-checkbox-label {
    width: 18px;
    height: 18px;
    display: block;
    border: 2px solid var(--task-border);
    border-radius: 4px;
    cursor: pointer;
    transition: var(--task-transition);
    position: relative;
}

.subtask-checkbox-label::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    color: white;
    font-size: 9px;
    transition: transform 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.subtask-checkbox:checked + .subtask-checkbox-label {
    background: var(--task-success);
    border-color: var(--task-success);
}

.subtask-checkbox:checked + .subtask-checkbox-label::after {
    transform: translate(-50%, -50%) scale(1);
}

.subtask-title {
    flex: 1;
    font-size: 13px;
    color: var(--task-text);
    line-height: 1.4;
}

.subtask-checkbox:checked ~ .subtask-title {
    text-decoration: line-through;
    color: var(--task-text-subtle);
}

/* Empty State */
.tasks-empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--task-card);
    border: 2px dashed var(--task-border);
    border-radius: var(--task-radius);
    margin: 40px auto;
    max-width: 500px;
}

.empty-state-illustration {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    font-size: 32px;
    color: white;
}

.empty-state-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--task-text);
    margin: 0 0 8px 0;
}

.empty-state-description {
    font-size: 14px;
    color: var(--task-text-subtle);
    line-height: 1.6;
    margin: 0 0 24px 0;
}

.empty-state-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 20px;
    background: var(--task-primary);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--task-transition);
}

.empty-state-btn:hover {
    background: var(--task-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(9, 30, 66, 0.54);
    backdrop-filter: blur(4px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s;
}

.modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    width: 90%;
    max-width: 500px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--task-border);
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--task-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    border-radius: 6px;
    color: var(--task-text-subtle);
    cursor: pointer;
    transition: var(--task-transition);
}

.modal-close:hover {
    background: var(--task-hover);
    color: var(--task-text);
}

.modal-body {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--task-text);
    margin-bottom: 8px;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 10px 12px;
    background: white;
    border: 1px solid var(--task-border);
    border-radius: 6px;
    font-size: 14px;
    color: var(--task-text);
    transition: var(--task-transition);
    font-family: inherit;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--task-primary);
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.form-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.form-checkbox input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--task-primary);
}

.form-checkbox span {
    font-size: 14px;
    color: var(--task-text);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    border-top: 1px solid var(--task-border);
    background: var(--task-bg);
}

.modal-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 36px;
    padding: 0 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--task-transition);
}

.modal-btn-secondary {
    background: white;
    color: var(--task-text);
    border: 1px solid var(--task-border);
}

.modal-btn-secondary:hover {
    background: var(--task-hover);
}

.modal-btn-primary {
    background: var(--task-primary);
    color: white;
}

.modal-btn-primary:hover {
    background: var(--task-primary-hover);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    z-index: 2000;
    display: none;
    align-items: center;
    justify-content: center;
}

.loading-overlay.active {
    display: flex;
}

.loading-spinner {
    text-align: center;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--task-border);
    border-top-color: var(--task-primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-spinner p {
    font-size: 14px;
    color: var(--task-text-subtle);
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .tasks-page-header {
        padding: 16px 20px;
        margin: -16px -20px 24px;
    }

    .tasks-header-top {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .tasks-page-title {
        font-size: 20px;
    }

    .tasks-header-actions {
        width: 100%;
    }

    .task-header-btn {
        flex: 1;
        justify-content: center;
    }

    .tasks-quick-filters {
        gap: 6px;
    }

    .quick-filter-btn {
        font-size: 12px;
        padding: 0 10px;
    }

    .project-section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .project-section-right {
        width: 100%;
        justify-content: space-between;
    }

    .project-progress-card {
        flex: 1;
    }

    .task-content {
        flex-direction: column;
        gap: 12px;
    }

    .task-actions {
        opacity: 1;
        width: 100%;
        justify-content: flex-end;
    }

    .subtasks-list {
        padding: 12px 20px;
    }

    .modal-container {
        width: 95%;
        margin: 20px;
    }
}

/* Animations */
@keyframes taskComplete {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.task-checkbox:checked + .task-checkbox-label {
    animation: taskComplete 0.3s ease;
}

/* Print Styles */
@media print {
    .tasks-page-header,
    .task-actions,
    .project-menu-btn,
    .task-drag-handle,
    .subtask-drag-handle {
        display: none !important;
    }

    .project-section {
        break-inside: avoid;
        box-shadow: none;
    }
}
</style>

<script>
// ===================================
// CSRF TOKEN HELPER
// ===================================
function csrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
}

// ===================================
// LOADING STATE
// ===================================
function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
}

// ===================================
// NOTIFICATIONS
// ===================================
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
        max-width: 400px;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ===================================
// PROGRESS UPDATE
// ===================================
function updateProjectProgressUI(projectId, progress) {
    if (!progress) return;
    
    const card = document.getElementById(`progress-card-${projectId}`);
    const bar = document.getElementById(`progress-bar-${projectId}`);
    if (!card || !bar) return;

    const pct = progress.progressPct ?? 0;
    const doneUnits = progress.doneUnits ?? 0;
    const totalUnits = progress.totalUnits ?? 0;

    const valueEl = card.querySelector('[data-progress-value]');
    const statsEl = card.querySelector('[data-progress-stats]');

    if (valueEl) valueEl.textContent = pct + '%';
    if (statsEl) statsEl.textContent = `${doneUnits}/${totalUnits}`;
    
    // Animate the progress bar
    bar.style.width = pct + '%';
}

// ===================================
// TASK COMPLETION (WITH CASCADING)
// ===================================
async function handleTaskComplete(taskId, projectId, completed, hasSubtasks) {
    if (completed && hasSubtasks) {
        const confirmMsg = 'This task has subtasks. Mark all subtasks as complete too?';
        if (!confirm(confirmMsg)) {
            // Revert checkbox
            document.getElementById(`task-${taskId}`).checked = false;
            return;
        }
    }

    showLoading();

    const url = "{{ route('tenant.manage.projects.tasks.quick-status', [$username, 'TASK_ID']) }}"
        .replace('TASK_ID', taskId);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                status: completed ? 'done' : 'in-progress',
                cascade_subtasks: completed && hasSubtasks
            })
        });

        if (!res.ok) throw new Error('Failed to update task');

        const data = await res.json();
        updateProjectProgressUI(projectId, data.progress);

        // If completed with subtasks, mark all subtasks as complete
        if (completed && hasSubtasks) {
            const subtaskCheckboxes = document.querySelectorAll(
                `#subtasks-${taskId} .subtask-checkbox`
            );
            subtaskCheckboxes.forEach(cb => {
                cb.checked = true;
                cb.disabled = true;
            });
        }

        showNotification(
            completed ? '✓ Task completed!' : 'Task reopened',
            'success'
        );

        // Visual feedback
        const taskItem = document.querySelector(`[data-task-id="${taskId}"]`);
        if (taskItem && completed) {
            taskItem.style.opacity = '0.6';
            setTimeout(() => {
                taskItem.style.transition = 'all 0.5s ease';
                taskItem.style.transform = 'scale(0.95)';
            }, 100);
        }

    } catch (error) {
        console.error('Task complete error:', error);
        showNotification('Failed to update task', 'error');
        document.getElementById(`task-${taskId}`).checked = !completed;
    } finally {
        hideLoading();
    }
}

// ===================================
// SUBTASK COMPLETION
// ===================================
async function handleSubtaskComplete(taskId, subtaskId, projectId, completed) {
    const url = "{{ route('tenant.manage.projects.tasks.subtasks.toggle-complete', [$username, 'TASK_ID', 'SUBTASK_ID']) }}"
        .replace('TASK_ID', taskId)
        .replace('SUBTASK_ID', subtaskId);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ completed })
        });

        if (!res.ok) throw new Error('Failed to update subtask');

        const data = await res.json();
        updateProjectProgressUI(projectId, data.progress);

        // Update subtask count in task meta
        updateSubtaskCount(taskId);

    } catch (error) {
        console.error('Subtask complete error:', error);
        showNotification('Failed to update subtask', 'error');
        document.getElementById(`subtask-${subtaskId}`).checked = !completed;
    }
}

function updateSubtaskCount(taskId) {
    const container = document.getElementById(`subtasks-${taskId}`);
    if (!container) return;

    const checkboxes = container.querySelectorAll('.subtask-checkbox');
    const completed = Array.from(checkboxes).filter(cb => cb.checked).length;
    const total = checkboxes.length;

    const toggle = document.querySelector(`[data-task-id="${taskId}"].task-subtasks-toggle`);
    if (toggle) {
        toggle.querySelector('span').textContent = `${completed}/${total} subtasks`;
    }
}

// ===================================
// SUBTASKS TOGGLE
// ===================================
function toggleSubtasks(taskId) {
    const container = document.getElementById(`subtasks-${taskId}`);
    const toggle = document.querySelector(`[data-task-id="${taskId}"].task-subtasks-toggle`);
    
    if (!container) return;

    const isOpen = container.classList.contains('open');
    
    if (isOpen) {
        container.classList.remove('open');
        toggle?.classList.remove('open');
    } else {
        container.classList.add('open');
        toggle?.classList.add('open');
    }
}

// ===================================
// POSTPONE MODAL
// ===================================
let postponeTaskData = null;

function openPostponeModal(taskId, projectId) {
    postponeTaskData = { taskId, projectId };
    document.getElementById('postponeModal').classList.add('active');
    
    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('postponeDate').min = tomorrow.toISOString().split('T')[0];
}

function closePostponeModal() {
    postponeTaskData = null;
    document.getElementById('postponeModal').classList.remove('active');
    document.getElementById('postponeDate').value = '';
    document.getElementById('postponeReason').value = '';
    document.getElementById('postponeSubtasks').checked = false;
}

async function confirmPostpone() {
    if (!postponeTaskData) return;

    const date = document.getElementById('postponeDate').value;
    const reason = document.getElementById('postponeReason').value;
    const cascadeSubtasks = document.getElementById('postponeSubtasks').checked;

    if (!date) {
        showNotification('Please select a date', 'error');
        return;
    }

    showLoading();
    closePostponeModal();

    const url = "{{ route('tenant.manage.projects.tasks.quick-postpone', [$username, 'TASK_ID']) }}"
        .replace('TASK_ID', postponeTaskData.taskId);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                postponed_until: date,
                reason: reason || '',
                cascade_subtasks: cascadeSubtasks
            })
        });

        if (!res.ok) throw new Error('Failed to postpone task');

        const data = await res.json();
        updateProjectProgressUI(postponeTaskData.projectId, data.progress);

        showNotification('Task postponed successfully', 'success');

        // Reload to show updated UI
        setTimeout(() => location.reload(), 1500);

    } catch (error) {
        console.error('Postpone error:', error);
        showNotification('Failed to postpone task', 'error');
    } finally {
        hideLoading();
    }
}

// ===================================
// DRAG & DROP: TASKS
// ===================================
let dragState = { draggingEl: null, projectId: null };

function taskDragStart(e) {
    const row = e.currentTarget;
    row.classList.add('dragging');
    dragState.draggingEl = row;
    dragState.projectId = row.dataset.projectId || null;
    e.dataTransfer.effectAllowed = "move";
}

function taskDragEnd(e) {
    const row = e.currentTarget;
    row.classList.remove('dragging');
    saveNewTaskOrder(dragState.projectId);
    dragState.draggingEl = null;
    dragState.projectId = null;
}

function taskDragOver(e) {
    e.preventDefault();
    const list = e.currentTarget;
    const dragging = dragState.draggingEl;
    if (!dragging) return;
    if (list.dataset.projectId !== dragState.projectId) return;

    const afterEl = getDragAfterElement(list, e.clientY, '.task-item:not(.dragging)');
    if (afterEl == null) {
        list.appendChild(dragging);
    } else {
        list.insertBefore(dragging, afterEl);
    }
}

function taskDrop(e) {
    e.preventDefault();
}

async function saveNewTaskOrder(projectId) {
    if (!projectId) return;
    const list = document.querySelector(`.tasks-list[data-project-id="${projectId}"]`);
    if (!list) return;

    const ids = [...list.querySelectorAll('.task-item')].map(li => li.dataset.taskId);

    const url = "{{ route('tenant.manage.projects.tasks.reorder', $username) }}";

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                project_id: projectId,
                tasks: ids,
            })
        });

        if (!res.ok) throw new Error('Failed to reorder');

        const data = await res.json();
        updateProjectProgressUI(projectId, data.progress);

    } catch (error) {
        console.error('Reorder error:', error);
    }
}

// ===================================
// DRAG & DROP: SUBTASKS
// ===================================
let subDragState = { draggingEl: null, taskId: null };

function subtaskDragStart(e) {
    const row = e.currentTarget;
    row.classList.add('dragging');
    const parentList = row.closest('.subtasks-list');
    subDragState.draggingEl = row;
    subDragState.taskId = parentList ? parentList.dataset.taskId : null;
    e.dataTransfer.effectAllowed = "move";
}

function subtaskDragEnd(e) {
    const row = e.currentTarget;
    row.classList.remove('dragging');
    saveNewSubtaskOrder(subDragState.taskId);
    subDragState.draggingEl = null;
    subDragState.taskId = null;
}

function subtaskDragOver(e) {
    e.preventDefault();
    const list = e.currentTarget;
    const dragging = subDragState.draggingEl;
    if (!dragging) return;
    if (list.dataset.taskId !== subDragState.taskId) return;

    const afterEl = getDragAfterElement(list, e.clientY, '.subtask-item:not(.dragging)');
    if (afterEl == null) {
        list.appendChild(dragging);
    } else {
        list.insertBefore(dragging, afterEl);
    }
}

function subtaskDrop(e) {
    e.preventDefault();
}

async function saveNewSubtaskOrder(taskId) {
    if (!taskId) return;
    const list = document.querySelector(`.subtasks-list[data-task-id="${taskId}"]`);
    if (!list) return;

    const parentTaskRow = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
    const projectId = parentTaskRow ? parentTaskRow.dataset.projectId : null;

    const ids = [...list.querySelectorAll('.subtask-item')].map(li => li.dataset.subtaskId);

    const url = "{{ route('tenant.manage.projects.tasks.subtasks.reorder', [$username, 'TASK_ID']) }}"
        .replace('TASK_ID', taskId);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ subtasks: ids })
        });

        if (!res.ok) throw new Error('Failed to reorder subtasks');

        const data = await res.json();
        updateProjectProgressUI(projectId, data.progress);

    } catch (error) {
        console.error('Reorder subtasks error:', error);
    }
}

// ===================================
// DRAG HELPER
// ===================================
function getDragAfterElement(list, mouseY, selector) {
    const els = [...list.querySelectorAll(selector)];
    return els.reduce((closest,child) => {
        const box = child.getBoundingClientRect();
        const offset = mouseY - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

// ===================================
// TASK DRAWER
// ===================================
function openTaskDrawer(taskId) {
    // Show loading state
    showLoading();

    // Fetch task details via AJAX
    const url = "{{ route('tenant.manage.projects.tasks.drawer', [$username, 'TASK_ID']) }}"
        .replace('TASK_ID', taskId);

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(res => res.text())
    .then(html => {
        // Create drawer container if doesn't exist
        let drawer = document.getElementById('taskDrawer');
        if (!drawer) {
            drawer = document.createElement('div');
            drawer.id = 'taskDrawer';
            drawer.className = 'task-drawer';
            document.body.appendChild(drawer);
        }

        // Insert content
        drawer.innerHTML = html;
        drawer.classList.add('active');

        // Add close on backdrop click
        drawer.addEventListener('click', (e) => {
            if (e.target === drawer) {
                closeTaskDrawer();
            }
        });

        hideLoading();
    })
    .catch(error => {
        console.error('Failed to load task:', error);
        showNotification('Failed to load task details', 'error');
        hideLoading();
    });
}

function closeTaskDrawer() {
    const drawer = document.getElementById('taskDrawer');
    if (drawer) {
        drawer.classList.remove('active');
        setTimeout(() => drawer.remove(), 300);
    }
}

// ===================================
// FILTERS
// ===================================
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.quick-filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            
            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Filter tasks
            applyTaskFilter(filter);
        });
    });
});

function applyTaskFilter(filter) {
    const allTasks = document.querySelectorAll('.task-item');
    
    allTasks.forEach(task => {
        const taskEl = task;
        let shouldShow = true;
        
        switch(filter) {
            case 'overdue':
                shouldShow = task.querySelector('.task-due-date.overdue') !== null;
                break;
            case 'today':
                const dueDate = task.querySelector('.task-due-date');
                if (dueDate) {
                    const dateText = dueDate.textContent.trim();
                    const today = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    shouldShow = dateText === today;
                } else {
                    shouldShow = false;
                }
                break;
            case 'in-progress':
                shouldShow = task.querySelector('[data-status="in-progress"]') !== null;
                break;
            case 'all':
            default:
                shouldShow = true;
        }
        
        taskEl.style.display = shouldShow ? '' : 'none';
    });
    
    // Hide empty projects
    document.querySelectorAll('.project-section').forEach(section => {
        const visibleTasks = section.querySelectorAll('.task-item:not([style*="display: none"])');
        section.style.display = visibleTasks.length > 0 ? '' : 'none';
    });
}

// ===================================
// KEYBOARD SHORTCUTS
// ===================================
document.addEventListener('keydown', (e) => {
    // Cmd/Ctrl + K to focus search
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        // Focus search if you have one
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        closePostponeModal();
        closeTaskDrawer();
    }
    
    // Cmd/Ctrl + N to create new task
    if ((e.metaKey || e.ctrlKey) && e.key === 'n') {
        e.preventDefault();
        openCreateTaskModal();
    }
});

// ===================================
// UTILITY FUNCTIONS
// ===================================
function toggleTaskView() {
    showNotification('View switcher coming soon!', 'info');
}

function openCreateTaskModal() {
    showNotification('Create task modal coming soon!', 'info');
}

function toggleProjectMenu(projectId) {
    showNotification('Project menu coming soon!', 'info');
}

// ===================================
// AUTO-SAVE INDICATOR
// ===================================
let saveTimeout = null;

function showSaveIndicator(message = 'Saving...') {
    clearTimeout(saveTimeout);
    
    let indicator = document.getElementById('saveIndicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'saveIndicator';
        indicator.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 10px 16px;
            background: #0052CC;
            color: white;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.2s;
        `;
        document.body.appendChild(indicator);
    }
    
    indicator.textContent = message;
    indicator.style.opacity = '1';
    
    saveTimeout = setTimeout(() => {
        indicator.style.opacity = '0';
    }, 2000);
}

// ===================================
// REFRESH DATA
// ===================================
function refreshTaskData() {
    showNotification('Refreshing...', 'info');
    location.reload();
}

// Auto-refresh every 5 minutes
setInterval(refreshTaskData, 5 * 60 * 1000);

// ===================================
// INITIALIZE
// ===================================
console.log('✨ Task Management System Initialized');
</script>

@endsection