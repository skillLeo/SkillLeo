{{-- resources/views/tenant/manage/projects/tabs/timeline.blade.php --}}

<div class="pro-timeline">
    <!-- Timeline Toolbar -->
    <div class="pro-timeline-toolbar">
        <div class="pro-timeline-controls">
            <button class="pro-btn pro-btn-secondary">
                <i class="fas fa-filter"></i> Filters
            </button>
            <select class="pro-select">
                <option value="month">Month View</option>
                <option value="week">Week View</option>
                <option value="quarter">Quarter View</option>
            </select>
        </div>
        <div class="pro-timeline-actions">
            <button class="pro-btn pro-btn-secondary">
                <i class="fas fa-download"></i> Export
            </button>
            <div class="pro-zoom">
                <button onclick="zoomOut()"><i class="fas fa-search-minus"></i></button>
                <button onclick="zoomIn()"><i class="fas fa-search-plus"></i></button>
            </div>
        </div>
    </div>

    @if($project->tasks->count() > 0)
        <!-- Timeline Grid -->
        <div class="pro-timeline-grid">
            <!-- Header -->
            <div class="pro-timeline-header">
                <div class="pro-timeline-col-task">Tasks</div>
                <div class="pro-timeline-col-dates">
                    @php
                        $startDate = $project->start_date ?? now();
                        $endDate = $project->due_date ?? now()->addDays(28);
                        $weeks = min(ceil($startDate->diffInDays($endDate) / 7), 4);
                    @endphp
                    @for($i = 0; $i < $weeks; $i++)
                        @php
                            $weekStart = $startDate->copy()->addWeeks($i);
                            $weekEnd = $weekStart->copy()->addDays(6);
                        @endphp
                        <div class="pro-timeline-week">
                            <span class="pro-week-label">Week {{ $i + 1 }}</span>
                            <span class="pro-week-dates">{{ $weekStart->format('M d') }}-{{ $weekEnd->format('d') }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Body -->
            <div class="pro-timeline-body">
                @php
                    $grouped = $project->tasks->groupBy(fn($task) => $task->assignee?->id ?? 'unassigned');
                @endphp

                @foreach($grouped as $assigneeId => $tasks)
                    <div class="pro-timeline-group">
                        <div class="pro-timeline-group-header" onclick="toggleGroup('group-{{ $assigneeId }}')">
                            <button class="pro-toggle-icon active">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            @if($assigneeId === 'unassigned')
                                <div class="pro-timeline-assignee">
                                    <div class="pro-avatar-fallback"><i class="fas fa-inbox"></i></div>
                                    <span>Unassigned</span>
                                </div>
                            @else
                                <div class="pro-timeline-assignee">
                                    @if($tasks->first()->assignee->avatar_url)
                                        <img src="{{ $tasks->first()->assignee->avatar_url }}" alt="{{ $tasks->first()->assignee->name }}" />
                                    @else
                                        <div class="pro-avatar-fallback">{{ strtoupper(substr($tasks->first()->assignee->name, 0, 1)) }}</div>
                                    @endif
                                    <span>{{ $tasks->first()->assignee->name }}</span>
                                </div>
                            @endif
                            <span class="pro-timeline-count">{{ $tasks->count() }}</span>
                        </div>

                        <div class="pro-timeline-group-tasks active" id="group-{{ $assigneeId }}">
                            @foreach($tasks as $task)
                                @php
                                    $taskStart = $task->created_at ?? $startDate;
                                    $taskDue = $task->due_date ?? $endDate;
                                    $startWeek = floor($startDate->diffInDays($taskStart) / 7);
                                    $duration = max(1, ceil($taskStart->diffInDays($taskDue) / 7));
                                    
                                    $totalSubs = $task->subtasks->count();
                                    $completedSubs = $task->subtasks->where('completed', true)->count();
                                    $progress = $totalSubs > 0 ? round(($completedSubs / $totalSubs) * 100) : 0;
                                @endphp
                                <div class="pro-timeline-task-row">
                                    <div class="pro-timeline-col-task">
                                        <div class="pro-timeline-task-info">
                                            <i class="fas fa-grip-vertical pro-task-drag"></i>
                                            <span class="pro-task-key">{{ $project->key }}-{{ $task->id }}</span>
                                            <span class="pro-task-title">{{ $task->title }}</span>
                                        </div>
                                    </div>
                                    <div class="pro-timeline-col-dates">
                                        <div class="pro-timeline-bars" style="grid-column: {{ max(1, $startWeek + 1) }} / span {{ min($duration, $weeks - $startWeek) }};">
                                            <div class="pro-timeline-bar {{ $progress == 0 ? 'not-started' : '' }}" 
                                                 style="width: {{ $progress > 0 ? $progress : 100 }}%;"
                                                 title="{{ $progress }}% complete">
                                                <span>{{ $progress > 0 ? $progress . '%' : 'Not Started' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Legend -->
        <div class="pro-timeline-legend">
            <div class="pro-legend-item">
                <div class="pro-legend-color" style="background: linear-gradient(90deg, var(--accent), var(--accent-dark));"></div>
                <span>In Progress</span>
            </div>
            <div class="pro-legend-item">
                <div class="pro-legend-color" style="background: #10b981;"></div>
                <span>Completed</span>
            </div>
            <div class="pro-legend-item">
                <div class="pro-legend-color" style="background: var(--border);"></div>
                <span>Not Started</span>
            </div>
            <div class="pro-legend-item">
                <div class="pro-legend-color" style="background: #ef4444;"></div>
                <span>Overdue</span>
            </div>
        </div>
    @else
        <div class="pro-empty">
            <i class="fas fa-chart-gantt"></i>
            <h3>No Timeline Data</h3>
            <p>Add tasks with due dates to see the timeline</p>
            <button class="pro-btn pro-btn-primary">
                <i class="fas fa-plus"></i> Create Task
            </button>
        </div>
    @endif
</div>

<style>
/* Timeline Styles */
.pro-timeline {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.pro-timeline-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.pro-timeline-controls,
.pro-timeline-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.pro-select {
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--card);
    font-size: 13px;
    color: var(--text-body);
    cursor: pointer;
}

.pro-zoom {
    display: flex;
    gap: 2px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 2px;
}

.pro-zoom button {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s;
}

.pro-zoom button:hover {
    background: var(--bg);
    color: var(--text-body);
}

.pro-timeline-grid {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.pro-timeline-header {
    display: grid;
    grid-template-columns: 320px 1fr;
    background: var(--bg);
    border-bottom: 2px solid var(--border);
}

.pro-timeline-col-task {
    padding: 12px 16px;
    border-right: 1px solid var(--border);
    font-size: 12px;
    font-weight: 700;
    color: var(--text-heading);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pro-timeline-col-dates {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
}

.pro-timeline-week {
    padding: 10px 14px;
    text-align: center;
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.pro-timeline-week:last-child {
    border-right: none;
}

.pro-week-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-heading);
}

.pro-week-dates {
    font-size: 11px;
    color: var(--text-muted);
}

.pro-timeline-body {
    max-height: 500px;
    overflow-y: auto;
}

.pro-timeline-group {
    border-bottom: 1px solid var(--border);
}

.pro-timeline-group:last-child {
    border-bottom: none;
}

.pro-timeline-group-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    background: var(--bg);
    cursor: pointer;
    transition: background 0.15s;
}

.pro-timeline-group-header:hover {
    background: rgba(var(--accent-rgb), 0.05);
}

.pro-toggle-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    transition: transform 0.2s;
}

.pro-toggle-icon.active i {
    transform: rotate(0deg);
}

.pro-toggle-icon:not(.active) i {
    transform: rotate(-90deg);
}

.pro-timeline-assignee {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
}

.pro-timeline-assignee img,
.pro-timeline-assignee .pro-avatar-fallback {
    width: 28px;
    height: 28px;
    border-radius: 50%;
}

.pro-timeline-assignee .pro-avatar-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: #fff;
    font-size: 12px;
}

.pro-timeline-assignee span {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
}

.pro-timeline-count {
    padding: 2px 8px;
    background: var(--card);
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
}

.pro-timeline-group-tasks {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.pro-timeline-group-tasks.active {
    max-height: 1500px;
}

.pro-timeline-task-row {
    display: grid;
    grid-template-columns: 320px 1fr;
    min-height: 42px;
    border-bottom: 1px solid var(--border);
}

.pro-timeline-task-row:last-child {
    border-bottom: none;
}

.pro-timeline-task-row:hover {
    background: var(--bg);
}

.pro-timeline-task-row .pro-timeline-col-task {
    display: flex;
    align-items: center;
    font-weight: normal;
    text-transform: none;
    letter-spacing: normal;
}

.pro-timeline-task-info {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    min-width: 0;
}

.pro-task-drag {
    font-size: 12px;
    color: var(--text-muted);
    cursor: grab;
}

.pro-task-key {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}

.pro-task-title {
    font-size: 13px;
    color: var(--text-body);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-timeline-task-row .pro-timeline-col-dates {
    padding: 8px 0;
    align-items: center;
}

.pro-timeline-bars {
    margin: 0 8px;
    position: relative;
}

.pro-timeline-bar {
    height: 26px;
    background: linear-gradient(90deg, var(--accent), var(--accent-dark));
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
    cursor: pointer;
}

.pro-timeline-bar:hover {
    transform: scaleY(1.1);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
}

.pro-timeline-bar.not-started {
    background: var(--border);
}

.pro-timeline-bar span {
    font-size: 10px;
    font-weight: 700;
    color: #fff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.pro-timeline-legend {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 12px 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    flex-wrap: wrap;
}

.pro-legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-body);
}

.pro-legend-color {
    width: 20px;
    height: 10px;
    border-radius: 2px;
}

/* Responsive */
@media (max-width: 768px) {
    .pro-timeline-header,
    .pro-timeline-task-row {
        grid-template-columns: 200px 1fr;
    }

    .pro-timeline-col-dates {
        grid-template-columns: repeat(2, 1fr);
    }

    .pro-timeline-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .pro-timeline-controls {
        width: 100%;
    }

    .pro-select {
        flex: 1;
    }
}
</style>

<script>
function toggleGroup(groupId) {
    const content = document.getElementById(groupId);
    const toggle = content.previousElementSibling.querySelector('.pro-toggle-icon');
    
    content.classList.toggle('active');
    toggle.classList.toggle('active');
}

function zoomIn() {
    console.log('Zoom in timeline');
}

function zoomOut() {
    console.log('Zoom out timeline');
}
</script>