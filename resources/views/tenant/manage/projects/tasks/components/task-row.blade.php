{{-- resources/views/tenant/manage/projects/tasks/components/task-row.blade.php --}}
@php
/**
 * Props:
 *  - $task (Task model)
 *  - $themeColor (string) - from parent section for subtle accent ring if needed
 */

$isOverdue = $task->is_overdue ?? false;
$dueLabel  = $task->due_date?->format('M d') ?? '—';
$keyLabel  = ($task->project?->key ?? 'TASK') . '-' . $task->id;
$assigneeName = $task->assignee?->name ?? null;
$assigneeAvatar = $task->assignee->avatar_url
    ?? asset('images/avatar-fallback.png');
@endphp

<div class="task-row-card" onclick="openTaskDrawer({{ $task->id }})">
    <div class="task-row-main">
        <div class="task-row-left">
            <!-- Complete Checkbox -->
            <button class="task-complete-toggle"
                    onclick="event.stopPropagation();markTaskDone({{ $task->id }});"
                    title="Mark as done">
                <i class="far fa-square"></i>
            </button>

            <div class="task-row-text">
                <div class="task-row-key">
                    {{ $keyLabel }}
                </div>
                <div class="task-row-title">
                    {{ $task->title }}
                </div>

                @if($task->subtasks && $task->subtasks->count() > 0)
                    @php
                        $subCompleted = $task->subtasks->where('completed', true)->count();
                        $subTotal     = $task->subtasks->count();
                    @endphp
                    <div class="task-row-subtasks">
                        <i class="fas fa-check-square"></i>
                        <span>{{ $subCompleted }}/{{ $subTotal }} subtasks</span>
                    </div>
                @endif

                @if($task->notes)
                    <div class="task-row-notes">
                        {{ \Illuminate\Support\Str::limit(strip_tags($task->notes), 140) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="task-row-right">
            <!-- Assignee -->
            <div class="task-row-assignee">
                @if($assigneeName)
                    <img class="task-row-avatar"
                         src="{{ $assigneeAvatar }}"
                         alt="{{ $assigneeName }}">
                @else
                    <div class="task-row-unassigned">Unassigned</div>
                @endif
            </div>

            <!-- Due -->
            <div class="task-row-due"
                 style="color:{{ $isOverdue ? '#ef4444' : 'var(--text-heading)' }}">
                <i class="fas fa-calendar"></i>
                <span>{{ $dueLabel }}</span>
            </div>

            <!-- Quick actions -->
            <div class="task-row-actions">
                <button class="task-row-action-btn"
                        title="Remind"
                        onclick="event.stopPropagation();remindTask({{ $task->id }});">
                    <i class="fas fa-bell"></i>
                </button>

                <button class="task-row-action-btn"
                        title="Postpone"
                        onclick="event.stopPropagation();postponeTask({{ $task->id }});">
                    <i class="fas fa-clock"></i>
                </button>

                <button class="task-row-action-btn"
                        title="More"
                        onclick="event.stopPropagation();openTaskActions({{ $task->id }});">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.task-row-card{
    border-bottom:1px solid var(--border);
    padding:16px 20px;
    cursor:pointer;
    transition:background .15s ease;
    background:var(--card);
}
.task-row-card:hover{
    background:var(--bg);
}

.task-row-main{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px;
    flex-wrap:wrap;
}

.task-row-left{
    display:flex;
    align-items:flex-start;
    gap:12px;
    flex:1;
    min-width:200px;
}

.task-complete-toggle{
    flex-shrink:0;
    background:none;
    border:1px solid var(--border);
    border-radius:6px;
    width:28px;
    height:28px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--text-muted);
    font-size:14px;
    cursor:pointer;
    transition:all .15s ease;
}
.task-complete-toggle:hover{
    background:var(--accent-light);
    border-color:var(--accent);
    color:var(--accent);
}

.task-row-text{
    min-width:0;
    max-width:100%;
}

.task-row-key{
    font-size:var(--fs-micro);
    font-family:monospace;
    color:var(--text-muted);
    line-height:1.3;
    margin-bottom:4px;
    user-select:text;
}
.task-row-title{
    font-size:var(--fs-body);
    font-weight:var(--fw-semibold);
    color:var(--text-heading);
    line-height:1.4;
    word-break:break-word;
}
.task-row-subtasks{
    display:flex;
    align-items:center;
    gap:6px;
    font-size:var(--fs-subtle);
    color:var(--text-muted);
    margin-top:6px;
    line-height:1.3;
}
.task-row-notes{
    font-size:var(--fs-subtle);
    color:var(--text-muted);
    margin-top:8px;
    line-height:1.4;
    max-width:600px;
    word-break:break-word;
}

.task-row-right{
    display:flex;
    align-items:flex-start;
    flex-wrap:wrap;
    gap:16px;
}

.task-row-assignee{
    min-width:40px;
}
.task-row-avatar{
    width:32px;
    height:32px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid var(--card);
}
.task-row-unassigned{
    font-size:var(--fs-subtle);
    color:var(--text-muted);
    font-style:italic;
}

.task-row-due{
    font-size:var(--fs-subtle);
    font-weight:var(--fw-semibold);
    line-height:1.3;
    display:flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
}

.task-row-actions{
    display:flex;
    gap:6px;
}
.task-row-action-btn{
    background:none;
    border:1px solid var(--border);
    border-radius:6px;
    width:32px;
    height:32px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    color:var(--text-muted);
    cursor:pointer;
    transition:all .15s ease;
}
.task-row-action-btn:hover{
    background:var(--accent-light);
    border-color:var(--accent);
    color:var(--accent);
}

@media (max-width:600px){
    .task-row-right{
        width:100%;
        justify-content:space-between;
    }
}
</style>
