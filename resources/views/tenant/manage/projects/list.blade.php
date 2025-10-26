{{-- resources/views/tenant/manage/projects/list.blade.php --}}
@extends('tenant.manage.app')

@section('main')

    <!-- Breadcrumbs -->
    <div class="project-breadcrumbs">
        <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="project-breadcrumb-item">
            <i class="fas fa-home"></i> Projects
        </a>
        <span class="project-breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
        <span class="project-breadcrumb-item active">All Projects</span>
    </div>

    <!-- Page Header -->
    <div class="project-page-header">
        <div>
            <h1 class="project-page-title">All Projects</h1>
            <p class="project-page-subtitle">Manage your internal projects and client orders</p>
        </div>
        <div class="project-page-actions">
            <button class="project-btn project-btn-secondary">
                <i class="fas fa-download"></i>
                <span>Export</span>
            </button>
            <button type="button" class="project-btn project-btn-primary" onclick="openCreateProjectModal()">
                <i class="fas fa-plus"></i>
                <span>New Project</span>
            </button>
        </div>
    </div>

    <!-- Filters Bar -->
    @include('tenant.manage.projects.components.filters-bar', [
        'showSearch' => true,
        'showFilters' => ['type', 'status', 'assignee'],
        'showSort' => true,
    ])

    <!-- Stats Cards -->
    <div class="project-stats-grid">
        <div class="project-stat-card">
            <div class="project-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="project-stat-content">
                <div class="project-stat-value">{{ $stats['total'] }}</div>
                <div class="project-stat-label">Total Projects</div>
            </div>
        </div>

        <div class="project-stat-card">
            <div class="project-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="project-stat-content">
                <div class="project-stat-value">{{ $stats['active'] }}</div>
                <div class="project-stat-label">Active</div>
            </div>
        </div>

        <div class="project-stat-card">
            <div class="project-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="project-stat-content">
                <div class="project-stat-value">{{ $stats['orders'] }}</div>
                <div class="project-stat-label">Client Orders</div>
            </div>
        </div>

        <div class="project-stat-card">
            <div class="project-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="project-stat-content">
                <div class="project-stat-value">{{ number_format($stats['hours_this_month'], 1) }}h</div>
                <div class="project-stat-label">This Month</div>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    @if($projects->count() > 0)
        <div class="project-grid">
            @foreach($projects as $project)
                @php
                    $totalTasks     = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'done')->count();
                    $progress       = $totalTasks > 0
                        ? round(($completedTasks / $totalTasks) * 100)
                        : 0;

                    // Pick a repeatable color based on project id
                    $colors = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#feca57', '#48dbfb', '#ff9ff3'];
                    $color  = $colors[$project->id % count($colors)];
                @endphp

                @include('tenant.manage.projects.components.project-card', [
                    'project' => [
                        'id'        => $project->id,
                        'key'       => $project->key,
                        'name'      => $project->name,
                        'type'      => $project->client_id ? 'order' : 'project',
                        'status'    => $project->status,
                        'progress'  => $progress,
                        'client'    => $project->client?->clientUser?->name,
                        'team'      => $project->team->count(),
                        'tasks'     => $totalTasks,
                        'due_date'  => optional($project->due_date)->format('M d, Y'),
                        'color'     => $color,
                    ],
                    'username' => $username,
                ])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="project-pagination">
            {{ $projects->links() }}
        </div>
    @else
        <div class="project-empty-state">
            <div class="project-empty-state-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h3 class="project-empty-state-title">No projects yet</h3>
            <p class="project-empty-state-desc">Create your first project to get started</p>
            <button type="button" class="project-btn project-btn-primary" onclick="openCreateProjectModal()">
                <i class="fas fa-plus"></i>
                <span>Create Project</span>
            </button>
        </div>
    @endif

    <style>
        /* Stats Grid */
        .project-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .project-stat-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            transition: all 0.2s ease;
        }

        .project-stat-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .project-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .project-stat-content {
            flex: 1;
        }

        .project-stat-value {
            font-size: 24px;
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            line-height: 1;
            margin-bottom: 4px;
        }

        .project-stat-label {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        /* Projects Grid */
        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        /* Pagination */
        .project-pagination {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }

        /* Empty State */
        .project-empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .project-empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-light);
            border-radius: 50%;
            font-size: 32px;
            color: var(--accent);
        }

        .project-empty-state-title {
            font-size: var(--fs-h2);
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            margin: 0 0 8px 0;
        }

        .project-empty-state-desc {
            font-size: var(--fs-body);
            color: var(--text-muted);
            margin: 0 0 24px 0;
        }

        @media (max-width: 768px) {
            .project-grid {
                grid-template-columns: 1fr;
            }

            .project-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection

@push('modals')
    @include('tenant.manage.projects.modals.create-project')
@endpush
