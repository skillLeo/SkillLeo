{{-- resources/views/tenant/manage/projects/show.blade.php --}}
@extends('tenant.manage.app')

@section('main')
    @php
        $activeTab = request()->get('tab', 'overview');
        $owner = $project->user;
        $colors = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#feca57', '#48dbfb', '#ff9ff3'];
        $color = $colors[$project->id % count($colors)];
        
        $tasksDone = $project->tasks->where('status', 'done')->count();
        $tasksTotal = $project->tasks->count();
        $tasksRemaining = max($tasksTotal - $tasksDone, 0);
        $progress = $tasksTotal > 0 ? round(($tasksDone / $tasksTotal) * 100) : 0;
    @endphp

    <!-- Compact Breadcrumbs -->
    <div class="pro-breadcrumbs">
        <a href="{{ route('tenant.manage.projects.dashboard', $username) }}">
            <i class="fas fa-home"></i> Projects
        </a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('tenant.manage.projects.list', $username) }}">All Projects</a>
        <i class="fas fa-chevron-right"></i>
        <span class="active">{{ $project->key }}</span>
    </div>

    <!-- Compact Header with Integrated Actions -->
    <div class="pro-header">
        <div class="pro-header-main">
            <div class="pro-avatar" style="background: {{ $color }};">
                {{ \Illuminate\Support\Str::of($project->key)->substr(0, 2)->upper() }}
            </div>
            
            <div class="pro-header-info">
                <div class="pro-header-top">
                    <h1 class="pro-title">{{ $project->name }}</h1>
                    <div class="pro-badges">
                        <span class="pro-badge pro-badge-type">
                            <i class="fas fa-{{ $project->type === 'scrum' ? 'layer-group' : 'stream' }}"></i>
                            {{ ucfirst($project->type) }}
                        </span>
                        @if ($project->client_id)
                            <span class="pro-badge pro-badge-client">
                                <i class="fas fa-user-tie"></i> Client
                            </span>
                        @endif
                        <span class="pro-badge pro-badge-status status-{{ $project->status }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="pro-meta-compact">
                    <span><i class="fas fa-user-circle"></i> {{ $owner?->name ?? '—' }}</span>
                    <span><i class="fas fa-users"></i> {{ $project->team->count() }} members</span>
                    <span><i class="fas fa-calendar"></i> Due {{ optional($project->due_date)->format('M d, Y') ?? '—' }}</span>
                    <span class="pro-progress-inline">
                        <i class="fas fa-tasks"></i> {{ $tasksDone }}/{{ $tasksTotal }} 
                        <div class="pro-progress-mini">
                            <div style="width: {{ $progress }}%; background: {{ $color }};"></div>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <div class="pro-header-actions">
            <button class="pro-btn pro-btn-icon" title="Star">
                <i class="far fa-star"></i>
            </button>
            <button class="pro-btn pro-btn-icon" title="Share">
                <i class="fas fa-share-alt"></i>
            </button>
            <button class="pro-btn pro-btn-secondary">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="pro-btn pro-btn-icon" title="More">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>

    <!-- Compact Tabs Navigation -->
    <div class="pro-tabs">
        <a href="?tab=overview" class="pro-tab {{ $activeTab === 'overview' ? 'active' : '' }}">
            <i class="fas fa-info-circle"></i> Overview
        </a>
        <a href="?tab=board" class="pro-tab {{ $activeTab === 'board' ? 'active' : '' }}">
            <i class="fas fa-columns"></i> Board
        </a>
        <a href="?tab=list" class="pro-tab {{ $activeTab === 'list' ? 'active' : '' }}">
            <i class="fas fa-list"></i> List
            @if($tasksTotal > 0)<span class="pro-tab-badge">{{ $tasksTotal }}</span>@endif
        </a>
        <a href="?tab=timeline" class="pro-tab {{ $activeTab === 'timeline' ? 'active' : '' }}">
            <i class="fas fa-chart-gantt"></i> Timeline
        </a>
        <a href="?tab=backlog" class="pro-tab {{ $activeTab === 'backlog' ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> Backlog
        </a>
        <a href="?tab=files" class="pro-tab {{ $activeTab === 'files' ? 'active' : '' }}">
            <i class="fas fa-folder"></i> Files
            @if($project->media->count() > 0)<span class="pro-tab-badge">{{ $project->media->count() }}</span>@endif
        </a>
        <a href="?tab=activity" class="pro-tab {{ $activeTab === 'activity' ? 'active' : '' }}">
            <i class="fas fa-history"></i> Activity
        </a>
        <a href="?tab=settings" class="pro-tab {{ $activeTab === 'settings' ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Settings
        </a>
    </div>

    <!-- Tab Content -->
    <div class="pro-content">
        @if ($activeTab === 'overview')
            @include('tenant.manage.projects.tabs.overview', ['project' => $project])
        @elseif ($activeTab === 'board')
            @include('tenant.manage.projects.tabs.board', ['project' => $project])
        @elseif($activeTab === 'list')
            @include('tenant.manage.projects.tabs.list', ['project' => $project])
        @elseif($activeTab === 'timeline')
            @include('tenant.manage.projects.tabs.timeline', ['project' => $project])
        @elseif($activeTab === 'backlog')
            @include('tenant.manage.projects.tabs.backlog', ['project' => $project])
        @elseif($activeTab === 'files')
            @include('tenant.manage.projects.tabs.files', ['project' => $project])
        @elseif($activeTab === 'activity')
            @include('tenant.manage.projects.tabs.activity', ['project' => $project])
        @elseif($activeTab === 'settings')
            <div class="pro-empty">
                <i class="fas fa-cog"></i>
                <h3>Project Settings</h3>
                <p>Configure project preferences and permissions</p>
            </div>
        @else
            <div class="pro-empty">
                <i class="fas fa-inbox"></i>
                <h3>{{ ucfirst($activeTab) }}</h3>
                <p>This section is under development</p>
            </div>
        @endif
    </div>

    <style>
        /* ===== PROFESSIONAL COMPACT STYLES ===== */
        
        /* Breadcrumbs - More Compact */
        .pro-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 16px;
            padding: 8px 0;
        }

        .pro-breadcrumbs a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .pro-breadcrumbs a:hover {
            color: var(--accent);
        }

        .pro-breadcrumbs .active {
            color: var(--text-heading);
            font-weight: 500;
        }

        .pro-breadcrumbs i {
            font-size: 11px;
            opacity: 0.6;
        }

        /* Compact Header */
        .pro-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .pro-header-main {
            display: flex;
            gap: 14px;
            flex: 1;
            min-width: 0;
        }

        .pro-avatar {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .pro-header-info {
            flex: 1;
            min-width: 0;
        }

        .pro-header-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .pro-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-heading);
            margin: 0;
            line-height: 1.2;
        }

        .pro-badges {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pro-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .pro-badge-type {
            background: var(--accent-light);
            color: var(--accent);
        }

        .pro-badge-client {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .pro-badge-status {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .pro-badge-status.status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .pro-badge-status.status-completed {
            background: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .pro-meta-compact {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .pro-meta-compact span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pro-meta-compact i {
            font-size: 12px;
            opacity: 0.7;
        }

        .pro-progress-inline {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pro-progress-mini {
            width: 60px;
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
        }

        .pro-progress-mini div {
            height: 100%;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .pro-header-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .pro-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--card);
            color: var(--text-body);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .pro-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--accent-light);
        }

        .pro-btn-icon {
            padding: 8px;
            min-width: 36px;
        }

        .pro-btn-secondary {
            background: var(--bg);
        }

        /* Compact Tabs */
        .pro-tabs {
            display: flex;
            gap: 2px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 16px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .pro-tabs::-webkit-scrollbar {
            display: none;
        }

        .pro-tab {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            position: relative;
        }

        .pro-tab:hover {
            color: var(--text-body);
            background: var(--card);
        }

        .pro-tab.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pro-tab i {
            font-size: 12px;
        }

        .pro-tab-badge {
            padding: 1px 6px;
            background: rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }

        .pro-tab.active .pro-tab-badge {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Content Area */
        .pro-content {
            min-height: 400px;
        }

        /* Empty State */
        .pro-empty {
            text-align: center;
            padding: 60px 20px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .pro-empty i {
            font-size: 48px;
            color: var(--text-muted);
            opacity: 0.5;
            margin-bottom: 12px;
        }

        .pro-empty h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-heading);
            margin: 0 0 6px 0;
        }

        .pro-empty p {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pro-header {
                flex-direction: column;
            }

            .pro-header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .pro-title {
                font-size: 18px;
            }

            .pro-meta-compact {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .pro-tabs {
                padding: 3px;
                gap: 1px;
            }

            .pro-tab {
                padding: 7px 12px;
                font-size: 12px;
            }
        }
    </style>

    <script>
        function openTaskDrawer(id) {
            console.log('openTaskDrawer()', id);
        }

        function openTaskActions(id) {
            console.log('openTaskActions()', id);
        }

        function openRequestChangesModal(id) {
            console.log('openRequestChangesModal()', id);
        }
    </script>
@endsection