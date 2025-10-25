{{-- resources/views/tenant/projects/issues/show.blade.php --}}
@extends('tenant.manage.app')
@section('main')

@php
    $issue = [
        'key' => 'PROJ-101',
        'type' => 'story',
        'priority' => 'high',
        'status' => 'progress',
        'title' => 'Implement user authentication system',
        'description' => 'Create a comprehensive authentication system with login, registration, password reset, and email verification features. The system should support OAuth providers (Google, GitHub, LinkedIn) and implement security best practices including rate limiting, 2FA, and session management.',
        'project' => ['key' => 'PROJ', 'name' => 'Website Redesign'],
        'assignee' => ['name' => 'Hassan Mehmood', 'avatar' => 'https://ui-avatars.com/api/?name=Hassan+M&background=667eea&color=fff'],
        'reporter' => ['name' => 'Ali Khan', 'avatar' => 'https://ui-avatars.com/api/?name=Ali+K&background=f093fb&color=fff'],
        'sprint' => 'Sprint 5',
        'story_points' => 8,
        'created' => now()->subDays(5),
        'updated' => now()->subHours(2),
        'due_date' => now()->addDays(7),
    ];
@endphp

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ route('tenant.manage.projects.list', $username) }}" class="project-breadcrumb-item">
        {{ $issue['project']['name'] }}
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">{{ $issue['key'] }}</span>
</div>

<!-- Issue Detail Container -->
<div class="issue-detail-container">
    <!-- Main Content -->
    <div class="issue-detail-main">
        <!-- Issue Header -->
        <div class="issue-detail-header">
            <div class="issue-detail-header-top">
                <div class="issue-type-badge">
                    @include('tenant.manage.projects.components.issue-type-icon', ['type' => $issue['type']])
                    <span>{{ ucfirst($issue['type']) }}</span>
                </div>
                <div class="issue-key-large">{{ $issue['key'] }}</div>
            </div>
            
            <h1 class="issue-detail-title">{{ $issue['title'] }}</h1>

            <div class="issue-detail-actions">
                <button class="project-btn project-btn-ghost">
                    <i class="fas fa-share"></i>
                    <span>Share</span>
                </button>
                <button class="project-btn project-btn-ghost">
                    <i class="fas fa-link"></i>
                    <span>Copy Link</span>
                </button>
                <button class="project-btn project-btn-ghost">
                    <i class="fas fa-star"></i>
                    <span>Watch</span>
                </button>
                <button class="project-btn project-btn-primary">
                    <i class="fas fa-play"></i>
                    <span>Start Progress</span>
                </button>
                <button class="project-icon-btn">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>

        <!-- Issue Tabs -->
        <div class="issue-tabs">
            <button class="issue-tab active" data-tab="details">
                <i class="fas fa-info-circle"></i>
                <span>Details</span>
            </button>
            <button class="issue-tab" data-tab="comments">
                <i class="fas fa-comments"></i>
                <span>Comments</span>
                <span class="issue-tab-count">8</span>
            </button>
            <button class="issue-tab" data-tab="activity">
                <i class="fas fa-history"></i>
                <span>Activity</span>
            </button>
            <button class="issue-tab" data-tab="subtasks">
                <i class="fas fa-tasks"></i>
                <span>Sub-tasks</span>
                <span class="issue-tab-count">5</span>
            </button>
            <button class="issue-tab" data-tab="links">
                <i class="fas fa-link"></i>
                <span>Linked Issues</span>
                <span class="issue-tab-count">3</span>
            </button>
            <button class="issue-tab" data-tab="attachments">
                <i class="fas fa-paperclip"></i>
                <span>Attachments</span>
                <span class="issue-tab-count">2</span>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="issue-tab-content active" id="tab-details">
            <!-- Description -->
            <div class="issue-section">
                <div class="issue-section-header">
                    <h3 class="issue-section-title">Description</h3>
                    <button class="project-btn project-btn-ghost project-btn-sm" onclick="editDescription()">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </button>
                </div>
                <div class="issue-description">
                    <p>{{ $issue['description'] }}</p>
                    
                    <h4>Acceptance Criteria:</h4>
                    <ul>
                        <li>User can register with email and password</li>
                        <li>User can login with email and password</li>
                        <li>User can login with Google OAuth</li>
                        <li>User can login with GitHub OAuth</li>
                        <li>User can reset password via email</li>
                        <li>User receives email verification after registration</li>
                        <li>System implements rate limiting (5 attempts per 15 minutes)</li>
                        <li>Optional 2FA can be enabled</li>
                    </ul>
                </div>
            </div>

            <!-- Sub-tasks -->
            <div class="issue-section">
                <div class="issue-section-header">
                    <h3 class="issue-section-title">
                        Sub-tasks
                        <span class="section-count">3 of 5 done</span>
                    </h3>
                    <button class="project-btn project-btn-ghost project-btn-sm">
                        <i class="fas fa-plus"></i>
                        <span>Add Sub-task</span>
                    </button>
                </div>
                <div class="subtasks-list">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="subtask-item {{ $i <= 3 ? 'done' : '' }}">
                            <input type="checkbox" {{ $i <= 3 ? 'checked' : '' }} class="subtask-checkbox">
                            <div class="subtask-info">
                                <span class="subtask-key">PROJ-10{{ $i }}</span>
                                <span class="subtask-title">{{ ['Setup OAuth providers', 'Create registration form', 'Implement password hashing', 'Add email verification', 'Create password reset flow'][$i - 1] }}</span>
                            </div>
                            <div class="subtask-meta">
                                <img src="https://ui-avatars.com/api/?name={{ ['Hassan+M', 'Ali+K', 'Sara+A'][$i % 3] }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                                     alt="Assignee" 
                                     class="subtask-avatar">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Linked Issues -->
            <div class="issue-section">
                <div class="issue-section-header">
                    <h3 class="issue-section-title">Linked Issues</h3>
                    <button class="project-btn project-btn-ghost project-btn-sm">
                        <i class="fas fa-plus"></i>
                        <span>Link Issue</span>
                    </button>
                </div>
                <div class="linked-issues-list">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="linked-issue-item">
                            <div class="linked-issue-type">
                                <span class="link-type-badge">{{ ['Blocks', 'Relates to', 'Is blocked by'][$i - 1] }}</span>
                            </div>
                            <div class="linked-issue-info">
                                <span class="linked-issue-key">PROJ-{{ 200 + $i }}</span>
                                <span class="linked-issue-title">{{ ['Design authentication UI', 'Setup email service', 'Create user database schema'][$i - 1] }}</span>
                            </div>
                            <div class="linked-issue-status">
                                <span class="status-badge status-{{ ['progress', 'done', 'todo'][$i - 1] }}">
                                    {{ ['In Progress', 'Done', 'To Do'][$i - 1] }}
                                </span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Comments Tab -->
        <div class="issue-tab-content" id="tab-comments">
            <!-- Add Comment -->
            <div class="add-comment-box">
                <img src="https://ui-avatars.com/api/?name=Hassan+M&background=667eea&color=fff" alt="You" class="comment-avatar">
                <div class="comment-editor">
                    <textarea class="project-form-control project-textarea" placeholder="Add a comment..." rows="4"></textarea>
                    <div class="comment-actions">
                        <div class="comment-formatting">
                            <button class="formatting-btn" title="Bold"><i class="fas fa-bold"></i></button>
                            <button class="formatting-btn" title="Italic"><i class="fas fa-italic"></i></button>
                            <button class="formatting-btn" title="Link"><i class="fas fa-link"></i></button>
                            <button class="formatting-btn" title="Code"><i class="fas fa-code"></i></button>
                            <button class="formatting-btn" title="Mention"><i class="fas fa-at"></i></button>
                        </div>
                        <button class="project-btn project-btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            <span>Comment</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Comments List -->
            <div class="comments-list">
                @for($i = 1; $i <= 8; $i++)
                    <div class="comment-item">
                        <img src="https://ui-avatars.com/api/?name={{ ['Hassan+M', 'Ali+K', 'Sara+A'][$i % 3] }}&background={{ ['667eea', 'f093fb', '4facfe'][$i % 3] }}&color=fff" 
                             alt="User" 
                             class="comment-avatar">
                        <div class="comment-content">
                            <div class="comment-header">
                                <span class="comment-author">{{ ['Hassan Mehmood', 'Ali Khan', 'Sara Ahmed'][$i % 3] }}</span>
                                <span class="comment-time">{{ now()->subHours(rand(1, 48))->diffForHumans() }}</span>
                            </div>
                            <div class="comment-body">
                                {{ ['I\'ve started working on the OAuth integration. Google and GitHub providers are configured.', 'Great progress! Make sure to add proper error handling for failed OAuth attempts.', 'Should we also consider LinkedIn OAuth? Many of our users prefer it.', 'Yes, let\'s add LinkedIn too. I\'ll update the requirements.', 'The registration form is now complete with validation.', 'Email verification is working. Users receive the email within seconds.', 'I\'ve added rate limiting to prevent brute force attacks.', 'The password reset flow is functional and tested.'][$i - 1] }}
                            </div>
                            <div class="comment-footer">
                                <button class="comment-action-btn">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="comment-action-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="comment-action-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Other tabs content (placeholders) -->
        <div class="issue-tab-content" id="tab-activity">
            <div class="project-empty-state">
                <div class="project-empty-state-icon"><i class="fas fa-history"></i></div>
                <h3 class="project-empty-state-title">Activity Feed</h3>
                <p class="project-empty-state-desc">Track all changes and updates to this issue</p>
            </div>
        </div>

        <div class="issue-tab-content" id="tab-subtasks">
            <div class="project-empty-state">
                <div class="project-empty-state-icon"><i class="fas fa-tasks"></i></div>
                <h3 class="project-empty-state-title">Sub-tasks</h3>
                <p class="project-empty-state-desc">Break down this issue into smaller tasks</p>
            </div>
        </div>

        <div class="issue-tab-content" id="tab-links">
            <div class="project-empty-state">
                <div class="project-empty-state-icon"><i class="fas fa-link"></i></div>
                <h3 class="project-empty-state-title">Linked Issues</h3>
                <p class="project-empty-state-desc">Connect related issues</p>
            </div>
        </div>

        <div class="issue-tab-content" id="tab-attachments">
            <div class="project-empty-state">
                <div class="project-empty-state-icon"><i class="fas fa-paperclip"></i></div>
                <h3 class="project-empty-state-title">Attachments</h3>
                <p class="project-empty-state-desc">Upload files and images</p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="issue-detail-sidebar">
        <!-- Status -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Status</label>
            <select class="project-form-control project-select">
                <option value="todo">To Do</option>
                <option value="progress" selected>In Progress</option>
                <option value="review">In Review</option>
                <option value="done">Done</option>
            </select>
        </div>

        <!-- Assignee -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Assignee</label>
            <div class="issue-sidebar-user">
                <img src="{{ $issue['assignee']['avatar'] }}" alt="{{ $issue['assignee']['name'] }}" class="sidebar-user-avatar">
                <span class="sidebar-user-name">{{ $issue['assignee']['name'] }}</span>
                <button class="sidebar-user-change">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>

        <!-- Reporter -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Reporter</label>
            <div class="issue-sidebar-user">
                <img src="{{ $issue['reporter']['avatar'] }}" alt="{{ $issue['reporter']['name'] }}" class="sidebar-user-avatar">
                <span class="sidebar-user-name">{{ $issue['reporter']['name'] }}</span>
            </div>
        </div>

        <!-- Priority -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Priority</label>
            <select class="project-form-control project-select">
                <option value="highest">Highest</option>
                <option value="high" selected>High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
                <option value="lowest">Lowest</option>
            </select>
        </div>

        <!-- Sprint -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Sprint</label>
            <div class="issue-sidebar-value">
                <i class="fas fa-play-circle" style="color: #10b981;"></i>
                <span>{{ $issue['sprint'] }}</span>
            </div>
        </div>

        <!-- Story Points -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Story Points</label>
            <input type="number" class="project-form-control" value="{{ $issue['story_points'] }}" min="1" max="100">
        </div>

        <!-- Due Date -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Due Date</label>
            <input type="date" class="project-form-control" value="{{ $issue['due_date']->format('Y-m-d') }}">
        </div>

        <!-- Labels -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Labels</label>
            <div class="issue-labels">
                <span class="issue-label" style="background: #ef4444;">Critical</span>
                <span class="issue-label" style="background: #3b82f6;">Backend</span>
                <button class="add-label-btn">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <!-- Dates -->
        <div class="issue-sidebar-section">
            <label class="issue-sidebar-label">Dates</label>
            <div class="issue-dates">
                <div class="issue-date-item">
                    <span class="date-label">Created:</span>
                    <span class="date-value">{{ $issue['created']->format('M d, Y') }}</span>
                </div>
                <div class="issue-date-item">
                    <span class="date-label">Updated:</span>
                    <span class="date-value">{{ $issue['updated']->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="issue-sidebar-section">
            <button class="project-btn project-btn-ghost" style="width: 100%; justify-content: center;">
                <i class="fas fa-trash"></i>
                <span>Delete Issue</span>
            </button>
        </div>
    </div>
</div>

<style>
    /* ===================================== 
       ISSUE DETAIL PAGE STYLES
    ===================================== */

    .issue-detail-container {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
    }

    /* Main Content */
    .issue-detail-main {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        min-height: 600px;
    }

    /* Issue Header */
    .issue-detail-header {
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .issue-detail-header-top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .issue-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: var(--bg);
        border-radius: 8px;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-body);
    }

    .issue-key-large {
        font-size: var(--fs-h3);
        font-weight: var(--fw-bold);
        font-family: monospace;
        color: var(--text-muted);
    }

    .issue-detail-title {
        font-size: var(--fs-h1);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        margin: 0 0 20px 0;
        line-height: var(--lh-tight);
    }

    .issue-detail-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* Issue Tabs */
    .issue-tabs {
        display: flex;
        gap: 4px;
        margin-bottom: 24px;
        border-bottom: 2px solid var(--border);
        overflow-x: auto;
        scrollbar-width: none;
    }

    .issue-tabs::-webkit-scrollbar {
        display: none;
    }

    .issue-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        color: var(--text-muted);
        font-weight: var(--fw-medium);
        font-size: var(--fs-body);
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .issue-tab:hover {
        color: var(--text-body);
        background: var(--bg);
    }

    .issue-tab.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }

    .issue-tab-count {
        padding: 2px 8px;
        background: var(--bg);
        border-radius: 10px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-bold);
        min-width: 20px;
        text-align: center;
    }

    .issue-tab.active .issue-tab-count {
        background: var(--accent);
        color: var(--btn-text-primary);
    }

    /* Tab Content */
    .issue-tab-content {
        display: none;
    }

    .issue-tab-content.active {
        display: block;
    }

    /* Issue Section */
    .issue-section {
        margin-bottom: 32px;
    }

    .issue-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .issue-section-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-count {
        font-size: var(--fs-subtle);font-weight: var(--fw-medium);
        color: var(--text-muted);
    }

    .project-btn-sm {
        height: 32px;
        padding: 0 12px;
        font-size: var(--fs-subtle);
    }

    /* Description */
    .issue-description {
        font-size: var(--fs-body);
        line-height: var(--lh-relaxed);
        color: var(--text-body);
    }

    .issue-description p {
        margin: 0 0 16px 0;
    }

    .issue-description h4 {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 20px 0 12px 0;
    }

    .issue-description ul {
        margin: 0;
        padding-left: 24px;
    }

    .issue-description li {
        margin-bottom: 8px;
        color: var(--text-body);
    }

    /* Sub-tasks */
    .subtasks-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .subtask-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .subtask-item:hover {
        background: var(--card);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .subtask-item.done {
        opacity: 0.6;
    }

    .subtask-item.done .subtask-title {
        text-decoration: line-through;
    }

    .subtask-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .subtask-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .subtask-key {
        font-family: monospace;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--accent);
        flex-shrink: 0;
    }

    .subtask-title {
        font-size: var(--fs-body);
        color: var(--text-body);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .subtask-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Linked Issues */
    .linked-issues-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .linked-issue-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .linked-issue-item:hover {
        background: var(--card);
        cursor: pointer;
    }

    .link-type-badge {
        padding: 4px 10px;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 6px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .linked-issue-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .linked-issue-key {
        font-family: monospace;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--accent);
        flex-shrink: 0;
    }

    .linked-issue-title {
        font-size: var(--fs-body);
        color: var(--text-body);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Comments */
    .add-comment-box {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        padding: 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .comment-editor {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .comment-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .comment-formatting {
        display: flex;
        gap: 4px;
    }

    .formatting-btn {
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

    .formatting-btn:hover {
        background: var(--card);
        color: var(--text-body);
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .comment-item {
        display: flex;
        gap: 12px;
    }

    .comment-content {
        flex: 1;
        min-width: 0;
    }

    .comment-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .comment-author {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .comment-time {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .comment-body {
        font-size: var(--fs-body);
        line-height: var(--lh-normal);
        color: var(--text-body);
        margin-bottom: 8px;
    }

    .comment-footer {
        display: flex;
        gap: 16px;
    }

    .comment-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .comment-action-btn:hover {
        background: var(--bg);
        color: var(--accent);
    }

    /* Sidebar */
    .issue-detail-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .issue-sidebar-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
    }

    .issue-sidebar-label {
        display: block;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .issue-sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background: var(--bg);
        border-radius: 8px;
    }

    .sidebar-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .sidebar-user-name {
        font-size: var(--fs-body);
        color: var(--text-body);
        flex: 1;
    }

    .sidebar-user-change {
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

    .sidebar-user-change:hover {
        background: var(--card);
        color: var(--accent);
    }

    .issue-sidebar-value {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background: var(--bg);
        border-radius: 8px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .issue-labels {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .issue-label {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: white;
    }

    .add-label-btn {
        width: 32px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 12px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .add-label-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .issue-dates {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .issue-date-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        background: var(--bg);
        border-radius: 6px;
    }

    .date-label {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .date-value {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-medium);
        color: var(--text-body);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .issue-detail-container {
            grid-template-columns: 1fr;
        }

        .issue-detail-sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .issue-detail-main {
            padding: 16px;
        }

        .issue-detail-title {
            font-size: var(--fs-h2);
        }

        .issue-detail-actions {
            width: 100%;
        }

        .issue-detail-actions .project-btn {
            flex: 1;
        }

        .issue-tabs {
            gap: 0;
        }

        .issue-tab {
            padding: 10px 12px;
            font-size: var(--fs-subtle);
        }

        .add-comment-box {
            flex-direction: column;
        }

        .comment-item {
            flex-direction: column;
        }
    }
</style>

<script>
    // ===================================== 
    // ISSUE DETAIL PAGE FUNCTIONALITY
    // ===================================== 

    // Tab switching
    document.querySelectorAll('.issue-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active from all tabs
            document.querySelectorAll('.issue-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.issue-tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active to clicked tab
            this.classList.add('active');
            const tabName = this.getAttribute('data-tab');
            document.getElementById(`tab-${tabName}`).classList.add('active');
        });
    });

    function editDescription() {
        console.log('Edit description');
        alert('Edit Description - Coming Soon!');
    }

    console.log('✅ Issue Detail Page Initialized');
</script>

@endsection