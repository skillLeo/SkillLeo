{{-- resources/views/tenant/manage/projects/tasks/tabs/mine.blade.php --}}

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
                        <span class="tasks-project-meta">
                            {{ $projectTasks->count() }} assigned • 
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
                        @include('tenant.manage.projects.tasks.components.task-card-compact', [
                            'task' => $task,
                            'canEdit' => false, // Can't edit tasks just assigned to me
                            'canDelete' => false,
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
            <i class="fas fa-user-check"></i>
        </div>
        <h3 class="project-empty-state-title">No Assigned Tasks</h3>
        <p class="project-empty-state-desc">You don't have any tasks assigned to you yet.</p>
    </div>
@endif

<style>
.tasks-grid-wrapper {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.tasks-project-group {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px;
}

.tasks-project-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}

.tasks-project-avatar {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: var(--fw-bold);
    color: white;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.tasks-project-info {
    flex: 1;
    min-width: 0;
}

.tasks-project-name {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.tasks-project-meta {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.tasks-project-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    color: var(--accent);
    text-decoration: none;
    transition: var(--task-transition);
}

.tasks-project-link:hover {
    color: var(--accent-dark);
}

.tasks-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
}

.project-empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.project-empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-light);
    border-radius: 50%;
    font-size: 32px;
    color: var(--accent);
}

.project-empty-state-title {
    font-size: var(--fs-h2);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    margin: 0 0 8px 0;
}

.project-empty-state-desc {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0;
}

@media (max-width: 768px) {
    .tasks-card-grid {
        grid-template-columns: 1fr;
    }
}
</style>