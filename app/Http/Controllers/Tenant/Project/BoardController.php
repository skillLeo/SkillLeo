<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Tenant\BaseTenantController;
use App\Models\Project;
use App\Models\Task;

class BoardController extends BaseTenantController
{
    /**
     * GET /{project}/board
     * Project execution board (Kanban)
     */
    public function show($username, Project $project)
    {
        // Security: project must belong to workspaceOwner
        abort_unless($project->user_id === $this->workspaceOwner->id, 403);

        // Load tasks in this project grouped by status (columns)
        $tasks = Task::query()
            ->with(['assignee', 'reporter', 'subtasks'])
            ->where('project_id', $project->id)
            ->orderBy('order')
            ->get()
            ->groupBy('status'); // e.g. todo, in_progress, review, blocked, postponed, done

        $columns = [
            Task::STATUS_TODO        => 'To Do',
            Task::STATUS_IN_PROGRESS => 'In Progress',
            Task::STATUS_REVIEW      => 'In Review',
            Task::STATUS_BLOCKED     => 'Blocked',
            Task::STATUS_POSTPONED   => 'Postponed',
            Task::STATUS_DONE        => 'Done',
        ];

        return view('tenant.manage.projects.project.board', [
            'username'       => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer'         => $this->viewer,
            'project'        => $project,
            'columns'        => $columns,
            'tasksByStatus'  => $tasks,
        ]);
    }
}
