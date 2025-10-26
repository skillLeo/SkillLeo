<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Tenant\BaseTenantController;
use App\Models\Project;
use App\Models\Task;

class BacklogController extends BaseTenantController
{
    /**
     * GET /{project}/backlog
     * Backlog / future work. Reorderable by PM / owner.
     */
    public function index($username, Project $project)
    {
        abort_unless($project->user_id === $this->workspaceOwner->id, 403);

        // For backlog we typically show tasks not started yet.
        $backlogTasks = Task::query()
            ->with(['assignee', 'reporter'])
            ->where('project_id', $project->id)
            ->whereIn('status', [Task::STATUS_TODO]) // only not-started
            ->orderBy('order')
            ->get();

        $canPrioritize = $this->viewer->id === $this->workspaceOwner->id
                      || $project->user_id === $this->viewer->id
                      || $this->viewer->canSeeAllTasks($this->workspaceOwner);

        return view('tenant.manage.projects.project.backlog', [
            'username'       => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer'         => $this->viewer,
            'project'        => $project,
            'backlogTasks'   => $backlogTasks,
            'canPrioritize'  => $canPrioritize,
        ]);
    }
}
