@extends('tenant.manage.app')

@section('main')
<div class="page-head">
    <div>
        <h1 class="page-title">
            <i class="fas fa-columns"></i>
            {{ $project->name }} &mdash; Board
        </h1>
        <p class="page-sub">Live board for this project. Drag cards to update status.</p>
    </div>
    <div class="page-actions">
        <a class="project-btn project-btn-secondary"
           href="{{ route('tenant.manage.projects.backlog', [$username, $project->id]) }}">
            <i class="fas fa-list-ul"></i>
            Backlog
        </a>
        <a class="project-btn project-btn-secondary"
           href="{{ route('tenant.manage.projects.task-list', [$username, $project->id]) }}">
            <i class="fas fa-table"></i>
            List
        </a>
    </div>
</div>

<div class="kanban-board">
    @foreach($columns as $statusKey => $label)
        @php
            $cards = $tasksByStatus->get($statusKey, collect());
        @endphp

        <div class="kanban-column" data-status="{{ $statusKey }}">
            <div class="kanban-column-head">
                <span class="kanban-column-title">{{ $label }}</span>
                <span class="kanban-column-count">{{ $cards->count() }}</span>
            </div>

            <div class="kanban-column-body droppable">
                @foreach($cards as $task)
                    <div class="kanban-card draggable"
                         draggable="true"
                         data-task-id="{{ $task->id }}">
                        @include('tenant.manage.projects.tasks.components.task-card-compact', [
                            'task' => $task,
                            'canEdit' => ($task->reporter_id === $viewer->id),
                            'canDelete' => ($task->reporter_id === $viewer->id),
                            'canComplete' => ($task->assigned_to === $viewer->id),
                            'canPostpone' => ($task->assigned_to === $viewer->id),
                            'compactMode' => true, // hide footer buttons in board
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<style>
.page-head {
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    margin-bottom:24px;
}
.page-title {
    font-size:20px;
    font-weight:600;
    color:var(--text-heading);
    display:flex;
    align-items:center;
    gap:8px;
    margin:0 0 4px 0;
}
.page-sub {
    font-size:14px;
    color:var(--text-muted);
    margin:0;
}
.page-actions a {
    margin-left:8px;
}
.kanban-board {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:16px;
}
.kanban-column {
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    display:flex;
    flex-direction:column;
    max-height:80vh;
}
.kanban-column-head {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 16px;
    background:var(--bg);
    border-bottom:1px solid var(--border);
}
.kanban-column-title {
    font-size:14px;
    font-weight:600;
    color:var(--text-heading);
}
.kanban-column-count {
    background:var(--accent-light);
    color:var(--accent);
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    padding:2px 8px;
}
.kanban-column-body {
    flex:1;
    overflow-y:auto;
    padding:12px;
    display:flex;
    flex-direction:column;
    gap:12px;
}
.kanban-card {
    cursor:grab;
}
.kanban-card.dragging {
    opacity:0.5;
}
.droppable.drag-hover {
    background:var(--accent-light);
    border-radius:var(--radius);
    transition:0.15s;
}
</style>

<script>
// basic vanilla drag & drop
let dragged = null;

document.querySelectorAll('.draggable').forEach(card => {
    card.addEventListener('dragstart', e => {
        dragged = card;
        card.classList.add('dragging');
    });
    card.addEventListener('dragend', e => {
        card.classList.remove('dragging');
        dragged = null;
    });
});

document.querySelectorAll('.droppable').forEach(column => {
    column.addEventListener('dragover', e => {
        e.preventDefault();
        column.classList.add('drag-hover');
    });
    column.addEventListener('dragleave', () => {
        column.classList.remove('drag-hover');
    });
    column.addEventListener('drop', () => {
        if (!dragged) return;
        column.classList.remove('drag-hover');
        column.appendChild(dragged);

        const taskId = dragged.getAttribute('data-task-id');
        const newStatus = column.closest('.kanban-column').getAttribute('data-status');
        updateTaskStatus(taskId, newStatus);
    });
});

function updateTaskStatus(taskId, newStatus) {
    fetch(`{{ route('tenant.manage.projects.tasks.quick-status', [$username, 'TASK_ID']) }}`.replace('TASK_ID', taskId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            status: newStatus,
            cascade_subtasks: false
        })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            alert('Failed to update task status');
        }
    })
    .catch(() => alert('Failed to update task status'));
}
</script>
@endsection
