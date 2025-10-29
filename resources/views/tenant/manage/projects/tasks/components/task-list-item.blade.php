{{-- resources/views/tenant/manage/projects/tasks/components/task-list-item.blade.php --}}
@php
    $highlightClass = $highlight ?? '';
@endphp

<div class="task-list-item {{ $highlightClass }}" data-task-id="{{ $task->id }}" onclick="openTaskDrawer({{ $task->id }})">
    <div class="task-list-check">
        <div class="task-checkbox"></div>
    </div>

    <div class="task-list-content">
        <div class="task-list-header">
            <h3 class="task-list-title">{{ $task->title }}</h3>
            <div class="task-list-badges">
                <span class="task-project-tag">{{ $task->project->key }}</span>
                @if($task->priority === 'urgent' || $task->priority === 'high')
                    <span class="task-priority-tag priority-{{ $task->priority }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="task-list-meta">
            @if($task->due_date)
                <div class="task-meta-group {{ $task->is_overdue ? 'overdue' : '' }}">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <rect x="2" y="3" width="10" height="9" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path d="M2 5h10M4 1v2M10 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>{{ $task->due_date->format('M d, Y') }}</span>
                </div>
            @endif

            @if($task->subtasks_count > 0)
                <div class="task-meta-group">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M3 7h8M3 4h8M3 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>{{ $task->completed_subtasks_count }}/{{ $task->subtasks_count }}</span>
                </div>
            @endif

            <div class="task-status-badge status-{{ $task->status }}">
                {{ ucfirst(str_replace('-', ' ', $task->status)) }}
            </div>
        </div>

        @if($task->description)
            <p class="task-list-desc">{{ \Illuminate\Support\Str::limit($task->description, 100) }}</p>
        @endif
    </div>

    <div class="task-list-assignee">
        @if($task->assignee)
            <div class="task-avatar-large" title="{{ $task->assignee->name }}">
                @if($task->assignee->avatar_url)
                    <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}">
                @else
                    <div class="task-avatar-placeholder">{{ substr($task->assignee->name, 0, 1) }}</div>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
.tasks-list-view {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.task-list-item {
    background: white;
    border: 1px solid #DFE1E6;
    border-radius: 8px;
    padding: 16px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
    transition: all 0.2s;
    cursor: pointer;
}

.task-list-item:hover {
    border-color: #0052CC;
    box-shadow: 0 2px 8px rgba(0, 82, 204, 0.15);
}

.task-list-item.overdue {
    border-left: 3px solid #DE350B;
}

.task-list-item.today {
    border-left: 3px solid #FF8B00;
}

.task-list-item.review {
    border-left: 3px solid #00875A;
}

.task-list-item.urgent {
    border-left: 3px solid #DE350B;
    background: #FFFAF0;
}

.task-list-check {
    padding-top: 2px;
}

.task-checkbox {
    width: 20px;
    height: 20px;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.task-checkbox:hover {
    border-color: #0052CC;
    background: #DEEBFF;
}

.task-list-content {
    flex: 1;
    min-width: 0;
}

.task-list-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 8px;
}

.task-list-title {
    font-size: 15px;
    font-weight: 600;
    color: #172B4D;
    margin: 0;
    line-height: 1.4;
}

.task-list-badges {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.task-project-tag {
    padding: 3px 8px;
    background: #F4F5F7;
    color: #5E6C84;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.task-priority-tag {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.task-priority-tag.priority-high {
    background: #FFEBE6;
    color: #FF5630;
}

.task-priority-tag.priority-urgent {
    background: #DE350B;
    color: white;
}

.task-list-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.task-meta-group {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #5E6C84;
}

.task-meta-group.overdue {
    color: #DE350B;
}

.task-meta-group.overdue svg {
    stroke: #DE350B;
}

.task-list-desc {
    font-size: 13px;
    color: #5E6C84;
    margin: 8px 0 0 0;
    line-height: 1.5;
}

.task-list-assignee {
    flex-shrink: 0;
}

.task-avatar-large {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.task-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.task-avatar-large .task-avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #0052CC;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .task-list-item {
        flex-direction: column;
    }

    .task-list-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
