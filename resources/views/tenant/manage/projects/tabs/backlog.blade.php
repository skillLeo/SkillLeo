{{-- resources/views/tenant/manage/projects/tabs/backlog.blade.php --}}

@php
use Illuminate\Support\Str;
    
$backlogTasks = $project->tasks()->whereIn('status', ['todo', 'backlog'])->orderBy('order')->get();
$totalBacklog = $backlogTasks->count();
@endphp

<div class="pro-backlog">
    <!-- Backlog Header -->
    <div class="pro-backlog-header">
        <div class="pro-backlog-info">
            <h2><i class="fas fa-inbox"></i> Backlog</h2>
            <p>{{ $totalBacklog }} {{ Str::plural('task', $totalBacklog) }} waiting to be scheduled</p>
        </div>
        <div class="pro-backlog-actions">
            <button class="pro-btn pro-btn-secondary">
                <i class="fas fa-sort"></i> Sort
            </button>
            <button class="pro-btn pro-btn-primary" onclick="createBacklogTask()">
                <i class="fas fa-plus"></i> Add to Backlog
            </button>
        </div>
    </div>

    @if($totalBacklog > 0)
        <!-- Backlog List -->
        <div class="pro-backlog-list">
            @foreach($backlogTasks as $index => $task)
                <div class="pro-backlog-item" draggable="true">
                    <div class="pro-backlog-drag">
                        <i class="fas fa-grip-vertical"></i>
                    </div>

                    <div class="pro-backlog-checkbox">
                        <input type="checkbox" onclick="event.stopPropagation()" />
                    </div>

                    <div class="pro-backlog-content" onclick="openTaskDetail({{ $task->id }})">
                        <div class="pro-backlog-main">
                            <span class="pro-backlog-key">{{ $project->key }}-{{ $task->id }}</span>
                            <h4 class="pro-backlog-title">{{ $task->title }}</h4>
                            
                            <div class="pro-backlog-badges">
                                @if($task->priority)
                                    <span class="pro-priority-badge priority-{{ $task->priority }}">
                                        <i class="fas fa-{{ 
                                            $task->priority === 'urgent' ? 'exclamation-circle' :
                                            ($task->priority === 'high' ? 'arrow-up' :
                                            ($task->priority === 'medium' ? 'minus' : 'arrow-down'))
                                        }}"></i>
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                @endif

                                @if($task->estimated_hours)
                                    <span class="pro-estimate-badge">
                                        <i class="fas fa-clock"></i>
                                        {{ $task->estimated_hours }}h
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($task->description)
                            <p class="pro-backlog-desc">{{ Str::limit($task->description, 100) }}</p>
                        @endif

                        <div class="pro-backlog-meta">
                            @if($task->assignee)
                                <div class="pro-backlog-assignee">
                                    @if($task->assignee->avatar_url)
                                        <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}" />
                                    @else
                                        <div class="pro-avatar-fallback">{{ strtoupper(substr($task->assignee->name, 0, 1)) }}</div>
                                    @endif
                                    <span>{{ $task->assignee->name }}</span>
                                </div>
                            @endif

                            @if($task->subtasks->count() > 0)
                                <span class="pro-backlog-subtasks">
                                    <i class="fas fa-check-square"></i>
                                    {{ $task->subtasks->count() }} subtasks
                                </span>
                            @endif

                            @if($task->attachments->count() > 0)
                                <span class="pro-backlog-attachments">
                                    <i class="fas fa-paperclip"></i>
                                    {{ $task->attachments->count() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="pro-backlog-actions">
                        <button class="pro-btn-icon" onclick="event.stopPropagation(); moveToSprint({{ $task->id }})" title="Move to Sprint">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button class="pro-btn-icon" onclick="event.stopPropagation(); openTaskMenu({{ $task->id }})">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="pro-empty">
            <i class="fas fa-inbox"></i>
            <h3>Backlog is Empty</h3>
            <p>Add tasks to your backlog to organize and prioritize your work</p>
            <button class="pro-btn pro-btn-primary" onclick="createBacklogTask()">
                <i class="fas fa-plus"></i> Add First Task
            </button>
        </div>
    @endif
</div>

<style>
/* Backlog Styles */
.pro-backlog {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.pro-backlog-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.pro-backlog-info h2 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.pro-backlog-info h2 i {
    color: var(--accent);
}

.pro-backlog-info p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

.pro-backlog-actions {
    display: flex;
    gap: 8px;
}

.pro-backlog-list {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.pro-backlog-item {
    display: flex;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    align-items: flex-start;
    transition: background 0.15s;
    cursor: move;
}

.pro-backlog-item:last-child {
    border-bottom: none;
}

.pro-backlog-item:hover {
    background: var(--bg);
}

.pro-backlog-drag {
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    cursor: grab;
    padding-top: 2px;
}

.pro-backlog-drag:active {
    cursor: grabbing;
}

.pro-backlog-checkbox {
    padding-top: 2px;
}

.pro-backlog-checkbox input {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.pro-backlog-content {
    flex: 1;
    min-width: 0;
    cursor: pointer;
}

.pro-backlog-main {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
    flex-wrap: wrap;
}

.pro-backlog-key {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pro-backlog-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
}

.pro-backlog-badges {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.pro-priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.pro-priority-badge.priority-urgent {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.pro-priority-badge.priority-high {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.pro-priority-badge.priority-medium {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.pro-priority-badge.priority-low {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.pro-estimate-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background: var(--bg);
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-body);
}

.pro-backlog-desc {
    font-size: 13px;
    line-height: 1.5;
    color: var(--text-muted);
    margin: 0 0 8px 0;
}

.pro-backlog-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.pro-backlog-assignee {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pro-backlog-assignee img,
.pro-backlog-assignee .pro-avatar-fallback {
    width: 24px;
    height: 24px;
    border-radius: 50%;
}

.pro-backlog-assignee .pro-avatar-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: #fff;
    font-size: 10px;
    font-weight: 700;
}

.pro-backlog-assignee span {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-body);
}

.pro-backlog-subtasks,
.pro-backlog-attachments {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--text-muted);
}

.pro-backlog-subtasks i,
.pro-backlog-attachments i {
    font-size: 11px;
}

.pro-backlog-actions {
    display: flex;
    gap: 4px;
    padding-top: 2px;
}

/* Responsive */
@media (max-width: 768px) {
    .pro-backlog-header {
        flex-direction: column;
        align-items: stretch;
    }

    .pro-backlog-actions {
        width: 100%;
    }

    .pro-backlog-item {
        flex-wrap: wrap;
    }

    .pro-backlog-drag {
        order: -1;
    }
}
</style>

<script>
function openTaskDetail(taskId) {
    console.log('Open task:', taskId);
}

function createBacklogTask() {
    console.log('Create backlog task');
}

function moveToSprint(taskId) {
    console.log('Move to sprint:', taskId);
}

function openTaskMenu(taskId) {
    console.log('Open menu:', taskId);
}

// Drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.pro-backlog-item');
    
    items.forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            this.style.opacity = '0.4';
        });
        
        item.addEventListener('dragend', function() {
            this.style.opacity = '1';
        });
        
        item.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            return false;
        });
        
        item.addEventListener('drop', function(e) {
            e.stopPropagation();
            console.log('Dropped');
            return false;
        });
    });
});
</script>