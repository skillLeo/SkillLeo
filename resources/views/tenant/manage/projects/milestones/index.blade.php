{{-- resources/views/tenant/projects/milestones/index.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Milestones</span>
</div>

<!-- Page Header -->
<div class="milestones-page-header">
    <div class="milestones-header-left">
        <h1 class="project-page-title">Milestones</h1>
        <p class="project-page-subtitle">Track major deliverables and payment milestones across orders</p>
    </div>
    <div class="milestones-header-right">
        <button class="project-btn project-btn-secondary" onclick="exportMilestones()">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="createMilestone()">
            <i class="fas fa-plus"></i>
            <span>Create Milestone</span>
        </button>
    </div>
</div>

<!-- Milestone Stats -->
<div class="milestone-stats-grid">
    <div class="milestone-stat-card">
        <div class="milestone-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-flag"></i>
        </div>
        <div class="milestone-stat-content">
            <div class="milestone-stat-value">24</div>
            <div class="milestone-stat-label">Total Milestones</div>
            <div class="milestone-stat-trend">
                <i class="fas fa-arrow-up"></i>
                <span>+3 this month</span>
            </div>
        </div>
    </div>

    <div class="milestone-stat-card">
        <div class="milestone-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="milestone-stat-content">
            <div class="milestone-stat-value">8</div>
            <div class="milestone-stat-label">In Progress</div>
            <div class="milestone-stat-trend">
                <i class="fas fa-exclamation-triangle"></i>
                <span>2 overdue</span>
            </div>
        </div>
    </div>

    <div class="milestone-stat-card">
        <div class="milestone-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="milestone-stat-content">
            <div class="milestone-stat-value">16</div>
            <div class="milestone-stat-label">Completed</div>
            <div class="milestone-stat-trend">
                <i class="fas fa-check"></i>
                <span>67% completion rate</span>
            </div>
        </div>
    </div>

    <div class="milestone-stat-card">
        <div class="milestone-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="milestone-stat-content">
            <div class="milestone-stat-value">PKR 2.4M</div>
            <div class="milestone-stat-label">Total Value</div>
            <div class="milestone-stat-trend">
                <i class="fas fa-check"></i>
                <span>PKR 1.6M paid</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Timeline View Toggle -->
<div class="milestones-toolbar">
    <div class="milestones-toolbar-left">
        <div class="project-search-box" style="max-width: 350px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search milestones..." id="milestonesSearch">
        </div>

        <select class="project-form-control project-select" style="width: auto; min-width: 160px;">
            <option value="">All Orders</option>
            <option value="order-1">Website Redesign</option>
            <option value="order-2">Mobile App</option>
            <option value="order-3">API Integration</option>
        </select>

        <select class="project-form-control project-select" style="width: auto; min-width: 140px;">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="progress">In Progress</option>
            <option value="review">Under Review</option>
            <option value="approved">Approved</option>
            <option value="paid">Paid</option>
        </select>
    </div>

    <div class="milestones-toolbar-right">
        <div class="view-switcher">
            <button class="view-btn active" data-view="list" title="List View">
                <i class="fas fa-list"></i>
            </button>
            <button class="view-btn" data-view="timeline" title="Timeline View">
                <i class="fas fa-stream"></i>
            </button>
            <button class="view-btn" data-view="calendar" title="Calendar View">
                <i class="fas fa-calendar"></i>
            </button>
        </div>

        <select class="project-form-control project-select" style="width: auto; min-width: 140px;">
            <option value="due">Sort by Due Date</option>
            <option value="amount">Sort by Amount</option>
            <option value="status">Sort by Status</option>
            <option value="created">Recently Created</option>
        </select>
    </div>
</div>

<!-- Milestones List View -->
<div class="milestones-list-view" id="milestonesListView">
    <!-- Overdue Milestones -->
    <div class="milestone-group">
        <div class="milestone-group-header">
            <div class="milestone-group-title">
                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                <span>Overdue (2)</span>
            </div>
        </div>

        @for($i = 1; $i <= 2; $i++)
            <div class="milestone-card milestone-overdue">
                <div class="milestone-card-header">
                    <div class="milestone-card-left">
                        <div class="milestone-number">M{{ 100 + $i }}</div>
                        <div class="milestone-info">
                            <h4 class="milestone-title">{{ ['Initial Design Mockups', 'Backend API Development'][$i - 1] }}</h4>
                            <div class="milestone-meta">
                                <span class="milestone-order">
                                    <i class="fas fa-box"></i>
                                    {{ ['Website Redesign', 'Mobile App'][$i - 1] }}
                                </span>
                                <span class="milestone-amount">
                                    <i class="fas fa-money-bill-wave"></i>
                                    PKR {{ number_format(rand(80, 150) * 1000) }}
                                </span>
                                <span class="milestone-date overdue-date">
                                    <i class="fas fa-calendar-times"></i>
                                    Due {{ now()->subDays(rand(2, 7))->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="milestone-card-right">
                        <span class="milestone-status-badge milestone-status-progress">In Progress</span>
                        <button class="milestone-menu-btn" onclick="openMilestoneMenu({{ $i }})">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <div class="milestone-progress-section">
                    <div class="progress-info">
                        <span class="progress-label">Completion</span>
                        <span class="progress-percentage">{{ rand(60, 85) }}%</span>
                    </div>
                    <div class="milestone-progress-bar">
                        <div class="milestone-progress-fill" style="width: {{ rand(60, 85) }}%;"></div>
                    </div>
                </div>

                <div class="milestone-footer">
                    <div class="milestone-assignee">
                        <span class="assignee-label">Assigned to:</span>
                        <div class="assignee-avatars">
                            @for($j = 1; $j <= rand(2, 4); $j++)
                                <img src="https://ui-avatars.com/api/?name=User+{{ $j }}&background={{ ['667eea', 'f093fb', '4facfe'][$j % 3] }}&color=fff" 
                                     alt="User" 
                                     class="assignee-avatar"
                                     title="User {{ $j }}">
                            @endfor
                        </div>
                    </div>
                    <div class="milestone-actions">
                        <button class="milestone-action-btn" onclick="viewMilestoneDetails({{ $i }})">
                            <i class="fas fa-eye"></i>
                            <span>View Details</span>
                        </button>
                        <button class="milestone-action-btn milestone-action-primary" onclick="requestApproval({{ $i }})">
                            <i class="fas fa-check"></i>
                            <span>Request Approval</span>
                        </button>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Upcoming Milestones -->
    <div class="milestone-group">
        <div class="milestone-group-header">
            <div class="milestone-group-title">
                <i class="fas fa-calendar-day" style="color: #3b82f6;"></i>
                <span>Upcoming (6)</span>
            </div>
        </div>

        @for($i = 3; $i <= 8; $i++)
            @php
                $statuses = ['pending', 'progress', 'review'];
                $status = $statuses[$i % 3];
            @endphp
            <div class="milestone-card">
                <div class="milestone-card-header">
                    <div class="milestone-card-left">
                        <div class="milestone-number">M{{ 100 + $i }}</div>
                        <div class="milestone-info">
                            <h4 class="milestone-title">{{ ['Frontend Development', 'Database Schema', 'User Authentication', 'Payment Integration', 'Admin Dashboard', 'Final Testing & QA'][$i - 3] }}</h4>
                            <div class="milestone-meta">
                                <span class="milestone-order">
                                    <i class="fas fa-box"></i>
                                    {{ ['Website Redesign', 'Mobile App', 'API Integration'][$i % 3] }}
                                </span>
                                <span class="milestone-amount">
                                    <i class="fas fa-money-bill-wave"></i>
                                    PKR {{ number_format(rand(80, 200) * 1000) }}
                                </span>
                                <span class="milestone-date">
                                    <i class="fas fa-calendar-check"></i>
                                    Due {{ now()->addDays(rand(3, 30))->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="milestone-card-right">
                        <span class="milestone-status-badge milestone-status-{{ $status }}">
                            {{ ['Pending', 'In Progress', 'Under Review'][$i % 3] }}
                        </span>
                        <button class="milestone-menu-btn" onclick="openMilestoneMenu({{ $i }})">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <div class="milestone-progress-section">
                    <div class="progress-info">
                        <span class="progress-label">Completion</span>
                        <span class="progress-percentage">{{ rand(0, 70) }}%</span>
                    </div>
                    <div class="milestone-progress-bar">
                        <div class="milestone-progress-fill" style="width: {{ rand(0, 70) }}%;"></div>
                    </div>
                </div>

                <div class="milestone-footer">
                    <div class="milestone-assignee">
                        <span class="assignee-label">Assigned to:</span>
                        <div class="assignee-avatars">
                            @for($j = 1; $j <= rand(1, 3); $j++)
                                <img src="https://ui-avatars.com/api/?name=User+{{ $j }}&background={{ ['667eea', 'f093fb', '4facfe'][$j % 3] }}&color=fff" 
                                     alt="User" 
                                     class="assignee-avatar"
                                     title="User {{ $j }}">
                            @endfor
                        </div>
                    </div>
                    <div class="milestone-actions">
                        <button class="milestone-action-btn" onclick="viewMilestoneDetails({{ $i }})">
                            <i class="fas fa-eye"></i>
                            <span>View Details</span>
                        </button>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Completed Milestones -->
    <div class="milestone-group milestone-group-collapsed" id="completedGroup">
        <div class="milestone-group-header" onclick="toggleMilestoneGroup('completedGroup')">
            <div class="milestone-group-title">
                <i class="fas fa-check-circle" style="color: #10b981;"></i>
                <span>Completed (16)</span>
            </div>
            <button class="milestone-group-toggle">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div class="milestone-group-content">
            @for($i = 9; $i <= 12; $i++)
                <div class="milestone-card milestone-completed">
                    <div class="milestone-card-header">
                        <div class="milestone-card-left">
                            <div class="milestone-number">M{{ 90 + $i }}</div>
                            <div class="milestone-info">
                                <h4 class="milestone-title">{{ ['Project Kickoff', 'Requirements Analysis', 'Architecture Design', 'Prototype Development'][$i - 9] }}</h4>
                                <div class="milestone-meta">
                                    <span class="milestone-order">
                                        <i class="fas fa-box"></i>
                                        {{ ['Website Redesign', 'Mobile App', 'API Integration', 'CRM System'][$i % 4] }}
                                    </span>
                                    <span class="milestone-amount paid">
                                        <i class="fas fa-check-circle"></i>
                                        PKR {{ number_format(rand(50, 150) * 1000) }} (Paid)
                                    </span>
                                    <span class="milestone-date completed-date">
                                        <i class="fas fa-calendar-check"></i>
                                        Completed {{ now()->subDays(rand(10, 60))->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="milestone-card-right">
                            <span class="milestone-status-badge milestone-status-paid">Paid</span>
                            <button class="milestone-menu-btn" onclick="openMilestoneMenu({{ $i }})">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>

                    <div class="milestone-footer">
                        <div class="milestone-assignee">
                            <span class="assignee-label">Completed by:</span>
                            <div class="assignee-avatars">
                                @for($j = 1; $j <= rand(1, 2); $j++)
                                    <img src="https://ui-avatars.com/api/?name=User+{{ $j }}&background={{ ['667eea', 'f093fb', '4facfe'][$j % 3] }}&color=fff" 
                                         alt="User" 
                                         class="assignee-avatar"
                                         title="User {{ $j }}">
                                @endfor
                            </div>
                        </div>
                        <div class="milestone-actions">
                            <button class="milestone-action-btn" onclick="viewMilestoneDetails({{ $i }})">
                                <i class="fas fa-eye"></i>
                                <span>View Details</span>
                            </button>
                            <button class="milestone-action-btn" onclick="viewInvoice({{ $i }})">
                                <i class="fas fa-file-invoice"></i>
                                <span>View Invoice</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Timeline View (Hidden by default) -->
<div class="milestones-timeline-view" id="milestonesTimelineView" style="display: none;">
    <div class="timeline-container">
        <div class="timeline-line"></div>
        
        @for($i = 1; $i <= 8; $i++)
            @php
                $isPast = $i <= 3;
                $isFuture = $i > 6;
            @endphp
            <div class="timeline-item {{ $isPast ? 'timeline-past' : ($isFuture ? 'timeline-future' : 'timeline-current') }}">
                <div class="timeline-marker">
                    <div class="timeline-marker-dot"></div>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">{{ $isPast ? now()->subDays((4 - $i) * 10)->format('M d, Y') : now()->addDays($i * 7)->format('M d, Y') }}</div>
                    <div class="timeline-card">
                        <div class="timeline-card-header">
                            <h4 class="timeline-title">Milestone {{ $i }}</h4>
                            <span class="timeline-badge {{ $isPast ? 'timeline-badge-completed' : ($isFuture ? 'timeline-badge-future' : 'timeline-badge-active') }}">
                                {{ $isPast ? 'Completed' : ($isFuture ? 'Upcoming' : 'In Progress') }}
                            </span>
                        </div>
                        <p class="timeline-description">{{ ['Project Kickoff & Planning', 'Design Phase', 'Development Sprint 1', 'Development Sprint 2', 'Testing & QA', 'Client Review', 'Final Adjustments', 'Deployment & Handover'][$i - 1] }}</p>
                        <div class="timeline-meta">
                            <span class="timeline-amount">PKR {{ number_format(rand(80, 200) * 1000) }}</span>
                            <span class="timeline-order">{{ ['Website Redesign', 'Mobile App', 'API Integration'][$i % 3] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<style>
    /* ===================================== 
       MILESTONES PAGE STYLES
    ===================================== */

    /* Page Header */
    .milestones-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .milestones-header-left {
        flex: 1;
        min-width: 300px;
    }

    .milestones-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Milestone Stats */
    .milestone-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .milestone-stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .milestone-stat-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .milestone-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .milestone-stat-content {
        flex: 1;
    }

    .milestone-stat-value {
        font-size: 24px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .milestone-stat-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .milestone-stat-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Toolbar */
    .milestones-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .milestones-toolbar-left,
    .milestones-toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Milestone Group */
    .milestone-group {
        margin-bottom: 32px;
    }

    .milestone-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding: 12px 16px;
        background: var(--bg);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .milestone-group-header:hover {
        background: var(--card);
    }

    .milestone-group-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .milestone-group-toggle {
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
        transition: all 0.2s ease;
    }

    .milestone-group-toggle:hover {
        background: var(--bg);
    }

    .milestone-group-collapsed .milestone-group-toggle i {
        transform: rotate(-90deg);
    }

    .milestone-group-content {
        display: flex;
        flex-direction: column;
        gap: 16px;
        max-height: 5000px;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .milestone-group-collapsed .milestone-group-content {
        max-height: 0;
    }

    /* Milestone Card */
    .milestone-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-left: 4px solid #3b82f6;
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .milestone-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(4px);
    }

    .milestone-card.milestone-overdue {
        border-left-color: #ef4444;
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.05) 0%, var(--card) 20%);
    }

    .milestone-card.milestone-completed {
        border-left-color: #10b981;
        opacity: 0.9;
    }

    .milestone-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
        gap: 16px;
    }

    .milestone-card-left {
        display: flex;
        gap: 16px;
        flex: 1;
        min-width: 0;
    }

    .milestone-number {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 12px;
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
        font-family: monospace;
        flex-shrink: 0;
    }

    .milestone-info {
        flex: 1;
        min-width: 0;
    }

    .milestone-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 8px 0;
    }

    .milestone-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .milestone-meta > span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .milestone-meta i {
        font-size: var(--ic-sm);
    }

    .milestone-amount {
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .milestone-amount.paid {
        color: #10b981;
    }

    .milestone-date.overdue-date {
        color: #ef4444;
        font-weight: var(--fw-semibold);
    }

    .milestone-date.completed-date {
        color: #10b981;
    }

    .milestone-card-right {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    /* Status Badges */
    .milestone-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .milestone-status-pending {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .milestone-status-progress {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .milestone-status-review {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .milestone-status-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .milestone-status-paid {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .milestone-menu-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .milestone-menu-btn:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    /* Progress Section */
    .milestone-progress-section {
        margin-bottom: 16px;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .progress-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-muted);
    }

    .progress-percentage {
        font-size: var(--fs-body);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
    }

    .milestone-progress-bar {
        height: 8px;
        background: var(--card);
        border-radius: 4px;
        overflow: hidden;
    }

    .milestone-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 4px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Milestone Footer */
    .milestone-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16pxborder-top: 1px solid var(--border);
        gap: 16px;
        flex-wrap: wrap;
    }

    .milestone-assignee {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .assignee-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    .assignee-avatars {
        display: flex;
        align-items: center;
    }

    .assignee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid var(--card);
        margin-left: -8px;
        transition: all 0.2s ease;
    }

    .assignee-avatar:first-child {
        margin-left: 0;
    }

    .assignee-avatar:hover {
        transform: translateY(-4px) scale(1.1);
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .milestone-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .milestone-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: none;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .milestone-action-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .milestone-action-btn.milestone-action-primary {
        background: var(--accent);
        color: var(--btn-text-primary);
        border-color: var(--accent);
    }

    .milestone-action-btn.milestone-action-primary:hover {
        background: var(--accent-dark);
    }

    /* Timeline View */
    .milestones-timeline-view {
        position: relative;
        padding: 40px 0;
    }

    .timeline-container {
        position: relative;
        max-width: 900px;
        margin: 0 auto;
    }

    .timeline-line {
        position: absolute;
        left: 40px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #3b82f6 0%, #10b981 100%);
    }

    .timeline-item {
        position: relative;
        padding-left: 100px;
        margin-bottom: 40px;
    }

    .timeline-marker {
        position: absolute;
        left: 24px;
        top: 8px;
    }

    .timeline-marker-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--card);
        border: 4px solid #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transition: all 0.3s ease;
    }

    .timeline-item.timeline-past .timeline-marker-dot {
        background: #10b981;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .timeline-item.timeline-future .timeline-marker-dot {
        background: var(--card);
        border-color: var(--border);
        box-shadow: none;
    }

    .timeline-item.timeline-current .timeline-marker-dot {
        background: #3b82f6;
        border-color: #3b82f6;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.2);
        }
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-date {
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .timeline-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .timeline-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(4px);
    }

    .timeline-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        gap: 12px;
    }

    .timeline-title {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .timeline-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .timeline-badge-completed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .timeline-badge-active {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .timeline-badge-future {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .timeline-description {
        font-size: var(--fs-body);
        color: var(--text-body);
        margin: 0 0 12px 0;
        line-height: var(--lh-normal);
    }

    .timeline-meta {
        display: flex;
        gap: 16px;
        font-size: var(--fs-body);
    }

    .timeline-amount {
        font-weight: var(--fw-semibold);
        color: var(--accent);
    }

    .timeline-order {
        color: var(--text-muted);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .milestones-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .milestones-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .milestone-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .milestone-card-header {
            flex-direction: column;
        }

        .milestone-card-left {
            flex-direction: column;
        }

        .milestone-meta {
            flex-direction: column;
            gap: 8px;
        }

        .milestone-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .milestone-actions {
            width: 100%;
        }

        .milestone-action-btn {
            flex: 1;
            justify-content: center;
        }

        .timeline-item {
            padding-left: 60px;
        }

        .timeline-line {
            left: 20px;
        }

        .timeline-marker {
            left: 4px;
        }
    }
</style>

<script>
    // ===================================== 
    // MILESTONES PAGE FUNCTIONALITY
    // ===================================== 

    function exportMilestones() {
        console.log('Export Milestones');
        alert('Export Milestones - Coming Soon!');
    }

    function createMilestone() {
        console.log('Create Milestone');
        alert('Create Milestone Modal - Coming Soon!');
    }

    function openMilestoneMenu(id) {
        console.log('Open milestone menu', id);
    }

    function viewMilestoneDetails(id) {
        console.log('View milestone details', id);
        window.location.href = `?milestone=${id}`;
    }

    function requestApproval(id) {
        console.log('Request approval for milestone', id);
        if (confirm('Request client approval for this milestone?')) {
            alert('Approval request sent to client!');
        }
    }

    function viewInvoice(id) {
        console.log('View invoice', id);
        alert('View Invoice - Coming Soon!');
    }

    function toggleMilestoneGroup(groupId) {
        const group = document.getElementById(groupId);
        group.classList.toggle('milestone-group-collapsed');
    }

    // View Switcher
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.getAttribute('data-view');
            const listView = document.getElementById('milestonesListView');
            const timelineView = document.getElementById('milestonesTimelineView');
            
            if (view === 'list') {
                listView.style.display = 'block';
                timelineView.style.display = 'none';
            } else if (view === 'timeline') {
                listView.style.display = 'none';
                timelineView.style.display = 'block';
            } else if (view === 'calendar') {
                alert('Calendar View - Coming Soon!');
            }
        });
    });

    console.log('✅ Milestones Page Initialized');
</script>

@endsection