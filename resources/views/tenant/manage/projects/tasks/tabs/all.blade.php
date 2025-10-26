{{-- resources/views/tenant/manage/projects/tasks/tabs/all.blade.php --}}

@php
    $colorPalette = ['#667eea','#f093fb','#4facfe','#43e97b','#fa709a','#feca57','#48dbfb','#ff9ff3'];
@endphp

<!-- Filters for All Tasks -->
<div class="tasks-filters-bar">
    <div class="tasks-filter-group">
        <label class="tasks-filter-label">Project</label>
        <select class="tasks-filter-select" onchange="filterTasks()">
            <option value="">All Projects</option>
            @foreach($tasks->pluck('project')->unique('id')->filter() as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="tasks-filter-group">
        <label class="tasks-filter-label">Status</label>
        <select class="tasks-filter-select" onchange="filterTasks()">
            <option value="">All Status</option>
            <option value="todo">To Do</option>
            <option value="in-progress">In Progress</option>
            <option value="review">Review</option>
            <option value="done">Done</option>
            <option value="blocked">Blocked</option>
            <option value="postponed">Postponed</option>
        </select>
    </div>

    <div class="tasks-filter-group">
        <label class="tasks-filter-label">Priority</label>
        <select class="tasks-filter-select" onchange="filterTasks()">
            <option value="">All Priorities</option>
            <option value="urgent">Urgent</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="tasks-grid-wrapper">
        @foreach($tasks->groupBy('project_id') as $projectId => $projectTasks)
            @php
                $project = $projectTasks->first()->project;
                $colorIndex = $project ? $project->id % count($colorPalette) : 0;
                $projectColor = $colorPalette[$colorIndex];
            @endphp

            <div class="tasks-project-group">
                <!-- Project Header -->
                <div class="tasks-project-header">
                    <div class="tasks-project-avatar" style="background: {{ $projectColor }}">
                        {{ \Illuminate\Support\Str::of($project?->key)->substr(0,2)->upper() ?? 'PR' }}
                    </div>
                    <div class="tasks-project-info">
                        <h3 class="tasks-project-name">{{ $project?->name ?? 'Unknown Project' }}</h3>
                        <span class="tasks-project-meta">
                            {{ $projectTasks->count() }} tasks • 
                            {{ $projectTasks->where('status', 'done')->count() }} completed
                        </span>
                    </div>
                    <a href="{{ route('tenant.manage.projects.show', [$username, $project->id]) }}" 
                       class="tasks-project-link">
                        View Project <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Task Cards -->
                <div class="tasks-card-grid">
                    @foreach($projectTasks as $task)
                        @php
                            $isCreator = $task->reporter_id === $viewer->id;
                            $isAssignee = $task->assigned_to === $viewer->id;
                        @endphp

                        @include('tenant.manage.projects.tasks.components.task-card-compact', [
                            'task' => $task,
                            'canEdit' => $isCreator,
                            'canDelete' => $isCreator,
                            'canComplete' => $isAssignee || $isCreator,
                            'canPostpone' => $isAssignee || $isCreator,
                        ])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon">
            <i class="fas fa-tasks"></i>
        </div>
        <h3 class="project-empty-state-title">No Tasks Found</h3>
        <p class="project-empty-state-desc">No tasks match your current filters.</p>
    </div>
@endif

<style>
.tasks-filters-bar {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    margin-bottom: 24px;
    padding: 20px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    flex-wrap: wrap;
}

.tasks-filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    min-width: 200px;
}

.tasks-filter-label {
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    color: var(--text-muted);
}

.tasks-filter-select {
    height: 40px;
    padding: 0 32px 0 12px;
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235E6C84' d='M6 9L1 4h10z'/%3E%3C/svg%3E") no-repeat right 12px center;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: var(--fs-body);
    color: var(--text-body);
    cursor: pointer;
    appearance: none;
    transition: var(--task-transition);
}

.tasks-filter-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(19, 81, 216, 0.1);
}

@media (max-width: 768px) {
    .tasks-filter-group {
        min-width: 100%;
    }
}
</style>

<script>
function filterTasks() {
    // Implement filtering logic
    console.log('Filter tasks');
}
</script>