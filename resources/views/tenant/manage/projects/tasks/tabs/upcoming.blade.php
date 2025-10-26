{{-- resources/views/tenant/manage/projects/tasks/tabs/upcoming.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

<div class="task-section-header primary">
    <div class="task-section-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
    </div>
    <div class="task-section-content">
        <h3 class="task-section-title">Upcoming Tasks</h3>
        <p class="task-section-description">{{ $tasks->count() }} tasks scheduled for future dates</p>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="tasks-list-view">
        @foreach($tasks->groupBy(fn($t) => $t->due_date?->format('Y-m-d')) as $date => $dateTasks)
            <div class="date-group">
                <div class="date-group-header">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="12" height="11" rx="2"/>
                        <path d="M2 6h12M5 2v2M11 2v2"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</span>
                    <span class="date-count">{{ $dateTasks->count() }}</span>
                </div>
                @foreach($dateTasks as $task)
                    @include('tenant.manage.projects.tasks.components.task-list-item', ['task' => $task])
                @endforeach
            </div>
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon" style="background: rgba(0, 82, 204, 0.1); color: #0052CC;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <h3 class="project-empty-state-title">No Upcoming Tasks</h3>
        <p class="project-empty-state-desc">Future tasks will be displayed here when scheduled.</p>
    </div>
@endif