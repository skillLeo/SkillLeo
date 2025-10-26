{{-- resources/views/tenant/manage/projects/tasks/tabs/created.blade.php --}}

@php
    $colorPalette = ['#667eea','#f093fb','#4facfe','#43e97b','#fa709a','#feca57','#48dbfb','#ff9ff3'];
@endphp

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
                        <span class="tasks-project-meta">{{ $projectTasks->count() }} tasks created</span>
                    </div>
                    <a href="{{ route('tenant.manage.projects.show', [$username, $project->id]) }}" 
                       class="tasks-project-link">
                        View Project <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Task Cards with Full CRUD -->
                <div class="tasks-card-grid">
                    @foreach($projectTasks as $task)
                        @include('tenant.manage.projects.tasks.components.task-card-compact', [
                            'task' => $task,
                            'canEdit' => true, // Creator can edit
                            'canDelete' => true, // Creator can delete
                            'canComplete' => true,
                            'canPostpone' => true,
                        ])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        <h3 class="project-empty-state-title">No Tasks Created Yet</h3>
        <p class="project-empty-state-desc">Tasks you create will appear here.</p>
        <button type="button" class="project-btn project-btn-primary" onclick="openCreateTaskModal()">
            <i class="fas fa-plus"></i>
            <span>Create Task</span>
        </button>
    </div>
@endif