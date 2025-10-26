{{-- resources/views/tenant/manage/projects/tasks/tabs/today.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

<div class="task-section-header warning">
    <div class="task-section-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
    </div>
    <div class="task-section-content">
        <h3 class="task-section-title">Due Today</h3>
        <p class="task-section-description">{{ $tasks->count() }} tasks need your attention today</p>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="tasks-list-view">
        @foreach($tasks as $task)
            @include('tenant.manage.projects.tasks.components.task-list-item', [
                'task' => $task, 
                'highlight' => 'today'
            ])
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon" style="background: rgba(255, 139, 0, 0.1); color: #FF8B00;">
            <i class="fas fa-calendar-day"></i>
        </div>
        <h3 class="project-empty-state-title">Nothing Due Today</h3>
        <p class="project-empty-state-desc">You're all caught up! No tasks are due today.</p>
    </div>
@endif