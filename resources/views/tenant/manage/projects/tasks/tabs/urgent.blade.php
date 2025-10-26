{{-- resources/views/tenant/manage/projects/tasks/tabs/urgent.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

<div class="task-section-header danger">
    <div class="task-section-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>
    <div class="task-section-content">
        <h3 class="task-section-title">Urgent Priority</h3>
        <p class="task-section-description">{{ $tasks->count() }} tasks require immediate attention</p>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="tasks-list-view">
        @foreach($tasks as $task)
            @include('tenant.manage.projects.tasks.components.task-list-item', [
                'task' => $task, 
                'highlight' => 'urgent'
            ])
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon" style="background: rgba(0, 135, 90, 0.1); color: #00875A;">
            <i class="fas fa-thumbs-up"></i>
        </div>
        <h3 class="project-empty-state-title">No Urgent Tasks</h3>
        <p class="project-empty-state-desc">Excellent! You have no urgent priority tasks.</p>
    </div>
@endif