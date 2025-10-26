{{-- resources/views/tenant/manage/projects/tasks/index.blade.php --}}
@extends('tenant.manage.app')

@section('main')

@php
    $activeTab = request()->get('tab', 'active');
    
    // Calculate stats
    $myActiveTasks = $allTasks->where('assigned_to', $viewer->id)
                               ->whereNotIn('status', ['done']);
    $myCompletedTasks = $allTasks->where('assigned_to', $viewer->id)
                                  ->where('status', 'done');
    $tasksICreated = $allTasks->where('reporter_id', $viewer->id);
    $overdueTasks = $allTasks->where('is_overdue', true);
@endphp

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator">
        <i class="fas fa-chevron-right"></i>
    </span>
    <span class="project-breadcrumb-item active">Tasks</span>
</div>

<!-- Page Header -->
<div class="project-page-header">
    <div>
        <h1 class="project-page-title">
            <i class="fas fa-tasks"></i>
            Tasks
        </h1>
        <p class="project-page-subtitle">Manage all your tasks across projects</p>
    </div>
    <div class="project-page-actions">
        <button class="project-btn project-btn-secondary" onclick="exportTasks()">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
        <button type="button" class="project-btn project-btn-primary" onclick="openCreateTaskModal()">
            <i class="fas fa-plus"></i>
            <span>New Task</span>
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="project-stats-grid">
    <div class="project-stat-card">
        <div class="project-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="project-stat-content">
            <div class="project-stat-value">{{ $myActiveTasks->count() }}</div>
            <div class="project-stat-label">My Active Tasks</div>
        </div>
    </div>

    <div class="project-stat-card">
        <div class="project-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="project-stat-content">
            <div class="project-stat-value">{{ $myCompletedTasks->count() }}</div>
            <div class="project-stat-label">Completed</div>
        </div>
    </div>

    <div class="project-stat-card">
        <div class="project-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="project-stat-content">
            <div class="project-stat-value">{{ $overdueTasks->count() }}</div>
            <div class="project-stat-label">Overdue</div>
        </div>
    </div>

    <div class="project-stat-card">
        <div class="project-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="project-stat-content">
            <div class="project-stat-value">{{ $tasksICreated->count() }}</div>
            <div class="project-stat-label">Tasks I Created</div>
        </div>
    </div>
</div>

<!-- Navigation Tabs (like Project Detail) -->
<div class="project-detail-tabs">
    <a href="?tab=active" class="project-detail-tab {{ $activeTab === 'active' ? 'active' : '' }}">
        <i class="fas fa-play-circle"></i>
        <span>Active</span>
        <span class="project-tab-count">{{ $myActiveTasks->count() }}</span>
    </a>

    <a href="?tab=mine" class="project-detail-tab {{ $activeTab === 'mine' ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span>Assigned to Me</span>
        <span class="project-tab-count">{{ $allTasks->where('assigned_to', $viewer->id)->count() }}</span>
    </a>

    <a href="?tab=created" class="project-detail-tab {{ $activeTab === 'created' ? 'active' : '' }}">
        <i class="fas fa-pencil-alt"></i>
        <span>Created by Me</span>
        <span class="project-tab-count">{{ $tasksICreated->count() }}</span>
    </a>

    <a href="?tab=all" class="project-detail-tab {{ $activeTab === 'all' ? 'active' : '' }}">
        <i class="fas fa-th-list"></i>
        <span>All Tasks</span>
        <span class="project-tab-count">{{ $allTasks->count() }}</span>
    </a>

    <a href="?tab=completed" class="project-detail-tab {{ $activeTab === 'completed' ? 'active' : '' }}">
        <i class="fas fa-check-double"></i>
        <span>Completed</span>
        <span class="project-tab-count">{{ $allTasks->where('status', 'done')->count() }}</span>
    </a>

    <a href="?tab=overdue" class="project-detail-tab {{ $activeTab === 'overdue' ? 'active' : '' }}">
        <i class="fas fa-clock"></i>
        <span>Overdue</span>
        <span class="project-tab-count">{{ $overdueTasks->count() }}</span>
    </a>
</div>

<!-- Tab Content -->
<div class="project-detail-tab-content">
    @if($activeTab === 'active')
        @include('tenant.manage.projects.tasks.tabs.active', [
            'tasks' => $myActiveTasks,
            'viewer' => $viewer,
            'username' => $username,
        ])

    @elseif($activeTab === 'mine')
        @include('tenant.manage.projects.tasks.tabs.mine', [
            'tasks' => $allTasks->where('assigned_to', $viewer->id),
            'viewer' => $viewer,
            'username' => $username,
        ])

    @elseif($activeTab === 'created')
        @include('tenant.manage.projects.tasks.tabs.created', [
            'tasks' => $tasksICreated,
            'viewer' => $viewer,
            'username' => $username,
        ])

    @elseif($activeTab === 'all')
        @include('tenant.manage.projects.tasks.tabs.all', [
            'tasks' => $allTasks,
            'viewer' => $viewer,
            'username' => $username,
        ])

    @elseif($activeTab === 'completed')
        @include('tenant.manage.projects.tasks.tabs.completed', [
            'tasks' => $allTasks->where('status', 'done'),
            'viewer' => $viewer,
            'username' => $username,
        ])

    @elseif($activeTab === 'overdue')
        @include('tenant.manage.projects.tasks.tabs.overdue', [
            'tasks' => $overdueTasks,
            'viewer' => $viewer,
            'username' => $username,
        ])
    @endif
</div>

<style>
/* Import project detail styles */
.project-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.project-stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    transition: all 0.2s ease;
}

.project-stat-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.project-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.project-stat-content {
    flex: 1;
}

.project-stat-value {
    font-size: 24px;
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    line-height: 1;
    margin-bottom: 4px;
}

.project-stat-label {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

/* Reuse project detail tabs styles */
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
    border-radius: 6px 6px 0 0;
}

.project-detail-tab:hover {
    color: var(--text-body);
    background: var(--accent-light);
}

.project-detail-tab.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
    font-weight: var(--fw-semibold);
    background: var(--bg);
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

.project-detail-tab-content {
    min-height: 500px;
}
</style>

<script>
function exportTasks() {
    showNotification('Export functionality coming soon!', 'info');
}

function openCreateTaskModal() {
    showNotification('Create task modal coming soon!', 'info');
}

function showNotification(message, type = 'info') {
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
        background: ${colors[type]};
        color: white;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

@endsection