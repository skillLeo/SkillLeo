{{-- resources/views/tenant/manage/projects/tasks/tabs/overdue.blade.php --}}

@php
use Illuminate\Support\Str;

    $colorPalette = ['#667eea','#f093fb','#4facfe','#43e97b','#fa709a','#feca57','#48dbfb','#ff9ff3'];
@endphp

<!-- Overdue Alert Banner -->
@if($tasks->count() > 0)
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
                                • Oldest: {{ $daysOverdue }} {{ Str::plural('day', $daysOverdue) }} late
                            @endif
                        </span>
                    </div>
                    <a href="{{ route('tenant.manage.projects.show', [$username, $project->id]) }}" 
                       class="tasks-project-link">
                        View Project <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Task Cards -->
                <div class="tasks-card-grid">
                    @foreach($projectTasks->sortBy('due_date') as $task)
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
        <div class="project-empty-state-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="project-empty-state-title">No Overdue Tasks!</h3>
        <p class="project-empty-state-desc">Great job staying on top of your deadlines. Keep it up!</p>
    </div>
@endif

<style>
.overdue-alert-banner {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border: 2px solid #ef4444;
    border-radius: var(--radius);
    margin-bottom: 24px;
}

.overdue-alert-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    font-size: 20px;
    flex-shrink: 0;
}

.overdue-alert-content {
    flex: 1;
}

.overdue-alert-title {
    font-size: var(--fs-h3);
    font-weight: var(--fw-bold);
    color: #991b1b;
    margin: 0 0 4px 0;
}

.overdue-alert-desc {
    font-size: var(--fs-body);
    color: #7f1d1d;
    margin: 0;
}

.overdue-alert-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 20px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: var(--task-transition);
}

.overdue-alert-btn:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.tasks-grid-wrapper {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.tasks-project-group.overdue-group {
    background: var(--card);
    border: 2px solid #fca5a5;
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
    display: flex;
    align-items: center;
    gap: 6px;
}

.overdue-meta {
    color: #ef4444;
    font-weight: var(--fw-semibold);
}

.overdue-meta i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
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
    border-radius: 50%;
    font-size: 32px;
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
    .overdue-alert-banner {
flex-direction: column;
text-align: center;
}.overdue-alert-btn {
    width: 100%;
}

.tasks-card-grid {
    grid-template-columns: 1fr;
}}
</style>
<script>
    function sendBulkReminders() {
        if (!confirm('Send reminders for all overdue tasks?')) return;
        
        showNotification('Sending reminders...', 'info');
        
        // In real implementation, make API call
        setTimeout(() => {
            showNotification('Reminders sent successfully!', 'success');
        }, 1500);
    }
    
    function showNotification(message, type = 'info') {
        const colors = {
            success: '#00875A',
            error: '#DE350B',
            warning: '#FF991F',
            info: '#0052CC'
        };
    
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10000;
            padding: 12px 20px;
            background: ${colors[type]};
            color: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 14px;
            font-weight: 500;
            animation: slideInRight 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
    
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    </script>
    