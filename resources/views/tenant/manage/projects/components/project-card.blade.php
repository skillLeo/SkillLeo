{{-- resources/views/tenant/projects/components/project-card.blade.php --}}
@php
    $statusColors = [
        'active' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'text' => '#10b981', 'label' => 'Active'],
        'planning' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'text' => '#3b82f6', 'label' => 'Planning'],
        'on-hold' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'text' => '#f59e0b', 'label' => 'On Hold'],
        'completed' => ['bg' => 'rgba(139, 92, 246, 0.1)', 'text' => '#8b5cf6', 'label' => 'Completed'],
    ];
    $status = $statusColors[$project['status']] ?? $statusColors['active'];
@endphp

<div class="project-card-item" onclick="window.location.href='{{ route('tenant.manage.projects.show', [$username, $project['id']]) }}'">
    <!-- Header -->
    <div class="project-card-item-header">
        <div class="project-card-item-key-type">
            <div class="project-card-item-avatar" style="background: {{ $project['color'] }};">
                {{ substr($project['key'], 0, 2) }}
            </div>
            <div>
                <div class="project-card-item-key">{{ $project['key'] }}</div>
                <div class="project-card-item-type">
                    @if($project['type'] === 'order')
                        <i class="fas fa-shopping-cart"></i> Client Order
                    @else
                        <i class="fas fa-project-diagram"></i> Internal Project
                    @endif
                </div>
            </div>
        </div>
        
        <button class="project-icon-btn" onclick="event.stopPropagation(); openProjectMenu({{ $project['id'] }})">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>

    <!-- Title -->
    <h3 class="project-card-item-title">{{ $project['name'] }}</h3>

    <!-- Client (if order) -->
    @if($project['client'])
        <div class="project-card-item-client">
            <i class="fas fa-user-circle"></i>
            <span>{{ $project['client'] }}</span>
        </div>
    @endif

    <!-- Progress -->
    <div class="project-card-item-progress-section">
        <div class="project-card-item-progress-header">
            <span class="project-card-item-progress-label">Progress</span>
            <span class="project-card-item-progress-value">{{ $project['progress'] }}%</span>
        </div>
        <div class="project-card-item-progress-bar">
            <div class="project-card-item-progress-fill" style="width: {{ $project['progress'] }}%;"></div>
        </div>
    </div>

    <!-- Meta -->
    <div class="project-card-item-meta">
        <div class="project-card-item-meta-item">
            <i class="fas fa-tasks"></i>
            <span>{{ $project['tasks'] }} tasks</span>
        </div>
        <div class="project-card-item-meta-item">
            <i class="fas fa-users"></i>
            <span>{{ $project['team'] }} members</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="project-card-item-footer">
        <div class="project-card-item-status" style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};">
            <div class="project-pill-dot" style="background: {{ $status['text'] }};"></div>
            <span>{{ $status['label'] }}</span>
        </div>
        <div class="project-card-item-due">
            <i class="fas fa-calendar"></i>
            <span>{{ $project['due_date'] }}</span>
        </div>
    </div>
</div>

<style>
    .project-card-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .project-card-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    .project-card-item-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .project-card-item-key-type {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .project-card-item-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
        color: white;
        flex-shrink: 0;
    }

    .project-card-item-key {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        font-family: monospace;
    }

    .project-card-item-type {
        font-size: var(--fs-micro);
        color: var(--text-subtle);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .project-card-item-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 12px 0;
        line-height: var(--lh-tight);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .project-card-item-client {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin-bottom: 12px;
        padding: 6px 10px;
        background: var(--bg);
        border-radius: 6px;
        width: fit-content;
    }

    .project-card-item-progress-section {
        margin: 16px 0;
    }

    .project-card-item-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .project-card-item-progress-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .project-card-item-progress-value {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .project-card-item-progress-bar {
        height: 6px;
        background: var(--bg);
        border-radius: 3px;
        overflow: hidden;
    }

    .project-card-item-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-dark) 100%);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .project-card-item-meta {
        display: flex;
        gap: 16px;
        margin: 16px 0;
        padding: 12px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    .project-card-item-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .project-card-item-meta-item i {
        font-size: var(--ic-sm);
    }

    .project-card-item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .project-card-item-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
    }

    .project-card-item-due {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .project-card-item-due i {
        font-size: var(--ic-sm);
    }
</style>