{{-- resources/views/tenant/manage/projects/tasks/components/task-grid-card.blade.php --}}

@php
    use Illuminate\Support\Str;

    $priorityConfig = [
        'urgent' => ['color' => '#DE350B', 'bg' => 'rgba(222, 53, 11, 0.1)', 'label' => 'Urgent'],
        'high' => ['color' => '#FF991F', 'bg' => 'rgba(255, 153, 31, 0.1)', 'label' => 'High'],
        'medium' => ['color' => '#0065FF', 'bg' => 'rgba(0, 101, 255, 0.1)', 'label' => 'Medium'],
        'low' => ['color' => '#00875A', 'bg' => 'rgba(0, 135, 90, 0.1)', 'label' => 'Low'],
    ];

    $statusConfig = [
        'todo' => ['color' => '#6B778C', 'bg' => '#F4F5F7', 'label' => 'To Do'],
        'in-progress' => ['color' => '#0052CC', 'bg' => '#DEEBFF', 'label' => 'In Progress'],
        'review' => ['color' => '#FF991F', 'bg' => '#FFFAE6', 'label' => 'Review'],
        'done' => ['color' => '#00875A', 'bg' => '#E3FCEF', 'label' => 'Done'],
        'blocked' => ['color' => '#DE350B', 'bg' => '#FFEBE6', 'label' => 'Blocked'],
        'postponed' => ['color' => '#8777D9', 'bg' => '#EAE6FF', 'label' => 'Postponed'],
    ];

    $priority = $priorityConfig[$task->priority ?? 'medium'] ?? $priorityConfig['medium'];
    $status = $statusConfig[$task->status ?? 'todo'] ?? $statusConfig['todo'];

    $totalSubtasks = $task->subtasks->count();
    $completedSubtasks = $task->subtasks->where('completed', true)->count();
    $subtaskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

    $isOverdue = $task->is_overdue;
    $daysUntilDue = $task->due_date ? now()->diffInDays($task->due_date, false) : null;
    $isAssignedToMe = $task->assigned_to === auth()->id();
@endphp

<div class="pro-task-card" data-task-id="{{ $task->id }}" data-task-status="{{ $task->status }}"> <!-- Card Header -->
    <div class="pro-card-header">
        <div class="pro-card-meta">
            <span class="pro-task-key">{{ $task->project->key ?? 'TASK' }}-{{ $task->id }}</span>
            @if ($isOverdue)
                <span class="pro-overdue-indicator" title="Overdue">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
                        <circle cx="6" cy="6" r="6" />
                    </svg>
                </span>
            @endif
        </div>

        <div class="pro-card-actions">
            @if ($isAssignedToMe)
                <!-- Modern Action Buttons - Only Show on Hover -->
                <button class="pro-action-btn pro-action-btn--done"
                    onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'done')"
                    title="Mark as done">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 8l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <button class="pro-action-btn pro-action-btn--postpone"
                    onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'postponed')"
                    title="Postpone">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <circle cx="8" cy="8" r="6.5" />
                        <path d="M8 5v3l2 1" />
                    </svg>
                </button>

                <button class="pro-action-btn pro-action-btn--block"
                    onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'blocked')"
                    title="Mark as blocked">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <circle cx="8" cy="8" r="6.5" />
                        <line x1="3" y1="3" x2="13" y2="13" />
                    </svg>
                </button>
            @endif

            <button class="pro-action-btn pro-action-btn--note"
                onclick="event.stopPropagation(); openStatusModal({{ $task->id }}, 'remark')" title="Add note">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <rect x="3" y="2" width="10" height="12" rx="1" />
                    <line x1="5" y1="5" x2="11" y2="5" />
                    <line x1="5" y1="8" x2="11" y2="8" />
                    <line x1="5" y1="11" x2="8" y2="11" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Task Title -->
    <h3 class="pro-card-title" onclick="event.stopPropagation(); toggleSubtasksExpand({{ $task->id }})">
        {{ $task->title }}
    </h3>

    <!-- Task Description -->
    @if ($task->notes)
        <p class="pro-card-description">
            {{ Str::limit(strip_tags($task->notes), 120) }}
        </p>
    @endif

    <!-- Subtasks Section -->
    @if ($totalSubtasks > 0)
        <div class="pro-subtasks-section">
            <div class="pro-subtasks-header"
                onclick="event.stopPropagation(); toggleSubtasksExpand({{ $task->id }})">
                <div class="pro-subtasks-info">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <rect x="2" y="2" width="10" height="10" rx="1" />
                        <line x1="4" y1="6" x2="10" y2="6" />
                        <line x1="4" y1="8" x2="8" y2="8" />
                    </svg>
                    <span class="pro-subtasks-count">{{ $completedSubtasks }}/{{ $totalSubtasks }}</span>
                    <span class="pro-subtasks-label">subtasks</span>
                </div>

                <div class="pro-progress-mini">
                    <div class="pro-progress-mini-bar"
                        style="width: {{ $subtaskProgress }}%; background: {{ $status['color'] }};"></div>
                </div>

                <button class="pro-expand-btn" id="expand-btn-{{ $task->id }}">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 5l3 3 3-3" />
                    </svg>
                </button>
            </div>

            <!-- Expandable Subtasks List -->
            <div class="pro-subtasks-list" id="subtasks-list-{{ $task->id }}" style="display: none;">
                @foreach ($task->subtasks as $index => $subtask)
                    <div class="pro-subtask-item {{ $subtask->completed ? 'is-completed' : '' }}"
                        onclick="subtaskRowClick(event, {{ $task->id }}, {{ $subtask->id }}, {{ $totalSubtasks }}, {{ $index + 1 }})"
                        data-task-id="{{ $task->id }}" data-subtask-id="{{ $subtask->id }}">
                        <label class="pro-subtask-checkbox-wrapper" style="cursor:pointer;">
                            <input type="checkbox" class="pro-subtask-checkbox" id="subtask-cb-{{ $subtask->id }}"
                                {{ $subtask->completed ? 'checked' : '' }} onclick="event.stopPropagation();">
                            <span class="pro-checkbox-custom">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6l3 3 5-5" stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </label>

                        <span class="pro-subtask-title">{{ $subtask->title }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Card Footer -->
    <div class="pro-card-footer">
        <div class="pro-footer-left">
            <!-- Priority Badge -->
            <span class="pro-priority-badge"
                style="background: {{ $priority['bg'] }}; color: {{ $priority['color'] }};">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor">
                    <circle cx="5" cy="5" r="2" />
                </svg>
                {{ $priority['label'] }}
            </span>

            <!-- Due Date -->
            @if ($task->due_date)
                <span class="pro-due-date {{ $isOverdue ? 'is-overdue' : '' }}">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <rect x="2" y="2" width="8" height="8" rx="1" />
                        <line x1="2" y1="4" x2="10" y2="4" />
                    </svg>
                    {{ $task->due_date->format('M d') }}
                    @if ($daysUntilDue !== null && $daysUntilDue >= 0 && $daysUntilDue <= 3)
                        <span class="pro-days-left">({{ $daysUntilDue }}d)</span>
                    @endif
                </span>
            @endif

            <!-- Attachments -->
            @if ($task->attachments->count() > 0)
                <span class="pro-attachment-count">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M9 6v3a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1h3" />
                        <path d="M9 2h-1M9 2v1M9 2L6 5" />
                    </svg>
                    {{ $task->attachments->count() }}
                </span>
            @endif
        </div>

        <div class="pro-footer-right">
            <!-- Status Badge -->
            <span class="pro-status-badge" style="background: {{ $status['bg'] }}; color: {{ $status['color'] }};">
                {{ $status['label'] }}
            </span>

            <!-- Assignee -->
            @if ($task->assignee)
                <div class="pro-assignee" title="{{ $task->assignee->name }}">
                    @if ($task->assignee->avatar_url)
                        <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}"
                            class="pro-avatar" referrerpolicy="no-referrer" crossorigin="anonymous"
                            onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                    @else
                        <div class="pro-avatar pro-avatar-placeholder">
                            {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* ====================
   PROFESSIONAL TASK CARD
   Modern, Clean, Minimal
   ==================== */
    .pro-subtask-item {
        cursor: pointer;
    }


    .pro-task-card {
        background: #FFFFFF;
        border: 1px solid #E1E4E8;
        border-radius: 8px;
        padding: 16px;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .pro-task-card:hover {
        border-color: #0052CC;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .pro-task-card:hover .pro-card-actions {
        opacity: 1;
        pointer-events: all;
    }

    /* Card Header */
    .pro-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .pro-card-meta {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pro-task-key {
        font-size: 11px;
        font-weight: 600;
        color: #6B778C;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .pro-overdue-indicator {
        display: flex;
        align-items: center;
        color: #DE350B;
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    /* Action Buttons - Linear Style */
    .pro-card-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    .pro-action-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #E1E4E8;
        border-radius: 6px;
        background: #FFFFFF;
        color: #6B778C;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        position: relative;
        overflow: hidden;
    }

    .pro-action-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: currentColor;
        opacity: 0;
        transition: opacity 0.15s ease;
    }

    .pro-action-btn svg {
        position: relative;
        z-index: 1;
    }

    .pro-action-btn:hover {
        transform: scale(1.05);
    }

    .pro-action-btn--done:hover {
        background: #00875A;
        border-color: #00875A;
        color: #FFFFFF;
    }

    .pro-action-btn--postpone:hover {
        background: #8777D9;
        border-color: #8777D9;
        color: #FFFFFF;
    }

    .pro-action-btn--block:hover {
        background: #DE350B;
        border-color: #DE350B;
        color: #FFFFFF;
    }

    .pro-action-btn--note:hover {
        background: #0052CC;
        border-color: #0052CC;
        color: #FFFFFF;
    }

    /* Title */
    .pro-card-title {
        font-size: 15px;
        font-weight: 600;
        color: #172B4D;
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pro-card-title:hover {
        color: #0052CC;
    }

    /* Description */
    .pro-card-description {
        font-size: 13px;
        color: #5E6C84;
        line-height: 1.5;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Subtasks Section */
    .pro-subtasks-section {
        background: #F7F8F9;
        border-radius: 6px;
        overflow: hidden;
    }

    .pro-subtasks-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .pro-subtasks-header:hover {
        background: #EBECF0;
    }

    .pro-subtasks-info {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #5E6C84;
        flex: 1;
    }

    .pro-subtasks-count {
        font-size: 13px;
        font-weight: 600;
        color: #172B4D;
    }

    .pro-subtasks-label {
        font-size: 12px;
        color: #6B778C;
    }

    .pro-progress-mini {
        flex: 1;
        height: 4px;
        background: #DFE1E6;
        border-radius: 2px;
        overflow: hidden;
    }

    .pro-progress-mini-bar {
        height: 100%;
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    .pro-expand-btn {
        width: 20px;
        height: 20px;
        border: none;
        background: transparent;
        color: #6B778C;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.15s ease;
    }

    .pro-expand-btn:hover {
        background: #DFE1E6;
    }

    .pro-expand-btn.is-expanded svg {
        transform: rotate(180deg);
    }

    /* Subtasks List */
    .pro-subtasks-list {
        padding: 0 12px 12px 12px;
    }

    .pro-subtask-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        border-radius: 4px;
        transition: background 0.15s ease;
        margin-bottom: 4px;
        cursor: pointer;

    }

    .pro-subtask-item:last-child {
        margin-bottom: 0;
    }

    .pro-subtask-item:hover {
        background: #FFFFFF;
    }

    .pro-subtask-item.is-completed .pro-subtask-title {
        color: #6B778C;
        text-decoration: line-through;
    }

    .pro-subtask-checkbox-wrapper {
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
    }

    .pro-subtask-checkbox {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .pro-checkbox-custom {
        width: 18px;
        height: 18px;
        border: 2px solid #DFE1E6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        transition: all 0.15s ease;
    }

    .pro-checkbox-custom svg {
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.15s ease;
    }

    .pro-subtask-checkbox:checked+.pro-checkbox-custom {
        background: #00875A;
        border-color: #00875A;
    }

    .pro-subtask-checkbox:checked+.pro-checkbox-custom svg {
        opacity: 1;
        transform: scale(1);
    }

    .pro-subtask-checkbox-wrapper:hover .pro-checkbox-custom {
        border-color: #0052CC;
    }

    .pro-subtask-title {
        flex: 1;
        font-size: 13px;
        color: #172B4D;
        line-height: 1.4;
    }

    /* Card Footer */
    .pro-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 12px;
        border-top: 1px solid #F0F1F3;
    }

    .pro-footer-left,
    .pro-footer-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Badges */
    .pro-priority-badge,
    .pro-due-date,
    .pro-attachment-count {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .pro-priority-badge {
        border: 1px solid currentColor;
    }

    .pro-due-date {
        color: #5E6C84;
    }

    .pro-due-date.is-overdue {
        color: #DE350B;
        font-weight: 600;
    }

    .pro-days-left {
        font-size: 11px;
        font-weight: 600;
    }

    .pro-attachment-count {
        color: #6B778C;
    }

    .pro-status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid currentColor;
    }

    /* Avatar */
    .pro-assignee {
        display: flex;
        align-items: center;
    }

    .pro-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #FFFFFF;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .pro-avatar-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .pro-task-card {
            padding: 14px;
        }

        .pro-card-actions {
            opacity: 1;
            pointer-events: all;
        }

        .pro-card-title {
            font-size: 14px;
        }
    }
</style>

<script>
    // ========================= FULL SCRIPT FROM resources/views/tenant/manage/projects/tasks/components/task-grid-card.blade.php =========================
(function () {
    'use strict';

    // ============================
    // GLOBAL CONFIG
    // ============================
    if (!window.TENANT_USERNAME) {
        window.TENANT_USERNAME = "{{ $username ?? request()->segment(1) }}";
    }

    let selectedFiles = [];

    // MUST MATCH BACKEND statusMeta()
    const STATUS_STYLE = {
        'todo': {
            label: 'To Do',
            bg: '#F4F5F7',
            color: '#6B778C'
        },
        'in-progress': {
            label: 'In Progress',
            bg: '#DEEBFF',
            color: '#0052CC'
        },
        'review': {
            label: 'Review',
            bg: '#FFFAE6',
            color: '#FF991F'
        },
        'done': {
            label: 'Done',
            bg: '#E3FCEF',
            color: '#00875A'
        },
        'blocked': {
            label: 'Blocked',
            bg: '#FFEBE6',
            color: '#DE350B'
        },
        'postponed': {
            label: 'Postponed',
            bg: '#EAE6FF',
            color: '#8777D9'
        },
        'cancelled': {
            label: 'Cancelled',
            bg: '#F4F5F7',
            color: '#6B778C'
        },
    };

    // ============================
    // TOAST NOTIFICATIONS
    // ============================
    function ensureToastContainer() {
        let box = document.getElementById('app-toast-container');
        if (!box) {
            box = document.createElement('div');
            box.id = 'app-toast-container';
            box.style.cssText = 'position:fixed;top:16px;right:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
            document.body.appendChild(box);
        }
        return box;
    }

    function showToast(message, type = 'info') {
        const container = ensureToastContainer();
        const toast = document.createElement('div');
        toast.style.cssText = 'min-width:220px;max-width:320px;padding:12px 14px;border-radius:6px;box-shadow:0 10px 24px rgba(0,0,0,0.12);display:flex;align-items:flex-start;gap:8px;font-size:13px;line-height:1.4;border:1px solid transparent;font-weight:500;';

        let iconSvg = '';
        if (type === 'success') {
            toast.style.backgroundColor = '#E3FCEF';
            toast.style.color = '#006644';
            toast.style.borderColor = '#36B37E33';
            iconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#006644" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>';
        } else if (type === 'error') {
            toast.style.backgroundColor = '#FFEBE6';
            toast.style.color = '#BF2600';
            toast.style.borderColor = '#FF563033';
            iconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#BF2600" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        } else {
            toast.style.backgroundColor = '#DEEBFF';
            toast.style.color = '#0747A6';
            toast.style.borderColor = '#0052CC33';
            iconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0747A6" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        }

        toast.innerHTML = `
            <div style="flex-shrink:0;">${iconSvg}</div>
            <div style="flex:1;">${message}</div>
            <button style="background:transparent;border:none;color:inherit;cursor:pointer;line-height:1;padding:0;font-size:14px;font-weight:600;" aria-label="Close">&times;</button>
        `;

        toast.querySelector('button').addEventListener('click', () => toast.remove());
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 200ms ease, transform 200ms ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-4px)';
            setTimeout(() => toast.remove(), 220);
        }, 3000);
    }

    // ============================
    // 🔥 PERFECT REALTIME UPDATE LOGIC 🔥
    // THIS IS THE FIX YOU NEED!
    // ============================
    function applyTaskStateFromServer(taskId, data) {
        const {
            subtask,
            completed_subtasks_count,
            subtasks_count,
            task_status,
            task_status_label,
            task_status_bg,
            task_status_color
        } = data;

        const card = document.querySelector(`.pro-task-card[data-task-id="${taskId}"]`);
        if (!card) {
            console.warn(`Card not found for task ${taskId}`);
            return;
        }

        // 1. UPDATE THE SUBTASK ROW (checkbox + class)
        if (subtask && subtask.id) {
            const row = card.querySelector(`.pro-subtask-item[data-subtask-id="${subtask.id}"]`);
            if (row) {
                const cb = row.querySelector('.pro-subtask-checkbox');
                if (cb) {
                    cb.checked = !!subtask.completed;
                }
                if (subtask.completed) {
                    row.classList.add('is-completed');
                } else {
                    row.classList.remove('is-completed');
                }
            }
        }

        // 2. UPDATE SUBTASKS COUNTER (X/Y)
        const countEl = card.querySelector('.pro-subtasks-count');
        if (countEl) {
            countEl.textContent = `${completed_subtasks_count}/${subtasks_count}`;
        }

        // 3. UPDATE PROGRESS BAR WIDTH & COLOR 🔥
        const progressBar = card.querySelector('.pro-progress-mini-bar');
        if (progressBar) {
            const pct = subtasks_count > 0
                ? Math.round((completed_subtasks_count / subtasks_count) * 100)
                : 0;
            
            progressBar.style.width = pct + '%';

            // 🔥 USE THE NEW STATUS COLOR FROM SERVER
            const statusStyle = STATUS_STYLE[task_status];
            if (task_status_color) {
                progressBar.style.background = task_status_color;
            } else if (statusStyle) {
                progressBar.style.background = statusStyle.color;
            }
        }

        // 4. 🔥 UPDATE STATUS BADGE IN REALTIME 🔥
        const badge = card.querySelector('.pro-status-badge');
        if (badge) {
            const statusStyle = STATUS_STYLE[task_status] || STATUS_STYLE['todo'];
            
            const label = task_status_label || statusStyle.label;
            const bg = task_status_bg || statusStyle.bg;
            const col = task_status_color || statusStyle.color;

            // Update text
            badge.textContent = label;
            
            // Update styles
            badge.style.background = bg;
            badge.style.color = col;
            badge.style.borderColor = col;
        }

        // 5. UPDATE data-task-status ATTRIBUTE
        card.setAttribute('data-task-status', task_status);

        // 6. RECALC GLOBAL COMPLETION
        recalcGlobalCompletion();
    }

    // ============================
    // GLOBAL COMPLETION RING
    // ============================
    function recalcGlobalCompletion() {
        const wrapper = document.getElementById('pm-completion-wrapper');
        if (!wrapper) return;

        const taskCards = document.querySelectorAll('.pro-task-card');
        let totalUnits = 0;
        let completedUnits = 0;

        taskCards.forEach(card => {
            totalUnits += 1;
            const taskStatus = card.getAttribute('data-task-status');
            if (taskStatus === 'done') {
                completedUnits += 1;
            }

            const subtaskRows = card.querySelectorAll('.pro-subtask-item');
            subtaskRows.forEach(row => {
                totalUnits += 1;
                const cb = row.querySelector('.pro-subtask-checkbox');
                if (cb && cb.checked) {
                    completedUnits += 1;
                }
            });
        });

        const pct = totalUnits > 0 ? Math.round((completedUnits / totalUnits) * 100) : 0;

        const circle = wrapper.querySelector('.pm-circle');
        if (circle) {
            circle.setAttribute('stroke-dasharray', pct + ', 100');
        }

        const pctText = wrapper.querySelector('.pm-percentage');
        if (pctText) {
            pctText.textContent = pct + '%';
        }

        wrapper.setAttribute('data-total-units', totalUnits);
        wrapper.setAttribute('data-completed-units', completedUnits);
    }

    // ============================
    // SUBTASK ROW CLICK
    // ============================
    function subtaskRowClick(e, taskId, subtaskId, totalSubtasks) {
        const cb = document.getElementById(`subtask-cb-${subtaskId}`);
        if (!cb) return;

        const willBeChecked = !cb.checked;

        // Count future completed
        const checkboxes = document.querySelectorAll(
            `#subtasks-list-${taskId} .pro-subtask-checkbox`
        );

        let futureCompletedCount = 0;
        checkboxes.forEach(box => {
            if (box.id === `subtask-cb-${subtaskId}`) {
                if (willBeChecked) futureCompletedCount++;
            } else if (box.checked) {
                futureCompletedCount++;
            }
        });

        const wouldCompleteAll = (futureCompletedCount === totalSubtasks);

        // If completing ALL subtasks, open modal for final "done"
        if (wouldCompleteAll && willBeChecked) {
            e.preventDefault();
            openStatusModal(taskId, 'done', subtaskId);
            return;
        }

        // Optimistic UI update
        cb.checked = willBeChecked;

        // Send to backend
        toggleSubtask(taskId, subtaskId, willBeChecked);
    }

    // ============================
    // TOGGLE SUBTASK (AJAX)
    // ============================
    function toggleSubtask(taskId, subtaskId, isChecked) {
        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                completed: isChecked ? 1 : 0
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Update failed');
            }

            // 🔥 realtime apply
            applyTaskStateFromServer(taskId, data);

            if (data.subtask.completed) {
                showToast('Subtask marked complete', 'success');
            } else {
                showToast('Subtask reopened', 'info');
            }
        })
        .catch(err => {
            console.error('❌ Toggle failed:', err);
            // Rollback checkbox
            const cb = document.getElementById(`subtask-cb-${subtaskId}`);
            if (cb) cb.checked = !isChecked;
            showToast('Could not update subtask', 'error');
        });
    }

    // ============================
    // EXPAND/COLLAPSE SUBTASKS
    // ============================
    function toggleSubtasksExpand(taskId) {
        const list = document.getElementById(`subtasks-list-${taskId}`);
        const btn = document.getElementById(`expand-btn-${taskId}`);
        if (!list || !btn) return;

        const isHidden = (list.style.display === 'none' || list.style.display === '');
        list.style.display = isHidden ? 'block' : 'none';
        btn.classList.toggle('is-expanded', isHidden);
    }

    // ============================
    // MODAL FUNCTIONS
    // ============================
    function openStatusModal(taskId, action, subtaskId = null) {
        const modal = document.getElementById('taskStatusModal');
        const form = document.getElementById('taskStatusForm');
        const modalTitle = document.getElementById('modalTitle');
        const modalTaskId = document.getElementById('modalTaskId');
        const modalSubId = document.getElementById('modalSubtaskId');
        const modalAction = document.getElementById('modalAction');
        const statusWrap = document.getElementById('statusSelection');
        const remarkLabel = document.getElementById('remarkLabel');
        const submitBtnText = document.getElementById('submitBtnText');
        const postponeField = document.getElementById('postponeDateField');
        const postponeInput = document.getElementById('postponed_until');
        const filePreview = document.getElementById('filePreview');

        if (form) form.reset();
        selectedFiles = [];
        if (filePreview) {
            filePreview.innerHTML = '';
            filePreview.style.display = 'none';
        }

        modalTaskId.value = taskId;
        modalSubId.value = subtaskId || '';
        modalAction.value = action;

        if (action === 'remark') {
            modalTitle.textContent = 'Add Remark';
            statusWrap.style.display = 'none';
            remarkLabel.textContent = 'Your Remark';
            submitBtnText.textContent = 'Add Remark';
            postponeField.style.display = 'none';
            postponeInput.required = false;
        } else {
            modalTitle.textContent = 'Update Task Status';
            statusWrap.style.display = 'block';
            remarkLabel.textContent = 'Describe your update';
            submitBtnText.textContent = 'Update Status';

            if (action !== 'status') {
                const radio = document.querySelector(`input[name="status"][value="${action}"]`);
                if (radio) radio.checked = true;
            }

            updatePostponeDateVisibility();
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeStatusModal(evt) {
        if (evt && evt.target && evt.currentTarget && evt.target !== evt.currentTarget) {
            return;
        }
        const modal = document.getElementById('taskStatusModal');
        if (modal) modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function updatePostponeDateVisibility() {
        const checked = document.querySelector('input[name="status"]:checked');
        const val = checked ? checked.value : null;
        const postponeField = document.getElementById('postponeDateField');
        const postponeInput = document.getElementById('postponed_until');

        if (!postponeField || !postponeInput) return;

        if (val === 'postponed') {
            postponeField.style.display = 'block';
            postponeInput.required = true;
        } else {
            postponeField.style.display = 'none';
            postponeInput.required = false;
        }
    }

    function handleRemarkInput() {
        const remark = document.getElementById('remark');
        const cc = document.getElementById('charCount');
        if (!remark || !cc) return;

        const len = remark.value.length;
        cc.textContent = len;
        cc.style.color = len > 1900 ? '#DE350B' : '#6B778C';
    }

    // ============================
    // FILE UPLOAD
    // ============================
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleFileSelect(e) {
        handleFiles(e.target.files);
    }

    function handleFiles(fileList) {
        Array.from(fileList).forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                showToast(`"${file.name}" is too large (max 10MB)`, 'error');
                return;
            }
            selectedFiles.push(file);
            displayFilePreview(file);
        });
    }

    function displayFilePreview(file) {
        const container = document.getElementById('filePreview');
        if (!container) return;

        container.style.display = 'grid';

        const item = document.createElement('div');
        item.className = 'file-preview-item';
        item.dataset.fileName = file.name;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                item.innerHTML = `
                    <img src="${ev.target.result}" alt="${file.name}" class="file-preview-image">
                    <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                        </svg>
                    </button>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            item.innerHTML = `
                <div class="file-preview-file">
                    <svg class="file-preview-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        <path d="M13 2v7h7"/>
                    </svg>
                    <div class="file-preview-name">${file.name}</div>
                </div>
                <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                    </svg>
                </button>
            `;
        }

        container.appendChild(item);
    }

    function removeFile(fileName) {
        selectedFiles = selectedFiles.filter(f => f.name !== fileName);
        const item = document.querySelector(`.file-preview-item[data-file-name="${fileName}"]`);
        if (item) item.remove();

        if (selectedFiles.length === 0) {
            const container = document.getElementById('filePreview');
            if (container) container.style.display = 'none';
        }
    }

    // ============================
    // SUBMIT MODAL
    // ============================
    function submitTaskStatus(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const prevHTML = submitBtn.innerHTML;
        const formEl = document.getElementById('taskStatusForm');
        const taskId = document.getElementById('modalTaskId').value;
        const subtaskId = document.getElementById('modalSubtaskId').value;
        const actionValue = document.getElementById('modalAction').value;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" class="spinning">
                <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" fill="none" opacity="0.25"/>
                <path d="M8 1a7 7 0 017 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
            </svg>
            <span>Processing...</span>
        `;

        const fd = new FormData(formEl);
        selectedFiles.forEach(file => fd.append('attachments[]', file));

        let url = '';
        if (actionValue === 'remark') {
            url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/remark`;
        } else if (subtaskId) {
            url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/complete-final`;
        } else {
            url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/status`;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Request failed');
            }

            closeStatusModal({ target: null, currentTarget: null });

            if (!data.task_status) {
                showToast(data.message || 'Remark added successfully', 'success');
            } else {
                applyTaskStateFromServer(taskId, {
                    subtask: { id: '__modal__', completed: true },
                    completed_subtasks_count: data.completed_subtasks_count ?? 0,
                    subtasks_count: data.subtasks_count ?? 0,
                    task_status: data.task_status,
                    task_status_label: data.task_status_label,
                    task_status_bg: data.task_status_bg,
                    task_status_color: data.task_status_color
                });

                if (data.task_status === 'done') {
                    const card = document.querySelector(`.pro-task-card[data-task-id="${taskId}"]`);
                    if (card) {
                        card.querySelectorAll('.pro-subtask-item').forEach(r => {
                            r.classList.add('is-completed');
                            const cb = r.querySelector('.pro-subtask-checkbox');
                            if (cb) cb.checked = true;
                        });
                    }
                }

                const statusMessages = {
                    'done': 'Task marked done ✅',
                    'blocked': 'Task marked blocked',
                    'postponed': 'Task postponed',
                    'in-progress': 'Task set In Progress',
                    'todo': 'Task reopened',
                    'review': 'Task moved to Review'
                };
                showToast(statusMessages[data.task_status] || 'Task updated', data.task_status === 'blocked' ? 'error' : 'success');
            }

            submitBtn.disabled = false;
            submitBtn.innerHTML = prevHTML;
            const filePreview = document.getElementById('filePreview');
            if (filePreview) {
                filePreview.innerHTML = '';
                filePreview.style.display = 'none';
            }
            selectedFiles = [];
        })
        .catch(err => {
            console.error(err);
            showToast('Error updating task', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = prevHTML;
        });
    }

    // ============================
    // HELPER FUNCTIONS
    // ============================
    function refreshTasks() { window.location.reload(); }
    function openFilters() { console.log('openFilters()'); }
    function openSettings() { console.log('openSettings()'); }
    function openMenu() { console.log('openMenu()'); }
    function createTask() { console.log('createTask()'); }
    function openTaskDrawer(taskId) { console.log('openTaskDrawer()', taskId); }

    function handleKeyCommands(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshTasks();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            openFilters();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            createTask();
        }
        if (e.key === 'Escape') {
            closeStatusModal({ target: null, currentTarget: null });
        }
    }

    // ============================
    // INITIALIZATION
    // ============================
    document.addEventListener('DOMContentLoaded', function () {
        const navScroll = document.querySelector('.pm-nav-scroll');
        if (navScroll) {
            const activeItem = navScroll.querySelector('.pm-nav-item--active');
            if (activeItem) {
                activeItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }

        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', updatePostponeDateVisibility);
        });

        const remark = document.getElementById('remark');
        if (remark) {
            remark.addEventListener('input', handleRemarkInput);
        }

        const uploadArea = document.getElementById('uploadArea');
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evtName => {
                uploadArea.addEventListener(evtName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(evtName => {
                uploadArea.addEventListener(evtName, () => {
                    uploadArea.classList.add('drag-over');
                }, false);
            });

            ['dragleave', 'drop'].forEach(evtName => {
                uploadArea.addEventListener(evtName, () => {
                    uploadArea.classList.remove('drag-over');
                }, false);
            });

            uploadArea.addEventListener('drop', function (e) {
                const files = e.dataTransfer.files;
                handleFiles(files);
            }, false);
        }

        document.addEventListener('keydown', handleKeyCommands);

        recalcGlobalCompletion();
    });

    // ============================
    // EXPOSE TO WINDOW
    // ============================
    window.subtaskRowClick = subtaskRowClick;
    window.toggleSubtasksExpand = toggleSubtasksExpand;
    window.openStatusModal = openStatusModal;
    window.closeStatusModal = closeStatusModal;
    window.submitTaskStatus = submitTaskStatus;
    window.handleFileSelect = handleFileSelect;
    window.removeFile = removeFile;
    window.refreshTasks = refreshTasks;
    window.openFilters = openFilters;
    window.openSettings = openSettings;
    window.openMenu = openMenu;
    window.createTask = createTask;
    window.openTaskDrawer = openTaskDrawer;
    window.showToast = showToast;
    window.recalcGlobalCompletion = recalcGlobalCompletion;

    // Add spinner / drag-over / preview styles
    const spinStyle = document.createElement('style');
    spinStyle.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spinning {
            animation: spin 1s linear infinite;
        }
        .drag-over {
            outline: 2px dashed #0052CC;
            outline-offset: 4px;
            background: rgba(0,82,204,.05);
        }
        .file-preview-image {
            max-width: 64px;
            max-height: 64px;
            border-radius: 4px;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .file-preview-file {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            background:#F4F5F7;
            color:#172B4D;
            border-radius:4px;
            padding:6px 8px;
        }
        .file-preview-icon {
            width:16px;
            height:16px;
        }
        .file-preview-remove {
            background:transparent;
            border:none;
            cursor:pointer;
            color:#6B778C;
            margin-left:4px;
        }
        #filePreview {
            display:none;
            grid-template-columns:repeat(auto-fill,minmax(80px,1fr));
            gap:8px;
            margin-top:8px;
        }
    `;
    document.head.appendChild(spinStyle);

    console.log('✅ Task management system initialized');
})();
</script>
