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
    <div class="task-header">
        <div class="task-header-main">
            <div class="task-type-icon" style="background: {{ $statusColor }}20; color: {{ $statusColor }};">
                <i class="fas fa-tasks"></i>
            </div>
            
            <div class="task-header-content">
                <div class="task-header-top">
                    <div class="task-key-badge">{{ $project->key }}-{{ $task->id }}</div>
                    
                    <div class="task-badges">
                        <span class="task-status-badge" style="background: {{ $statusColor }}20; color: {{ $statusColor }};">
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

                <h1 class="task-title">{{ $task->title }}</h1>

                <div class="task-meta-bar">
                    <div class="task-meta-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Created by {{ $task->reporter->name }}</span>
                    </div>
                    
                    @if($task->assignee)
                        <div class="task-meta-item">
                            <i class="fas fa-user-check"></i>
                            <span>Assigned to {{ $task->assignee->name }}</span>
                        </div>
                    @endif

                    @if($task->due_date)
                        <div class="task-meta-item {{ $task->is_overdue ? 'task-meta-overdue' : '' }}">
                            <i class="fas fa-calendar"></i>
                            <span>Due {{ $task->due_date->format('M d, Y') }}</span>
                        </div>
                    @endif

                    @if($task->estimated_hours)
                        <div class="task-meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $task->estimated_hours }}h estimated</span>
                        </div>
                    @endif

                    @if($totalSubtasks > 0)
                        <div class="task-meta-item">
                            <i class="fas fa-check-square"></i>
                            <span>{{ $completedSubtasks }}/{{ $totalSubtasks }} subtasks</span>
                            <div class="task-mini-progress">
                                <div style="width: {{ $progress }}%; background: {{ $statusColor }};"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="task-header-actions">
            @if($task->assigned_to === $viewer->id)
                <button class="task-btn task-btn-primary" onclick="openStatusModal({{ $task->id }}, 'done')">
                    <i class="fas fa-check"></i> Mark Done
                </button>
            @endif

            @if($task->reporter_id === $viewer->id || $project->user_id === $workspaceOwner->id)
                <button class="task-btn task-btn-secondary" onclick="openEditTaskModal({{ $task->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                
                <button class="task-btn task-btn-secondary" onclick="openReassignModal({{ $task->id }})">
                    <i class="fas fa-user-plus"></i> Reassign
                </button>
            @endif

            <button class="task-btn task-btn-icon" onclick="shareTask()">
                <i class="fas fa-share-alt"></i>
            </button>

            <button class="task-btn task-btn-icon" onclick="openTaskMenu()">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="task-tabs">
        <a href="?tab=overview" class="task-tab {{ $activeTab === 'overview' ? 'task-tab-active' : '' }}">
            <i class="fas fa-info-circle"></i> Overview
        </a>
        <a href="?tab=activity" class="task-tab {{ $activeTab === 'activity' ? 'task-tab-active' : '' }}">
            <i class="fas fa-history"></i> Activity
            @if($task->activities->count() > 0)
                <span class="task-tab-badge">{{ $task->activities->count() }}</span>
            @endif
        </a>
        <a href="?tab=files" class="task-tab {{ $activeTab === 'files' ? 'task-tab-active' : '' }}">
            <i class="fas fa-paperclip"></i> Files
            @if($task->attachments->count() > 0)
                <span class="task-tab-badge">{{ $task->attachments->count() }}</span>
            @endif
        </a>
        <a href="?tab=links" class="task-tab {{ $activeTab === 'links' ? 'task-tab-active' : '' }}">
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

    @include('tenant.manage.projects.tasks.components.status-modal')
    @include('tenant.manage.projects.modals.task-modals')
    @include('tenant.manage.projects.modals.task-reassign-modal', ['username' => $username])

    <style>
        /* Professional Task Detail Styles - Jira/Atlassian Design */
        
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

        /* Task Header - Premium Design */
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

        .task-header-content {
            flex: 1;
            min-width: 0;
        }

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

        .task-meta-item i {
            font-size: 12px;
        }

        .task-meta-overdue {
            color: #DE350B;
            font-weight: 600;
        }

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

        .task-btn-primary:hover {
            background: #0747A6;
        }

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

        /* Tabs Navigation */
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

        /* Content Area */
        .task-content {
            background: #FFFFFF;
            border: 1px solid #DFE1E6;
            border-radius: 8px;
            padding: 24px;
            min-height: 400px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .task-header-main {
                flex-direction: column;
            }

            .task-title {
                font-size: 20px;
            }

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
@endsection