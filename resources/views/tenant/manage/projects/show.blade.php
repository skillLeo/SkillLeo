@extends('tenant.manage.app')

@section('main')

@php
    // Active tab from query string (?tab=board etc.)
    $activeTab = request()->get('tab', 'board');

    // Project owner (the person who created / owns it)
    $owner = $project->user;

    // Stable color badge from project id
    $colors = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#feca57', '#48dbfb', '#ff9ff3'];
    $color  = $colors[$project->id % count($colors)];

    // Task stats
    $tasksDone      = $project->tasks->where('status', 'done')->count();
    $tasksTotal     = $project->tasks->count();
    $tasksRemaining = $tasksTotal - $tasksDone;
@endphp

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.list', $username) }}" class="project-breadcrumb-item">
        All Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">{{ $project->key }}</span>
</div>

<!-- Project Header -->
<div class="project-detail-header">
    <div class="project-detail-header-left">
        <div class="project-detail-avatar" style="background: {{ $color }};">
            {{ \Illuminate\Support\Str::of($project->key)->substr(0,2)->upper() }}
        </div>

        <div class="project-detail-info">
            <div class="project-detail-key-type">
                <span class="project-detail-key">{{ $project->key }}</span>

                <span class="project-detail-type-badge">
                    <i class="fas fa-{{ $project->type === 'scrum' ? 'layer-group' : 'stream' }}"></i>
                    {{ ucfirst($project->type) }}
                </span>
            </div>

            <h1 class="project-detail-title">{{ $project->name }}</h1>

            <div class="project-detail-meta">
                <div class="project-detail-meta-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Lead by {{ $owner?->name ?? 'Unknown' }}</span>
                </div>

                <div class="project-detail-meta-item">
                    <i class="fas fa-users"></i>
                    <span>{{ $project->team->count() }} team members</span>
                </div>

                <div class="project-detail-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>
                        Due {{ optional($project->due_date)->format('M d, Y') ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="project-detail-header-right">
        <button class="project-btn project-btn-secondary">
            <i class="fas fa-star"></i>
            <span>Star</span>
        </button>
        <button class="project-btn project-btn-secondary">
            <i class="fas fa-share-alt"></i>
            <span>Share</span>
        </button>
        <button class="project-icon-btn">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>
</div>

<!-- Progress Card -->
<div class="project-detail-progress-card">
    <div class="project-detail-progress-info">
        <span class="project-detail-progress-label">Project Progress</span>
        <span class="project-detail-progress-value">{{ $progress }}% Complete</span>
    </div>

    <div class="project-detail-progress-bar">
        <div class="project-detail-progress-fill" style="width: {{ $progress }}%;"></div>
    </div>

    <div class="project-detail-progress-stats">
        <span>{{ $tasksDone }} of {{ $tasksTotal }} tasks completed</span>
        <span>{{ $tasksRemaining }} remaining</span>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="project-detail-tabs">
    <a href="?tab=board" class="project-detail-tab {{ $activeTab === 'board' ? 'active' : '' }}">
        <i class="fas fa-columns"></i>
        <span>Board</span>
    </a>

    <a href="?tab=list" class="project-detail-tab {{ $activeTab === 'list' ? 'active' : '' }}">
        <i class="fas fa-list"></i>
        <span>List</span>
        <span class="project-tab-count">{{ $tasksTotal }}</span>
    </a>

    <a href="?tab=timeline" class="project-detail-tab {{ $activeTab === 'timeline' ? 'active' : '' }}">
        <i class="fas fa-chart-gantt"></i>
        <span>Timeline</span>
    </a>

    <a href="?tab=backlog" class="project-detail-tab {{ $activeTab === 'backlog' ? 'active' : '' }}">
        <i class="fas fa-inbox"></i>
        <span>Backlog</span>
    </a>

    <a href="?tab=files" class="project-detail-tab {{ $activeTab === 'files' ? 'active' : '' }}">
        <i class="fas fa-folder"></i>
        <span>Files</span>
    </a>

    <a href="?tab=activity" class="project-detail-tab {{ $activeTab === 'activity' ? 'active' : '' }}">
        <i class="fas fa-history"></i>
        <span>Activity</span>
    </a>

    <a href="?tab=settings" class="project-detail-tab {{ $activeTab === 'settings' ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
</div>

<!-- Tab Content -->
<div class="project-detail-tab-content">
    @if($activeTab === 'board')
        @include('tenant.manage.projects.tabs.board', ['project' => $project])

    @elseif($activeTab === 'list')
        @include('tenant.manage.projects.tabs.list', ['project' => $project])

    @elseif($activeTab === 'timeline')
        @include('tenant.manage.projects.tabs.timeline', ['project' => $project])

    @elseif($activeTab === 'backlog')
        @include('tenant.manage.projects.tabs.backlog')

    @elseif($activeTab === 'files')
        @include('tenant.manage.projects.tabs.files')

    @else
        <div class="project-empty-state">
            <div class="project-empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="project-empty-state-title">{{ ucfirst($activeTab) }} View</h3>
            <p class="project-empty-state-desc">This section is under development</p>
        </div>
    @endif
</div>

<style>
    /* ===== PROJECT DETAIL PAGE STYLES ===== */

    .project-detail-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .project-detail-header-left {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        flex: 1;
    }

    .project-detail-avatar {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: var(--fw-bold);
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .project-detail-info {
        flex: 1;
        min-width: 0;
    }

    .project-detail-key-type {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .project-detail-key {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        font-family: monospace;
        background: var(--bg);
        padding: 4px 10px;
        border-radius: 6px;
    }

    .project-detail-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 6px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
    }

    .project-detail-title {
        font-size: var(--fs-h1);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0 0 12px 0;
        line-height: var(--lh-tight);
    }

    .project-detail-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .project-detail-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .project-detail-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Progress Card */
    .project-detail-progress-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 24px;
    }

    .project-detail-progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .project-detail-progress-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-body);
    }

    .project-detail-progress-value {
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
        color: var(--accent);
    }

    .project-detail-progress-bar {
        height: 10px;
        background: var(--bg);
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .project-detail-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-dark) 100%);
        border-radius: 5px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .project-detail-progress-fill::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .project-detail-progress-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Tabs */
    .project-detail-tabs {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 24px;
        border-bottom: 2px solid var(--border);
        overflow-x: auto;
        scrollbar-width: none;
    }

    .project-detail-tabs::-webkit-scrollbar {
        display: none;
    }

    .project-detail-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        color: var(--text-muted);
        font-weight: var(--fw-medium);
        font-size: var(--fs-body);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        white-space: nowrap;
    }

    .project-detail-tab:hover {
        color: var(--text-body);
        background: var(--accent-light);
    }

    .project-detail-tab.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
        font-weight: var(--fw-semibold);
    }

    .project-tab-count {
        padding: 2px 8px;
        background: var(--bg);
        border-radius: 10px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        min-width: 20px;
        text-align: center;
    }

    .project-detail-tab.active .project-tab-count {
        background: var(--accent);
        color: var(--btn-text-primary);
    }

    /* Tab Content container */
    .project-detail-tab-content {
        min-height: 500px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .project-detail-header {
            flex-direction: column;
        }

        .project-detail-header-left {
            flex-direction: column;
            width: 100%;
        }

        .project-detail-avatar {
            width: 56px;
            height: 56px;
            font-size: 18px;
        }

        .project-detail-title {
            font-size: var(--fs-h2);
        }

        .project-detail-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .project-detail-header-right {
            width: 100%;
            justify-content: flex-start;
        }

        .project-detail-tab {
            padding: 12px 16px;
            font-size: var(--fs-subtle);
        }
    }
</style>
@endsection
