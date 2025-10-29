{{-- resources/views/tenant/manage/projects/tasks/tabs/overdue.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

@php
    $colorPalette = ['#0052CC','#00875A','#DE350B','#8777D9','#FF8B00','#00B8D9'];
@endphp

@if($tasks->count() > 0)
    <!-- Alert Banner -->
    <div class="overdue-alert-banner">
        <div class="overdue-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="overdue-alert-content">
            <h4 class="overdue-alert-title">{{ $tasks->count() }} Overdue Tasks</h4>
            <p class="overdue-alert-desc">
                These tasks have passed their due date and need immediate attention.
            </p>
        </div>
        <button class="overdue-alert-btn" onclick="sendBulkReminders()">
            <i class="fas fa-bell"></i>
            Send Reminders
        </button>
    </div>

    <!-- Grouped by Project -->
    <div class="tasks-grid-wrapper">
        @foreach($tasks->groupBy('project_id') as $projectId => $projectTasks)
            @php
                $project = $projectTasks->first()->project;
                $colorIndex = $project ? $project->id % count($colorPalette) : 0;
                $projectColor = $colorPalette[$colorIndex];
                $mostOverdue = $projectTasks->sortBy('due_date')->first();
                $daysOverdue = $mostOverdue->due_date ? $mostOverdue->due_date->diffInDays(now()) : 0;
            @endphp

            <div class="tasks-project-group overdue-group">
                <!-- Project Header -->
                <div class="tasks-project-header">
                    <div class="tasks-project-avatar" style="background: {{ $projectColor }}">
                        {{ \Illuminate\Support\Str::of($project?->key)->substr(0,2)->upper() ?? 'PR' }}
                    </div>
                    <div class="tasks-project-info">
                        <h3 class="tasks-project-name">{{ $project?->name ?? 'Unknown Project' }}</h3>
                        <span class="tasks-project-meta overdue-meta">
                            <i class="fas fa-clock"></i>
                            {{ $projectTasks->count() }} overdue tasks
                            @if($daysOverdue > 0)
                                • Oldest: {{ $daysOverdue }} {{ \Illuminate\Support\Str::plural('day', $daysOverdue) }} late
                            @endif
                        </span>
                    </div>
                    <a href="#" class="tasks-project-link">
                        View Project <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Task Cards -->
                <div class="tasks-card-grid">
                    @foreach($projectTasks->sortBy('due_date') as $task)
                        @include('tenant.manage.projects.tasks.components.advanced-task-card', ['task' => $task])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon" style="background: rgba(16, 185, 129, 0.1); color: #00875A;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="project-empty-state-title">No Overdue Tasks!</h3>
        <p class="project-empty-state-desc">Great job staying on top of your deadlines. Keep it up!</p>
    </div>
@endif

<script>
function sendBulkReminders() {
    if (!confirm('Send reminders for all overdue tasks?')) return;
    console.log('Sending reminders...');
    // Implementation: make API call here
}
</script>