{{-- resources/views/tenant/manage/projects/tabs/list.blade.php --}}

<div class="pro-list">
    <!-- List Header -->
    <div class="pro-list-header">
        <div class="pro-list-actions">
            <button class="pro-btn pro-btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="pro-btn pro-btn-secondary">
                <i class="fas fa-sort"></i> Sort
            </button>
            <div class="pro-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search tasks..." />
            </div>
        </div>
        <button class="pro-btn pro-btn-primary">
            <i class="fas fa-plus"></i> New Task
        </button>
    </div>

    @if($project->tasks->count() > 0)
        <!-- List Table -->
        <div class="pro-list-table">
            <!-- Table Header -->
            <div class="pro-list-row pro-list-header-row">
                <div class="pro-list-cell pro-list-task">
                    <input type="checkbox" class="pro-checkbox" />
                    <span>Task</span>
                </div>
                <div class="pro-list-cell">Status</div>
                <div class="pro-list-cell">Priority</div>
                <div class="pro-list-cell">Assignee</div>
                <div class="pro-list-cell">Due Date</div>
                <div class="pro-list-cell pro-list-actions-cell"></div>
            </div>

            <!-- Table Body -->
            @foreach($project->tasks as $task)
                <div class="pro-list-row" onclick="openTaskDetail({{ $task->id }})">
                    <div class="pro-list-cell pro-list-task">
                        <input type="checkbox" class="pro-checkbox" onclick="event.stopPropagation()" />
                        <div class="pro-list-task-content">
                            <div class="pro-list-task-main">
                                <span class="pro-list-task-key">{{ $project->key }}-{{ $task->id }}</span>
                                <h4>{{ $task->title }}</h4>
                            </div>
                            @if($task->subtasks->count() > 0)
                                @php
                                    $completed = $task->subtasks->where('completed', true)->count();
                                    $total = $task->subtasks->count();
                                @endphp
                                <div class="pro-list-subtasks">
                                    <i class="fas fa-check-square"></i>
                                    <span>{{ $completed }}/{{ $total }}</span>
                                    <div class="pro-list-subtask-bar">
                                        <div style="width: {{ $total > 0 ? ($completed / $total * 100) : 0 }}%;"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pro-list-cell">
                        <span class="pro-status-badge status-{{ $task->status }}">
                            {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                        </span>
                    </div>
                    
                    <div class="pro-list-cell">
                        <span class="pro-priority priority-{{ $task->priority ?? 'medium' }}">
                            <i class="fas fa-{{ 
                                ($task->priority ?? 'medium') === 'urgent' ? 'exclamation-circle' :
                                (($task->priority ?? 'medium') === 'high' ? 'arrow-up' :
                                (($task->priority ?? 'medium') === 'medium' ? 'minus' : 'arrow-down'))
                            }}"></i>
                            {{ ucfirst($task->priority ?? 'medium') }}
                        </span>
                    </div>
                    
                    <div class="pro-list-cell">
                        @if($task->assignee)
                            <div class="pro-list-assignee">
                                @if($task->assignee->avatar_url)
                                    <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}"     referrerpolicy="no-referrer"
                                    crossorigin="anonymous"
                                    onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';"/>
                                @else
                                    <div class="pro-avatar-fallback">{{ strtoupper(substr($task->assignee->name, 0, 1)) }}</div>
                                @endif
                                <span>{{ $task->assignee->name }}</span>
                            </div>
                        @else
                            <span class="pro-list-unassigned">Unassigned</span>
                        @endif
                    </div>
                    
                    <div class="pro-list-cell">
                        @if($task->due_date)
                            <span class="pro-list-date {{ $task->is_overdue ? 'overdue' : '' }}">
                                <i class="fas fa-calendar"></i>
                                {{ $task->due_date->format('M d, Y') }}
                            </span>
                        @else
                            <span class="pro-list-no-date">—</span>
                        @endif
                    </div>
                    
                    <div class="pro-list-cell pro-list-actions-cell">
                        <button class="pro-btn-icon" onclick="event.stopPropagation(); openTaskMenu({{ $task->id }})">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pro-list-footer">
            <div class="pro-list-info">
                Showing {{ $project->tasks->count() }} of {{ $project->tasks->count() }} tasks
            </div>
        </div>
    @else
        <div class="pro-empty">
            <i class="fas fa-tasks"></i>
            <h3>No Tasks Yet</h3>
            <p>Create your first task to get started</p>
            <button class="pro-btn pro-btn-primary">
                <i class="fas fa-plus"></i> Create Task
            </button>
        </div>
    @endif
</div>

<style>
/* Pro List Styles */
.pro-list {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.pro-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    gap: 12px;
    flex-wrap: wrap;
}

.pro-list-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.pro-search {
    position: relative;
    display: flex;
    align-items: center;
}

.pro-search i {
    position: absolute;
    left: 12px;
    font-size: 13px;
    color: var(--text-muted);
}

.pro-search input {
    padding: 8px 12px 8px 36px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--bg);
    font-size: 13px;
    min-width: 200px;
    transition: all 0.2s;
}

.pro-search input:focus {
    outline: none;
    border-color: var(--accent);
    background: var(--card);
}

.pro-list-table {
    overflow-x: auto;
}

.pro-list-row {
    display: grid;
    grid-template-columns: 2fr 130px 120px 180px 140px 50px;
    gap: 12px;
    padding: 12px 16px;
    align-items: center;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
}

.pro-list-row:hover {
    background: var(--bg);
}

.pro-list-header-row {
    background: var(--bg);
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: default;
    border-bottom: 2px solid var(--border);
}

.pro-list-header-row:hover {
    background: var(--bg);
}

.pro-list-cell {
    overflow: hidden;
}

.pro-list-task {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pro-checkbox {
    width: 16px;
    height: 16px;
    cursor: pointer;
    flex-shrink: 0;
}

.pro-list-task-content {
    flex: 1;
    min-width: 0;
}

.pro-list-task-main {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}

.pro-list-task-key {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}

.pro-list-task-main h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-list-subtasks {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: var(--text-muted);
}

.pro-list-subtasks i {
    color: var(--accent);
}

.pro-list-subtask-bar {
    width: 40px;
    height: 3px;
    background: var(--border);
    border-radius: 2px;
    overflow: hidden;
}

.pro-list-subtask-bar div {
    height: 100%;
    background: var(--accent);
    transition: width 0.3s;
}

.pro-status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.pro-status-badge.status-todo {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.pro-status-badge.status-in-progress {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.pro-status-badge.status-review {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.pro-status-badge.status-done {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.pro-status-badge.status-blocked {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.pro-priority {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
}

.pro-priority.priority-urgent {
    color: #ef4444;
}

.pro-priority.priority-high {
    color: #f59e0b;
}

.pro-priority.priority-medium {
    color: #3b82f6;
}

.pro-priority.priority-low {
    color: #6b7280;
}

.pro-list-assignee {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pro-list-assignee img,
.pro-avatar-fallback {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    flex-shrink: 0;
}

.pro-avatar-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: #fff;
    font-size: 11px;
    font-weight: 700;
}

.pro-list-assignee span {
    font-size: 13px;
    color: var(--text-body);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-list-unassigned {
    font-size: 12px;
    color: var(--text-muted);
    font-style: italic;
}

.pro-list-date {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: var(--text-body);
}

.pro-list-date.overdue {
    color: #ef4444;
    font-weight: 600;
}

.pro-list-date i {
    font-size: 11px;
}

.pro-list-no-date {
    font-size: 12px;
    color: var(--text-muted);
}

.pro-list-actions-cell {
    display: flex;
    justify-content: flex-end;
}

.pro-btn-icon {
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

.pro-btn-icon:hover {
    background: var(--bg);
    color: var(--text-body);
}

.pro-list-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-top: 1px solid var(--border);
}

.pro-list-info {
    font-size: 12px;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 1024px) {
    .pro-list-row {
        grid-template-columns: 2fr 110px 100px 150px 120px 40px;
    }
}

@media (max-width: 768px) {
    .pro-list-header {
        flex-direction: column;
        align-items: stretch;
    }

    .pro-list-actions {
        width: 100%;
    }

    .pro-search input {
        flex: 1;
        min-width: 0;
    }

    .pro-list-row {
        grid-template-columns: 1fr;
        padding: 14px 16px;
    }

    .pro-list-cell:not(.pro-list-task) {
        display: none;
    }

    .pro-list-header-row {
        display: none;
    }
}
</style>

<script>
function openTaskDetail(taskId) {
    console.log('Open task:', taskId);
}

function openTaskMenu(taskId) {
    console.log('Open menu:', taskId);
}
</script>