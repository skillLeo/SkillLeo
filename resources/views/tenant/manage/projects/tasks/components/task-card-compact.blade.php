{{-- resources/views/tenant/manage/projects/tasks/components/task-card-compact.blade.php --}}

@php
    $isOverdue = $task->is_overdue ?? false;
    $canEdit = $canEdit ?? false;
    $canDelete = $canDelete ?? false;
    $canComplete = $canComplete ?? true;
    $canPostpone = $canPostpone ?? true;
@endphp

<div class="task-card-compact" data-task-id="{{ $task->id }}">
    <!-- Header -->
    <div class="task-card-header">
        <div class="task-card-key-status">
            <span class="task-card-key">{{ $task->project?->key }}-{{ $task->id }}</span>
            @include('tenant.manage.projects.tasks.components.task-status-badge', ['status' => $task->status])
        </div>

        <div class="task-card-actions-menu">
            <button class="task-card-menu-btn" onclick="openTaskCardMenu({{ $task->id }}, event, {
                canEdit: {{ $canEdit ? 'true' : 'false' }},
                canDelete: {{ $canDelete ? 'true' : 'false' }}
            })">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
    </div>

    <!-- Title -->
    <h4 class="task-card-title" onclick="openTaskDrawer({{ $task->id }})">
        {{ $task->title }}
    </h4>

    <!-- Meta Info -->
    <div class="task-card-meta">
        @if($task->due_date)
            <span class="task-card-due {{ $isOverdue ? 'overdue' : '' }}">
                <i class="fas fa-calendar"></i>
                {{ $task->due_date->format('M d') }}
            </span>
        @endif

        @if($task->priority)
            <span class="task-card-priority priority-{{ $task->priority }}">
                <i class="fas fa-{{ ['urgent' => 'exclamation-circle', 'high' => 'arrow-up', 'medium' => 'minus', 'low' => 'arrow-down'][$task->priority] ?? 'minus' }}"></i>
                {{ ucfirst($task->priority) }}
            </span>
        @endif

        @if($task->subtasks && $task->subtasks->count() > 0)
            <span class="task-card-subtasks">
                <i class="fas fa-check-square"></i>
                {{ $task->subtasks->where('completed', true)->count() }}/{{ $task->subtasks->count() }}
            </span>
        @endif

        @if($task->assignee && $task->assignee->id !== $viewer->id)
            <span class="task-card-assignee">
                <img src="{{ $task->assignee->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->assignee->name) }}" 
                     alt="{{ $task->assignee->name }}"
                     class="task-card-avatar">
                {{ $task->assignee->name }}
            </span>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="task-card-actions">
        @if($canComplete && $task->status !== 'done')
            <button class="task-card-btn task-card-btn-complete" 
                    onclick="quickCompleteTask({{ $task->id }}, {{ $task->project_id }})">
                <i class="fas fa-check"></i>
                <span>Complete</span>
            </button>
        @endif

        @if($canPostpone && $task->status !== 'done')
            <button class="task-card-btn task-card-btn-postpone" 
                    onclick="postponeTaskModal({{ $task->id }}, {{ $task->project_id }})">
                <i class="fas fa-clock"></i>
                <span>Postpone</span>
            </button>
        @endif

        <button class="task-card-btn task-card-btn-view" 
                onclick="openTaskDrawer({{ $task->id }})">
            <i class="fas fa-eye"></i>
            <span>View</span>
        </button>
    </div>
</div>

<style>
.task-card-compact {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 16px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.task-card-compact:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.task-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.task-card-key-status {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.task-card-key {
    font-size: var(--fs-micro);
    font-weight: var(--fw-semibold);
    font-family: monospace;
    color: var(--text-muted);
    background: var(--bg);
    padding: 3px 8px;
    border-radius: 4px;
}

.task-card-menu-btn {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    border-radius: 6px;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--task-transition);
}

.task-card-menu-btn:hover {
    background: var(--bg);
    color: var(--text-body);
}

.task-card-title {
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 12px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.task-card-title:hover {
    color: var(--accent);
}

.task-card-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
    font-size: var(--fs-subtle);
}

.task-card-due,
.task-card-priority,
.task-card-subtasks,
.task-card-assignee {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: var(--text-muted);
}

.task-card-due.overdue {
    color: #ef4444;
    font-weight: var(--fw-semibold);
}

.task-card-priority {
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: var(--fw-medium);
}

.priority-urgent {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.priority-high {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.priority-medium {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.priority-low {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.task-card-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--card);
}

.task-card-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid var(--border);
}

.task-card-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 32px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    background: var(--card);
    color: var(--text-body);
    cursor: pointer;
    transition: var(--task-transition);
}

.task-card-btn:hover {
    background: var(--bg);
}

.task-card-btn-complete {
    background: var(--task-success);
    color: white;
    border-color: var(--task-success);
}

.task-card-btn-complete:hover {
    background: #007052;
}

.task-card-btn-postpone:hover {
    background: rgba(245, 158, 11, 0.1);
    border-color: var(--task-warning);
    color: var(--task-warning);
}

.task-card-btn-view:hover {
    background: var(--accent-light);
    border-color: var(--accent);
    color: var(--accent);
}

@media (max-width: 640px) {
    .task-card-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function quickCompleteTask(taskId, projectId) {
    if (!confirm('Mark this task as complete?')) return;
    
    // Show loading
    showTaskLoading(taskId);
    
    fetch(`/{{ $username }}/manage/projects/tasks/${taskId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: 'done',
            cascade_subtasks: true
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Remove card with animation
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(() => card.remove(), 300);
            }
        } else {
            showNotification(data.message || 'Failed to complete task', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to complete task', 'error');
    })
    .finally(() => {
        hideTaskLoading(taskId);
    });
}

function postponeTaskModal(taskId, projectId) {
    // Will be handled by global modal
    openPostponeModal(taskId, projectId);
}

function openTaskCardMenu(taskId, event, permissions) {
    event.stopPropagation();
    
    const menu = document.createElement('div');
    menu.className = 'task-card-context-menu';
    menu.style.cssText = `
        position: fixed;
        top: ${event.clientY}px;
        left: ${event.clientX}px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        z-index: 1000;
        min-width: 180px;
        padding: 8px 0;
    `;
    
    menu.innerHTML = `
        <button class="context-menu-item" onclick="openTaskDrawer(${taskId}); closeTaskCardMenu();">
            <i class="fas fa-eye"></i>
            View Details
        </button>
        ${permissions.canEdit ? `
        <button class="context-menu-item" onclick="editTask(${taskId}); closeTaskCardMenu();">
            <i class="fas fa-edit"></i>
            Edit Task
        </button>
        ` : ''}
        <button class="context-menu-item" onclick="duplicateTask(${taskId}); closeTaskCardMenu();">
            <i class="fas fa-copy"></i>
            Duplicate
        </button>
        <hr style="margin: 8px 0; border: none; border-top: 1px solid var(--border);">
        ${permissions.canDelete ? `
        <button class="context-menu-item danger" onclick="deleteTask(${taskId}); closeTaskCardMenu();">
            <i classfa-trash"></i>
            Delete Task
        </button>
        ` : ''}
    `;
    
    document.body.appendChild(menu);
    
    // Add style for menu items
    const style = document.createElement('style');
    style.textContent = `
        .context-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: none;
            border: none;
            text-align: left;
            font-size: 14px;
            color: var(--text-body);
            cursor: pointer;
            transition: var(--task-transition);
        }
        .context-menu-item:hover {
            background: var(--bg);
        }
        .context-menu-item.danger {
            color: var(--task-danger);
        }
        .context-menu-item i {
            width: 16px;
            text-align: center;
        }
    `;
    document.head.appendChild(style);
    
    // Close on outside click
    setTimeout(() => {
        document.addEventListener('click', closeTaskCardMenu);
    }, 0);
}

function closeTaskCardMenu() {
    const menu = document.querySelector('.task-card-context-menu');
    if (menu) menu.remove();
    document.removeEventListener('click', closeTaskCardMenu);
}

function showTaskLoading(taskId) {
    const card = document.querySelector(`[data-task-id="${taskId}"]`);
    if (card) {
        card.style.opacity = '0.6';
        card.style.pointerEvents = 'none';
    }
}

function hideTaskLoading(taskId) {
    const card = document.querySelector(`[data-task-id="${taskId}"]`);
    if (card) {
        card.style.opacity = '1';
        card.style.pointerEvents = 'auto';
    }
}

function openTaskDrawer(taskId) {
    // Implement task drawer
    console.log('Open task drawer:', taskId);
}

function editTask(taskId) {
    console.log('Edit task:', taskId);
}

function duplicateTask(taskId) {
    console.log('Duplicate task:', taskId);
}

function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;
    console.log('Delete task:', taskId);
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