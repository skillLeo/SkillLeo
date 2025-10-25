{{-- resources/views/tenant/projects/components/filters-bar.blade.php --}}
<div class="project-filters-bar">
    <div class="project-filters-left">
        @if($showSearch ?? false)
            <div class="project-search-box">
                <i class="fas fa-search project-search-icon"></i>
                <input type="text" placeholder="Search projects, orders, tasks..." id="projectSearch">
            </div>
        @endif

        @if(in_array('type', $showFilters ?? []))
            <select class="project-form-control project-select" id="filterType">
                <option value="">All Types</option>
                <option value="project">Internal Projects</option>
                <option value="order">Client Orders</option>
            </select>
        @endif

        @if(in_array('status', $showFilters ?? []))
            <select class="project-form-control project-select" id="filterStatus">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="planning">Planning</option>
                <option value="on-hold">On Hold</option>
                <option value="completed">Completed</option>
            </select>
        @endif

        @if(in_array('assignee', $showFilters ?? []))
            <select class="project-form-control project-select" id="filterAssignee">
                <option value="">All Members</option>
                <option value="me">Assigned to me</option>
                <option value="unassigned">Unassigned</option>
            </select>
        @endif
    </div>

    <div class="project-filters-right">
        @if($showSort ?? false)
            <select class="project-form-control project-select" id="sortBy">
                <option value="recent">Most Recent</option>
                <option value="name">Name (A-Z)</option>
                <option value="due_date">Due Date</option>
                <option value="progress">Progress</option>
            </select>
        @endif

        <button class="project-icon-btn" title="Grid View">
            <i class="fas fa-th"></i>
        </button>
        <button class="project-icon-btn" title="List View">
            <i class="fas fa-list"></i>
        </button>
    </div>
</div>

<style>
    .project-filters-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .project-filters-left,
    .project-filters-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .project-filters-left {
        flex: 1;
    }

    .project-select {
        width: auto;
        min-width: 160px;
    }

    @media (max-width: 768px) {
        .project-filters-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .project-filters-left,
        .project-filters-right {
            width: 100%;
        }

        .project-search-box {
            max-width: 100%;
        }

        .project-select {
            flex: 1;
        }
    }
</style>