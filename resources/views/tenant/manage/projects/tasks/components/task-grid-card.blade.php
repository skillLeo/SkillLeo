{{-- resources/views/tenant/manage/projects/tasks/components/task-grid-card.blade.php --}}

<div class="task-card" data-task-id="{{ $task->id }}" onclick="openTaskDrawer({{ $task->id }})">
    <div class="task-card-header">
        <div class="task-project-badge" style="background: {{ ['#DEEBFF','#E3FCEF','#FFEBE6','#EAE6FF'][$task->project_id % 4] }}; color: {{ ['#0052CC','#00875A','#DE350B','#6554C0'][$task->project_id % 4] }}">
            {{ $task->project->key }}
        </div>
        <div class="task-card-actions">
            <button class="task-action-btn" onclick="event.stopPropagation(); openTaskMenu({{ $task->id }})">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="4" r="1" fill="currentColor"/>
                    <circle cx="8" cy="8" r="1" fill="currentColor"/>
                    <circle cx="8" cy="12" r="1" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="task-card-body">
        <h3 class="task-card-title">{{ $task->title }}</h3>
        
        @if($task->description)
            <p class="task-card-desc">{{ \Illuminate\Support\Str::limit($task->description, 80) }}</p>
        @endif

        <div class="task-card-meta">
            <div class="task-meta-item">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="3" width="10" height="9" rx="1"/>
                    <path d="M2 5h10M4 1v2M10 1v2" stroke-linecap="round"/>
                </svg>
                <span>{{ $task->due_date?->format('M d') ?? 'No date' }}</span>
            </div>

            @if($task->priority)
                <div class="task-priority-badge priority-{{ $task->priority }}">
                    {{ ucfirst($task->priority) }}
                </div>
            @endif

            @if($task->subtasks_count > 0)
                <div class="task-meta-item">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 7h8M3 4h8M3 10h5" stroke-linecap="round"/>
                    </svg>
                    <span>{{ $task->completed_subtasks_count }}/{{ $task->subtasks_count }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="task-card-footer">
        <div class="task-status-badge status-{{ $task->status }}">
            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
        </div>

        @if($task->assignee)
            <div class="task-avatar" title="{{ $task->assignee->name }}">
                @if($task->assignee->avatar_url)
                    <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}">
                @else
                    <div class="task-avatar-placeholder">{{ substr($task->assignee->name, 0, 1) }}</div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
function openTaskDrawer(taskId) {
    console.log('Opening task drawer:', taskId);
    // Implementation: open task detail drawer/modal
}

function openTaskMenu(taskId) {
    console.log('Opening task menu:', taskId);
    // Implementation: show context menu
}
</script>