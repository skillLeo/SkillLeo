{{-- resources/views/tenant/projects/components/issue-card.blade.php --}}
@php
    $typeIcons = [
        'story' => ['icon' => 'bookmark', 'color' => '#10b981'],
        'task' => ['icon' => 'check-square', 'color' => '#3b82f6'],
        'bug' => ['icon' => 'bug', 'color' => '#ef4444'],
        'spike' => ['icon' => 'lightbulb', 'color' => '#f59e0b'],
    ];
    
    $priorityColors = [
        'highest' => '#ef4444',
        'high' => '#f59e0b',
        'medium' => '#3b82f6',
        'low' => '#10b981',
        'lowest' => '#6b7280',
    ];
    
    $type = $typeIcons[$issue['type']] ?? $typeIcons['task'];
    $priority = $priorityColors[$issue['priority']] ?? $priorityColors['medium'];
@endphp

<div class="issue-card" draggable="true" onclick="openIssueDetail('{{ $issue['key'] }}')">
    <!-- Issue Header -->
    <div class="issue-card-header">
        <span class="issue-card-key">{{ $issue['key'] }}</span>
        <div class="issue-card-icons">
            <i class="fas fa-{{ $type['icon'] }}" style="color: {{ $type['color'] }};" title="{{ ucfirst($issue['type']) }}"></i>
        </div>
    </div>

    <!-- Issue Title -->
    <h4 class="issue-card-title">{{ $issue['title'] }}</h4>

    <!-- Issue Footer -->
    <div class="issue-card-footer">
        <div class="issue-card-footer-left">
            <!-- Priority Indicator -->
            <div class="issue-priority-dot" style="background: {{ $priority }};" title="{{ ucfirst($issue['priority']) }} Priority"></div>
            
            <!-- Story<!-- Story Points -->
            @if(isset($issue['story_points']))
            <span class="issue-story-points">{{ $issue['story_points'] }}</span>
        @endif
    </div>

    <!-- Assignee Avatar -->
    @if(isset($issue['assignee']))
        <img src="{{ $issue['assignee']['avatar'] }}" 
             alt="{{ $issue['assignee']['name'] }}" 
             class="issue-card-avatar"
             title="{{ $issue['assignee']['name'] }}">
    @endif
</div>
</div>

<style>
/* ===================================== 
   ISSUE CARD COMPONENT STYLES
===================================== */

.issue-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.issue-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    border-color: var(--accent);
}

.issue-card:active {
    transform: scale(0.98);
}

/* Dragging State */
.issue-card.dragging {
    opacity: 0.5;
    transform: rotate(2deg);
}

/* Issue Header */
.issue-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.issue-card-key {
    font-size: var(--fs-micro);
    font-weight: var(--fw-semibold);
    color: var(--text-muted);
    font-family: monospace;
}

.issue-card-icons {
    display: flex;
    gap: 6px;
    font-size: var(--ic-sm);
}

/* Issue Title */
.issue-card-title {
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    color: var(--text-heading);
    margin: 0 0 12px 0;
    line-height: var(--lh-tight);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Issue Footer */
.issue-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.issue-card-footer-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Priority Dot */
.issue-priority-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Story Points */
.issue-story-points {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: var(--fs-micro);
    font-weight: var(--fw-bold);
    color: var(--text-muted);
}

/* Assignee Avatar */
.issue-card-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid var(--card);
    flex-shrink: 0;
}
</style>

<script>
function openIssueDetail(key) {
    console.log('Opening issue:', key);
    alert('Issue Detail Modal: ' + key);
    // TODO: Implement issue detail modal
}
</script>