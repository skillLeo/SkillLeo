@php
    use Illuminate\Support\Str;

    /**
     * Priority / Status presentation config
     */
    $priorityConfig = [
        'urgent' => [
            'color' => '#DE350B',
            'bg' => 'rgba(222, 53, 11, 0.1)',
            'label' => 'Urgent',
            'icon' => 'exclamation-circle',
        ],
        'high' => ['color' => '#FF991F', 'bg' => 'rgba(255, 153, 31, 0.1)', 'label' => 'High', 'icon' => 'arrow-up'],
        'medium' => ['color' => '#0065FF', 'bg' => 'rgba(0, 101, 255, 0.1)', 'label' => 'Medium', 'icon' => 'minus'],
        'low' => ['color' => '#00875A', 'bg' => 'rgba(0, 135, 90, 0.1)', 'label' => 'Low', 'icon' => 'arrow-down'],
    ];

    $statusConfig = [
        'todo' => ['color' => '#6B778C', 'bg' => '#F4F5F7', 'label' => 'To Do'],
        'in-progress' => ['color' => '#0052CC', 'bg' => '#DEEBFF', 'label' => 'In Progress'],
        'review' => ['color' => '#FF991F', 'bg' => '#FFFAE6', 'label' => 'Review'],
        'done' => ['color' => '#00875A', 'bg' => '#E3FCEF', 'label' => 'Done'],
        'blocked' => ['color' => '#DE350B', 'bg' => '#FFEBE6', 'label' => 'Blocked'],
        'postponed' => ['color' => '#8777D9', 'bg' => '#EAE6FF', 'label' => 'Postponed'],
    ];

    $priority = $priorityConfig[$task->priority ?? 'medium'] ?? $priorityConfig['medium'];
    $status = $statusConfig[$task->status ?? 'todo'] ?? $statusConfig['todo'];

    /**
     * Progress for subtasks
     */
    $totalSubtasks = $task->subtasks->count();
    $completedSubtasks = $task->subtasks->where('completed', true)->count();
    $subtaskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

    /**
     * Relationship checks
     */
    $isOverdue = $task->is_overdue ?? false;
    $isAssignedToMe = $task->assigned_to === auth()->id();
    $isCreatedByMe = $task->reporter_id === auth()->id();

    /**
     * Context coming from controller:
     * - 'mine'       -> assignedToMe page
     * - 'delegated'  -> assignedByMe page
     */
    $context = $context ?? null;
    $contextIsMine = $context === 'mine';
    $contextIsDelegated = $context === 'delegated';

    /**
     * Base capability:
     * - canEditBase: you can edit/reassign/delete if you CREATED the task
     * - canCompleteBase: you can mark done/postpone/etc if you ARE the assignee
     */
    $canEditBase = $isCreatedByMe;
    $canCompleteBase = $isAssignedToMe;

    /**
     * Apply page rules:
     * Rule 1: assignedToMe page => NEVER show edit/delete/reassign (even if I created it)
     * Rule 2: assignedByMe page => NEVER show quick-complete actions (done/postpone/block/note)
     */
    $canEdit = $contextIsMine ? false : $canEditBase;
    $canComplete = $contextIsDelegated ? false : $canCompleteBase;
@endphp

{{-- 
    ✅ This card relies on the shared modal helpers from:
    resources/views/tenant/manage/projects/modals/task-modals.blade.php

    That partial defines:
    - openEditTaskModal(taskId)
    - deleteTask(taskId)
    - modalToast()
    - toggleTaskMenu(), etc.

    Make sure you @include that partial ONCE on the page (not inside this loop).
--}}

<div class="jira-task-card"
     data-task-id="{{ $task->id }}"
     data-task-status="{{ $task->status }}"
     data-project-id="{{ $task->project_id }}">

    <!-- Card Header -->
    <div class="jira-card-header">
        <div class="jira-card-badges">
            <a href="{{ route('tenant.manage.projects.project.show', [$username, $task->project_id]) }}"
                class="jira-project-badge">
                <span class="jira-project-key">{{ $task->project->key ?? 'PROJ' }}</span>
                <span class="jira-task-number">-{{ $task->id }}</span>
            </a>

            @if ($isOverdue)
                <span class="jira-overdue-badge" title="Overdue">
                    <i class="fas fa-clock"></i> Overdue
                </span>
            @endif

            @if ($isCreatedByMe)
                <span class="jira-owner-badge" title="You created this">
                    <i class="fas fa-user-tie"></i> Owner
                </span>
            @endif
        </div>

        <div class="jira-card-menu">
            <button class="jira-menu-btn" onclick="event.stopPropagation(); toggleTaskMenu({{ $task->id }})">
                <i class="fas fa-ellipsis-h"></i>
            </button>

            <div class="jira-dropdown-menu" id="task-menu-{{ $task->id }}" style="display: none;">
                <a href="{{ route('tenant.manage.projects.tasks.show', [$username, $task->id]) }}"
                    class="jira-menu-item" onclick="event.stopPropagation()">
                    <i class="fas fa-eye"></i>
                    <span>View Details</span>
                </a>

                <a href="{{ route('tenant.manage.projects.project.show', [$username, $task->project_id, 'tab' => 'list']) }}"
                    class="jira-menu-item" onclick="event.stopPropagation()">
                    <i class="fas fa-folder-open"></i>
                    <span>Go to Project</span>
                </a>

                <div class="jira-menu-divider"></div>

                @if ($canEdit)
                    <button class="jira-menu-item"
                        onclick="event.stopPropagation(); openEditTaskModal({{ $task->id }})">
                        <i class="fas fa-edit"></i>
                        <span>Edit Task</span>
                    </button>

                    <button class="jira-menu-item"
                        onclick="event.stopPropagation(); openReassignModal({{ $task->id }})">
                        <i class="fas fa-user-plus"></i>
                        <span>Reassign Task</span>
                    </button>

                    <div class="jira-menu-divider"></div>

                    <button class="jira-menu-item jira-menu-item-danger"
                        onclick="event.stopPropagation(); deleteTask({{ $task->id }})">
                        <i class="fas fa-trash"></i>
                        <span>Delete Task</span>
                    </button>
                @else
                    @if ($canComplete)
                        <button class="jira-menu-item"
                            onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'remark')">
                            <i class="fas fa-comment"></i>
                            <span>Add Note</span>
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Title -->
    <a href="{{ route('tenant.manage.projects.tasks.show', [$username, $task->id]) }}" class="jira-card-title-link">
        <h3 class="jira-card-title">{{ $task->title }}</h3>
    </a>

    <!-- Description -->
    @if ($task->notes)
        <p class="jira-card-description">
            {{ Str::limit(strip_tags($task->notes), 120) }}
        </p>
    @endif

    <!-- Subtasks Section -->
    @if ($totalSubtasks > 0)
        <div class="jira-subtasks-section">
            <div class="jira-subtasks-header"
                onclick="event.stopPropagation(); toggleSubtasksExpand({{ $task->id }})">
                <div class="jira-subtasks-info">
                    <i class="fas fa-tasks"></i>
                    {{-- live-updated counter --}}
                    <span class="jira-subtasks-count">{{ $completedSubtasks }}/{{ $totalSubtasks }}</span>
                    <span class="jira-subtasks-label">subtasks</span>
                </div>

                {{-- live-updated progress bar --}}
                <div class="jira-progress-mini">
                    <div class="jira-progress-bar"
                        style="width: {{ $subtaskProgress }}%; background: {{ $status['color'] }};"></div>
                </div>

                <button class="jira-expand-btn" id="expand-btn-{{ $task->id }}">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            {{-- Subtasks List --}}
            <div class="jira-subtasks-list" id="subtasks-list-{{ $task->id }}" style="display: none;">
                @foreach ($task->subtasks as $index => $subtask)
                    <div class="jira-subtask-item {{ $subtask->completed ? 'is-completed' : '' }}"
                        data-task-id="{{ $task->id }}"
                        data-subtask-id="{{ $subtask->id }}"
                        @if ($canComplete)
                            onclick="subtaskRowClick(event, {{ $task->id }}, {{ $subtask->id }}, {{ $totalSubtasks }})"
                        @endif
                    >
                        <label class="jira-subtask-checkbox-wrapper">
                            <input
                                type="checkbox"
                                class="jira-subtask-checkbox"
                                id="subtask-cb-{{ $subtask->id }}"
                                {{ $subtask->completed ? 'checked' : '' }}
                                {{ !$canComplete ? 'disabled' : '' }}
                                onclick="event.stopPropagation();"
                            >
                            <span class="jira-checkbox-custom">
                                <i class="fas fa-check"></i>
                            </span>
                        </label>

                        <span class="jira-subtask-title">{{ $subtask->title }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="jira-card-footer">
        <div class="jira-footer-left">
            <!-- Priority -->
            <span class="jira-priority-badge"
                style="background: {{ $priority['bg'] }}; color: {{ $priority['color'] }}; border-color: {{ $priority['color'] }};">
                <i class="fas fa-{{ $priority['icon'] }}"></i>
                {{ $priority['label'] }}
            </span>

            <!-- Due Date -->
            @if ($task->due_date)
                <span class="jira-due-date {{ $isOverdue ? 'is-overdue' : '' }}">
                    <i class="fas fa-calendar"></i>
                    {{ $task->due_date->format('M d') }}
                </span>
            @endif

            <!-- Attachments -->
            @if ($task->attachments->count() > 0)
                <span class="jira-attachment-count">
                    <i class="fas fa-paperclip"></i>
                    {{ $task->attachments->count() }}
                </span>
            @endif

            <!-- Story Points -->
            @if ($task->story_points > 0)
                <span class="jira-story-points" title="Story Points">
                    <i class="fas fa-chart-line"></i>
                    {{ $task->story_points }}
                </span>
            @endif
        </div>

        <div class="jira-footer-right">
            {{-- status badge (updates live on DOM patch) --}}
            <span class="jira-status-badge"
                style="background: {{ $status['bg'] }}; color: {{ $status['color'] }};">
                {{ $status['label'] }}
            </span>

            <!-- Assignee -->
            @if ($task->assignee)
                <div class="jira-assignee" title="{{ $task->assignee->name }}">
                    @if ($task->assignee->avatar_url)
                        <img src="{{ $task->assignee->avatar_url }}"
                             alt="{{ $task->assignee->name }}"
                             class="jira-avatar"
                             referrerpolicy="no-referrer"
                             crossorigin="anonymous"
                             onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                    @else
                        <div class="jira-avatar jira-avatar-placeholder">
                            {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            @else
                <div class="jira-assignee jira-assignee-unassigned" title="Unassigned">
                    <i class="fas fa-user-slash"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions (only if I'm the assignee AND we're not on delegated page) -->
    @if ($canComplete)
        <div class="jira-quick-actions">
            <button class="jira-quick-btn jira-quick-btn-success"
                onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'done')" title="Mark as done">
                <i class="fas fa-check"></i>
                <span>Done</span>
            </button>

            <button class="jira-quick-btn jira-quick-btn-warning"
                onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'postponed')" title="Postpone">
                <i class="fas fa-clock"></i>
                <span>Postpone</span>
            </button>

            <button class="jira-quick-btn jira-quick-btn-danger"
                onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'blocked')" title="Mark as blocked">
                <i class="fas fa-ban"></i>
                <span>Block</span>
            </button>

            <button class="jira-quick-btn jira-quick-btn-secondary"
                onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'remark')" title="Add note">
                <i class="fas fa-comment-alt"></i>
                <span>Note</span>
            </button>
        </div>
    @endif
</div>

<style>
    /* === card styles (unchanged) === */

    .jira-task-card {
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        padding: 14px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .jira-task-card:not(:has(.jira-quick-btn:hover, .jira-menu-btn:hover, .jira-subtask-item:hover, .jira-dropdown-menu:hover)) {
        cursor: pointer;
    }

    .jira-task-card:hover {
        border-color: #0052CC;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .jira-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .jira-card-badges {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .jira-project-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        background: #F4F5F7;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #DFE1E6;
    }

    .jira-project-badge:hover {
        background: #0052CC;
        color: #FFFFFF;
        border-color: #0052CC;
    }

    .jira-project-key {
        color: #5E6C84;
    }

    .jira-task-number {
        color: #172B4D;
    }

    .jira-project-badge:hover .jira-project-key,
    .jira-project-badge:hover .jira-task-number {
        color: #FFFFFF;
    }

    .jira-overdue-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: rgba(222, 53, 11, 0.1);
        color: #DE350B;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        animation: pulse 2s infinite;
    }

    .jira-owner-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: rgba(0, 82, 204, 0.1);
        color: #0052CC;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
    }

    .jira-card-menu {
        position: relative;
    }

    .jira-menu-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #DFE1E6;
        border-radius: 4px;
        background: #FFFFFF;
        color: #6B778C;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }

    .jira-menu-btn:hover {
        background: #F4F5F7;
        border-color: #0052CC;
        color: #0052CC;
    }

    .jira-dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1000;
        min-width: 200px;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        margin-top: 4px;
        padding: 4px 0;
    }

    .jira-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        color: #172B4D;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.15s;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .jira-menu-item:hover {
        background: #F4F5F7;
    }

    .jira-menu-item-danger {
        color: #DE350B;
    }

    .jira-menu-item-danger:hover {
        background: rgba(222, 53, 11, 0.1);
    }

    .jira-menu-divider {
        height: 1px;
        background: #DFE1E6;
        margin: 4px 0;
    }

    .jira-card-title-link {
        text-decoration: none;
    }

    .jira-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #172B4D;
        line-height: 1.4;
        margin: 0;
        transition: color 0.2s;
    }

    .jira-card-title-link:hover .jira-card-title {
        color: #0052CC;
    }

    .jira-card-description {
        font-size: 12px;
        color: #5E6C84;
        line-height: 1.5;
        margin: 0;
    }

    /* subtasks block */
    .jira-subtasks-section {
        background: #F7F8F9;
        border-radius: 6px;
        overflow: hidden;
    }

    .jira-subtasks-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .jira-subtasks-header:hover {
        background: #EBECF0;
    }

    .jira-subtasks-info {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #5E6C84;
        font-size: 12px;
        flex: 1;
    }

    .jira-subtasks-count {
        font-weight: 700;
        color: #172B4D;
    }

    .jira-progress-mini {
        flex: 1;
        height: 3px;
        background: #DFE1E6;
        border-radius: 2px;
        overflow: hidden;
    }

    .jira-progress-bar {
        height: 100%;
        transition: width 0.3s;
    }

    .jira-expand-btn {
        width: 18px;
        height: 18px;
        border: none;
        background: transparent;
        color: #6B778C;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .jira-expand-btn:hover {
        color: #0052CC;
    }

    .jira-expand-btn.is-expanded i {
        transform: rotate(180deg);
    }

    .jira-subtasks-list {
        padding: 0 10px 10px 10px;
    }

    .jira-subtask-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        border-radius: 4px;
        transition: background 0.15s;
        margin-bottom: 3px;
    }

    .jira-subtask-item:hover {
        background: #FFFFFF;
    }

    .jira-subtask-item.is-completed .jira-subtask-title {
        color: #6B778C;
        text-decoration: line-through;
    }

    .jira-subtask-checkbox-wrapper {
        display: flex;
        cursor: pointer;
    }

    .jira-subtask-checkbox {
        position: absolute;
        opacity: 0;
    }

    .jira-checkbox-custom {
        width: 16px;
        height: 16px;
        border: 2px solid #DFE1E6;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        transition: all 0.15s;
    }

    .jira-checkbox-custom i {
        font-size: 10px;
        color: #FFFFFF;
        opacity: 0;
    }

    .jira-subtask-checkbox:checked + .jira-checkbox-custom {
        background: #00875A;
        border-color: #00875A;
    }

    .jira-subtask-checkbox:checked + .jira-checkbox-custom i {
        opacity: 1;
    }

    .jira-subtask-checkbox:disabled + .jira-checkbox-custom {
        cursor: not-allowed;
        opacity: 0.6;
    }

    .jira-subtask-title {
        flex: 1;
        font-size: 12px;
        color: #172B4D;
    }

    /* footer */
    .jira-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px solid #EBECF0;
    }

    .jira-footer-left,
    .jira-footer-right {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .jira-priority-badge,
    .jira-due-date,
    .jira-attachment-count,
    .jira-story-points {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .jira-priority-badge {
        border: 1px solid currentColor;
    }

    .jira-due-date {
        color: #5E6C84;
    }

    .jira-due-date.is-overdue {
        color: #DE350B;
        background: rgba(222, 53, 11, 0.1);
    }

    .jira-attachment-count,
    .jira-story-points {
        color: #6B778C;
    }

    .jira-status-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .jira-assignee {
        display: flex;
        align-items: center;
    }

    .jira-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #FFFFFF;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .jira-avatar-placeholder {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jira-assignee-unassigned {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #F4F5F7;
        color: #6B778C;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    /* quick actions */
    .jira-quick-actions {
        display: flex;
        gap: 4px;
        padding-top: 10px;
        border-top: 1px solid #EBECF0;
    }

    .jira-quick-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 6px 10px;
        border: 1px solid #DFE1E6;
        border-radius: 4px;
        background: #FFFFFF;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }

    .jira-quick-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .jira-quick-btn-success {
        color: #00875A;
    }

    .jira-quick-btn-success:hover {
        background: #E3FCEF;
        border-color: #00875A;
    }

    .jira-quick-btn-warning {
        color: #FF991F;
    }

    .jira-quick-btn-warning:hover {
        background: #FFFAE6;
        border-color: #FF991F;
    }

    .jira-quick-btn-danger {
        color: #DE350B;
    }

    .jira-quick-btn-danger:hover {
        background: #FFEBE6;
        border-color: #DE350B;
    }

    .jira-quick-btn-secondary {
        color: #0052CC;
    }

    .jira-quick-btn-secondary:hover {
        background: #DEEBFF;
        border-color: #0052CC;
    }

    /* animations */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50%      { opacity: 0.5; }
    }

    /* responsive */
    @media (max-width: 768px) {
        .jira-task-card {
            padding: 12px;
        }

        .jira-quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<script>
    // Card-level UX helpers only.
    // All heavy lifting (openEditTaskModal, deleteTask, modalToast, etc.)
    // comes from task-modals.blade.php which you include once per page.

    function toggleTaskMenu(taskId) {
        const menu = document.getElementById(`task-menu-${taskId}`);
        const allMenus = document.querySelectorAll('.jira-dropdown-menu');

        allMenus.forEach(m => {
            if (m.id !== `task-menu-${taskId}`) {
                m.style.display = 'none';
            }
        });

        if (!menu) return;
        menu.style.display =
            (menu.style.display === 'none' || menu.style.display === '')
                ? 'block'
                : 'none';
    }

    // Click anywhere on card -> go to task details
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.jira-task-card');
        if (!card) return;

        // don't hijack clicks on interactive UI
        if (
            e.target.closest('.jira-menu-btn, .jira-dropdown-menu, .jira-quick-btn, .jira-subtask-item, .jira-project-badge')
        ) {
            return;
        }

        const taskId = card.dataset.taskId;
        if (taskId) {
            window.location.href = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`;
        }
    });

    // Hide dropdown menus if click outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.jira-card-menu')) {
            document.querySelectorAll('.jira-dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    // Expand / collapse subtasks list inside a card
    function toggleSubtasksExpand(taskId) {
        const list = document.getElementById(`subtasks-list-${taskId}`);
        const btn = document.getElementById(`expand-btn-${taskId}`);

        if (list && btn) {
            const isHidden = (list.style.display === 'none' || list.style.display === '');
            list.style.display = isHidden ? 'block' : 'none';
            btn.classList.toggle('is-expanded', isHidden);
        }
    }

    // Placeholder for future reassignment modal (not in edit modal yet)
    function openReassignModal(taskId) {
        console.log('Open reassign modal for task:', taskId);
        modalToast('Reassign UI coming soon', 'info');
    }
</script>
