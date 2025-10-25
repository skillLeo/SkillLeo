{{-- resources/views/tenant/projects/sprints/planning.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.sprints.index', $username) }}" class="project-breadcrumb-item">
        Sprints
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Sprint Planning</span>
</div>

<!-- Planning Header -->
<div class="planning-header">
    <div class="planning-header-left">
        <h1 class="planning-title">Sprint Planning</h1>
        <p class="planning-subtitle">Plan your next sprint by selecting issues from the backlog</p>
    </div>
    <div class="planning-header-right">
        <button class="project-btn project-btn-secondary" onclick="alert('Cancel Planning')">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="startSprint()">
            <i class="fas fa-play"></i>
            <span>Start Sprint</span>
        </button>
    </div>
</div>

<!-- Sprint Configuration -->
<div class="sprint-config-card">
    <div class="sprint-config-row">
        <div class="sprint-config-item">
            <label class="sprint-config-label">Sprint Name</label>
            <input type="text" class="project-form-control" value="Sprint 6" placeholder="Enter sprint name">
        </div>
        <div class="sprint-config-item">
            <label class="sprint-config-label">Duration</label>
            <select class="project-form-control project-select">
                <option value="1">1 week</option>
                <option value="2" selected>2 weeks</option>
                <option value="3">3 weeks</option>
                <option value="4">4 weeks</option>
            </select>
        </div>
        <div class="sprint-config-item">
            <label class="sprint-config-label">Start Date</label>
            <input type="date" class="project-form-control" value="{{ now()->addDays(1)->format('Y-m-d') }}">
        </div>
        <div class="sprint-config-item">
            <label class="sprint-config-label">End Date</label>
            <input type="date" class="project-form-control" value="{{ now()->addDays(15)->format('Y-m-d') }}">
        </div>
    </div>

    <div class="sprint-config-row">
        <div class="sprint-config-item sprint-config-full">
            <label class="sprint-config-label">Sprint Goal</label>
            <textarea class="project-form-control project-textarea" rows="2" placeholder="What is the goal of this sprint?">Complete user authentication and profile management features</textarea>
        </div>
    </div>
</div>

<!-- Sprint Capacity -->
<div class="sprint-capacity-card">
    <div class="capacity-header">
        <h3 class="capacity-title">
            <i class="fas fa-tachometer-alt"></i>
            Sprint Capacity
        </h3>
        <button class="project-btn project-btn-ghost project-btn-sm" onclick="alert('Edit Capacity')">
            <i class="fas fa-edit"></i>
            <span>Edit</span>
        </button>
    </div>

    <div class="capacity-stats">
        <div class="capacity-stat">
            <div class="capacity-stat-label">Team Velocity</div>
            <div class="capacity-stat-value">42 <span class="capacity-unit">points/sprint</span></div>
        </div>
        <div class="capacity-stat">
            <div class="capacity-stat-label">Selected Points</div>
            <div class="capacity-stat-value capacity-selected">28 <span class="capacity-unit">points</span></div>
        </div>
        <div class="capacity-stat">
            <div class="capacity-stat-label">Remaining Capacity</div>
            <div class="capacity-stat-value capacity-remaining">14 <span class="capacity-unit">points</span></div>
        </div>
    </div>

    <div class="capacity-bar-container">
        <div class="capacity-bar">
            <div class="capacity-bar-fill" style="width: 67%;"></div>
        </div>
        <div class="capacity-bar-labels">
            <span>0</span>
            <span class="capacity-optimal">Optimal: 35-42</span>
            <span>50+</span>
        </div>
    </div>
</div>

<!-- Planning Board -->
<div class="planning-board">
    <!-- Backlog Column -->
    <div class="planning-column">
        <div class="planning-column-header">
            <div class="planning-column-title">
                <i class="fas fa-inbox"></i>
                <span>Backlog</span>
                <span class="planning-column-count">45</span>
            </div>
            <div class="planning-column-actions">
                <div class="project-search-box" style="max-width: 200px;">
                    <i class="fas fa-search project-search-icon"></i>
                    <input type="text" placeholder="Search..." id="backlogSearch">
                </div>
                <select class="project-form-control project-select" style="width: auto;">
                    <option value="priority">Sort by Priority</option>
                    <option value="points">Sort by Points</option>
                    <option value="recent">Recently Added</option>
                </select>
            </div>
        </div>

        <div class="planning-column-content">
            @for($i = 1; $i <= 15; $i++)
                @php
                    $points = [1, 2, 3, 5, 8, 13][$i % 6];
                    $priorities = ['highest', 'high', 'medium', 'low', 'lowest'];
                    $priority = $priorities[$i % 5];
                @endphp
                <div class="planning-card" data-points="{{ $points }}" draggable="true">
                    <div class="planning-card-header">
                        <div class="planning-card-left">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['story', 'task', 'bug'][$i % 3]])
                            <span class="planning-card-key">PROJ-{{ 200 + $i }}</span>
                        </div>
                        <div class="planning-card-right">
                            @include('tenant.manage.projects.components.priority-icon', ['priority' => $priority])
                            <span class="planning-card-points">{{ $points }}</span>
                        </div>
                    </div>

                    <h4 class="planning-card-title">{{ ['Implement search functionality', 'Add file upload feature', 'Create admin dashboard', 'Build notification system', 'Setup email templates', 'Add user roles & permissions', 'Integrate third-party API', 'Create reporting module', 'Add export functionality', 'Build customer portal', 'Setup automated backups', 'Create audit log system', 'Add multi-language support', 'Setup SSO integration', 'Create mobile API'][$i - 1] }}</h4>

                    <div class="planning-card-meta">
                        <span class="planning-card-project">Website Redesign</span>
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="Assignee" 
                             class="planning-card-avatar"
                             title="Assigned to User {{ $i }}">
                    </div>

                    <button class="planning-card-add" onclick="addToSprint(this)">
                        <i class="fas fa-plus"></i>
                        <span>Add to Sprint</span>
                    </button>
                </div>
            @endfor
        </div>
    </div>

    <!-- Sprint Column -->
    <div class="planning-column planning-column-sprint">
        <div class="planning-column-header">
            <div class="planning-column-title">
                <i class="fas fa-calendar-check"></i>
                <span>Sprint 6</span>
                <span class="planning-column-count">12</span>
            </div>
            <div class="planning-column-info">
                <span class="sprint-points-badge">28 points</span>
            </div>
        </div>

        <div class="planning-column-content" id="sprintColumn">
            @for($i = 1; $i <= 12; $i++)
                @php
                    $points = [2, 3, 5, 1, 8, 2][$i % 6];
                @endphp
                <div class="planning-card planning-card-selected" data-points="{{ $points }}" draggable="true">
                    <div class="planning-card-header">
                        <div class="planning-card-left">
                            @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['task', 'story', 'bug'][$i % 3]])
                            <span class="planning-card-key">PROJ-{{ 100 + $i }}</span>
                        </div>
                        <div class="planning-card-right">
                            @include('tenant.manage.projects.components.priority-icon', ['priority' => ['high', 'medium', 'highest'][$i % 3]])
                            <span class="planning-card-points">{{ $points }}</span>
                        </div>
                    </div>

                    <h4 class="planning-card-title">{{ ['Setup authentication', 'Create login page', 'Build user profile', 'Add password reset', 'Implement 2FA', 'Create registration flow', 'Add email verification', 'Setup OAuth', 'Create user settings', 'Add profile picture upload', 'Build security settings', 'Create activity log'][$i - 1] }}</h4>

                    <div class="planning-card-meta">
                        <span class="planning-card-project">Website Redesign</span>
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="Assignee" 
                             class="planning-card-avatar">
                    </div>

                    <button class="planning-card-remove" onclick="removeFromSprint(this)">
                        <i class="fas fa-times"></i>
                        <span>Remove</span>
                    </button>
                </div>
            @endfor

            <div class="sprint-column-empty" style="display: none;">
                <div class="empty-sprint-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <p class="empty-sprint-text">Drag issues from the backlog to add them to the sprint</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===================================== 
       SPRINT PLANNING STYLES
    ===================================== */

    /* Planning Header */
    .planning-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .planning-title {
        font-size: 28px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .planning-subtitle {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .planning-header-right {
        display: flex;
        gap: 8px;
    }

    /* Sprint Configuration */
    .sprint-config-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 20px;
    }

    .sprint-config-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .sprint-config-row:last-child {
        margin-bottom: 0;
    }

    .sprint-config-full {
        grid-column: 1 / -1;
    }

    .sprint-config-label {
        display: block;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-body);
        margin-bottom: 8px;
    }

    /* Sprint Capacity */
    .sprint-capacity-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 20px;
    }

    .capacity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .capacity-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .capacity-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .capacity-stat {
        padding: 16px;
        background: var(--bg);
        border-radius: 8px;
    }

    .capacity-stat-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .capacity-stat-value {
        font-size: 28px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
    }

    .capacity-selected {
        color: #3b82f6;
    }

    .capacity-remaining {
        color: #10b981;
    }

    .capacity-unit {
        font-size: var(--fs-body);
        font-weight: var(--fw-normal);
        color: var(--text-muted);
    }

    .capacity-bar-container {
        margin-top: 16px;
    }

    .capacity-bar {
        height: 12px;
        background: var(--bg);
        border-radius: 6px;
        overflow: hidden;
        position: relative;
        margin-bottom: 8px;
    }

    .capacity-bar::before {
        content: '';
        position: absolute;
        left: 83%;
        top: -4px;
        bottom: -4px;
        width: 2px;
        background: #10b981;
    }

    .capacity-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 6px;
        transition: width 0.6s ease;
    }

    .capacity-bar-labels {
        display: flex;
        justify-content: space-between;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .capacity-optimal {
        color: #10b981;
        font-weight: var(--fw-semibold);
    }

    /* Planning Board */
    .planning-board {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        min-height: 600px;
    }

    .planning-column {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .planning-column-sprint {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);
        border: 2px solid rgba(59, 130, 246, 0.2);
    }

    .planning-column-header {
        padding: 16px;
        background: var(--card);
        border-bottom: 1px solid var(--border);
    }

    .planning-column-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin-bottom: 12px;
    }

    .planning-column-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 24px;
        padding: 0 8px;
        background: var(--bg);
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
        color: var(--text-muted);
    }

    .planning-column-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .planning-column-info {
        display: flex;
        gap: 8px;
    }

    .sprint-points-badge {
        display: inline-flex;
        padding: 6px 12px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-radius: 12px;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
    }

    .planning-column-content {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Planning Card */
    .planning-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 14px;
        cursor: move;
        transition: all 0.2s ease;
    }

    .planning-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .planning-card-selected {
        border-color: #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, var(--card) 100%);
    }

    .planning-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .planning-card-left,
    .planning-card-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .planning-card-key {
        font-family: monospace;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
    }

    .planning-card-points {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        padding: 0 8px;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 50%;
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
    }

    .planning-card-title {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-heading);
        margin: 0 0 10px 0;
        line-height: 1.4;
    }

    .planning-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .planning-card-project {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .planning-card-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
    }

    .planning-card-add,
    .planning-card-remove {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px;
        background: none;
        border: 1px dashed var(--border);
        border-radius: 6px;
        color: var(--text-muted);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .planning-card-add:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .planning-card-remove {
        border-style: solid;
        border-color: #ef4444;
        color: #ef4444;
    }

    .planning-card-remove:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    /* Empty State */
    .sprint-column-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
    }

    .empty-sprint-icon {
        font-size: 64px;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 16px;
    }

    .empty-sprint-text {
        font-size: var(--fs-body);
        color: var(--text-muted);
        max-width: 300px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .planning-board {
            grid-template-columns: 1fr;
        }

        .planning-column {
            min-height: 400px;
        }
    }

    @media (max-width: 768px) {
        .planning-header {
            flex-direction: column;
            align-items: stretch;
        }

        .sprint-config-row {
            grid-template-columns: 1fr;
        }

        .capacity-stats {
            grid-template-columns: 1fr;
        }

        .planning-column-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .project-search-box {
            max-width: 100% !important;
        }
    }
</style>

<script>
    // ===================================== 
    // SPRINT PLANNING FUNCTIONALITY
    // ===================================== 

    let selectedPoints = 28;
    const maxCapacity = 42;

    function addToSprint(button) {
        const card = button.closest('.planning-card');
        const points = parseInt(card.dataset.points);
        
        if (selectedPoints + points > maxCapacity) {
            alert(`Adding this issue would exceed sprint capacity (${maxCapacity} points)`);
            return;
        }
        
        selectedPoints += points;
        updateCapacity();
        
        // Move card to sprint column
        const sprintColumn = document.getElementById('sprintColumn');
        card.classList.add('planning-card-selected');
        
        // Change button
        button.className = 'planning-card-remove';
        button.innerHTML = '<i class="fas fa-times"></i><span>Remove</span>';
        button.onclick = function() { removeFromSprint(this); };
        
        sprintColumn.appendChild(card);
        updateCounts();
    }

    function removeFromSprint(button) {
        const card = button.closest('.planning-card');
        const points = parseInt(card.dataset.points);
        
        selectedPoints -= points;
        updateCapacity();
        
        card.classList.remove('planning-card-selected');
        
        // Change button
        button.className = 'planning-card-add';
        button.innerHTML = '<i class="fas fa-plus"></i><span>Add to Sprint</span>';
        button.onclick = function() { addToSprint(this); };
        
        updateCounts();
    }

    function updateCapacity() {
        const remaining = maxCapacity - selectedPoints;
        const percentage = (selectedPoints / maxCapacity * 100).toFixed(0);
        
        document.querySelector('.capacity-selected .capacity-stat-value').innerHTML = 
            `${selectedPoints} <span class="capacity-unit">points</span>`;
        document.querySelector('.capacity-remaining .capacity-stat-value').innerHTML = 
            `${remaining} <span class="capacity-unit">points</span>`;
        document.querySelector('.capacity-bar-fill').style.width = `${percentage}%`;
        document.querySelector('.sprint-points-badge').textContent = `${selectedPoints} points`;
    }

    function updateCounts() {
        const sprintCount = document.querySelectorAll('#sprintColumn .planning-card').length;
        document.querySelectorAll('.planning-column-sprint .planning-column-count')[0].textContent = sprintCount;
    }

    function startSprint() {
        if (selectedPoints === 0) {
            alert('Please add at least one issue to the sprint');
            return;
        }
        
        if (confirm(`Start Sprint 6 with ${selectedPoints} story points?`)) {
            window.location.href = '{{ route("tenant.manage.projects.sprints.active", $username) }}';
        }
    }

    // Drag and Drop
    document.querySelectorAll('.planning-card').forEach(card => {
        card.addEventListener('dragstart', function(e) {
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', card.dataset.points);
            card.style.opacity = '0.4';
        });

        card.addEventListener('dragend', function() {
            card.style.opacity = '1';
        });
    });

    document.querySelectorAll('.planning-column-content').forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = 'rgba(59, 130, 246, 0.05)';
        });

        column.addEventListener('dragleave', function() {
            this.style.background = '';
        });

        column.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '';
            console.log('Card moved');
        });
    });

    console.log('✅ Sprint Planning Initialized');
</script>

@endsection