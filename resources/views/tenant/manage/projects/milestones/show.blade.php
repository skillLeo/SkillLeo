{{-- resources/views/tenant/projects/milestones/show.blade.php --}}
@extends('tenant.manage.app')
@section('main')

@php
    $milestone = [
        'id' => 'M101',
        'title' => 'Phase 1 - Core Features Development',
        'order' => 'Website Redesign',
        'amount' => 'PKR 150,000',
        'status' => 'progress',
        'progress' => 65,
        'due_date' => now()->addDays(10),
        'created_at' => now()->subDays(20),
    ];
@endphp

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.milestones.index', $username) }}" class="project-breadcrumb-item">
        Milestones
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">{{ $milestone['id'] }}</span>
</div>

<!-- Milestone Header -->
<div class="milestone-detail-header">
    <div class="milestone-detail-header-left">
        <div class="milestone-id-badge">{{ $milestone['id'] }}</div>
        <div>
            <h1 class="milestone-detail-title">{{ $milestone['title'] }}</h1>
            <div class="milestone-detail-meta">
                <span class="meta-item">
                    <i class="fas fa-box"></i>
                    {{ $milestone['order'] }}
                </span>
                <span class="meta-item">
                    <i class="fas fa-money-bill-wave"></i>
                    {{ $milestone['amount'] }}
                </span>
                <span class="meta-item">
                    <i class="fas fa-calendar"></i>
                    Due {{ $milestone['due_date']->format('M d, Y') }}
                </span>
            </div>
        </div>
    </div>
    <div class="milestone-detail-header-right">
        <span class="milestone-status-badge milestone-status-{{ $milestone['status'] }}">
            {{ ['progress' => 'In Progress', 'pending' => 'Pending', 'approved' => 'Approved', 'paid' => 'Paid'][$milestone['status']] }}
        </span>
        <button class="project-btn project-btn-secondary" onclick="alert('Edit Milestone')">
            <i class="fas fa-edit"></i>
            <span>Edit</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="requestApproval()">
            <i class="fas fa-check"></i>
            <span>Request Approval</span>
        </button>
    </div>
</div>

<!-- Milestone Progress Card -->
<div class="milestone-progress-card">
    <div class="progress-card-header">
        <h3 class="progress-card-title">Milestone Progress</h3>
        <div class="progress-percentage-large">{{ $milestone['progress'] }}%</div>
    </div>

    <div class="progress-bar-milestone">
<div class="progress-fill-milestone" style="width: {{ $milestone['progress'] }}%;"></div>
</div><div class="progress-breakdown-grid">
    <div class="progress-breakdown-item">
        <div class="breakdown-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="breakdown-content">
            <div class="breakdown-value">12</div>
            <div class="breakdown-label">Completed Tasks</div>
        </div>
    </div>

    <div class="progress-breakdown-item">
        <div class="breakdown-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-play-circle"></i>
        </div>
        <div class="breakdown-content">
            <div class="breakdown-value">5</div>
            <div class="breakdown-label">In Progress</div>
        </div>
    </div>

    <div class="progress-breakdown-item">
        <div class="breakdown-icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <i class="fas fa-circle"></i>
        </div>
        <div class="breakdown-content">
            <div class="breakdown-value">3</div>
            <div class="breakdown-label">To Do</div>
        </div>
    </div>

    <div class="progress-breakdown-item">
        <div class="breakdown-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="breakdown-content">
            <div class="breakdown-value">10</div>
            <div class="breakdown-label">Days Remaining</div>
        </div>
    </div>
</div></div>
<!-- Content Grid -->
<div class="milestone-content-grid">
    <!-- Main Content -->
    <div class="milestone-main-content">
        <!-- Description -->
        <div class="milestone-section">
            <div class="milestone-section-header">
                <h3 class="milestone-section-title">Description</h3>
                <button class="project-btn project-btn-ghost project-btn-sm">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </button>
            </div>
            <div class="milestone-description">
                <p>This milestone covers the development of core authentication and user management features including:</p>
                <ul>
                    <li>User registration and login system</li>
                    <li>OAuth integration (Google, GitHub, LinkedIn)</li>
                    <li>Password reset and email verification</li>
                    <li>User profile management</li>
                    <li>Role-based access control</li>
                    <li>Session management and security</li>
                </ul>
            </div>
        </div><!-- Acceptance Criteria -->
        <div class="milestone-section">
            <div class="milestone-section-header">
                <h3 class="milestone-section-title">Acceptance Criteria</h3>
            </div>
            <div class="acceptance-criteria-list">
                @for($i = 1; $i <= 6; $i++)
                    <div class="acceptance-item {{ $i <= 4 ? 'acceptance-completed' : '' }}">
                        <input type="checkbox" {{ $i <= 4 ? 'checked' : '' }} class="acceptance-checkbox">
                        <span class="acceptance-text">{{ ['All authentication endpoints are functional and tested', 'OAuth providers are integrated and working', 'Password reset flow is implemented with email notifications', 'User profile CRUD operations are complete', 'Role-based permissions are enforced', 'Security audit is passed with no critical issues'][$i - 1] }}</span>
                    </div>
                @endfor
            </div>
        </div>
    
        <!-- Tasks -->
        <div class="milestone-section">
            <div class="milestone-section-header">
                <h3 class="milestone-section-title">
                    Tasks
                    <span class="section-count">(20)</span>
                </h3>
                <button class="project-btn project-btn-ghost project-btn-sm" onclick="alert('Add Task')">
                    <i class="fas fa-plus"></i>
                    <span>Add Task</span>
                </button>
            </div>
    
            <div class="milestone-tasks-list">
                @for($i = 1; $i <= 8; $i++)
                    @php
                        $isDone = $i <= 5;
                        $statuses = ['done', 'done', 'done', 'done', 'done', 'progress', 'progress', 'todo'];
                        $status = $statuses[$i - 1];
                    @endphp
                    <div class="milestone-task-item {{ $isDone ? 'task-done' : '' }}">
                        <div class="task-item-left">
                            <input type="checkbox" {{ $isDone ? 'checked' : '' }} class="task-checkbox">
                            <div class="task-item-info">
                                @include('tenant.manage.projects.components.issue-type-icon', ['type' => ['task', 'story', 'bug'][$i % 3]])
                                <span class="task-key">PROJ-{{ 100 + $i }}</span>
                                <span class="task-title">{{ ['Setup authentication routes', 'Create login API endpoint', 'Build registration form', 'Implement OAuth flow', 'Add password validation', 'Create user profile page', 'Setup email verification', 'Add 2FA support'][$i - 1] }}</span>
                            </div>
                        </div>
                        <div class="task-item-right">
                            <span class="task-status-badge task-status-{{ $status }}">
                                {{ ['done' => 'Done', 'progress' => 'In Progress', 'todo' => 'To Do'][$status] }}
                            </span>
                            <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                                 alt="Assignee" 
                                 class="task-assignee-avatar">
                        </div>
                    </div>
                @endfor
            </div>
    
            <button class="show-more-tasks" onclick="alert('Show All Tasks')">
                <span>Show 12 more tasks</span>
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    
        <!-- Deliverables -->
        <div class="milestone-section">
            <div class="milestone-section-header">
                <h3 class="milestone-section-title">
                    Deliverables
                    <span class="section-count">(5)</span>
                </h3>
                <button class="project-btn project-btn-ghost project-btn-sm" onclick="alert('Add Deliverable')">
                    <i class="fas fa-plus"></i>
                    <span>Add</span>
                </button>
            </div>
    
            <div class="deliverables-list">
                @for($i = 1; $i <= 5; $i++)
                    <div class="deliverable-item">
                        <div class="deliverable-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="deliverable-info">
                            <h4 class="deliverable-name">{{ ['Authentication API Documentation', 'User Flow Diagrams', 'Security Audit Report', 'Test Coverage Report', 'Deployment Guide'][$i - 1] }}</h4>
                            <div class="deliverable-meta">
                                <span class="deliverable-size">{{ rand(100, 500) }} KB</span>
                                <span class="deliverable-date">{{ now()->subDays(rand(1, 10))->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <button class="deliverable-download">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                @endfor
            </div>
        </div>
    
        <!-- Activity Log -->
        <div class="milestone-section">
            <div class="milestone-section-header">
                <h3 class="milestone-section-title">Activity</h3>
            </div>
    
            <div class="milestone-activity-list">
                @for($i = 1; $i <= 5; $i++)
                    <div class="activity-item-milestone">
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="User" 
                             class="activity-avatar-milestone">
                        <div class="activity-content-milestone">
                            <div class="activity-text">
                                <strong>{{ ['Hassan Mehmood', 'Ali Khan', 'Sara Ahmed'][$i % 3] }}</strong>
                                {{ ['marked PROJ-101 as done', 'updated the milestone description', 'added a new deliverable', 'commented on the progress', 'changed the due date'][$i - 1] }}
                            </div>
                            <div class="activity-time">{{ now()->subHours(rand(1, 48))->diffForHumans() }}</div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="milestone-sidebar">
        <!-- Details -->
        <div class="milestone-sidebar-card">
            <h4 class="sidebar-card-title">Details</h4>
            
            <div class="sidebar-detail-item">
                <div class="sidebar-detail-label">Status</div>
                <select class="project-form-control project-select">
                    <option value="pending">Pending</option>
                    <option value="progress" selected>In Progress</option>
                    <option value="review">Under Review</option>
                    <option value="approved">Approved</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
    
            <div class="sidebar-detail-item">
                <div class="sidebar-detail-label">Amount</div>
                <div class="sidebar-detail-value">{{ $milestone['amount'] }}</div>
            </div>
    
            <div class="sidebar-detail-item">
                <div class="sidebar-detail-label">Due Date</div>
                <input type="date" class="project-form-control" value="{{ $milestone['due_date']->format('Y-m-d') }}">
            </div>
    
            <div class="sidebar-detail-item">
                <div class="sidebar-detail-label">Created</div>
                <div class="sidebar-detail-value">{{ $milestone['created_at']->format('M d, Y') }}</div>
            </div>
        </div>
    
        <!-- Team -->
        <div class="milestone-sidebar-card">
            <div class="sidebar-card-header">
                <h4 class="sidebar-card-title">Team</h4>
                <button class="sidebar-card-action">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
    
            <div class="team-members-list">
                @for($i = 1; $i <= 4; $i++)
                    <div class="team-member-item">
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background={{ ['667eea', 'f093fb', '4facfe', '43e97b'][$i - 1] }}&color=fff" 
                             alt="Member" 
                             class="team-member-avatar">
                        <div class="team-member-info">
                            <div class="team-member-name">{{ ['Hassan M.', 'Ali K.', 'Sara A.', 'Zain M.'][$i - 1] }}</div>
                            <div class="team-member-role">{{ ['Developer', 'Designer', 'QA', 'PM'][$i - 1] }}</div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    
        <!-- Quick Actions -->
        <div class="milestone-sidebar-card">
            <h4 class="sidebar-card-title">Quick Actions</h4>
            
            <div class="quick-actions-list">
                <button class="quick-action-btn-milestone" onclick="alert('View Invoice')">
                    <i class="fas fa-file-invoice"></i>
                    <span>View Invoice</span>
                </button>
                <button class="quick-action-btn-milestone" onclick="alert('Send Update')">
                    <i class="fas fa-paper-plane"></i>
                    <span>Send Update</span>
                </button>
                <button class="quick-action-btn-milestone" onclick="alert('Export Report')">
                    <i class="fas fa-download"></i>
                    <span>Export Report</span>
                </button>
            </div>
        </div>
    
        <!-- Client Portal Link -->
        <div class="milestone-sidebar-card milestone-portal-card">
            <div class="portal-icon">
                <i class="fas fa-external-link-alt"></i>
            </div>
            <h4 class="portal-title">Client Portal</h4>
            <p class="portal-desc">Share this milestone with your client</p>
            <button class="project-btn project-btn-primary" style="width: 100%; justify-content: center;">
                <i class="fas fa-share"></i>
                <span>Share Link</span>
            </button>
        </div>
    </div></div>
    <style>
        /* ===================================== 
           MILESTONE DETAIL STYLES
        ===================================== */
    
        /* Milestone Detail Header */
        .milestone-detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            gap: 20px;
            flex-wrap: wrap;
        }
    
        .milestone-detail-header-left {
            display: flex;
            gap: 16px;
            flex: 1;
        }
    
        .milestone-id-badge {
            width: 72px;
            height: 72px;
            background: var(--accent-light);
            color: var(--accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: var(--fw-bold);
            font-family: monospace;
            flex-shrink: 0;
        }
    
        .milestone-detail-title {
            font-size: 28px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            margin: 0 0 8px 0;
        }
    
        .milestone-detail-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
    
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: var(--fs-body);
            color: var(--text-muted);
        }
    
        .milestone-detail-header-right {
            display: flex;
            gap: 8px;
            align-items: center;
        }
    
        .milestone-status-badge {
            display: inline-flex;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
        }
    
        /* Progress Card */
        .milestone-progress-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 24px;
        }
    
        .progress-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
    
        .progress-card-title {
            font-size: var(--fs-h3);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
            margin: 0;
        }
    
        .progress-percentage-large {
            font-size: 32px;
            font-weight: var(--fw-bold);
            color: var(--accent);
        }
    
        .progress-bar-milestone {
            height: 16px;
            background: var(--bg);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }
    
        .progress-fill-milestone {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 8px;
            transition: width 0.6s ease;
        }
    
        .progress-breakdown-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
    
        .progress-breakdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: var(--bg);
            border-radius: 8px;
        }
    
        .breakdown-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
    
        .breakdown-value {
            font-size: 24px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            line-height: 1;
        }
    
        .breakdown-label {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }
    
        /* Content Grid */
        .milestone-content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
        }
    
        /* Milestone Section */
        .milestone-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 20px;
        }
    
        .milestone-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
    
        .milestone-section-title {
            font-size: var(--fs-h3);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    
        .section-count {
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            color: var(--text-muted);
        }
    
        .milestone-description {
            font-size: var(--fs-body);
            line-height: var(--lh-normal);
            color: var(--text-body);
        }
    
        .milestone-description p {
            margin: 0 0 16px 0;
        }
    
        .milestone-description ul {
            margin: 0;
            padding-left: 24px;
        }
    
        .milestone-description li {
            margin-bottom: 8px;
        }
    
        /* Acceptance Criteria */
        .acceptance-criteria-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
    
        .acceptance-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
            transition: all 0.15s ease;
        }
    
        .acceptance-item:hover {
            background: var(--card);
        }
    
        .acceptance-completed {
            opacity: 0.7;
        }
    
        .acceptance-checkbox {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            flex-shrink: 0;
        }
    
        .acceptance-text {
            font-size: var(--fs-body);
            color: var(--text-body);
            line-height: 1.5;
        }
    
        .acceptance-completed .acceptance-text {
            text-decoration: line-through;
        }
    
        /* Tasks List */
        .milestone-tasks-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    
        .milestone-task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
            transition: all 0.15s ease;
        }
    
        .milestone-task-item:hover {
            background: var(--card);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
    
        .task-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }
    
        .task-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            flex-shrink: 0;
        }
    
        .task-item-info {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 0;
        }
    
        .task-key {
            font-family: monospace;
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            color: var(--accent);
            flex-shrink: 0;
        }
    
        .task-title {
            font-size: var(--fs-body);
            color: var(--text-body);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    
        .task-done .task-title {
            text-decoration: line-through;
            opacity: 0.6;
        }
    
        .task-item-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
    
        .task-status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
        }
    
        .task-status-done {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
    
        .task-status-progress {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
    
        .task-status-todo {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }
    
        .task-assignee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }
    
        .show-more-tasks {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            margin-top: 12px;
            background: none;
            border: 1px dashed var(--border);
            border-radius: 8px;
            color: var(--text-muted);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            cursor: pointer;
            transition: all 0.15s ease;
        }
    
        .show-more-tasks:hover {
            background: var(--bg);
            border-color: var(--accent);
            color: var(--accent);
        }
    
        /* Deliverables */
        .deliverables-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
    
        .deliverable-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
            transition: all 0.15s ease;
        }
    
        .deliverable-item:hover {
            background: var(--card);
        }
    
        .deliverable-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-light);
            color: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
    
        .deliverable-info {
            flex: 1;
            min-width: 0;
        }
    
        .deliverable-name {
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            color: var(--text-heading);
            margin: 0 0 4px 0;
        }
    
        .deliverable-meta {
            display: flex;
            gap: 12px;
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }
    
        .deliverable-download {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s ease;
        }
    
        .deliverable-download:hover {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
        }
    
        /* Activity */
        .milestone-activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
    
        .activity-item-milestone {
            display: flex;
            gap: 12px;
        }
    
        .activity-avatar-milestone {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            flex-shrink: 0;
        }
    
        .activity-content-milestone {
            flex: 1;
        }
    
        .activity-text {
            font-size: var(--fs-body);
            color: var(--text-body);
            margin-bottom: 4px;
        }
    
        .activity-time {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }
    
        /* Sidebar */
        .milestone-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
    
        .milestone-sidebar-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
        }
    
        .sidebar-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
    
        .sidebar-card-title {
            font-size: var(--fs-h3);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
            margin: 0 0 16px 0;
        }
    
        .sidebar-card-action {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-light);
            color: var(--accent);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
    
        .sidebar-card-action:hover {
            background: var(--accent);
            color: var(--btn-text-primary);
        }
    
        .sidebar-detail-item {
            margin-bottom: 16px;
        }
    
        .sidebar-detail-item:last-child {
            margin-bottom: 0;
        }
    
        .sidebar-detail-label {
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    
        .sidebar-detail-value {
            font-size: var(--fs-body);
            color: var(--text-heading);
            font-weight: var(--fw-medium);
        }
    
        /* Team Members */
        .team-members-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
    
        .team-member-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
    
        .team-member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    
        .team-member-name {
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
        }
    
        .team-member-role {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }
    
        /* Quick Actions */
        .quick-actions-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    
        .quick-action-btn-milestone {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-body);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            cursor: pointer;
            transition: all 0.15s ease;
        }
    
        .quick-action-btn-milestone:hover {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
        }
    
        /* Portal Card */
        .milestone-portal-card {
            text-align: center;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
            border-color: rgba(59, 130, 246, 0.2);
        }
    
        .portal-icon {
            width: 56px;
            height: 56px;
            background: var(--accent-light);
            color: var(--accent);
            border-radius: 50%;
            display:flex;
align-items: center;
justify-content: center;
font-size: 24px;
margin: 0 auto 16px;
}.portal-title {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 8px 0;
}

.portal-desc {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0 0 16px 0;
}

/* Responsive */
@media (max-width: 1200px) {
    .milestone-content-grid {
        grid-template-columns: 1fr;
    }

    .milestone-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .milestone-detail-header {
        flex-direction: column;
        align-items: stretch;
    }

    .milestone-id-badge {
        width: 56px;
        height: 56px;
        font-size: 14px;
    }

    .milestone-detail-title {
        font-size: 22px;
    }

    .progress-breakdown-grid {
        grid-template-columns: 1fr;
    }

    .task-item-info {
        flex-wrap: wrap;
    }
}</style>
<script>
    function requestApproval() {
        if (confirm('Submit this milestone for client approval?')) {
            alert('Approval request sent!');
            // Here you would make an API call
        }
    }

    console.log('✅ Milestone Detail Initialized');
</script>
@endsection