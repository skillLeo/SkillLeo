{{-- resources/views/tenant/projects/timeline-content.blade.php --}}

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
    <!--<!-- Timeline Header (Dates) -->
    <div class="timeline-header">
        <div class="timeline-tasks-column">
            <span style="font-weight: var(--fw-semibold); color: var(--text-heading);">Tasks</span>
        </div>
        <div class="timeline-dates-grid">
            @php
                $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            @endphp
            @foreach($weeks as $week)
                <div class="timeline-date-header">
                    <span class="timeline-week">{{ $week }}</span>
                    <span class="timeline-dates">Jan {{ ($loop->index * 7) + 1 }}-{{ ($loop->index * 7) + 7 }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Timeline Body -->
    <div class="timeline-body">
        <!-- Epic Group -->
        <div class="timeline-group">
            <div class="timeline-group-header">
                <button class="timeline-group-toggle active" onclick="toggleTimelineGroup('epic-auth')">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <i class="fas fa-flag" style="color: #8b5cf6;"></i>
                <span class="timeline-group-title">User Authentication</span>
                <span class="timeline-group-count">8 tasks</span>
            </div>
            
            <div class="timeline-group-content active" id="epic-auth">
                @for($i = 1; $i <= 5; $i++)
                    @php
                        $startWeek = rand(0, 2);
                        $duration = rand(1, 2);
                        $progress = rand(30, 100);
                    @endphp
                    <div class="timeline-row">
                        <div class="timeline-task-info">
                            <div class="timeline-task-details">
                                <i class="fas fa-check-square" style="color: #3b82f6; font-size: var(--ic-sm);"></i>
                                <span class="timeline-task-key">PROJ-50{{ $i }}</span>
                                <span class="timeline-task-title">{{ ['Setup OAuth flow', 'Design login UI', 'API integration', 'Add validation', 'Write tests'][$i - 1] }}</span>
                            </div>
                            <div class="timeline-task-meta">
                                <img src="https://ui-avatars.com/api/?name={{ ['Hassan+M', 'Ali+K', 'Sara+A'][$i % 3] }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                                     alt="Avatar" 
                                     class="timeline-task-avatar">
                            </div>
                        </div>
                        
                        <div class="timeline-bars-container">
                            <div class="timeline-bar" 
                                 style="grid-column: {{ $startWeek + 1 }} / span {{ $duration }};">
                                <div class="timeline-bar-inner" style="width: {{ $progress }}%;">
                                    <span class="timeline-bar-label">{{ $progress }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Epic Group 2 -->
        <div class="timeline-group">
            <div class="timeline-group-header">
                <button class="timeline-group-toggle active" onclick="toggleTimelineGroup('epic-payment')">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <i class="fas fa-flag" style="color: #10b981;"></i>
                <span class="timeline-group-title">Payment Integration</span>
                <span class="timeline-group-count">6 tasks</span>
            </div>
            
            <div class="timeline-group-content active" id="epic-payment">
                @for($i = 1; $i <= 6; $i++)
                    @php
                        $startWeek = rand(1, 3);
                        $duration = rand(1, 2);
                        $progress = rand(20, 90);
                    @endphp
                    <div class="timeline-row">
                        <div class="timeline-task-info">
                            <div class="timeline-task-details">
                                <i class="fas fa-check-square" style="color: #3b82f6; font-size: var(--ic-sm);"></i>
                                <span class="timeline-task-key">PROJ-60{{ $i }}</span>
                                <span class="timeline-task-title">{{ ['Stripe setup', 'Payment form', 'Webhook handler', 'Invoice generation', 'Receipt email', 'Payment history'][$i - 1] }}</span>
                            </div>
                            <div class="timeline-task-meta">
                                <img src="https://ui-avatars.com/api/?name={{ ['Hassan+M', 'Ali+K', 'Sara+A'][$i % 3] }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                                     alt="Avatar" 
                                     class="timeline-task-avatar">
                            </div>
                        </div>
                        
                        <div class="timeline-bars-container">
                            <div class="timeline-bar" 
                                 style="grid-column: {{ $startWeek + 1 }} / span {{ $duration }};">
                                <div class="timeline-bar-inner" style="width: {{ $progress }}%;">
                                    <span class="timeline-bar-label">{{ $progress }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Unassigned Tasks -->
        <div class="timeline-group">
            <div class="timeline-group-header">
                <button class="timeline-group-toggle active" onclick="toggleTimelineGroup('unassigned')">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <i class="fas fa-inbox" style="color: var(--text-muted);"></i>
                <span class="timeline-group-title">Unassigned</span>
                <span class="timeline-group-count">3 tasks</span>
            </div>
            
            <div class="timeline-group-content active" id="unassigned">
                @for($i = 1; $i <= 3; $i++)
                    @php
                        $startWeek = rand(0, 2);
                        $duration = 1;
                        $progress = 0;
                    @endphp
                    <div class="timeline-row">
                        <div class="timeline-task-info">
                            <div class="timeline-task-details">
                                <i class="fas fa-check-square" style="color: #3b82f6; font-size: var(--ic-sm);"></i>
                                <span class="timeline-task-key">PROJ-70{{ $i }}</span>
                                <span class="timeline-task-title">{{ ['Update docs', 'Code review', 'Deploy staging'][$i - 1] }}</span>
                            </div>
                            <div class="timeline-task-meta">
                                <button class="backlog-assign-btn" title="Assign">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="timeline-bars-container">
                            <div class="timeline-bar" 
                                 style="grid-column: {{ $startWeek + 1 }} / span {{ $duration }};">
                                <div class="timeline-bar-inner timeline-bar-unstarted">
                                    <span class="timeline-bar-label">Not Started</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
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
    /* ===================================== 
       TIMELINE/GANTT VIEW STYLES
    ===================================== */

    /* Toolbar */
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

    .timeline-body::-webkit-scrollbar {
        width: 8px;
    }

    .timeline-body::-webkit-scrollbar-track {
        background: var(--bg);
    }

    .timeline-body::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 4px;
    }

    /* Timeline Group */
    .timeline-group {
        border-bottom: 1px solid var(--border);
    }

    .timeline-group:last-child {
        border-bottom: none;
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

    .timeline-group-toggle i {
        font-size: var(--ic-sm);
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

    .timeline-row:last-child {
        border-bottom: none;
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

    .timeline-task-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .timeline-task-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--card);
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
        position: relative;
        overflow: hidden;
    }

    .timeline-bar-inner::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 2s infinite;
    }

    .timeline-bar-inner:hover {
        transform: scaleY(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .timeline-bar-unstarted {
        background: var(--border) !important;
    }

    .timeline-bar-label {
        font-size: var(--fs-micro);
        font-weight: var(--fw-bold);
        color: white;
        position: relative;
        z-index: 1;
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

    /* Responsive */
    @media (max-width: 1200px) {
        .timeline-header,
        .timeline-row {
            grid-template-columns: 300px 1fr;
        }

        .timeline-dates-grid {
            grid-template-columns: repeat(4, minmax(100px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .timeline-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .timeline-header,
        .timeline-row {
            grid-template-columns: 200px 1fr;
        }

        .timeline-task-details {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }

        .timeline-legend {
            flex-wrap: wrap;
            gap: 12px;
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
        // TODO: Implement zoom functionality
    }

    function zoomOut() {
        console.log('Zoom Out');
        // TODO: Implement zoom functionality
    }
</script>