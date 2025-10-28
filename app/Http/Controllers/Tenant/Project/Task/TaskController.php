<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\BaseTenantController;
use Illuminate\Database\Eloquent\Builder;

class TaskController extends BaseTenantController
{
    /**
     * GET /{username}/manage/projects/tasks/my-tasks
     * Tasks that are ASSIGNED TO the current viewer (the viewer needs to do them)
     */
    public function assignedToMe(Request $request)
    {
        $filter = $request->get('filter', 'all');

        // base query: only tasks where I'm the assignee
        $baseQuery = Task::query()
            ->with([
                'project:id,name,key,user_id',
                'assignee:id,name,avatar_url',
                'reporter:id,name,avatar_url',
                'subtasks' => function ($query) {
                    $query->orderBy('order')
                          ->orderBy('id');
                },
                'attachments',
            ])
            ->withinWorkspace($this->workspaceOwner)
            ->where('assigned_to', $this->viewer->id);

        // apply UI filter (overdue, urgent, etc.) + sorting
        $tasks = $this->applyTaskFilter(clone $baseQuery, $filter)->get();

        return view('tenant.manage.projects.tasks.index', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasks'           => $tasks,
            'activeFilter'    => $filter,
            'context'         => 'mine', // used by blade header ("My Tasks")
        ]);
    }

    /**
     * GET /{username}/manage/projects/tasks/assigned-out
     * Tasks that I REPORTED / CREATED and assigned to someone else.
     * (I'm tracking them, not doing them)
     */
    public function assignedByMe(Request $request)
    {
        $filter = $request->get('filter', 'all');

        // base query: only tasks where I'm the reporter
        $baseQuery = Task::query()
            ->with([
                'project:id,name,key,user_id',
                'assignee:id,name,avatar_url',
                'reporter:id,name,avatar_url',
                'subtasks' => function ($query) {
                    $query->orderBy('order')
                          ->orderBy('id');
                },
                'attachments',
            ])
            ->withinWorkspace($this->workspaceOwner)
            ->where('reporter_id', $this->viewer->id);

        // apply UI filter and sorting
        $tasks = $this->applyTaskFilter(clone $baseQuery, $filter)->get();

        return view('tenant.manage.projects.tasks.index', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasks'           => $tasks,
            'activeFilter'    => $filter,
            'context'         => 'delegated', // used by blade header ("Assigned Out")
        ]);
    }

    /**
     * Apply the "filter=" from the query string to the base builder.
     *
     * Supported filters:
     *  - all
     *  - overdue
     *  - today
     *  - upcoming
     *  - urgent
     *  - high
     *  - in-progress
     *  - review
     *  - blocked
     */
    protected function applyTaskFilter(Builder $query, string $filter): Builder
    {
        switch ($filter) {
            case 'overdue':
                // due_date in the past (not null)
                $query->whereNotNull('due_date')
                      ->where('due_date', '<', now());
                break;

            case 'today':
                // due today
                $query->whereDate('due_date', today());
                break;

            case 'upcoming':
                // due in the future (after right now)
                $query->whereNotNull('due_date')
                      ->where('due_date', '>', now())
                      ->orderBy('due_date', 'asc');
                break;

            case 'urgent':
                $query->where('priority', 'urgent');
                break;

            case 'high':
                $query->where('priority', 'high');
                break;

            case 'in-progress':
                $query->where('status', 'in-progress');
                break;

            case 'review':
                $query->where('status', 'review');
                break;

            case 'blocked':
                $query->where('status', 'blocked');
                break;

            case 'all':
            default:
                // default sort for "all"
                $query->orderBy('due_date', 'asc')
                      ->orderBy('priority', 'asc');
                break;
        }

        return $query;
    }
}
