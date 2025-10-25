{{-- resources/views/tenant/projects/clients/index.blade.php --}}
@extends('tenant.manage.app')
@section('main')

<!-- Breadcrumbs -->
<div class="project-breadcrumbs">
    <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
        <i class="fas fa-home"></i> Projects
    </a>
    <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
    <span class="project-breadcrumb-item active">Clients</span>
</div>

<!-- Page Header -->
<div class="clients-page-header">
    <div class="clients-header-left">
        <h1 class="project-page-title">Clients</h1>
        <p class="project-page-subtitle">Manage client relationships and track project portfolios</p>
    </div>
    <div class="clients-header-right">
        <button class="project-btn project-btn-secondary" onclick="exportClients()">
            <i class="fas fa-download"></i>
            <span>Export</span>
        </button>
        <button class="project-btn project-btn-primary" onclick="addClient()">
            <i class="fas fa-user-plus"></i>
            <span>Add Client</span>
        </button>
    </div>
</div>

<!-- Client Stats -->
<div class="client-stats-grid">
    <div class="client-stat-card">
        <div class="client-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="fas fa-users"></i>
        </div>
        <div class="client-stat-content">
            <div class="client-stat-value">{{ $stats['total_clients'] ?? 0 }}</div>
            <div class="client-stat-label">Total Clients</div>
            <div class="client-stat-trend">
                <i class="fas fa-arrow-up"></i>
                <span>+4 this month</span>
            </div>
        </div>
    </div>

    <div class="client-stat-card">
        <div class="client-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="client-stat-content">
            <div class="client-stat-value">{{ $stats['active_projects'] ?? 0 }}</div>
            <div class="client-stat-label">Active Projects</div>
            <div class="client-stat-trend">
                <i class="fas fa-check"></i>
                <span>18 completed</span>
            </div>
        </div>
    </div>

    <div class="client-stat-card">
        <div class="client-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="client-stat-content">
            <div class="client-stat-value">
                PKR {{ number_format($stats['total_order_value'] ?? 0, 2) }}
            </div>
            <div class="client-stat-label">Total Revenue</div>
            <div class="client-stat-trend">
                <i class="fas fa-arrow-up"></i>
                <span>+22% vs last month</span>
            </div>
        </div>
    </div>

    <div class="client-stat-card">
        <div class="client-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-star"></i>
        </div>
        <div class="client-stat-content">
            <div class="client-stat-value">
                {{ $stats['payment_completion_pct'] ?? 0 }}%
            </div>
            <div class="client-stat-label">Avg. Rating</div>
            <div class="client-stat-trend">
                <i class="fas fa-check"></i>
                <span>From 24 reviews</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="clients-toolbar">
    <div class="clients-toolbar-left">
        <div class="project-search-box" style="max-width: 350px;">
            <i class="fas fa-search project-search-icon"></i>
            <input type="text" placeholder="Search clients..." id="clientsSearch">
        </div>

        <select class="project-form-control project-select" style="width: auto; min-width: 140px;">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="vip">VIP</option>
        </select>

        <select class="project-form-control project-select" style="width: auto; min-width: 140px;">
            <option value="">All Industries</option>
            <option value="tech">Technology</option>
            <option value="ecommerce">E-commerce</option>
            <option value="healthcare">Healthcare</option>
            <option value="finance">Finance</option>
        </select>
    </div>

    <div class="clients-toolbar-right">
        <div class="view-switcher">
            <button class="view-btn active" data-view="grid" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="list" title="List View">
                <i class="fas fa-list"></i>
            </button>
        </div>

        <select class="project-form-control project-select" style="width: auto; min-width: 160px;">
            <option value="name">Sort by Name</option>
            <option value="revenue">Sort by Revenue</option>
            <option value="projects">Sort by Projects</option>
            <option value="recent">Recently Added</option>
        </select>
    </div>
</div>

<!-- Clients Grid View -->
<div class="clients-grid-view" id="clientsGridView">
    @foreach($clients as $index => $client)
        @php
            $i = $index + 1;

            // pick some colors just like the demo arrays did
            $colorsStart = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#ffd500'];
            $colorsEnd   = ['#764ba2', '#f5576c', '#00f2fe', '#4facfe', '#f5576c', '#ff6b6b'];
            $colorStart  = $colorsStart[$i % 6];
            $colorEnd    = $colorsEnd[$i % 6];

            // derive "status" similar to old mock data ('vip', 'active', etc.)
            $status = ($client->priority ?? '') === 'urgent' ? 'vip' : 'active';

            // "industry" placeholder: using priority as a label if nothing else
            $industry = $client->priority ? ucfirst($client->priority) : 'General';

            // company/name
            $companyName = $client->company 
                ?? ($client->clientUser->full_name ?? $client->clientUser->name ?? 'Client '.$client->id);

            $initials = strtoupper(mb_substr($companyName, 0, 2));

            // contact person, email, phone
            $contactPerson = $client->clientUser->full_name 
                ?? $client->clientUser->name 
                ?? '—';

            $contactEmail = $client->clientUser->email ?? '—';
            $contactPhone = $client->phone ?? '—';

            // stats
            $projectsCount = $client->projects?->count() ?? 0;
            $revenueAmount = $client->order_value ?? 0;
            $revenueCurrency = $client->currency ?? 'PKR';

            if ($client->payment_status === 'paid') {
                $paidPercentVal = 100;
            } elseif ($client->payment_status === 'partial') {
                $paidPercentVal = 50;
            } else {
                $paidPercentVal = 0;
            }
        @endphp

        <div class="client-card">
            <div class="client-card-header">
                <div class="client-avatar-wrapper">
                    <div class="client-avatar" style="background: linear-gradient(135deg, {{ $colorStart }} 0%, {{ $colorEnd }} 100%);">
                        <span>{{ $initials }}</span>
                    </div>
                    @if($status === 'vip')
                        <span class="client-badge-vip" title="VIP Client">
                            <i class="fas fa-crown"></i>
                        </span>
                    @endif
                </div>
                <button class="client-menu-btn" onclick="openClientMenu({{ $i }})">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <div class="client-info">
                <h4 class="client-name">{{ $companyName }}</h4>
                <p class="client-industry">{{ $industry }}</p>
            </div>

            <div class="client-contact">
                <div class="contact-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $contactPerson }}</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $contactEmail }}</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $contactPhone }}</span>
                </div>
            </div>

            <div class="client-stats-mini">
                <div class="client-stat-mini">
                    <span class="stat-mini-value">{{ $projectsCount }}</span>
                    <span class="stat-mini-label">Projects</span>
                </div>
                <div class="client-stat-mini">
                    <span class="stat-mini-value">{{ $revenueCurrency }} {{ number_format($revenueAmount, 2) }}</span>
                    <span class="stat-mini-label">Revenue</span>
                </div>
                <div class="client-stat-mini">
                    <span class="stat-mini-value">{{ $paidPercentVal }}%</span>
                    <span class="stat-mini-label">Paid</span>
                </div>
            </div>

            <div class="client-card-footer">
                <button class="client-action-btn" onclick="viewClientDetails({{ $i }})">
                    <i class="fas fa-eye"></i>
                    <span>View Details</span>
                </button>
                <button class="client-action-btn" onclick="contactClient({{ $i }})">
                    <i class="fas fa-comment"></i>
                    <span>Contact</span>
                </button>
            </div>
        </div>
    @endforeach
</div>

<!-- Clients List View (Hidden by default) -->
<div class="clients-list-view" id="clientsListView" style="display: none;">
    <table class="clients-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Contact Person</th>
                <th>Industry</th>
                <th>Projects</th>
                <th>Revenue</th>
                <th>Status</th>
                <th>Last Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $index => $client)
                @php
                    $i = $index + 1;

                    $colorsStart = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#ffd500'];
                    $colorsEnd   = ['#764ba2', '#f5576c', '#00f2fe', '#4facfe', '#f5576c', '#ff6b6b'];
                    $colorStart  = $colorsStart[$i % 6];
                    $colorEnd    = $colorsEnd[$i % 6];

                    $status = ($client->priority ?? '') === 'urgent' ? 'vip' : 'active';

                    $industry = $client->priority ? ucfirst($client->priority) : 'General';

                    $companyName = $client->company 
                        ?? ($client->clientUser->full_name ?? $client->clientUser->name ?? 'Client '.$client->id);

                    $initials = strtoupper(mb_substr($companyName, 0, 2));

                    $contactPerson = $client->clientUser->full_name 
                        ?? $client->clientUser->name 
                        ?? '—';

                    $projectsCount = $client->projects?->count() ?? 0;

                    $revenueAmount = $client->order_value ?? 0;
                    $revenueCurrency = $client->currency ?? 'PKR';

                    if ($client->payment_status === 'paid') {
                        $paidPercentVal = 100;
                    } elseif ($client->payment_status === 'partial') {
                        $paidPercentVal = 50;
                    } else {
                        $paidPercentVal = 0;
                    }

                    $lastContact = $client->updated_at
                        ? $client->updated_at->diffForHumans()
                        : '—';
                @endphp
                <tr class="clients-table-row">
                    <td>
                        <div class="table-client-info">
                            <div class="table-client-avatar" style="background: linear-gradient(135deg, {{ $colorStart }} 0%, {{ $colorEnd }} 100%);">
                                <span>{{ $initials }}</span>
                            </div>
                            <div>
                                <div class="table-client-name">
                                    {{ $companyName }}
                                    @if($status === 'vip')
                                        <i class="fas fa-crown" style="color: #f59e0b; margin-left: 4px;" title="VIP Client"></i>
                                    @endif
                                </div>
                                <div class="table-client-email">{{ $client->clientUser->email ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $contactPerson }}</td>
                    <td>
                        <span class="industry-badge">{{ $industry }}</span>
                    </td>
                    <td>{{ $projectsCount }}</td>
                    <td>
                        <span class="revenue-amount">{{ $revenueCurrency }} {{ number_format($revenueAmount, 2) }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $status }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td>{{ $lastContact }}</td>
                    <td>
                        <div class="table-actions">
                            <button class="table-action-btn" onclick="viewClientDetails({{ $i }})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="table-action-btn" onclick="contactClient({{ $i }})" title="Contact">
                                <i class="fas fa-comment"></i>
                            </button>
                            <button class="table-action-btn" onclick="viewProjects({{ $i }})" title="View Projects">
                                <i class="fas fa-briefcase"></i>
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
       CLIENTS PAGE STYLES
    ===================================== */

    /* Page Header */
    .clients-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .clients-header-left {
        flex: 1;
        min-width: 300px;
    }

    .clients-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Client Stats */
    .client-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .client-stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        transition: all 0.2s ease;
    }

    .client-stat-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .client-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .client-stat-content {
        flex: 1;
    }

    .client-stat-value {
        font-size: 24px;
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .client-stat-label {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .client-stat-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Toolbar */
    .clients-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .clients-toolbar-left,
    .clients-toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Clients Grid */
    .clients-grid-view {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .client-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        transition: all 0.2s ease;
    }

    .client-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
    }

    .client-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .client-avatar-wrapper {
        position: relative;
    }

    .client-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: var(--fw-bold);
        color: white;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .client-badge-vip {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 28px;
        height: 28px;
        background: #f59e0b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
    }

    .client-menu-btn {
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

    .client-menu-btn:hover {
        background: var(--bg);
        color: var(--text-body);
    }

    .client-info {
        text-align: center;
        margin-bottom: 20px;
    }

    .client-name {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .client-industry {
        font-size: var(--fs-body);
        color: var(--text-muted);
        margin: 0;
    }

    .client-contact {
        display: flex;flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
        padding: 16px;
        background: var(--bg);
        border-radius: 8px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: var(--fs-body);
        color: var(--text-body);
    }

    .contact-item i {
        width: 16px;
        font-size: var(--ic-sm);
        color: var(--text-muted);
    }

    .contact-item span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .client-stats-mini {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .client-stat-mini {
        text-align: center;
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
    }

    .stat-mini-value {
        display: block;
        font-size: var(--fs-h3);
        font-weight: var(--fw-bold);
        color: var(--text-heading);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-mini-label {
        display: block;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .client-card-footer {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .client-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 16px;
        background: none;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .client-action-btn:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    /* Clients Table */
    .clients-list-view {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .clients-table {
        width: 100%;
        border-collapse: collapse;
    }

    .clients-table thead {
        background: var(--bg);
        border-bottom: 2px solid var(--border);
    }

    .clients-table th {
        padding: 14px 16px;
        text-align: left;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .clients-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s ease;
    }

    .clients-table tbody tr:hover {
        background: var(--bg);
    }

    .clients-table td {
        padding: 14px 16px;
        font-size: var(--fs-body);
        color: var(--text-body);
        vertical-align: middle;
    }

    .table-client-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-client-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: var(--fw-bold);
        color: white;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .table-client-name {
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .table-client-email {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .industry-badge {
        display: inline-block;
        padding: 4px 10px;
        background: var(--accent-light);
        color: var(--accent);
        border-radius: 12px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
    }

    .revenue-amount {
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

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

    .status-inactive {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .status-vip {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
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

    /* Responsive */
    @media (max-width: 768px) {
        .clients-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .clients-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .client-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .clients-grid-view {
            grid-template-columns: 1fr;
        }

        .clients-list-view {
            overflow-x: auto;
        }
    }
</style>

<script>
    // ===================================== 
    // CLIENTS PAGE FUNCTIONALITY
    // ===================================== 

    function exportClients() {
        console.log('Export Clients');
        alert('Export Clients - Coming Soon!');
    }

    function addClient() {
        console.log('Add Client');
        alert('Add Client Modal - Coming Soon!');
    }

    function openClientMenu(id) {
        console.log('Open client menu', id);
    }

    function viewClientDetails(id) {
        console.log('View client details', id);
        alert('Client Details Page - Coming Soon!');
    }

    function contactClient(id) {
        console.log('Contact client', id);
        alert('Contact Client - Coming Soon!');
    }

    function viewProjects(id) {
        console.log('View client projects', id);
        alert('Client Projects - Coming Soon!');
    }

    // View Switcher
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.getAttribute('data-view');
            const gridView = document.getElementById('clientsGridView');
            const listView = document.getElementById('clientsListView');
            
            if (view === 'grid') {
                gridView.style.display = 'grid';
                listView.style.display = 'none';
            } else {
                gridView.style.display = 'none';
                listView.style.display = 'block';
            }
        });
    });

    console.log('✅ Clients Page Initialized');
</script>

@endsection