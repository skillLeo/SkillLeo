{{-- resources/views/tenant/projects/tabs/timeline.blade.php --}}

<!-- Timeline Toolbar -->
<div class="timeline-toolbar">
    <div class="timeline-toolbar-left">
        <button class="project-btn project-btn-ghost">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
        </button>
        <select class="project-form-control project-select" style="width: auto;">
            <option value="month">Month View</option>
            <option value="week">Week View</option>
            <option value="quarter">Quarter View</option>
        </select>
    </div>
    <div class="timeline-toolbar-right">
        <button class="project-btn project-btn-ghost">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
        <div class="timeline-zoom-controls">
            <button class="timeline-zoom-btn" onclick="zoomOut()">
                <i class="fas fa-search-minus"></i>
            </button>
            <button class="timeline-zoom-btn" onclick="zoomIn()">
                <i class="fas fa-search-plus"></i>
            </button>
        </div>
    </div>
</div>

<!-- Timeline Container -->
<div class="timeline-container">
    <!-- Timeline Header (Dates) -->
    <div class="timeline-header">
        <div class="timeline-tasks-column">
            <span style="font-weight: var(--fw-semibold); color: var(--text-heading);">Tasks</span>
        </div>
        <div class="timeline-dates-grid">
            @php
                $startDate = $project->start_date;
                $endDate = $project->due_date;
                $totalDays = $startDate->diffInDays($endDate);
                $weeks = ceil($totalDays / 7);
            @endphp
            @for($i = 0; $i < min($weeks, 4); $i++)
                @php
                    $weekStart = $startDate->copy()->addWeeks($i);
                    $weekEnd = $weekStart->copy()->addDays(6);
                @endphp
                <div class="timeline-date-header">
                    <span class="timeline-week">Week {{ $i + 1 }}</span>
                    <span class="timeline-dates">{{ $weekStart->format('M d') }}-{{ $weekEnd->format('d') }}</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Timeline Body -->
    <div class="timeline-body">
        @if($project->tasks->count() > 0)
            @php
                $groupedTasks = $project->tasks->groupBy(function($task) {
                    return $task->assignedTo ? $task->assignedTo->id : 'unassigned';
                });
            @endphp

            @foreach($groupedTasks as $assigneeId => $tasks)
                <div class="timeline-group">
                    <div class="timeline-group-header" onclick="toggleTimelineGroup('group-{{ $assigneeId }}')">
                        <button class="timeline-group-toggle active">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        @if($assigneeId === 'unassigned')
                            <i class="fas fa-inbox" style="color: var(--text-muted);"></i>
                            <span class="timeline-group-title">Unassigned</span>
                        @else
                            <img src="{{ $tasks->first()->assignedTo->avatar_url }}" 
                                 alt="{{ $tasks->first()->assignedTo->name }}"
                                 style="width: 24px; height: 24px; border-radius: 50%;">
                            <span class="timeline-group-title">{{ $tasks->first()->assignedTo->name }}</span>
                        @endif
                        <span class="timeline-group-count">{{ $tasks->count() }} tasks</span>
                    </div>
                    
                    <div class="timeline-group-content active" id="group-{{ $assigneeId }}">
                        @foreach($tasks as $task)
                            @php
                                $taskStart = $task->created_at ?? $project->start_date;
                                $taskDue = $task->due_date ?? $project->due_date;
                                $startWeek = floor($project->start_date->diffInDays($taskStart) / 7);
                                $duration = max(1, ceil($taskStart->diffInDays($taskDue) / 7));
                                
                                $totalSubtasks = $task->subtasks->count();
                                $completedSubtasks = $task->subtasks->where('completed', true)->count();
                                $progress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                            @endphp
                            <div class="timeline-row">
                                <div class="timeline-task-info">
                                    <div class="timeline-task-details">
                                        <i class="fas fa-check-square" style="color: #3b82f6; font-size: var(--ic-sm);"></i>
                                        <span class="timeline-task-key">{{ $project->key }}-{{ $task->id }}</span>
                                        <span class="timeline-task-title">{{ $task->title }}</span>
                                    </div>
                                    <div class="timeline-task-meta">
                                        @if($task->assignedTo)
                                            <img src="{{ $task->assignedTo->avatar_url }}" 
                                                 alt="{{ $task->assignedTo->name }}"
                                                 class="timeline-task-avatar">
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="timeline-bars-container">
                                    <div class="timeline-bar" 
                                         style="grid-column: {{ $startWeek + 1 }} / span {{ min($duration, 4 - $startWeek) }};">
                                        <div class="timeline-bar-inner {{ $progress == 0 ? 'timeline-bar-unstarted' : '' }}" 
                                             style="width: {{ $progress > 0 ? $progress : 100 }}%;">
                                            <span class="timeline-bar-label">
                                                {{ $progress > 0 ? $progress . '%' : 'Not Started' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="project-empty-state">
                <div class="project-empty-state-icon">
                    <i class="fas fa-chart-gantt"></i>
                </div>
                <h3 class="project-empty-state-title">No tasks to display</h3>
                <p class="project-empty-state-desc">Add tasks to see them on the timeline</p>
            </div>
        @endif
    </div>
</div>

<!-- Timeline Legend -->
<div class="timeline-legend">
    <div class="timeline-legend-item">
        <div class="timeline-legend-color" style="background: linear-gradient(90deg, var(--accent) 0%, var(--accent-dark) 100%);"></div>
        <span>In Progress</span>
    </div>
    <div class="timeline-legend-item">
        <div class="timeline-legend-color" style="background: #10b981;"></div>
        <span>Completed</span>
    </div>
    <div class="timeline-legend-item">
        <div class="timeline-legend-color" style="background: var(--border);"></div>
        <span>Not Started</span>
    </div>
    <div class="timeline-legend-item">
        <div class="timeline-legend-color" style="background: #ef4444;"></div>
        <span>Overdue</span>
    </div>
</div>

<style>
    /* Timeline Toolbar */
    .timeline-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .timeline-toolbar-left,
    .timeline-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .timeline-zoom-controls {
        display: flex;
        gap: 4px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 2px;
    }

    .timeline-zoom-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .timeline-zoom-btn:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    /* Timeline Container */
    .timeline-container {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    /* Timeline Header */
    .timeline-header {
        display: grid;
        grid-template-columns: 400px 1fr;
        background: var(--bg);
        border-bottom: 2px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .timeline-tasks-column {
        padding: 16px 20px;
        border-right: 1px solid var(--border);
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .timeline-dates-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
    }

    .timeline-date-header {
        padding: 12px 16px;
        text-align: center;
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .timeline-date-header:last-child {
        border-right: none;
    }

    .timeline-week {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .timeline-dates {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Timeline Body */
    .timeline-body {
        max-height: 600px;
        overflow-y: auto;
    }

    /* Timeline Group */
    .timeline-group {
        border-bottom: 1px solid var(--border);
    }

    .timeline-group-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        background: var(--bg);
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .timeline-group-header:hover {
        background: var(--card);
    }

    .timeline-group-toggle {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .timeline-group-toggle.active i {
        transform: rotate(0deg);
    }

    .timeline-group-toggle:not(.active) i {
        transform: rotate(-90deg);
    }

    .timeline-group-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        flex: 1;
    }

    .timeline-group-count {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        background: var(--card);
        padding: 2px 8px;
        border-radius: 10px;
    }

    .timeline-group-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .timeline-group-content.active {
        max-height: 2000px;
    }

    /* Timeline Row */
    .timeline-row {
        display: grid;
        grid-template-columns: 400px 1fr;
        border-bottom: 1px solid var(--border);
        min-height: 52px;
        transition: background 0.15s ease;
    }

    .timeline-row:hover {
        background: var(--bg);
    }

    .timeline-task-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-right: 1px solid var(--border);
    }

    .timeline-task-details {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .timeline-task-key {
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        font-family: monospace;
        flex-shrink: 0;
    }

    .timeline-task-title {
        font-size: var(--fs-body);
        color: var(--text-body);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .timeline-task-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
    }

    /* Timeline Bars */
    .timeline-bars-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        padding: 8px 0;
        align-items: center;
    }

    .timeline-bar {
        margin: 0 8px;
        position: relative;
    }

    .timeline-bar-inner {
        height: 32px;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-dark) 100%);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .timeline-bar-inner:hover {
        transform: scaleY(1.1);
    }

    .timeline-bar-unstarted {
        background: var(--border) !important;
    }

    .timeline-bar-label {
        font-size: var(--fs-micro);
        font-weight: var(--fw-bold);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    /* Timeline Legend */
    .timeline-legend {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-top: 20px;
        padding: 16px 20px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .timeline-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--fs-subtle);
        color: var(--text-body);
    }

    .timeline-legend-color {
        width: 24px;
        height: 12px;
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .timeline-header,
        .timeline-row {
            grid-template-columns: 200px 1fr;
        }
    }
</style>

<script>
    function toggleTimelineGroup(groupId) {
        const content = document.getElementById(groupId);
        const button = content.previousElementSibling.querySelector('.timeline-group-toggle');
        
        content.classList.toggle('active');
        button.classList.toggle('active');
    }

    function zoomIn() {
        console.log('Zoom In');
    }

    function zoomOut() {
        console.log('Zoom Out');
    }
</script>