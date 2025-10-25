{{-- resources/views/tenant/projects/team/index.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Team Members</span>
</div>

<!-- Page Header -->
<div class="team-page-header">
    <div class="team-header-left">
        <h1 class="project-page-title">Team Members</h1>
        <p class="project-page-subtitle">Manage your team and track their workload and performance</p>
    </div>
    <div class="team-header-right">
        <button class="project-btn project-btn-secondary" onclick="viewWorkload()">
            <i class="fas fa-chart-pie"></i>
            <span>View Workload</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="inviteMember()">
            <i class="fas fa-user-plus"></i>
            <span>Invite Member</span>
        </button>
    </div>
</div>

<!-- Team Stats -->
<div class="team-stats-grid">
    <div class="team-stat-card">
        <div class="team-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-users"></i>
        </div>
        <div class="team-stat-content">
            <div class="team-stat-value">{{ $stats['total_members'] ?? 0 }}</div>
            <div class="team-stat-label">Total Members</div>
        </div>
    </div>

    <div class="team-stat-card">
        <div class="team-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="team-stat-content">
            <div class="team-stat-value">{{ $stats['active_now'] ?? 0 }}</div>
            <div class="team-stat-label">Active Now</div>
        </div>
    </div>

    <div class="team-stat-card">
        <div class="team-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="team-stat-content">
            <div class="team-stat-value">{{ $stats['active_tasks'] ?? 0 }}</div>
            <div class="team-stat-label">Active Tasks</div>
        </div>
    </div>

    <div class="team-stat-card">
        <div class="team-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="team-stat-content">
            <div class="team-stat-value">{{ $stats['avg_capacity_pct'] ?? 0 }}%</div>
            <div class="team-stat-label">Avg. Capacity</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="team-toolbar">
    <div class="team-toolbar-left">
        <div class="project-search-box" style="max-width: 300px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search team members..." id="teamSearch">
        </div>

        <select class="project-form-control project-select" style="width: auto; min-width: 160px;">
            <option value="">All Roles</option>
            <option value="developer">Developer</option>
            <option value="designer">Designer</option>
            <option value="pm">Project Manager</option>
            <option value="qa">QA Engineer</option>
        </select>

        <select class="project-form-control project-select" style="width: auto; min-width: 140px;">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="away">Away</option>
            <option value="busy">Busy</option>
        </select>
    </div>

    <div class="team-toolbar-right">
        <div class="view-switcher">
            <button class="view-btn active" data-view="grid" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="list" title="List View">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
</div>

<!-- Team Members Grid -->
<div class="team-members-grid" id="teamGridView">
    @foreach($teamMembers as $index => $member)
        @php
            $i = $index + 1;

            // pick colors same pattern as mock just for avatar bg fallback if needed
            $bgColors = ['667eea', 'f093fb', '4facfe', '43e97b', 'fa709a'];

            // role (from pivot first project if available, then computed, then fallback)
            $role = $member->display_role 
                ?? ($member->projectsAsTeamMember->first()->pivot->role ?? null) 
                ?? 'Team Member';

            // status bubble style
            // map online_status -> badge class
            // online -> active (green), active_recently -> busy (red-ish), offline -> away (yellow)
            $statusClass = $member->computed_status ?? 'away';

            $activeTasks    = $member->active_tasks_count ?? 0;
            $completedTasks = $member->completed_tasks_count ?? 0;
            $workload       = $member->capacity_percent ?? 0;

            $nameForDisplay = $member->full_name ?: trim($member->name . ' ' . ($member->last_name ?? ''));
            if ($nameForDisplay === '') {
                $nameForDisplay = 'Member '.$member->id;
            }

            $avatarUrl = $member->avatar_url 
                ?? "https://ui-avatars.com/api/?name=" . urlencode($nameForDisplay) .
                   "&background=" . $bgColors[$i % 5] . "&color=fff";
        @endphp

        <div class="team-member-card">
            <!-- Member Header -->
            <div class="team-member-header">
                <div class="team-member-avatar-wrapper">
                    <img 
                        src="{{ $avatarUrl }}" 
                        alt="{{ $nameForDisplay }}" 
                        class="team-member-avatar">
                    <span class="member-status member-status-{{ $statusClass }}"></span>
                </div>
                <button class="team-member-menu" onclick="openMemberMenu({{ $member->id }})">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <!-- Member Info -->
            <div class="team-member-info">
                <h4 class="team-member-name">{{ $nameForDisplay }}</h4>
                <p class="team-member-role">{{ $role }}</p>
            </div>

            <!-- Member Stats -->
            <div class="team-member-stats">
                <div class="member-stat">
                    <span class="member-stat-label">Active Tasks</span>
                    <span class="member-stat-value">{{ $activeTasks }}</span>
                </div>
                <div class="member-stat">
                    <span class="member-stat-label">Completed</span>
                    <span class="member-stat-value">{{ $completedTasks }}</span>
                </div>
            </div>

            <!-- Workload Bar -->
            <div class="team-member-workload">
                <div class="workload-header">
                    <span class="workload-label">Workload</span>
                    <span class="workload-value {{ $workload > 90 ? 'overload' : '' }}">{{ $workload }}%</span>
                </div>
                <div class="workload-bar">
                    <div class="workload-fill {{ $workload > 90 ? 'overload' : '' }}" style="width: {{ $workload }}%;"></div>
                </div>
            </div>

            <!-- Member Actions -->
            <div class="team-member-actions">
                <button class="team-action-btn" onclick="assignTask({{ $member->id }})" title="Assign Task">
                    <i class="fas fa-tasks"></i>
                </button>
                <button class="team-action-btn" onclick="sendMessage({{ $member->id }})" title="Send Message">
                    <i class="fas fa-comment"></i>
                </button>
                <button class="team-action-btn" onclick="viewProfile({{ $member->id }})" title="View Profile">
                    <i class="fas fa-user"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

<!-- Team Members List (Hidden by default) -->
<div class="team-members-list" id="teamListView" style="display: none;">
    <table class="team-table">
        <thead>
            <tr>
                <th>Member</th>
                <th>Role</th>
                <th>Status</th>
                <th>Active Tasks</th>
                <th>Completed</th>
                <th>Workload</th>
                <th>Last Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teamMembers as $index => $member)
                @php
                    $i = $index + 1;

                    $bgColors = ['667eea', 'f093fb', '4facfe', '43e97b', 'fa709a'];

                    $role = $member->display_role 
                        ?? ($member->projectsAsTeamMember->first()->pivot->role ?? null) 
                        ?? 'Team Member';

                    $statusClass = $member->computed_status ?? 'away';

                    $activeTasks    = $member->active_tasks_count ?? 0;
                    $completedTasks = $member->completed_tasks_count ?? 0;
                    $workload       = $member->capacity_percent ?? 0;

                    $nameForDisplay = $member->full_name ?: trim($member->name . ' ' . ($member->last_name ?? ''));
                    if ($nameForDisplay === '') {
                        $nameForDisplay = 'Member '.$member->id;
                    }

                    $avatarUrl = $member->avatar_url 
                        ?? "https://ui-avatars.com/api/?name=" . urlencode($nameForDisplay) .
                           "&background=" . $bgColors[$i % 5] . "&color=fff";

                    $lastActive = $member->last_active_human ?? '—';
                @endphp
                <tr class="team-table-row">
                    <td>
                        <div class="table-member-info">
                            <div class="table-member-avatar-wrapper">
                                <img 
                                    src="{{ $avatarUrl }}" 
                                    alt="{{ $nameForDisplay }}" 
                                    class="table-member-avatar">
                                <span class="member-status member-status-{{ $statusClass }}"></span>
                            </div>
                            <div>
                                <div class="table-member-name">{{ $nameForDisplay }}</div>
                                <div class="table-member-email">{{ $member->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $role }}</td>
                    <td>
                        <span class="status-badge status-{{ $statusClass }}">
                            {{ ucfirst($statusClass) }}
                        </span>
                    </td>
                    <td>{{ $activeTasks }}</td>
                    <td>{{ $completedTasks }}</td>
                    <td>
                        <div class="table-workload">
                            <div class="table-workload-bar">
                                <div class="table-workload-fill {{ $workload > 90 ? 'overload' : '' }}" style="width: {{ $workload }}%;"></div>
                            </div>
                            <span class="table-workload-text {{ $workload > 90 ? 'overload' : '' }}">{{ $workload }}%</span>
                        </div>
                    </td>
                    <td>{{ $lastActive }}</td>
                    <td>
                        <div class="table-actions">
                            <button class="table-action-btn" onclick="assignTask({{ $member->id }})">
                                <i class="fas fa-tasks"></i>
                            </button>
                            <button class="table-action-btn" onclick="sendMessage({{ $member->id }})">
                                <i class="fas fa-comment"></i>
                            </button>
                            <button class="table-action-btn" onclick="viewProfile({{ $member->id }})">
                                <i class="fas fa-user"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

 

    <style>
        /* =====================================
           TEAM MEMBERS PAGE STYLES
        ===================================== */

        /* Page Header */
        .team-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .team-header-left {
            flex: 1;
            min-width: 300px;
        }

        .team-header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Team Stats */
        .team-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .team-stat-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            transition: all 0.2s ease;
        }

        .team-stat-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .team-stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .team-stat-content {
            flex: 1;
        }

        .team-stat-value {
            font-size: 28px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            line-height: 1;
            margin-bottom: 4px;
        }

        .team-stat-label {
            font-size: var(--fs-body);
            color: var(--text-muted);
        }

        /* Toolbar */
        .team-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .team-toolbar-left,
        .team-toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Team Members Grid */
        .team-members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .team-member-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            transition: all 0.2s ease;
            position: relative;
        }

        .team-member-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        /* Member Header */
        .team-member-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .team-member-avatar-wrapper {
            position: relative;
        }

        .team-member-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 3px solid var(--bg);
        }

        .member-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid var(--card);
        }

        .member-status-active {
            background: #10b981;
        }

        .member-status-busy {
            background: #ef4444;
        }

        .member-status-away {
            background: #f59e0b;
        }

        .team-member-menu {
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

        .team-member-menu:hover {
            background: var(--bg);
            color: var(--text-body);
        }

        /* Member Info */
        .team-member-info {
            text-align: center;
            margin-bottom: 16px;
        }

        .team-member-name {
            font-size: var(--fs-h3);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
            margin: 0 0 4px 0;
        }

        .team-member-role {
            font-size: var(--fs-body);
            color: var(--text-muted);
            margin: 0;
        }

        /* Member Stats */
        .team-member-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .member-stat {
            text-align: center;
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
        }

        .member-stat-label {
            display: block;
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .member-stat-value {
            display: block;
            font-size: 20px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            line-height: 1;
        }

        /* Workload */
        .team-member-workload {
            margin-bottom: 16px;
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
        }

        .workload-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .workload-label {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .workload-value {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-heading);
        }

        .workload-value.overload {
            color: #ef4444;
        }

        .workload-bar {
            height: 8px;
            background: var(--card);
            border-radius: 4px;
            overflow: hidden;
        }

        .workload-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .workload-fill.overload {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }

        /* Member Actions */
        .team-member-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .team-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .team-action-btn:hover {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Team Table */
        .team-members-list {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .team-table {
            width: 100%;
            border-collapse: collapse;
        }

        .team-table thead {
            background: var(--bg);
            border-bottom: 2px solid var(--border);
        }

        .team-table th {
            padding: 14px 16px;
            text-align: left;
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .team-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s ease;
        }

        .team-table tbody tr:hover {
            background: var(--bg);
        }

        .team-table td {
            padding: 14px 16px;
            font-size: var(--fs-body);
            color: var(--text-body);
            vertical-align: middle;
        }

        .table-member-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-member-avatar-wrapper {
            position: relative;
        }

        .table-member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .table-member-name {
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
            margin-bottom: 2px;
        }

        .table-member-email {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .table-workload {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-workload-bar {
            flex: 1;
            height: 6px;
            background: var(--bg);
            border-radius: 3px;
            overflow: hidden;
            min-width: 80px;
        }

        .table-workload-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .table-workload-fill.overload {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }

        .table-workload-text {
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
            white-space: nowrap;
        }

        .table-workload-text.overload {
            color: #ef4444;
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .table-action-btn {
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

        .table-action-btn:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: var(--fs-subtle);
            font-weight: var(--fw-semibold);
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-busy {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-away {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .team-page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .team-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .team-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .team-members-grid {
                grid-template-columns: 1fr;
            }

            /* Hide table on mobile, show cards only */
            .team-members-list {
                overflow-x: auto;
            }
        }
    </style>

    <script>
        // ===================================== 
        // TEAM MEMBERS PAGE FUNCTIONALITY
        // ===================================== 

        function viewWorkload() {
            window.location.href = '{{ route('tenant.manage.projects.team.workload', $username) }}';
        }

        function inviteMember() {
            alert('Invite Team Member - Coming Soon!');
        }

        function openMemberMenu(memberId) {
            console.log('Open member menu', memberId);
        }

        function assignTask(memberId) {
            console.log('Assign task to member', memberId);
            alert('Assign Task Modal - Coming Soon!');
        }

        function sendMessage(memberId) {
            console.log('Send message to member', memberId);
            alert('Message Modal - Coming Soon!');
        }

        function viewProfile(memberId) {
            console.log('View member profile', memberId);
        }

        // View switcher
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const view = this.getAttribute('data-view');
                const gridView = document.getElementById('teamGridView');
                const listView = document.getElementById('teamListView');

                if (view === 'grid') {
                    gridView.style.display = 'grid';
                    listView.style.display = 'none';
                } else {
                    gridView.style.display = 'none';
                    listView.style.display = 'block';
                }
            });
        });

        console.log('✅ Team Members Page Initialized');
    </script>
@endsection
