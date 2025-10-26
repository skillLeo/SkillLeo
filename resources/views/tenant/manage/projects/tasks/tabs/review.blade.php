    {{-- resources/views/tenant/manage/projects/tasks/tabs/review.blade.php --}}
    @once
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
        @endpush
    @endonce

    <div class="task-section-header success">
        <div class="task-section-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                <path d="M22 4L12 14.01l-3-3"/>
            </svg>
        </div>
        <div class="task-section-content">
            <h3 class="task-section-title">Pending Review</h3>
            <p class="task-section-description">{{ $tasks->count() }} tasks waiting for your approval</p>
        </div>
    </div>

    @if($tasks->count() > 0)
        <div class="tasks-list-view">
            @foreach($tasks as $task)
                @include('tenant.manage.projects.tasks.components.task-list-item', [
                    'task' => $task, 
                    'highlight' => 'review'
                ])
            @endforeach
        </div>
    @else
        <div class="project-empty-state">
            <div class="project-empty-state-icon" style="background: rgba(0, 135, 90, 0.1); color: #00875A;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="project-empty-state-title">No Tasks in Review</h3>
            <p class="project-empty-state-desc">Completed tasks waiting for approval will appear here.</p>
        </div>
    @endif