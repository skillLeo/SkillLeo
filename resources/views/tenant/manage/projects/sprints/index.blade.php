{{-- resources/views/tenant/projects/sprints/index.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Sprints</span>
</div>

<!-- Page Header -->
<div class="sprints-page-header">
    <div class="sprints-header-left">
        <h1 class="project-page-title">Sprints</h1>
        <p class="project-page-subtitle">Plan, track, and manage agile sprints across your projects</p>
    </div>
    <div class="sprints-header-right">
        <button class="project-btn project-btn-secondary" onclick="viewReports()">
            <i class="fas fa-chart-line"></i>
            <span>Sprint Reports</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="openCreateSprintModal()">
            <i class="fas fa-plus"></i>
            <span>Create Sprint</span>
        </button>
    </div>
</div>

<!-- Sprint Stats -->
<div class="sprint-stats-grid">
    <div class="sprint-stat-card">
        <div class="sprint-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-play-circle"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">1</div>
            <div class="sprint-stat-label">Active Sprint</div>
        </div>
    </div>

    <div class="sprint-stat-card">
        <div class="sprint-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">12</div>
            <div class="sprint-stat-label">Total Sprints</div>
        </div>
    </div>

    <div class="sprint-stat-card">
        <div class="sprint-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">78%</div>
            <div class="sprint-stat-label">Avg. Completion</div>
        </div>
    </div>

    <div class="sprint-stat-card">
        <div class="sprint-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div class="sprint-stat-content">
            <div class="sprint-stat-value">42</div>
            <div class="sprint-stat-label">Avg. Velocity</div>
        </div>
    </div>
</div>

<!-- Active Sprint Section -->
<div class="sprint-section active-sprint-section">
    <div class="sprint-section-header">
        <div class="sprint-section-title-group">
            <h2 class="sprint-section-title">
                <i class="fas fa-play-circle" style="color: #10b981;"></i>
                Active Sprint
            </h2>
            <span class="sprint-badge sprint-badge-active">In Progress</span>
        </div>
        <div class="sprint-section-actions">
            <button class="project-btn project-btn-ghost" onclick="viewSprintBoard()">
                <i class="fas fa-th"></i>
                <span>View Board</span>
            </button>
            <button class="project-btn project-btn-secondary" onclick="completeSprint()">
                <i class="fas fa-check-circle"></i>
                <span>Complete Sprint</span>
            </button>
        </div>
    </div>

    <div class="active-sprint-card">
        <div class="active-sprint-header">
            <div class="active-sprint-info">
                <h3 class="active-sprint-name">Sprint 5 - Authentication & User Management</h3>
                <div class="active-sprint-meta">
                    <span class="sprint-meta-item">
                        <i class="fas fa-calendar"></i>
                        Jan 15 - Jan 29, 2025
                    </span>
                    <span class="sprint-meta-item">
                        <i class="fas fa-clock"></i>
                        5 days remaining
                    </span>
                    <span class="sprint-meta-item">
                        <i class="fas fa-tasks"></i>
                        34 issues
                    </span>
                    <span class="sprint-meta-item">
                        <i class="fas fa-chart-line"></i>
                        52 story points
                    </span>
                </div>
            </div>
        </div>

        <!-- Sprint Progress -->
        <div class="active-sprint-progress">
            <div class="progress-header">
                <span class="progress-label">Sprint Progress</span>
                <span class="progress-value">62%</span>
            </div>
            <div class="progress-bar-large">
                <div class="progress-fill-large" style="width: 62%;"></div>
            </div>
            <div class="progress-stats">
                <div class="progress-stat">
                    <span class="stat-count">21</span>
                    <span class="stat-label">Done</span>
                </div>
                <div class="progress-stat">
                    <span class="stat-count">8</span>
                    <span class="stat-label">In Progress</span>
                </div>
                <div class="progress-stat">
                    <span class="stat-count">5</span>
                    <span class="stat-label">To Do</span>
                </div>
            </div>
        </div>

        <!-- Sprint Burndown Chart Preview -->
        <div class="sprint-burndown-preview">
            <div class="burndown-header">
                <span class="burndown-title">Burndown Chart</span>
                <button class="project-btn project-btn-ghost project-btn-sm" onclick="viewFullBurndown()">
                    <span>View Full Chart</span>
                    <i class="fas fa-external-link-alt"></i>
                </button>
            </div>
            <div class="burndown-chart-mini">
                <svg width="100%" height="120" viewBox="0 0 400 120" preserveAspectRatio="none">
                    <!-- Grid lines -->
                    <line x1="0" y1="30" x2="400" y2="30" stroke="var(--border)" stroke-width="1"/>
                    <line x1="0" y1="60" x2="400" y2="60" stroke="var(--border)" stroke-width="1"/>
                    <line x1="0" y1="90" x2="400" y2="90" stroke="var(--border)" stroke-width="1"/>
                    
                    <!-- Ideal line -->
                    <line x1="0" y1="10" x2="400" y2="110" stroke="#94a3b8" stroke-width="2" stroke-dasharray="5,5"/>
                    
                    <!-- Actual line -->
                    <polyline fill="none" stroke="#10b981" stroke-width="3"
                        points="0,10 50,25 100,35 150,50 200,60 250,70 300,85 350,95 400,100"/>
                </svg>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="sprint-quick-actions">
            <button class="quick-action-btn" onclick="addIssueToSprint()">
                <i class="fas fa-plus-circle"></i>
                <span>Add Issues</span>
            </button>
            <button class="quick-action-btn" onclick="viewSprintBacklog()">
                <i class="fas fa-inbox"></i>
                <span>View Backlog</span>
            </button>
            <button class="quick-action-btn" onclick="viewSprintReports()">
                <i class="fas fa-chart-bar"></i>
                <span>Sprint Reports</span>
            </button>
        </div>
    </div>
</div>

<!-- Upcoming Sprints -->
<div class="sprint-section">
    <div class="sprint-section-header">
        <div class="sprint-section-title-group">
            <h2 class="sprint-section-title">
                <i class="fas fa-calendar-plus"></i>
                Upcoming Sprints
            </h2>
        </div>
    </div>

    <div class="sprints-grid">
        @for($i = 6; $i <= 7; $i++)
            <div class="sprint-card sprint-card-future">
                <div class="sprint-card-header">
                    <div class="sprint-card-title-group">
                        <h4 class="sprint-card-name">Sprint {{ $i }}</h4>
                        <span class="sprint-badge sprint-badge-future">Planned</span>
                    </div>
                    <button class="sprint-card-menu" onclick="openSprintMenu({{ $i }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>

                <div class="sprint-card-dates">
                    <i class="fas fa-calendar"></i>
                    <span>{{ now()->addDays($i * 14)->format('M d') }} - {{ now()->addDays($i * 14 + 13)->format('M d, Y') }}</span>
                </div>

                <div class="sprint-card-stats">
                    <div class="sprint-card-stat">
                        <i class="fas fa-tasks"></i>
                        <span>{{ rand(20, 40) }} issues planned</span>
                    </div>
                    <div class="sprint-card-stat">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ rand(30, 60) }} story points</span>
                    </div>
                </div>

                <div class="sprint-card-actions">
                    <button class="project-btn project-btn-ghost" style="width: 100%; justify-content: center;" onclick="startSprint({{ $i }})">
                        <i class="fas fa-play"></i>
                        <span>Start Sprint</span>
                    </button>
                </div>
            </div>
        @endfor
    </div>
</div>

<!-- Completed Sprints -->
<div class="sprint-section">
    <div class="sprint-section-header">
        <div class="sprint-section-title-group">
            <h2 class="sprint-section-title">
                <i class="fas fa-check-circle"></i>
                Completed Sprints
            </h2>
        </div>
        <button class="project-btn project-btn-ghost" onclick="toggleCompletedSprints()">
            <span id="toggleText">Show All</span>
            <i class="fas fa-chevron-down" id="toggleIcon"></i>
        </button>
    </div>

    <div class="completed-sprints-container" id="completedSprintsContainer">
        @for($i = 4; $i >= 1; $i--)
            <div class="sprint-card sprint-card-completed">
                <div class="sprint-card-header">
                    <div class="sprint-card-title-group">
                        <h4 class="sprint-card-name">Sprint {{ $i }}</h4>
                        <span class="sprint-badge sprint-badge-completed">Completed</span>
                    </div>
                    <button class="sprint-card-menu" onclick="openSprintMenu({{ $i }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>

                <div class="sprint-card-dates">
                    <i class="fas fa-calendar"></i>
                    <span>{{ now()->subDays((5 - $i) * 14 + 14)->format('M d') }} - {{ now()->subDays((5 - $i) * 14)->format('M d, Y') }}</span>
                </div>

                <div class="sprint-completion">
                    <div class="completion-bar">
                        @php $completion = rand(70, 95); @endphp
                        <div class="completion-fill" style="width: {{ $completion }}%;"></div>
                    </div>
                    <span class="completion-text">{{ $completion }}% completed</span>
                </div>

                <div class="sprint-card-stats">
                    <div class="sprint-card-stat">
                        <span class="stat-number">{{ rand(25, 35) }}</span>
                        <span class="stat-label">Issues</span>
                    </div>
                    <div class="sprint-card-stat">
                        <span class="stat-number">{{ rand(35, 50) }}</span>
                        <span class="stat-label">Points</span>
                    </div>
                    <div class="sprint-card-stat">
                        <span class="stat-number">{{ rand(30, 45) }}</span>
                        <span class="stat-label">Velocity</span>
                    </div>
                </div>

                <div class="sprint-card-actions">
                    <button class="project-btn project-btn-ghost" onclick="viewSprintReport({{ $i }})">
                        <i class="fas fa-chart-bar"></i>
                        <span>View Report</span>
                    </button>
                </div>
            </div>
        @endfor
    </div>
</div>

<style>
    /* ===================================== 
       SPRINTS PAGE STYLES
    ===================================== */

    /* Page Header */
    .sprints-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .sprints-header-left {
        flex: 1;
        min-width: 300px;
    }

    .sprints-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Sprint Stats Grid */
    .sprint-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .sprint-stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .sprint-stat-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .sprint-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .sprint-stat-content {
        flex: 1;
    }

    .sprint-stat-value {
        font-size: 28px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .sprint-stat-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    /* Sprint Section */
    .sprint-section {
        margin-bottom: 32px;
    }

    .sprint-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .sprint-section-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sprint-section-title {
        font-size: var(--fs-h2);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sprint-section-actions {
        display: flex;
        gap: 8px;
    }

    /* Sprint Badges */
    .sprint-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .sprint-badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .sprint-badge-future {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .sprint-badge-completed {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    /* Active Sprint Card */
    .active-sprint-section {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%);
        border: 2px solid rgba(16, 185, 129, 0.2);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 32px;
    }

    .active-sprint-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .active-sprint-header {
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }

    .active-sprint-name {
        font-size: var(--fs-h2);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 12px 0;
    }

    .active-sprint-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .sprint-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .sprint-meta-item i {
        font-size: var(--ic-sm);
    }

    /* Sprint Progress */
    .active-sprint-progress {
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .progress-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .progress-value {
        font-size: var(--fs-h3);
        font-weight: var(--fw-bold);
        color: #10b981;
    }

    .progress-bar-large {
        height: 12px;
        background: var(--bg);
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .progress-fill-large {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 6px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .progress-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .progress-stat {
        text-align: center;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
    }

    .progress-stat .stat-count {
        display: block;
        font-size: 24px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .progress-stat .stat-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Burndown Preview */
    .sprint-burndown-preview {
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }

    .burndown-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .burndown-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .burndown-chart-mini {
        background: var(--bg);
        border-radius: 8px;
        padding: 16px;
    }

    /* Quick Actions */
    .sprint-quick-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--border);
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 20px;
        background: var(--card);
        border: none;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .quick-action-btn:hover {
        background: var(--accent-light);
        color: var(--accent);
    }

    .quick-action-btn i {
        font-size: 24px;
    }

    /* Sprints Grid */
    .sprints-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    /* Sprint Card */
    .sprint-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .sprint-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .sprint-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .sprint-card-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .sprint-card-name {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .sprint-card-menu {
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

    .sprint-card-menu:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    .sprint-card-dates {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding: 10px 12px;
        background: var(--bg);
        border-radius: 8px;
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .sprint-card-dates i {
        font-size: var(--ic-sm);
    }

    .sprint-card-stats {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .sprint-card-stat {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .sprint-card-stat i {
        font-size: var(--ic-sm);
        color: var(--text-muted);
        width: 16px;
    }

    .sprint-card-stat .stat-number {
        font-weight: var(--fw-bold);
        color: var(--text-heading);
    }

    .sprint-card-stat .stat-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .sprint-card-actions {
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    /* Sprint Completion */
    .sprint-completion {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .completion-bar {
        flex: 1;
        height: 8px;
        background: var(--bg);
        border-radius: 4px;
        overflow: hidden;
    }

    .completion-fill {
        height: 100%;
        background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
        border-radius: 4px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .completion-text {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: #8b5cf6;
        white-space: nowrap;
    }

    /* Completed Sprints Container */
    .completed-sprints-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .completed-sprints-container.expanded {
        max-height: 3000px;
    }

    /* Sprint Card Variants */
    .sprint-card-future {
        border-left: 4px solid #3b82f6;
    }

    .sprint-card-completed {
        border-left: 4px solid #8b5cf6;
        opacity: 0.9;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sprints-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .sprint-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .sprints-grid,
        .completed-sprints-container {
            grid-template-columns: 1fr;
        }

        .sprint-quick-actions {
            grid-template-columns: 1fr;
        }

        .progress-stats {
            grid-template-columns: 1fr;
        }

        .active-sprint-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<script>
    // ===================================== 
    // SPRINTS PAGE FUNCTIONALITY
    // ===================================== 

    function viewReports() {
        console.log('View Sprint Reports');
        window.location.href = '{{ route("tenant.manage.projects.reports.index", $username) }}';
    }

    function openCreateSprintModal() {
        console.log('Create Sprint Modal');
        alert('Create Sprint Modal - Coming Soon!');
    }

    function viewSprintBoard() {
        console.log('View Sprint Board');
    }

    function completeSprint() {
        console.log('Complete Sprint');
        if (confirm('Are you sure you want to complete this sprint?')) {
            alert('Sprint completed successfully!');
        }
    }

    function addIssueToSprint() {
        console.log('Add Issues to Sprint');
        alert('Add Issues Modal - Coming Soon!');
    }

    function viewSprintBacklog() {
        console.log('View Sprint Backlog');
    }

    function viewSprintReports() {
        console.log('View Sprint Reports');
    }

    function viewFullBurndown() {
        console.log('View Full Burndown Chart');
    }

    function startSprint(sprintNumber) {
        console.log('Start Sprint', sprintNumber);
        if (confirm(`Start Sprint ${sprintNumber}?`)) {
            alert(`Sprint ${sprintNumber} started!`);
        }
    }

    function openSprintMenu(sprintNumber) {
        console.log('Open Sprint Menu', sprintNumber);
    }

    function viewSprintReport(sprintNumber) {
        console.log('View Sprint Report', sprintNumber);
    }

    function toggleCompletedSprints() {
        const container = document.getElementById('completedSprintsContainer');
        const toggleText = document.getElementById('toggleText');
        const toggleIcon = document.getElementById('toggleIcon');
        
        container.classList.toggle('expanded');
        
        if (container.classList.contains('expanded')) {
            toggleText.textContent = 'Show Less';
            toggleIcon.style.transform = 'rotate(180deg)';
        } else {
            toggleText.textContent = 'Show All';
            toggleIcon.style.transform = 'rotate(0deg)';
        }
    }

    console.log('✅ Sprints Page Initialized');
</script>

@endsection