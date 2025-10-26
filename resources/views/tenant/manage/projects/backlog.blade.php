@extends('tenant.manage.app')

@section('main')
<div class="page-head">
    <div>
        <h1 class="page-title">
            <i class="fas fa-list-ul"></i>
            {{ $project->name }} &mdash; Backlog
        </h1>
        <p class="page-sub">Upcoming work. Reorder by priority.</p>
    </div>
    <div class="page-actions">
        <a class="project-btn project-btn-secondary"
           href="{{ route('tenant.manage.projects.board', [$username, $project->id]) }}">
            <i class="fas fa-columns"></i>
            Board
        </a>
        <a class="project-btn project-btn-secondary"
           href="{{ route('tenant.manage.projects.task-list', [$username, $project->id]) }}">
            <i class="fas fa-table"></i>
            List
        </a>
    </div>
</div>

<div class="backlog-wrapper">
    <ul class="backlog-list" id="backlogList">
        @foreach($backlogTasks as $task)
            <li class="backlog-item"
                data-task-id="{{ $task->id }}"
                draggable="{{ $canPrioritize ? 'true' : 'false' }}">
                <div class="backlog-left">
                    <div class="backlog-drag-handle">
                        <i class="fas fa-grip-lines"></i>
                    </div>
                    <div>
                        <div class="backlog-title">{{ $task->title }}</div>
                        <div class="backlog-meta">
                            <span>Assigned to: {{ $task->assignee?->name ?? 'Unassigned' }}</span>
                            @if($task->due_date)
                                <span>• Due {{ $task->due_date->format('M d, Y') }}</span>
                            @endif
                            <span>• Created by {{ $task->reporter?->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="backlog-actions">
                    <button class="mini-btn" onclick="openDrawer({{ $task->id }})">
                        <i class="fas fa-external-link-alt"></i>
                        View
                    </button>
                    @if($canPrioritize)
                        <button class="mini-btn danger" onclick="deleteTask({{ $task->id }})">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</div>

<style>
.backlog-wrapper {
    background: var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px;
}
.backlog-list {
    list-style:none;
    margin:0;
    padding:0;
    display:flex;
    flex-direction:column;
    gap:12px;
}
.backlog-item {
    background:#fff;
    border:1px solid var(--border);
    border-radius:8px;
    padding:12px 16px;
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    cursor:grab;
}
.backlog-left {
    display:flex;
    gap:12px;
    align-items:flex-start;
}
.backlog-drag-handle {
    width:24px;
    color:#9ca3af;
    cursor:grab;
}
.backlog-title {
    font-size:15px;
    font-weight:600;
    color:var(--text-heading);
    margin-bottom:4px;
}
.backlog-meta {
    font-size:13px;
    color:var(--text-muted);
}
.backlog-actions .mini-btn {
    background:#f9fafb;
    border:1px solid var(--border);
    border-radius:6px;
    font-size:12px;
    padding:6px 10px;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
}
.backlog-actions .mini-btn.danger {
    color:#dc2626;
    border-color:#dc2626;
    background:#fff5f5;
}
.page-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;}
.page-title{font-size:20px;font-weight:600;display:flex;align-items:center;gap:8px;color:var(--text-heading);margin:0 0 4px 0;}
.page-sub{font-size:14px;color:var(--text-muted);margin:0;}
</style>

<script>
@if($canPrioritize)
// lightweight drag+drop reorder → POST /tasks/reorder-mylist
let dragEl = null;
document.querySelectorAll('.backlog-item').forEach(item => {
    item.addEventListener('dragstart', () => {
        dragEl = item;
        item.style.opacity = '0.5';
    });
    item.addEventListener('dragend', () => {
        item.style.opacity = '1';
        dragEl = null;
        saveNewOrder();
    });
});
document.querySelectorAll('.backlog-item').forEach(item => {
    item.addEventListener('dragover', e => {
        e.preventDefault();
        const list = document.getElementById('backlogList');
        const bounding = item.getBoundingClientRect();
        const offset = bounding.y + bounding.height / 2;
        if (e.clientY - offset > 0) {
            list.insertBefore(dragEl, item.nextSibling);
        } else {
            list.insertBefore(dragEl, item);
        }
    });
});

function saveNewOrder() {
    const ids = Array.from(document.querySelectorAll('.backlog-item'))
        .map(li => li.getAttribute('data-task-id'));

    fetch("{{ route('tenant.manage.projects.tasks.reorder', [$username]) }}", {
        method:'POST',
        headers:{
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept':'application/json',
            'Content-Type':'application/json'
        },
        body: JSON.stringify({
            project_id: {{ $project->id }},
            tasks: ids
        })
    }).then(r=>r.json()).then(data=>{
        if(!data.success){ alert('Failed to save new priority order'); }
    }).catch(()=>alert('Failed to save new priority order'));
}
@endif

function openDrawer(taskId) {
    window.open("{{ route('tenant.manage.projects.tasks.drawer', [$username, 'TASK_ID']) }}".replace('TASK_ID', taskId),
        '_blank'); // you can also show side drawer modal via AJAX
}

function deleteTask(taskId){
    if(!confirm('Delete this task?')) return;
    fetch("{{ route('tenant.manage.projects.tasks.index', [$username]) }}/"+taskId, {
        method:'DELETE',
        headers:{
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept':'application/json'
        }
    }).then(r=>r.json()).then(data=>{
        if(data.success){ location.reload(); }
        else{ alert(data.message || 'Delete failed'); }
    }).catch(()=>alert('Delete failed'));
}
</script>
@endsection
