<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Tenant\BaseTenantController;
use App\Models\Project;
use App\Models\Task;

class ProjectTaskListController extends BaseTenantController
{
    /**
     * GET /{project}/list
     * Spreadsheet-style list view of all tasks in this project.
     */
    public function index($username, Project $project)
    {
        abort_unless($project->user_id === $this->workspaceOwner->id, 403);

        $tasks = Task::query()
            ->with(['assignee', 'reporter'])
            ->where('project_id', $project->id)
            ->orderBy('due_date')
            ->orderBy('status')
            ->orderBy('order')
            ->get();

        return view('tenant.manage.projects.project.list', [
            'username'       => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer'         => $this->viewer,
            'project'        => $project,
            'tasks'          => $tasks,
        ]);
    }
}
