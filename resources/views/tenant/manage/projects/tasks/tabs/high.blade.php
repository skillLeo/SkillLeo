{{-- resources/views/tenant/manage/projects/tasks/tabs/high.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

<div class="task-section-header warning">
    <div class="task-section-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
        </svg>
    </div>
    <div class="task-section-content">
        <h3 class="task-section-title">High Priority</h3>
        <p class="task-section-description">{{ $tasks->count() }} important tasks to focus on</p>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="tasks-grid">
        @foreach($tasks as $task)
            @include('tenant.manage.projects.tasks.components.advanced-task-card', ['task' => $task])
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon" style="background: rgba(0, 135, 90, 0.1); color: #00875A;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="project-empty-state-title">No High Priority Tasks</h3>
        <p class="project-empty-state-desc">All high priority items have been addressed.</p>
    </div>
@endif