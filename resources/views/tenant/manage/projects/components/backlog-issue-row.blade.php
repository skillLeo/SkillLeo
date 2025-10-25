{{-- resources/views/tenant/projects/components/backlog-issue-row.blade.php --}}
@php
    $typeIcons = [
        'story' => ['icon' => 'bookmark', 'color' => '#10b981'],
        'task' => ['icon' => 'check-square', 'color' => '#3b82f6'],
        'bug' => ['icon' => 'bug', 'color' => '#ef4444'],
        'spike' => ['icon' => 'lightbulb', 'color' => '#f59e0b'],
    ];
    
    $priorityIcons = [
        'highest' => ['icon' => 'angle-double-up', 'color' => '#ef4444'],
        'high' => ['icon' => 'angle-up', 'color' => '#f59e0b'],
        'medium' => ['icon' => 'minus', 'color' => '#3b82f6'],
        'low' => ['icon' => 'angle-down', 'color' => '#10b981'],
        'lowest' => ['icon' => 'angle-double-down', 'color' => '#6b7280'],
    ];
    
    $type = $typeIcons[$issue['type']] ?? $typeIcons['task'];
    $priority = $priorityIcons[$issue['priority']] ?? $priorityIcons['medium'];
@endphp

<div class="backlog-issue-row {{ $issue['status'] === 'done' ? 'done' : '' }}" draggable="true">
    <!-- Drag Handle -->
    <div class="backlog-issue-drag-handle">
        <i class="fas fa-grip-vertical"></i>
    </div>

    <!-- Checkbox -->
    <div class="backlog-issue-checkbox">
        <input type="checkbox" {{ $issue['status'] === 'done' ? 'checked' : '' }}>
    </div>

    <!-- Type Icon -->
    <div class="backlog-issue-type">
        <i class="fas fa-{{ $type['icon'] }}" style="color: {{ $type['color'] }};" title="{{ ucfirst($issue['type']) }}"></i>
    </div>

    <!-- Issue Key -->
    <div class="backlog-issue-key">
        {{ $issue['key'] }}
    </div>

    <!-- Issue Title -->
    <div class="backlog-issue-title" onclick="openIssueDetail('{{ $issue['key'] }}')">
        {{ $issue['title'] }}
    </div>

    <!-- Priority -->
    <div class="backlog-issue-priority">
        <i class="fas fa-{{ $priority['icon'] }}" style="color: {{ $priority['color'] }};" title="{{ ucfirst($issue['priority']) }} Priority"></i>
    </div>

    <!-- Story Points -->
    <div class="backlog-issue-points">
        <span class="issue-story-points">{{ $issue['story_points'] }}</span>
    </div>

    <!-- Assignee -->
    <div class="backlog-issue-assignee">
        @if($issue['assignee'])
            <img src="{{ $issue['assignee']['avatar'] }}" 
                 alt="{{ $issue['assignee']['name'] }}" 
                 class="issue-card-avatar"
                 title="{{ $issue['assignee']['name'] }}">
        @else
            <button class="backlog-assign-btn" title="Assign">
                <i class="fas fa-user-plus"></i>
            </button>
        @endif
    </div>

    <!-- Actions -->
    <div class="backlog-issue-actions">
        <button class="backlog-issue-action-btn" title="More options">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </div>
</div>

<style>
    /* ===================================== 
       BACKLOG ISSUE ROW STYLES
    ===================================== */

    .backlog-issue-row {
        display: grid;
        grid-template-columns: 24px 32px 32px 100px 1fr 40px 60px 48px 40px;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 6px;
        transition: all 0.15s ease;
        cursor: grab;
    }

    .backlog-issue-row:hover {
        background: var(--bg);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .backlog-issue-row:active {
        cursor: grabbing;
    }

    .backlog-issue-row.done {
        opacity: 0.6;
    }

    .backlog-issue-row.done .backlog-issue-title {
        text-decoration: line-through;
    }

    /* Drag Handle */
    .backlog-issue-drag-handle {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-subtle);
        cursor: grab;
    }

    .backlog-issue-drag-handle:active {
        cursor: grabbing;
    }

    /* Checkbox */
    .backlog-issue-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Type Icon */
    .backlog-issue-type {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--ic-md);
    }

    /* Issue Key */
    .backlog-issue-key {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        font-family: monospace;
    }

    /* Issue Title */
    .backlog-issue-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-heading);
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .backlog-issue-title:hover {
        color: var(--accent);
        text-decoration: underline;
    }

    /* Priority */
    .backlog-issue-priority {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--ic-md);
    }

    /* Story Points */
    .backlog-issue-points {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Assignee */
    .backlog-issue-assignee {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .backlog-assign-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 50%;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .backlog-assign-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    /* Actions */
    .backlog-issue-actions {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .backlog-issue-action-btn {
        width: 28px;
        height: 28px;
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

    .backlog-issue-action-btn:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .backlog-issue-row {
            grid-template-columns: 24px 32px 32px 80px 1fr 60px 48px 40px;
            gap: 8px;
        }

        .backlog-issue-priority {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .backlog-issue-row {
            grid-template-columns: 24px 32px 1fr 48px 40px;
            padding: 10px;
        }

        .backlog-issue-type,
        .backlog-issue-key,
        .backlog-issue-points {
            display: none;
        }
    }
</style>