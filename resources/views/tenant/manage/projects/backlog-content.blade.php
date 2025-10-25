{{-- resources/views/tenant/projects/backlog-content.blade.php --}}

<!-- Backlog Toolbar -->
<div class="backlog-toolbar">
    <div class="backlog-toolbar-left">
        <div class="project-search-box" style="max-width: 350px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search backlog..." id="backlogSearch">
        </div>
        <select class="project-form-control project-select" style="width: auto;">
            <option value="">All Epics</option>
            <option value="epic-1">User Authentication</option>
            <option value="epic-2">Payment System</option>
            <option value="epic-3">Admin Dashboard</option>
        </select>
        <button class="project-btn project-btn-ghost">
            <i class="fas fa-filter"></i>
            <span>More Filters</span>
        </button>
    </div>
    <div class="backlog-toolbar-right">
        <button class="project-btn project-btn-secondary">
            <i class="fas fa-play-circle"></i>
            <span>Start Sprint</span>
        </button>
        <button class="project-btn project-btn-primary">
            <i class="fas fa-plus"></i>
            <span>Create Issue</span>
        </button>
    </div>
</div>

<!-- Sprint Section -->
<div class="backlog-section">
    <div class="backlog-section-header">
        <div class="backlog-section-header-left">
            <button class="backlog-section-toggle active" onclick="toggleBacklogSection('sprint-current')">
                <i class="fas fa-chevron-down"></i>
            </button>
            <h3 class="backlog-section-title">
                <i class="fas fa-play-circle" style="color: #10b981;"></i>
                Sprint 5 (Active)
            </h3>
            <span class="backlog-section-meta">Jan 15 - Jan 29 • 8 issues • 34 points</span>
        </div>
        <div class="backlog-section-actions">
            <button class="project-btn project-btn-ghost project-btn-sm">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </button>
            <button class="project-icon-btn">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
    </div>
    
    <div class="backlog-section-content active" id="sprint-current">
        <!-- Sprint Progress -->
        <div class="sprint-progress-card">
            <div class="sprint-progress-info">
                <div class="sprint-progress-stat">
                    <span class="sprint-progress-label">Completed</span>
                    <span class="sprint-progress-value">5 / 8</span>
                </div>
                <div class="sprint-progress-stat">
                    <span class="sprint-progress-label">Story Points</span>
                    <span class="sprint-progress-value">21 / 34</span>
                </div>
                <div class="sprint-progress-stat">
                    <span class="sprint-progress-label">Days Remaining</span>
                    <span class="sprint-progress-value">5 days</span>
                </div>
            </div>
            <div class="sprint-progress-bar">
                <div class="sprint-progress-fill" style="width: 62%;"></div>
            </div>
        </div>

        <!-- Issues List -->
        <div class="backlog-issues-list">
            @for($i = 1; $i <= 8; $i++)
                @include('tenant.manage.projects.components.backlog-issue-row', [
                    'issue' => [
                        'key' => 'PROJ-' . (500 + $i),
                        'title' => ['Implement user login flow', 'Design password reset UI', 'Add OAuth integration', 'Create user profile page', 'Build settings panel', 'Add email verification', 'Implement 2FA', 'Create onboarding flow'][$i - 1],
                        'type' => ['story', 'task', 'task', 'story', 'task', 'task', 'story', 'task'][$i - 1],
                        'priority' => ['high', 'medium', 'high', 'medium', 'low', 'medium', 'high', 'low'][$i - 1],
                        'status' => $i <= 5 ? 'done' : 'todo',
                        'assignee' => [
                            'name' => ['Hassan M', 'Ali K', 'Sara A'][$i % 3],
                            'avatar' => 'https://ui-avatars.com/api/?name=' . ['Hassan+M', 'Ali+K', 'Sara+A'][$i % 3] . '&background=' . ['667eea', 'f093fb', '4facfe'][$i % 3] . '&color=fff'
                        ],
                        'story_points' => [5, 3, 8, 5, 2, 3, 5, 3][$i - 1]
                    ]
                ])
            @endfor
        </div>
    </div>
</div>

<!-- Product Backlog Section -->
<div class="backlog-section">
    <div class="backlog-section-header">
        <div class="backlog-section-header-left">
            <button class="backlog-section-toggle active" onclick="toggleBacklogSection('product-backlog')">
                <i class="fas fa-chevron-down"></i>
            </button>
            <h3 class="backlog-section-title">
                <i class="fas fa-inbox"></i>
                Product Backlog
            </h3>
            <span class="backlog-section-meta">24 issues • 89 points</span>
        </div>
        <div class="backlog-section-actions">
            <button class="project-btn project-btn-ghost project-btn-sm">
                <i class="fas fa-sort"></i>
                <span>Sort</span>
            </button>
            <button class="project-icon-btn">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
    </div>
    
    <div class="backlog-section-content active" id="product-backlog">
        <!-- Epic Group -->
        <div class="backlog-epic-group">
            <div class="backlog-epic-header">
                <button class="backlog-epic-toggle active" onclick="toggleEpic('epic-1')">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="backlog-epic-info">
                    <i class="fas fa-flag" style="color: #8b5cf6;"></i>
                    <span class="backlog-epic-title">Epic: User Authentication System</span>
                    <span class="backlog-epic-progress">7/12 done</span>
                </div>
            </div>
            <div class="backlog-epic-content active" id="epic-1">
                <div class="backlog-issues-list">
                    @for($i = 1; $i <= 5; $i++)
                        @include('tenant.manage.projects.components.backlog-issue-row', [
                            'issue' => [
                                'key' => 'PROJ-' . (600 + $i),
                                'title' => ['Add social login', 'Implement remember me', 'Create forgot password flow', 'Add login analytics', 'Build session management'][$i - 1],
                                'type' => ['task', 'task', 'story', 'task', 'task'][$i - 1],
                                'priority' => ['medium', 'low', 'high', 'low', 'medium'][$i - 1],
                                'status' => 'todo',
                                'assignee' => null,
                                'story_points' => [3, 2, 5, 2, 3][$i - 1]
                            ]
                        ])
                    @endfor
                </div>
            </div>
        </div>

        <!-- Orphan Issues (No Epic) -->
        <div class="backlog-issues-list" style="margin-top: 16px;">
            @for($i = 1; $i <= 3; $i++)
                @include('tenant.manage.projects.components.backlog-issue-row', [
                    'issue' => [
                        'key' => 'PROJ-' . (700 + $i),
                        'title' => ['Update dependencies', 'Fix mobile layout bug', 'Improve page load speed'][$i - 1],
                        'type' => ['task', 'bug', 'task'][$i - 1],
                        'priority' => ['low', 'high', 'medium'][$i - 1],
                        'status' => 'todo',
                        'assignee' => null,
                        'story_points' => [1, 2, 3][$i - 1]
                    ]
                ])
            @endfor
        </div>
    </div>
</div>

<style>
    /* ===================================== 
       BACKLOG VIEW STYLES
    ===================================== */

    /* Toolbar */
    .backlog-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .backlog-toolbar-left,
    .backlog-toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Section */
    .backlog-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 16px;
        overflow: hidden;
    }

    .backlog-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: var(--bg);
        border-bottom: 1px solid var(--border);
    }

    .backlog-section-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .backlog-section-toggle {
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

    .backlog-section-toggle:hover {
        background: var(--card);
        color: var(--text-body);
    }

    .backlog-section-toggle i {
        font-size: var(--ic-sm);
        transition: transform 0.2s ease;
    }

    .backlog-section-toggle.active i {
        transform: rotate(90deg);
    }

    .backlog-section-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .backlog-section-meta {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .backlog-section-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .project-btn-sm {
        height: 32px;
        padding: 0 12px;
        font-size: var(--fs-subtle);
    }

    .backlog-section-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .backlog-section-content.active {
        max-height: 5000px;
        padding: 20px;
    }

    /* Sprint Progress Card */
    .sprint-progress-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .sprint-progress-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .sprint-progress-stat {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sprint-progress-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .sprint-progress-value {
        font-size: var(--fs-h3);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
    }

    .sprint-progress-bar {
        height: 8px;
        background: var(--card);
        border-radius: 4px;
        overflow: hidden;
    }

    .sprint-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 4px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Epic Group */
    .backlog-epic-group {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .backlog-epic-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .backlog-epic-header:hover {
        background: var(--card);
    }

    .backlog-epic-toggle {
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

    .backlog-epic-toggle.active {
        transform: rotate(90deg);
    }

    .backlog-epic-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    .backlog-epic-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .backlog-epic-progress {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        background: var(--card);
        padding: 2px 8px;
        border-radius: 10px;
    }

    .backlog-epic-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .backlog-epic-content.active {
        max-height: 3000px;
        padding: 0 16px 12px;
    }

    /* Issues List */
    .backlog-issues-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .backlog-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .backlog-toolbar-left,
        .backlog-toolbar-right {
            width: 100%;
        }

        .backlog-section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .sprint-progress-info {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function toggleBacklogSection(sectionId) {
        const content = document.getElementById(sectionId);
        const button = content.previousElementSibling.querySelector('.backlog-section-toggle');
        
        content.classList.toggle('active');
        button.classList.toggle('active');
    }

    function toggleEpic(epicId) {
        const content = document.getElementById(epicId);
        const button = content.previousElementSibling.querySelector('.backlog-epic-toggle');
        
        content.classList.toggle('active');
        button.classList.toggle('active');
    }
</script>