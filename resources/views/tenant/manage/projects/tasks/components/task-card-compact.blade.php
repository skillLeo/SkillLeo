@props([
    'task',
    'canEdit' => false,
    'canDelete' => false,
    'canComplete' => false,
    'canPostpone' => false,
    'compactMode' => false,
])

@php
    $isOverdue = $task->due_date && $task->due_date->isPast() && $task->status !== \App\Models\Task::STATUS_DONE;
    $statusLabel = ucfirst(str_replace('_', ' ', $task->status));
@endphp

<div class="task-card">
    <div class="task-card-head">
        <div class="task-card-title">{{ $task->title }}</div>
        <div class="task-card-meta">
            <span class="status-chip status-{{ $task->status }}">
                {{ $statusLabel }}
            </span>

            @if($task->due_date)
                <span class="due-chip {{ $isOverdue ? 'overdue' : '' }}">
                    <i class="far fa-clock"></i>
                    {{ $task->due_date->format('M d') }}
                    @if($isOverdue)
                        <strong>OVERDUE</strong>
                    @endif
                </span>
            @endif
        </div>
    </div>

    <div class="task-card-body">
        <div class="task-card-row">
            <div class="task-card-label">Assignee</div>
            <div class="task-card-value">{{ $task->assignee?->name ?? 'Unassigned' }}</div>
        </div>

        <div class="task-card-row">
            <div class="task-card-label">Reporter</div>
            <div class="task-card-value">{{ $task->reporter?->name ?? '—' }}</div>
        </div>

        @if(!$compactMode)
            <div class="task-card-row">
                <div class="task-card-label">Progress</div>
                <div class="task-card-value">
                    @php
                        $subs = $task->subtasks;
                        $totalSub = $subs->count();
                        $doneSub = $subs->where('completed', true)->count();
                        $pct = $totalSub > 0 ? round(($doneSub / $totalSub) * 100) : 0;
                    @endphp
                    @if($totalSub === 0)
                        <span class="progress-chip">No subtasks</span>
                    @else
                        <span class="progress-chip">{{ $doneSub }}/{{ $totalSub }} ({{ $pct }}%)</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if(!$compactMode)
    <div class="task-card-actions">
        <button class="mini-btn" onclick="openDrawer({{ $task->id }})">
            <i class="fas fa-external-link-alt"></i> View
        </button>

        @if($canComplete && $task->status !== \App\Models\Task::STATUS_DONE)
            <form method="POST"
                action="#">
                @csrf
                <button class="mini-btn success">
                    <i class="fas fa-check"></i> Submit Review
                </button>
            </form>
        @endif

        @if($canPostpone && $task->status !== \App\Models\Task::STATUS_POSTPONED)
            <form method="POST"
                action="">
                @csrf
                <input type="hidden" name="postponed_until" value="{{ now()->addDay()->toDateString() }}">
                <input type="hidden" name="reason" value="Need more time">
                <button class="mini-btn warn">
                    <i class="fas fa-clock"></i> Postpone
                </button>
            </form>
        @endif

        @if($canDelete)
            <form method="POST"
                action="#"
                onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')
                <button class="mini-btn danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        @endif
    </div>
    @endif
</div>

<style>
.task-card {
    background:#fff;
    border:1px solid var(--border);
    border-radius:var(--radius);
    box-shadow:0 1px 2px rgba(0,0,0,0.03);
    display:flex;
    flex-direction:column;
    font-size:13px;
}
.task-card-head {
    border-bottom:1px solid var(--border);
    padding:12px 16px;
}
.task-card-title {
    font-size:14px;
    font-weight:600;
    color:var(--text-heading);
    margin-bottom:4px;
}
.task-card-meta {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    font-size:12px;
    color:var(--text-muted);
}
.status-chip {
    display:inline-flex;
    align-items:center;
    font-size:11px;
    font-weight:600;
    padding:2px 8px;
    border-radius:999px;
    border:1px solid var(--border);
    background:#f9fafb;
    text-transform:capitalize;
}
.status-done {
    background:#ecfdf5;
    border-color:#10b981;
    color:#065f46;
}
.status-blocked {
    background:#fef2f2;
    border-color:#dc2626;
    color:#991b1b;
}
.status-postponed {
    background:#fff7ed;
    border-color:#facc15;
    color:#92400e;
}
.due-chip {
    display:inline-flex;
    align-items:center;
    gap:4px;
    background:#f3f4f6;
    border-radius:999px;
    padding:2px 8px;
    font-size:11px;
    border:1px solid #e5e7eb;
    color:#374151;
}
.due-chip.overdue {
    background:#fee2e2;
    border-color:#dc2626;
    color:#dc2626;
    font-weight:600;
}
.task-card-body {
    padding:12px 16px;
    display:flex;
    flex-direction:column;
    gap:8px;
}
.task-card-row{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:6px 12px;
}
.task-card-label{
    color:#6b7280;
    font-size:12px;
    min-width:80px;
}
.task-card-value{
    color:var(--text-heading);
    font-weight:500;
    font-size:13px;
}
.progress-chip {
    background:#eef2ff;
    border:1px solid #6366f1;
    color:#4f46e5;
    font-size:11px;
    font-weight:600;
    border-radius:999px;
    padding:2px 8px;
}
.task-card-actions {
    padding:12px 16px;
    border-top:1px solid var(--border);
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}
.mini-btn {
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    font-size:12px;
    padding:6px 10px;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
    color:var(--text-heading);
    font-weight:500;
}
.mini-btn.success {
    background:#10b981;
    border-color:#10b981;
    color:#fff;
}
.mini-btn.warn {
    background:#facc15;
    border-color:#facc15;
    color:#1f2937;
}
.mini-btn.danger {
    background:#dc2626;
    border-color:#dc2626;
    color:#fff;
}
</style>

<script>
function openDrawer(taskId){
    window.open("#".replace('TASK_ID', taskId),
        '_blank');
}
</script>
