@extends('tenant.manage.app')

@section('main')
<div class="page-head">
    <div>
        <h1 class="page-title">
            <i class="fas fa-clipboard-check"></i>
            Approval Queue
        </h1>
        <p class="page-sub">These tasks were submitted for review and are waiting for approval.</p>
    </div>
</div>

@if($tasks->count() === 0)
    <div class="approval-empty">
        <div class="approval-empty-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="approval-empty-title">Nothing to review 🎉</div>
        <div class="approval-empty-desc">No tasks are currently waiting for approval.</div>
    </div>
@else
    <div class="approval-list">
        @foreach($tasks as $task)
            <div class="approval-card">
                <div class="approval-main">
                    <div class="approval-header">
                        <div class="approval-title">{{ $task->title }}</div>
                        <div class="approval-project">{{ $task->project?->name ?? 'Project' }}</div>
                    </div>

                    <div class="approval-meta">
                        <span>Owner: <strong>{{ $task->assignee?->name ?? 'Unassigned' }}</strong></span>
                        <span>• Submitted {{ $task->submitted_for_review_at?->diffForHumans() ?? '—' }}</span>
                        @if($task->due_date)
                            <span>• Due {{ $task->due_date->format('M d, Y') }}</span>
                        @endif
                        @if($task->requires_client_approval)
                            <span class="chip chip-client">Client Approval</span>
                        @endif
                    </div>

                    {{-- Attachments preview --}}
                    @if($task->attachments->count() > 0)
                        <div class="approval-files">
                            @foreach($task->attachments as $file)
                                <div class="approval-file">
                                    <i class="fas {{ $file->type === 'image' ? 'fa-image' : 'fa-paperclip' }}"></i>
                                    <span class="file-name">{{ $file->label }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Activity summary (last few log lines) --}}
                    <div class="approval-activity">
                        @foreach($task->activity->sortByDesc('created_at')->take(3) as $log)
                            <div class="approval-activity-line">
                                <span class="log-actor">{{ $log->actor?->name ?? 'System' }}</span>
                                <span class="log-body">{{ $log->body }}</span>
                                <span class="log-time">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>

                <div class="approval-actions">
                    <form method="POST" action="{{ route('tenant.manage.projects.tasks.approve', [$username, $task->id]) }}">
                        @csrf
                        <button class="approve-btn">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('tenant.manage.projects.tasks.request_changes', [$username, $task->id]) }}">
                        @csrf
                        <textarea name="feedback" class="feedback-box" placeholder="Request changes..."></textarea>
                        <button class="changes-btn">
                            <i class="fas fa-undo"></i>
                            Request Changes
                        </button>
                    </form>

                    <button class="mini-btn" onclick="openDrawer({{ $task->id }})">
                        <i class="fas fa-external-link-alt"></i> View details
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
.page-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;}
.page-title{font-size:20px;font-weight:600;display:flex;align-items:center;gap:8px;color:var(--text-heading);margin:0 0 4px 0;}
.page-sub{font-size:14px;color:var(--text-muted);margin:0;}

.approval-empty {
    text-align:center;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:60px 20px;
}
.approval-empty-icon {
    font-size:32px;
    color:#10b981;
    margin-bottom:16px;
}
.approval-empty-title {
    font-size:18px;
    font-weight:600;
    color:var(--text-heading);
    margin-bottom:4px;
}
.approval-empty-desc {
    font-size:14px;
    color:var(--text-muted);
}

.approval-list {
    display:flex;
    flex-direction:column;
    gap:24px;
}
.approval-card {
    background:#fff;
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:20px;
    display:flex;
    flex-wrap:wrap;
    gap:20px;
}
.approval-main {
    flex:1;
    min-width:250px;
}
.approval-header {
    display:flex;
    flex-direction:column;
    margin-bottom:8px;
}
.approval-title {
    font-size:16px;
    font-weight:600;
    color:var(--text-heading);
}
.approval-project {
    font-size:13px;
    color:var(--text-muted);
}
.approval-meta {
    font-size:13px;
    color:var(--text-muted);
    display:flex;
    flex-wrap:wrap;
    gap:6px 12px;
    margin-bottom:12px;
}
.chip-client {
    background:#fff7ed;
    color:#c2410c;
    border:1px solid #fdba74;
    border-radius:999px;
    font-size:11px;
    font-weight:600;
    padding:2px 8px;
}
.approval-files {
    display:flex;
    flex-wrap:wrap;
    gap:8px 12px;
    margin-bottom:12px;
    font-size:13px;
}
.approval-file {
    display:flex;
    align-items:center;
    gap:6px;
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    padding:6px 10px;
}
.approval-activity {
    border-top:1px solid var(--border);
    padding-top:12px;
    font-size:12px;
    color:var(--text-muted);
}
.approval-activity-line {
    display:flex;
    flex-wrap:wrap;
    gap:6px 8px;
    margin-bottom:4px;
}
.log-actor { font-weight:600; color:var(--text-heading); }
.log-time  { color:#9ca3af; }

.approval-actions {
    width:220px;
    min-width:220px;
    display:flex;
    flex-direction:column;
    gap:12px;
}
.approve-btn {
    width:100%;
    height:40px;
    background:#10b981;
    border:none;
    color:#fff;
    font-size:14px;
    font-weight:600;
    border-radius:6px;
    cursor:pointer;
}
.changes-btn {
    width:100%;
    height:40px;
    background:#f97316;
    border:none;
    color:#fff;
    font-size:14px;
    font-weight:600;
    border-radius:6px;
    cursor:pointer;
}
.feedback-box {
    width:100%;
    min-height:60px;
    font-size:13px;
    padding:8px 10px;
    border:1px solid var(--border);
    border-radius:6px;
}
.mini-btn {
    width:100%;
    height:36px;
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    font-size:13px;
    font-weight:500;
    color:var(--text-heading);
    cursor:pointer;
}
</style>

<script>
function openDrawer(taskId){
    window.open("{{ route('tenant.manage.projects.tasks.drawer', [$username, 'TASK_ID']) }}".replace('TASK_ID', taskId),
        '_blank');
}
</script>
@endsection
