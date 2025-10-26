<div class="task-drawer">
    <div class="task-drawer-header">
        <div class="task-drawer-title">
            <div class="task-drawer-project">
                {{ $task->project?->name ?? 'Project' }}
            </div>
            <h2 class="task-drawer-name">{{ $task->title }}</h2>
        </div>

        <div class="task-drawer-close" onclick="window.close()">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="task-drawer-body">
        <section class="drawer-section">
            <div class="drawer-field-row">
                <div class="drawer-field-col">
                    <label class="drawer-label">Assignee</label>
                    <div class="drawer-value">
                        {{ $task->assignee?->name ?? 'Unassigned' }}
                    </div>
                </div>

                <div class="drawer-field-col">
                    <label class="drawer-label">Reporter</label>
                    <div class="drawer-value">
                        {{ $task->reporter?->name ?? '—' }}
                    </div>
                </div>

                <div class="drawer-field-col">
                    <label class="drawer-label">Status</label>
                    <div class="drawer-value">
                        <form method="POST"
                              action="{{ route('tenant.manage.projects.tasks.quick-status', [$workspaceOwner->username, $task->id]) }}">
                            @csrf
                            <select name="status" class="drawer-inline-select" onchange="this.form.submit()">
                                @foreach(\App\Models\Task::statusOptions() as $statusOption)
                                    <option value="{{ $statusOption }}" @selected($task->status === $statusOption)>
                                        {{ ucfirst(str_replace('_',' ', $statusOption)) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <div class="drawer-field-col">
                    <label class="drawer-label">Due date</label>
                    <div class="drawer-value">
                        @if($task->due_date)
                            {{ $task->due_date->format('M d, Y') }}
                            @if($task->due_date->isPast() && $task->status !== \App\Models\Task::STATUS_DONE)
                                <span class="chip chip-overdue">Overdue</span>
                            @endif
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="drawer-section">
            <label class="drawer-label">Description</label>
            <div class="drawer-desc">
                {!! nl2br(e($task->description ?? 'No description')) !!}
            </div>
        </section>

        <section class="drawer-section">
            <label class="drawer-label">Subtasks</label>
            <ul class="drawer-subtasks">
                @foreach($task->subtasks->sortBy('order') as $sub)
                    <li class="drawer-subtask">
                        <form class="drawer-subtask-check"
                              method="POST"
                              action="{{ route('tenant.manage.projects.tasks.subtasks.toggle-complete', [$workspaceOwner->username, $task->id, $sub->id]) }}">
                            @csrf
                            <input type="hidden" name="completed" value="{{ $sub->completed ? 0 : 1 }}">
                            <button type="submit" class="check-btn">
                                <i class="far {{ $sub->completed ? 'fa-check-square' : 'fa-square' }}"></i>
                            </button>
                        </form>
                        <div class="drawer-subtask-text {{ $sub->completed ? 'done' : '' }}">
                            {{ $sub->title }}
                        </div>
                        @if($sub->completed && $sub->completed_at)
                            <div class="drawer-subtask-meta">
                                done {{ $sub->completed_at->diffForHumans() }}
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="drawer-section">
            <label class="drawer-label">Attachments</label>
            @if($task->attachments->count() === 0)
                <div class="drawer-muted">No files uploaded</div>
            @else
                <div class="drawer-files">
                    @foreach($task->attachments as $file)
                        <div class="drawer-file">
                            <i class="fas {{ $file->type === 'image' ? 'fa-image' : 'fa-paperclip' }}"></i>
                            <span class="drawer-file-name">{{ $file->label }}</span>
                            <span class="drawer-file-meta">by {{ $file->uploader?->name ?? '—' }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="drawer-section">
            <label class="drawer-label">Activity</label>
            <ul class="drawer-activity">
                @foreach($task->activity->sortByDesc('created_at') as $entry)
                    <li class="drawer-activity-item">
                        <div class="drawer-activity-head">
                            <span class="drawer-activity-actor">{{ $entry->actor?->name ?? 'System' }}</span>
                            <span class="drawer-activity-time">{{ $entry->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="drawer-activity-body">{{ $entry->body }}</div>
                    </li>
                @endforeach
            </ul>
        </section>

    </div>

    <div class="task-drawer-footer">
        {{-- Assignee actions --}}
        @php $isAssignee = $task->assigned_to === $viewer->id; @endphp
        @php $isReporter = $task->reporter_id === $viewer->id; @endphp
        @php $isOwner = $workspaceOwner->id === $viewer->id; @endphp
        @php $canDelete = $isReporter || $isOwner; @endphp

        <div class="drawer-actions-left">
            @if($isAssignee)
                <form method="POST"
                      action="{{ route('tenant.manage.projects.tasks.complete', [$workspaceOwner->username, $task->id]) }}"
                      style="display:inline-block;">
                    @csrf
                    <button class="drawer-btn success">
                        <i class="fas fa-check-circle"></i>
                        Submit For Review
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('tenant.manage.projects.tasks.postpone', [$workspaceOwner->username, $task->id]) }}"
                      style="display:inline-block;">
                    @csrf
                    <input type="hidden" name="postponed_until" value="{{ now()->addDay()->toDateString() }}">
                    <input type="hidden" name="reason" value="Need more time">
                    <button class="drawer-btn warn">
                        <i class="fas fa-clock"></i>
                        Postpone
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('tenant.manage.projects.tasks.block', [$workspaceOwner->username, $task->id]) }}"
                      style="display:inline-block;">
                    @csrf
                    <input type="hidden" name="reason" value="Blocked by dependency">
                    <button class="drawer-btn danger">
                        <i class="fas fa-ban"></i>
                        Blocked
                    </button>
                </form>
            @endif
        </div>

        <div class="drawer-actions-right">
            @if($canDelete)
                <form method="POST"
                      action="{{ route('tenant.manage.projects.tasks.index', $workspaceOwner->username) . '/' . $task->id }}"
                      onsubmit="return confirm('Delete this task?')"
                      style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="drawer-btn danger-outline">
                        <i class="fas fa-trash"></i>
                        Delete Task
                    </button>
                </form>
            @endif
            <button class="drawer-btn" onclick="window.close()">
                Close
            </button>
        </div>
    </div>
</div>

<style>
.task-drawer {
    max-width:480px;
    min-height:100vh;
    background:#fff;
    display:flex;
    flex-direction:column;
    border-left:1px solid var(--border);
    font-size:14px;
    color:var(--text-body);
}
.task-drawer-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    padding:20px;
    border-bottom:1px solid var(--border);
}
.task-drawer-project {
    font-size:12px;
    color:var(--text-muted);
    font-weight:500;
}
.task-drawer-name {
    margin:2px 0 0 0;
    font-size:16px;
    font-weight:600;
    color:var(--text-heading);
    line-height:1.3;
}
.task-drawer-close {
    cursor:pointer;
    color:var(--text-muted);
}
.task-drawer-body {
    flex:1;
    overflow-y:auto;
    padding:20px;
    display:flex;
    flex-direction:column;
    gap:24px;
}
.drawer-section { display:block; }
.drawer-label {
    font-size:12px;
    font-weight:600;
    color:var(--text-muted);
    margin-bottom:6px;
    display:block;
}
.drawer-value {
    font-size:14px;
    color:var(--text-body);
    font-weight:500;
}
.drawer-inline-select {
    font-size:13px;
    padding:4px 8px;
    border:1px solid var(--border);
    border-radius:4px;
    background:white;
}
.drawer-inline-select:focus {
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 3px rgba(19,81,216,0.1);
}
.drawer-desc {
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    padding:12px;
    font-size:13px;
    color:var(--text-body);
    white-space:pre-line;
}
.drawer-field-row {
    display:flex;
    flex-wrap:wrap;
    gap:16px;
}
.drawer-field-col {
    flex:1;
    min-width:140px;
}
.chip-overdue {
    background:#fee2e2;
    color:#dc2626;
    border:1px solid #dc2626;
    font-size:11px;
    font-weight:600;
    border-radius:999px;
    padding:2px 8px;
    margin-left:6px;
}
.drawer-subtasks {
    list-style:none;
    margin:0;
    padding:0;
    border:1px solid var(--border);
    border-radius:6px;
}
.drawer-subtask {
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:10px 12px;
    border-bottom:1px solid var(--border);
    font-size:13px;
}
.drawer-subtask:last-child {
    border-bottom:none;
}
.drawer-subtask-text.done {
    text-decoration:line-through;
    color:#9ca3af;
}
.drawer-subtask-meta {
    font-size:12px;
    color:#9ca3af;
}
.check-btn {
    background:none;
    border:none;
    font-size:16px;
    color:var(--accent);
    cursor:pointer;
    padding:0;
    line-height:1;
}
.drawer-files {
    display:flex;
    flex-direction:column;
    gap:8px;
    font-size:13px;
}
.drawer-file {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    padding:8px 10px;
}
.drawer-file-name {
    font-weight:500;
    color:var(--text-heading);
}
.drawer-file-meta {
    color:var(--text-muted);
    font-size:12px;
}
.drawer-activity {
    list-style:none;
    margin:0;
    padding:0;
    font-size:12px;
    color:var(--text-muted);
    border:1px solid var(--border);
    border-radius:6px;
}
.drawer-activity-item {
    border-bottom:1px solid var(--border);
    padding:10px 12px;
}
.drawer-activity-item:last-child {
    border-bottom:none;
}
.drawer-activity-head {
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:8px;
    font-size:12px;
    color:var(--text-muted);
    margin-bottom:4px;
}
.drawer-activity-actor {
    color:var(--text-heading);
    font-weight:600;
    font-size:12px;
}
.drawer-activity-time {
    font-size:12px;
    color:#9ca3af;
}
.drawer-activity-body {
    color:var(--text-body);
    font-size:13px;
}
.task-drawer-footer {
    border-top:1px solid var(--border);
    padding:16px 20px;
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    justify-content:space-between;
}
.drawer-actions-left,
.drawer-actions-right {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}
.drawer-btn {
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    font-size:13px;
    padding:8px 12px;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
    color:var(--text-heading);
    font-weight:500;
}
.drawer-btn.success {
    background:#10b981;
    border-color:#10b981;
    color:#fff;
}
.drawer-btn.warn {
    background:#facc15;
    border-color:#facc15;
    color:#1f2937;
}
.drawer-btn.danger {
    background:#dc2626;
    border-color:#dc2626;
    color:#fff;
}
.drawer-btn.danger-outline {
    background:#fff;
    border-color:#dc2626;
    color:#dc2626;
}
</style>
