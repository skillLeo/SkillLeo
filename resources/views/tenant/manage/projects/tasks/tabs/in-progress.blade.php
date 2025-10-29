{{-- resources/views/tenant/manage/projects/tasks/tabs/in-progress.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

<div class="task-section-header primary">
    <div class="task-section-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
        </svg>
    </div>
    <div class="task-section-content">
        <h3 class="task-section-title">In Progress</h3>
        <p class="task-section-description">{{ $tasks->count() }} tasks currently being worked on</p>
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
        <div class="project-empty-state-icon" style="background: rgba(0, 82, 204, 0.1); color: #0052CC;">
            <i class="fas fa-spinner"></i>
        </div>
        <h3 class="project-empty-state-title">No Tasks in Progress</h3>
        <p class="project-empty-state-desc">Tasks you're actively working on will appear here.</p>
    </div>
@endif