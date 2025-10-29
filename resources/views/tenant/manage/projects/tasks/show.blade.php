{{-- resources/views/tenant/manage/projects/tasks/show.blade.php --}}
@extends('tenant.manage.app')

@section('main')
    @php
        $activeTab = request()->get('tab', 'overview');

        $statusColors = [
            'todo' => '#6B778C',
            'in-progress' => '#0052CC',
            'review' => '#FF991F',
            'done' => '#00875A',
            'blocked' => '#DE350B',
            'postponed' => '#8777D9',
            'cancelled' => '#6B778C',
        ];
        $statusColor = $statusColors[$task->status] ?? '#6B778C';

        $priorityColors = [
            'urgent' => '#DE350B',
            'high' => '#FF991F',
            'medium' => '#0065FF',
            'low' => '#00875A',
        ];
        $priorityColor = $priorityColors[$task->priority ?? 'medium'] ?? '#0065FF';
    @endphp

    <!-- Breadcrumbs -->
    <div class="task-breadcrumbs">
        <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="task-breadcrumb-item">
            <i class="fas fa-home"></i> Projects
        </a>
        <i class="fas fa-chevron-right task-breadcrumb-separator"></i>
        <a href="{{ route('tenant.manage.projects.project.show', [$username, $project->id]) }}" class="task-breadcrumb-item">
            {{ $project->key }}
        </a>
        <i class="fas fa-chevron-right task-breadcrumb-separator"></i>
        <span class="task-breadcrumb-item task-breadcrumb-active">{{ $project->key }}-{{ $task->id }}</span>
    </div>

    <!-- Task Header -->
    <div class="task-header" data-task-id="{{ $task->id }}">
        <div class="task-header-main">
            <div class="task-type-icon" style="background: {{ $statusColor }}20; color: {{ $statusColor }};">
                <i class="fas fa-tasks"></i>
            </div>

            <div class="task-header-content">
                <div class="task-header-top">
                    <div class="task-key-badge">{{ $project->key }}-{{ $task->id }}</div>

                    <div class="task-badges">
                        <span
                            id="taskStatusBadge"
                            class="task-status-badge"
                            style="background: {{ $statusColor }}20; color: {{ $statusColor }};"
                        >
                            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                        </span>

                        <span class="task-priority-badge" style="background: {{ $priorityColor }}20; color: {{ $priorityColor }};">
                            <i class="fas fa-flag"></i>
                            {{ ucfirst($task->priority ?? 'medium') }}
                        </span>

                        @if($task->is_overdue)
                            <span class="task-overdue-badge">
                                <i class="fas fa-exclamation-circle"></i> Overdue
                            </span>
                        @endif
                    </div>
                </div>

                <h1 class="task-title" id="taskTitleText">{{ $task->title }}</h1>

                <div class="task-meta-bar">
                    <div class="task-meta-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Created by {{ $task->reporter->name }}</span>
                    </div>

                    @if($task->assignee)
                        <div class="task-meta-item" id="taskAssigneeMeta">
                            <i class="fas fa-user-check"></i>
                            <span>Assigned to {{ $task->assignee->name }}</span>
                        </div>
                    @else
                        <div class="task-meta-item" id="taskAssigneeMeta" style="display:none">
                            <i class="fas fa-user-check"></i>
                            <span>Assigned to —</span>
                        </div>
                    @endif

                    @if($task->due_date)
                        <div class="task-meta-item {{ $task->is_overdue ? 'task-meta-overdue' : '' }}" id="taskDueMeta">
                            <i class="fas fa-calendar"></i>
                            <span>Due {{ $task->due_date->format('M d, Y') }}</span>
                        </div>
                    @else
                        <div class="task-meta-item" id="taskDueMeta" style="display:none;">
                            <i class="fas fa-calendar"></i>
                            <span>Due —</span>
                        </div>
                    @endif

                    @if($task->estimated_hours)
                        <div class="task-meta-item" id="taskEstimateMeta">
                            <i class="fas fa-clock"></i>
                            <span>{{ $task->estimated_hours }}h estimated</span>
                        </div>
                    @else
                        <div class="task-meta-item" id="taskEstimateMeta" style="display:none;">
                            <i class="fas fa-clock"></i>
                            <span>— h estimated</span>
                        </div>
                    @endif

                    @if($totalSubtasks > 0)
                        <div class="task-meta-item">
                            <i class="fas fa-check-square"></i>
                            <span id="taskSubtaskHeaderCount">{{ $completedSubtasks }}/{{ $totalSubtasks }} subtasks</span>
                            <div class="task-mini-progress">
                                <div id="taskMiniProgressBar"
                                     style="width: {{ $progress }}%; background: {{ $statusColor }};">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- HEADER ACTIONS -->
        <div class="task-header-actions">
            {{-- Primary status CTA for assignee --}}
            @if($task->assigned_to === $viewer->id)
                <button class="task-btn task-btn-primary"
                        id="markDoneBtn"
                        onclick="openStatusModal({{ $task->id }}, 'done')">
                    <i class="fas fa-check"></i> Mark Done
                </button>
            @endif

            {{-- Management actions for reporter / project owner --}}
            @if($task->reporter_id === $viewer->id || $project->user_id === $workspaceOwner->id)
                <button class="task-btn task-btn-secondary"
                onclick="openEditTaskModal({{ $task->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>

                <button class="task-btn task-btn-secondary"
                        onclick="openReassignModal({{ $task->id }})">
                    <i class="fas fa-user-plus"></i> Reassign
                </button>
            @endif

            <button class="task-btn task-btn-icon"
                    onclick="shareTask({{ $task->id }})"
                    title="Copy share link">
                <i class="fas fa-share-alt"></i>
            </button>

            <div class="task-menu-wrapper" data-task-id="{{ $task->id }}" style="position: relative;">
                <button class="task-btn task-btn-icon jira-menu-btn" onclick="toggleHeaderMenu(event)">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            
                <div class="jira-menu-dropdown" id="taskHeaderMenu" style="display: none;">
                    <button class="jira-menu-item" onclick="window.location.href='{{ route('tenant.manage.projects.tasks.show', [$username, $task->id]) }}'">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </button>
            
                    <button class="jira-menu-item" onclick="window.location.href='{{ route('tenant.manage.projects.project.show', [$username, $project->id]) }}'">
                        <i class="fas fa-folder-open"></i>
                        <span>Go to Project</span>
                    </button>
            
                    <div class="jira-menu-separator"></div>
            
                    <button class="jira-menu-item" onclick="openEditTaskModal({{ $task->id }})">
                        <i class="fas fa-edit"></i>
                        <span>Edit Task</span>
                    </button>
            
                    <button class="jira-menu-item" onclick="openReassignModal({{ $task->id }})">
                        <i class="fas fa-user-plus"></i>
                        <span>Reassign Task</span>
                    </button>
            
                    <div class="jira-menu-separator"></div>
            
                    <button class="jira-menu-item jira-menu-danger" onclick="confirmDeleteTask({{ $task->id }})">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete Task</span>
                    </button>
                </div>
            </div>

            
            <style>
                /* --- task header kebab menu (same vibe as index cards) --- */
.jira-menu-dropdown {
    position: absolute;
    right: 0;
    top: 42px;
    min-width: 200px;
    background: #FFFFFF;
    border: 1px solid #DFE1E6;
    border-radius: 8px;
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    padding: 8px 0;
    z-index: 2000;
}

.jira-menu-item {
    width: 100%;
    background: transparent;
    border: 0;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 500;
    color: #172B4D;
    padding: 10px 14px;
    cursor: pointer;
    line-height: 1.3;
}

.jira-menu-item i {
    width: 16px;
    text-align: center;
    font-size: 13px;
    color: #42526E;
}

.jira-menu-item:hover {
    background: #F4F5F7;
    color: #0052CC;
}

.jira-menu-item:hover i {
    color: #0052CC;
}

.jira-menu-separator {
    height: 1px;
    background: #DFE1E6;
    margin: 6px 0;
}

.jira-menu-danger {
    color: #DE350B;
}

.jira-menu-danger i {
    color: #DE350B;
}

.jira-menu-danger:hover {
    background: #FFEBE6;
    color: #BF2600;
}

.jira-menu-danger:hover i {
    color: #BF2600;
}


            </style>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="task-tabs">
        <a href="?tab=overview"
           class="task-tab {{ $activeTab === 'overview' ? 'task-tab-active' : '' }}">
            <i class="fas fa-info-circle"></i> Overview
        </a>

        <a href="?tab=activity"
           class="task-tab {{ $activeTab === 'activity' ? 'task-tab-active' : '' }}">
            <i class="fas fa-history"></i> Activity
            @if($task->activities->count() > 0)
                <span class="task-tab-badge">{{ $task->activities->count() }}</span>
            @endif
        </a>

        <a href="?tab=files"
           class="task-tab {{ $activeTab === 'files' ? 'task-tab-active' : '' }}">
            <i class="fas fa-paperclip"></i> Files
            @if($task->attachments->count() > 0)
                <span class="task-tab-badge">{{ $task->attachments->count() }}</span>
            @endif
        </a>

        <a href="?tab=links"
           class="task-tab {{ $activeTab === 'links' ? 'task-tab-active' : '' }}">
            <i class="fas fa-link"></i> Links
            @if($dependencies->count() > 0)
                <span class="task-tab-badge">{{ $dependencies->count() }}</span>
            @endif
        </a>
    </div>

    <!-- Tab Content -->
    <div class="task-content">
        @if($activeTab === 'overview')
            @include('tenant.manage.projects.tasks.tabs-detail.overview')
        @elseif($activeTab === 'activity')
            @include('tenant.manage.projects.tasks.tabs-detail.activity')
        @elseif($activeTab === 'files')
            @include('tenant.manage.projects.tasks.tabs-detail.files')
        @elseif($activeTab === 'links')
            @include('tenant.manage.projects.tasks.tabs-detail.links')
        @endif
    </div>

    {{-- Modals already shared with index page --}}
    @include('tenant.manage.projects.tasks.components.status-modal')
    @include('tenant.manage.projects.modals.task-modals')
    @include('tenant.manage.projects.modals.task-reassign-modal', ['username' => $username])

    <style>
        /* ---------- breadcrumbs ---------- */
        .task-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #6B778C;
        }
        .task-breadcrumb-item {
            color: #6B778C;
            text-decoration: none;
            transition: color 0.2s;
        }
        .task-breadcrumb-item:hover {
            color: #0052CC;
        }
        .task-breadcrumb-active {
            color: #172B4D;
            font-weight: 600;
        }
        .task-breadcrumb-separator {
            font-size: 10px;
            opacity: 0.6;
        }

        /* ---------- header card ---------- */
        .task-header {
            background: #FFFFFF;
            border: 1px solid #DFE1E6;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .task-header-main {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }
        .task-type-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .task-header-content { flex: 1; min-width: 0; }

        .task-header-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        .task-key-badge {
            padding: 4px 10px;
            background: #F4F5F7;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            color: #5E6C84;
            letter-spacing: 0.5px;
        }
        .task-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .task-status-badge,
        .task-priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .task-overdue-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: rgba(222, 53, 11, 0.1);
            color: #DE350B;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            animation: pulse 2s infinite;
        }
        .task-title {
            font-size: 24px;
            font-weight: 700;
            color: #172B4D;
            margin: 0 0 16px 0;
            line-height: 1.3;
        }

        /* ---------- meta row ---------- */
        .task-meta-bar {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .task-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #5E6C84;
        }
        .task-meta-item i { font-size: 12px; }
        .task-meta-overdue { color: #DE350B; font-weight: 600; }

        .task-mini-progress {
            width: 60px;
            height: 4px;
            background: #DFE1E6;
            border-radius: 2px;
            overflow: hidden;
            margin-left: 6px;
        }
        .task-mini-progress div {
            height: 100%;
            transition: width 0.3s;
        }

        /* ---------- action buttons ---------- */
        .task-header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .task-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .task-btn-primary {
            background: #0052CC;
            color: #FFFFFF;
            border-color: #0052CC;
        }
        .task-btn-primary:hover { background: #0747A6; }
        .task-btn-secondary {
            background: #FFFFFF;
            color: #42526E;
            border-color: #DFE1E6;
        }
        .task-btn-secondary:hover {
            background: #F4F5F7;
            border-color: #C1C7D0;
        }
        .task-btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            background: #FFFFFF;
            color: #42526E;
            border-color: #DFE1E6;
            justify-content: center;
        }
        .task-btn-icon:hover {
            background: #F4F5F7;
            color: #0052CC;
        }

        /* ---------- action menu dropdown ---------- */
        .task-menu-dropdown {
            position: absolute;
            right: 0;
            top: 42px;
            background: #FFFFFF;
            border: 1px solid #DFE1E6;
            border-radius: 8px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            min-width: 200px;
            z-index: 2000;
            padding: 8px 0;
        }
        .task-menu-item {
            width: 100%;
            background: transparent;
            border: 0;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #172B4D;
            padding: 10px 14px;
            cursor: pointer;
        }
        .task-menu-item i {
            width: 16px;
            text-align: center;
            font-size: 13px;
            color: #42526E;
        }
        .task-menu-item:hover {
            background: #F4F5F7;
            color: #0052CC;
        }
        .task-menu-item:hover i {
            color: #0052CC;
        }
        .task-menu-separator {
            height: 1px;
            background: #DFE1E6;
            margin: 6px 0;
        }

        /* ---------- tabs ---------- */
        .task-tabs {
            display: flex;
            gap: 2px;
            background: #F4F5F7;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 20px;
            overflow-x: auto;
        }
        .task-tab {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #6B778C;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            position: relative;
        }
        .task-tab:hover {
            color: #172B4D;
            background: #FFFFFF;
        }
        .task-tab-active {
            background: #0052CC;
            color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(0,82,204,0.2);
        }
        .task-tab-badge {
            padding: 2px 6px;
            background: rgba(0,0,0,0.15);
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
        }
        .task-tab-active .task-tab-badge {
            background: rgba(255,255,255,0.25);
        }

        /* ---------- tab content wrapper ---------- */
        .task-content {
            background: #FFFFFF;
            border: 1px solid #DFE1E6;
            border-radius: 8px;
            padding: 24px;
            min-height: 400px;
        }

        /* ---------- responsive ---------- */
        @media (max-width: 768px) {
            .task-header-main { flex-direction: column; }
            .task-title { font-size: 20px; }
            .task-meta-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .task-header-actions {
                width: 100%;
                justify-content: flex-start;
            }
            .task-tabs {
                overflow-x: auto;
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
    </style>

    <script>
        // expose tenant username for all AJAX calls
        window.TENANT_USERNAME = "{{ $username }}";

        // ----------------------------
        // status style map (must match backend)
        // ----------------------------
        const STATUS_STYLE = {
            'todo':        { label: 'To Do',        bg: '#F4F5F7',         color: '#6B778C' },
            'in-progress': { label: 'In Progress',  bg: '#DEEBFF',         color: '#0052CC' },
            'review':      { label: 'Review',       bg: '#FFFAE6',         color: '#FF991F' },
            'done':        { label: 'Done',         bg: '#E3FCEF',         color: '#00875A' },
            'blocked':     { label: 'Blocked',      bg: '#FFEBE6',         color: '#DE350B' },
            'postponed':   { label: 'Postponed',    bg: '#EAE6FF',         color: '#8777D9' },
            'cancelled':   { label: 'Cancelled',    bg: '#F4F5F7',         color: '#6B778C' },
        };

        // global for status modal file uploads
        let selectedFiles = [];

        // ----------------------------
        // TOAST SYSTEM (same as index page)
        // ----------------------------
        function showToast(message, type = 'info') {
            let container = document.getElementById('app-toast-container');

            if (!container) {
                container = document.createElement('div');
                container.id = 'app-toast-container';
                container.style.cssText = `
                    position: fixed;
                    top: 16px;
                    right: 16px;
                    z-index: 9999;
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                `;
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');

            const styles = {
                success: {
                    bg: '#E3FCEF',
                    color: '#006644',
                    border: '#36B37E33',
                    icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#006644" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>'
                },
                error: {
                    bg: '#FFEBE6',
                    color: '#BF2600',
                    border: '#FF563033',
                    icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#BF2600" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
                },
                info: {
                    bg: '#DEEBFF',
                    color: '#0747A6',
                    border: '#0052CC33',
                    icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0747A6" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
                }
            };

            const style = styles[type] || styles.info;

            toast.style.cssText = `
                min-width: 220px;
                max-width: 320px;
                padding: 12px 14px;
                border-radius: 6px;
                box-shadow: 0 10px 24px rgba(0,0,0,0.12);
                display: flex;
                align-items: flex-start;
                gap: 8px;
                font-size: 13px;
                font-weight: 500;
                border: 1px solid ${style.border};
                background: ${style.bg};
                color: ${style.color};
            `;

            toast.innerHTML = `
                <div style="flex-shrink:0;">${style.icon}</div>
                <div style="flex:1;">${message}</div>
                <button style="background:transparent;border:none;color:${style.color};cursor:pointer;font-size:14px;font-weight:600;padding:0;" aria-label="Close">&times;</button>
            `;

            const closeBtn = toast.querySelector('button');
            closeBtn.addEventListener('click', () => toast.remove());

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.transition = 'opacity 200ms ease, transform 200ms ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-4px)';
                setTimeout(() => toast.remove(), 220);
            }, 3000);
        }

        // ----------------------------
        // APPLY REALTIME UPDATE TO *DETAIL PAGE*
        // called after status change or subtask toggle
        // serverData SHOULD be same as in index: { task_status, task_status_label, completed_subtasks_count, subtasks_count, ... }
        // ----------------------------
        function applyRealtimeUpdateDetail(taskId, serverData) {
            console.log('🔄 Detail realtime update', taskId, serverData);

            // 1. STATUS BADGE IN HEADER
            if (serverData.task_status) {
                const badgeEl = document.getElementById('taskStatusBadge');
                const style = STATUS_STYLE[serverData.task_status] || STATUS_STYLE['todo'];

                if (badgeEl) {
                    const label = serverData.task_status_label || style.label;
                    const bg    = serverData.task_status_bg    || style.bg;
                    const color = serverData.task_status_color || style.color;

                    badgeEl.textContent = label;
                    badgeEl.style.background = bg;
                    badgeEl.style.color = color;

                    // if task is done, disable Mark Done button
                    if (serverData.task_status === 'done') {
                        const doneBtn = document.getElementById('markDoneBtn');
                        if (doneBtn) {
                            doneBtn.disabled = true;
                            doneBtn.style.opacity = '0.5';
                            doneBtn.style.cursor = 'default';
                            doneBtn.innerHTML = '<i class="fas fa-check"></i> Completed';
                        }
                    }
                }

                // also update mini progress bar color to match new state for vibe
                const miniBar = document.getElementById('taskMiniProgressBar');
                if (miniBar) {
                    miniBar.style.background = serverData.task_status_color || style.color;
                }
                const mainBar = document.getElementById('taskDetailProgressBar');
                if (mainBar) {
                    mainBar.style.background = serverData.task_status_color || style.color;
                }
            }

            // 2. SUBTASK CHECKBOX LINE UI
            if (serverData.subtask?.id) {
                const row = document.querySelector(`.task-subtask-item[data-subtask-id="${serverData.subtask.id}"]`);
                if (row) {
                    const cb = row.querySelector('.task-checkbox');
                    if (cb) cb.checked = !!serverData.subtask.completed;
                    if (serverData.subtask.completed) {
                        row.classList.add('is-completed');
                    } else {
                        row.classList.remove('is-completed');
                    }
                }
            }

            // 3. COUNTS / PROGRESS
            const completed = serverData.completed_subtasks_count;
            const total     = serverData.subtasks_count;

            if (completed !== undefined && total !== undefined) {
                const pct = total > 0 ? Math.round((completed / total) * 100) : 0;

                // header "x/y subtasks"
                const headerCount = document.getElementById('taskSubtaskHeaderCount');
                if (headerCount) {
                    headerCount.textContent = `${completed}/${total} subtasks`;
                }

                // overview header count "X/Y"
                const overviewCount = document.getElementById('taskSubtaskOverviewCount');
                if (overviewCount) {
                    overviewCount.textContent = `${completed}/${total}`;
                }

                // mini header bar
                const miniBar = document.getElementById('taskMiniProgressBar');
                if (miniBar) {
                    miniBar.style.width = pct + '%';
                }

                // overview progress bar
                const detailBar = document.getElementById('taskDetailProgressBar');
                if (detailBar) {
                    detailBar.style.width = pct + '%';
                }

                const detailPct = document.getElementById('taskDetailProgressText');
                if (detailPct) {
                    detailPct.textContent = pct + '%';
                }
            }
        }

        // ----------------------------
        // SUBTASK TOGGLE FROM OVERVIEW TAB
        // ----------------------------
        function toggleSubtaskDetail(taskId, subtaskId, isChecked) {
            const url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/toggle`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    completed: isChecked ? 1 : 0
                })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Update failed');
                }

                // Update UI with latest state
                applyRealtimeUpdateDetail(taskId, data);

                // toast
                if (data.subtask?.completed) {
                    showToast('✅ Subtask completed', 'success');
                } else {
                    showToast('↩️ Subtask reopened', 'info');
                }
            })
            .catch(err => {
                console.error('❌ Toggle subtask failed:', err);

                // rollback checkbox
                const cb = document.getElementById(`detail-subtask-cb-${subtaskId}`);
                if (cb) {
                    cb.checked = !isChecked;
                }

                showToast('Failed to update subtask', 'error');
            });
        }

        // ----------------------------
        // TASK STATUS MODAL FLOW  (Done / Blocked / Postponed ...)
        // ----------------------------
        function openStatusModal(taskId, action, subtaskId = null) {
            const modal = document.getElementById('taskStatusModal');
            const form = document.getElementById('taskStatusForm');
            const modalTitle = document.getElementById('modalTitle');
            const modalTaskId = document.getElementById('modalTaskId');
            const modalSubId = document.getElementById('modalSubtaskId');
            const modalAction = document.getElementById('modalAction');
            const statusWrap = document.getElementById('statusSelection');
            const remarkLabel = document.getElementById('remarkLabel');
            const submitBtnText = document.getElementById('submitBtnText');
            const postponeField = document.getElementById('postponeDateField');
            const postponeInput = document.getElementById('postponed_until');
            const filePreview = document.getElementById('filePreview');

            if (!modal || !form) {
                console.warn('Status modal markup missing');
                return;
            }

            // reset state
            form.reset();
            selectedFiles = [];
            if (filePreview) {
                filePreview.innerHTML = '';
                filePreview.style.display = 'none';
            }

            modalTaskId.value = taskId;
            modalSubId.value = subtaskId || '';
            modalAction.value = action;

            if (action === 'remark') {
                modalTitle.textContent = 'Add Remark';
                statusWrap.style.display = 'none';
                remarkLabel.textContent = 'Your Remark';
                submitBtnText.textContent = 'Add Remark';
                postponeField.style.display = 'none';
                postponeInput.required = false;
            } else {
                modalTitle.textContent = 'Update Task Status';
                statusWrap.style.display = 'block';
                remarkLabel.textContent = 'Describe your update';
                submitBtnText.textContent = 'Update Status';

                // pre-select radio
                const radio = document.querySelector(`input[name="status"][value="${action}"]`);
                if (radio) radio.checked = true;

                updatePostponeDateVisibility();
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeStatusModal(event) {
            if (event && event.target && event.currentTarget && event.target !== event.currentTarget) {
                return;
            }
            const modal = document.getElementById('taskStatusModal');
            if (modal) modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function updatePostponeDateVisibility() {
            const checked = document.querySelector('input[name="status"]:checked');
            const postponeField = document.getElementById('postponeDateField');
            const postponeInput = document.getElementById('postponed_until');

            if (!postponeField || !postponeInput) return;

            if (checked && checked.value === 'postponed') {
                postponeField.style.display = 'block';
                postponeInput.required = true;
            } else {
                postponeField.style.display = 'none';
                postponeInput.required = false;
            }
        }

        function handleFileSelect(e) { handleFiles(e.target.files); }
        function handleFiles(fileList) {
            const filePreview = document.getElementById('filePreview');
            Array.from(fileList).forEach(file => {
                if (file.size > 10 * 1024 * 1024) {
                    showToast(`"${file.name}" is too large (max 10MB)`, 'error');
                    return;
                }
                selectedFiles.push(file);
                displayFilePreview(file, filePreview);
            });
            if (filePreview && selectedFiles.length > 0) {
                filePreview.style.display = 'grid';
            }
        }
        function displayFilePreview(file, container) {
            if (!container) return;
            const item = document.createElement('div');
            item.className = 'file-preview-item';
            item.dataset.fileName = file.name;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e2) {
                    item.innerHTML = `
                        <img src="${e2.target.result}" alt="${file.name}" class="file-preview-image">
                        <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                            </svg>
                        </button>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                item.innerHTML = `
                    <div class="file-preview-file">
                        <svg class="file-preview-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                            <path d="M13 2v7h7"/>
                        </svg>
                        <div class="file-preview-name">${file.name}</div>
                    </div>
                    <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                        </svg>
                    </button>
                `;
            }

            container.appendChild(item);
        }

        function removeFile(fileName) {
            selectedFiles = selectedFiles.filter(f => f.name !== fileName);
            const item = document.querySelector(`.file-preview-item[data-file-name="${fileName}"]`);
            if (item) item.remove();

            if (selectedFiles.length === 0) {
                const fp = document.getElementById('filePreview');
                if (fp) fp.style.display = 'none';
            }
        }

        function submitTaskStatus(event) {
            event.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const prevHTML = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" class="spinning">
                    <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" fill="none" opacity="0.25"/>
                    <path d="M8 1a7 7 0 017 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
                <span>Processing...</span>
            `;

            const form = document.getElementById('taskStatusForm');
            const taskId = document.getElementById('modalTaskId').value;
            const subtaskId = document.getElementById('modalSubtaskId').value;
            const action = document.getElementById('modalAction').value;

            const formData = new FormData(form);
            selectedFiles.forEach(file => {
                formData.append('attachments[]', file);
            });

            let url;
            if (action === 'remark') {
                url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/remark`;
            } else if (subtaskId) {
                url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/complete-final`;
            } else {
                url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/status`;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Request failed');
                }

                closeStatusModal();

                // update header / subtasks / progress instantly
                applyRealtimeUpdateDetail(taskId, data);

                showToast(data.message || 'Updated successfully', 'success');

                submitBtn.disabled = false;
                submitBtn.innerHTML = prevHTML;
            })
            .catch(err => {
                console.error('❌ Status update failed:', err);
                showToast(err.message || 'Failed to update task', 'error');

                submitBtn.disabled = false;
                submitBtn.innerHTML = prevHTML;
            });
        }

        // ----------------------------
        // TASK MENU / EDIT / REASSIGN / SHARE
        // ----------------------------
        function openTaskMenu() {
            const dd = document.getElementById('taskHeaderMenu');
            if (!dd) return;
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
        }

        // click outside to close the action dropdown
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('taskHeaderMenu');
            if (!menu) return;
            const wrapper = menu.closest('.task-menu-wrapper');
            if (!wrapper) return;
            if (!wrapper.contains(e.target)) {
                menu.style.display = 'none';
            }
        });

        // fallback open edit task modal
        function openEditTaskModal(taskId) {
            // we try a few common modal IDs from your partials
            const modal = document.getElementById('taskEditModal')
                        || document.getElementById('editTaskModal')
                        || document.querySelector('[data-modal="edit-task"]');
            if (modal) {
                // fill hidden input if exists
                const input = modal.querySelector('input[name="task_id"], #edit_task_id');
                if (input) input.value = taskId;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else if (window.showEditTaskModal) {
                // if you already expose a JS helper in task-modals partial
                window.showEditTaskModal(taskId);
            } else {
                console.warn('Edit task modal not found for task', taskId);
                showToast('Edit modal not found in DOM', 'error');
            }
        }

        function openReassignModal(taskId) {
            const modal = document.getElementById('taskReassignModal')
                        || document.querySelector('[data-modal="reassign-task"]');
            if (modal) {
                const input = modal.querySelector('input[name="task_id"], #reassign_task_id');
                if (input) input.value = taskId;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else if (window.showReassignTaskModal) {
                window.showReassignTaskModal(taskId);
            } else {
                console.warn('Reassign modal not found');
                showToast('Reassign modal not found in DOM', 'error');
            }
        }

        function shareTask(taskId) {
            const link = window.location.href;
            navigator.clipboard.writeText(link)
                .then(() => {
                    showToast('Share link copied ✅', 'success');
                })
                .catch(() => {
                    showToast('Copy failed, here is the link:\n' + link, 'info');
                });
        }

        // ----------------------------
        // KEYBOARD SHORTCUTS / INIT
        // ----------------------------
        document.addEventListener('DOMContentLoaded', function() {
            // status radios -> show postpone date only for postponed
            document.querySelectorAll('input[name="status"]').forEach(r => {
                r.addEventListener('change', updatePostponeDateVisibility);
            });

            // remark char counter in modal
            const remark = document.getElementById('remark');
            if (remark) {
                remark.addEventListener('input', function() {
                    const counter = document.getElementById('charCount');
                    if (counter) {
                        const length = this.value.length;
                        counter.textContent = length;
                        counter.style.color = length > 1900 ? '#DE350B' : '#6B778C';
                    }
                });
            }

            // drag & drop for modal file upload
            const uploadArea = document.getElementById('uploadArea');
            if (uploadArea) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, e => {
                        e.preventDefault();
                        e.stopPropagation();
                    }, false);
                });
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, () => {
                        uploadArea.classList.add('drag-over');
                    }, false);
                });
                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, () => {
                        uploadArea.classList.remove('drag-over');
                    }, false);
                });
                uploadArea.addEventListener('drop', function(e) {
                    handleFiles(e.dataTransfer.files);
                }, false);
            }

            // esc closes status modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeStatusModal(e);
                }
                // ctrl/cmd+r soft reload (same UX as index)
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    window.location.reload();
                }
            });
        });

        // spinner anim for submit button
        const _styleSpin = document.createElement('style');
        _styleSpin.textContent = `
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            .spinning { animation: spin 1s linear infinite; }
            .drag-over {
                outline: 2px dashed #0052CC;
                outline-offset: 4px;
                background: rgba(0,82,204,0.05);
            }
            .file-preview-image {
                max-width: 64px;
                max-height: 64px;
                border-radius: 4px;
                object-fit: cover;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            }
            .file-preview-file {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 12px;
                background: #F4F5F7;
                color: #172B4D;
                border-radius: 4px;
                padding: 6px 8px;
            }
            .file-preview-icon {
                width: 16px;
                height: 16px;
            }
            .file-preview-remove {
                background: transparent;
                border: none;
                cursor: pointer;
                color: #6B778C;
                margin-left: 4px;
            }
            #filePreview {
                display: none;
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 8px;
                margin-top: 8px;
            }
        `;
        document.head.appendChild(_styleSpin);

        // expose some fns global (blade inline onclicks rely on them)
        window.openStatusModal = openStatusModal;
        window.closeStatusModal = closeStatusModal;
        window.submitTaskStatus = submitTaskStatus;
        window.toggleSubtaskDetail = toggleSubtaskDetail;
        window.openTaskMenu = openTaskMenu;
        window.openEditTaskModal = openEditTaskModal;
        window.openReassignModal = openReassignModal;
        window.shareTask = shareTask;
        window.handleFileSelect = handleFileSelect;
        window.removeFile = removeFile;
    </script>
    <script>
        // toggle the kebab dropdown in the task header
        function toggleHeaderMenu(e) {
            e.stopPropagation();
    
            const wrapper = e.currentTarget.closest('.task-menu-wrapper');
            if (!wrapper) return;
    
            const menu = wrapper.querySelector('.jira-menu-dropdown');
            if (!menu) return;
    
            // close any other open header menus first (safety if you ever add more)
            document.querySelectorAll('.jira-menu-dropdown').forEach(m => {
                if (m !== menu) m.style.display = 'none';
            });
    
            // toggle this one
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
    
        // close menu if you click anywhere else
        document.addEventListener('click', function(e) {
            // if click is NOT inside a .task-menu-wrapper, close all
            if (!e.target.closest('.task-menu-wrapper')) {
                document.querySelectorAll('.jira-menu-dropdown').forEach(m => {
                    m.style.display = 'none';
                });
            }
        });
    
        // prompt + delete
        function confirmDeleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this task? This cannot be undone.')) {
                return;
            }
    
            fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Delete failed');
                }
    
                showToast('Task deleted', 'success');
    
                // after delete, go back to project (or tasks list)
                window.location.href = "{{ route('tenant.manage.projects.project.show', [$username, $project->id]) }}";
            })
            .catch(err => {
                console.error('delete error:', err);
                showToast(err.message || 'Could not delete task', 'error');
            });
        }
    
        // expose globally so Blade onclick="" can use them
        window.toggleHeaderMenu = toggleHeaderMenu;
        window.confirmDeleteTask = confirmDeleteTask;
    </script>
    
@endsection
