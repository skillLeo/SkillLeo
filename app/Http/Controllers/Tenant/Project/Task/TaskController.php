<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\BaseTenantController;

class TaskController extends BaseTenantController
{
    /**
     * GET /{username}/tasks/my-tasks
     * Tasks assigned TO me (I need to complete)
     */
    public function assignedToMe(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $tasksQuery = Task::query()
            ->with(['project', 'assignee', 'reporter', 'subtasks'])
            ->withinWorkspace($this->workspaceOwner)
            ->where('assigned_to', $this->viewer->id)
            ->whereNotIn('status', ['done']);
        
        // Apply filters
        $tasks = match($filter) {
            'overdue' => $tasksQuery->where('due_date', '<', now())->get(),
            'today' => $tasksQuery->whereDate('due_date', today())->get(),
            'upcoming' => $tasksQuery->where('due_date', '>', now())->orderBy('due_date')->get(),
            'urgent' => $tasksQuery->where('priority', 'urgent')->get(),
            'high' => $tasksQuery->where('priority', 'high')->get(),
            'in-progress' => $tasksQuery->where('status', 'in-progress')->get(),
            'review' => $tasksQuery->where('status', 'review')->get(),
            'blocked' => $tasksQuery->where('status', 'blocked')->get(),
            default => $tasksQuery->orderBy('due_date')->orderBy('priority')->get(),
        };
        
        return view('tenant.manage.projects.tasks.index', [
            'username' => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer' => $this->viewer,
            'tasks' => $tasks,
            'activeFilter' => $filter,
            'context'         => 'mine', // or 'delegated'

        ]);
    }
    
    /**
     * GET /{username}/tasks/assigned-out
     * Tasks I assigned to others (I'm tracking)
     */
    public function assignedByMe(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $tasksQuery = Task::query()
            ->with(['project', 'assignee', 'reporter', 'subtasks'])
            ->withinWorkspace($this->workspaceOwner)
            ->where('reporter_id', $this->viewer->id)
            ->whereNotIn('status', ['done']);
        
        // Apply filters
        $tasks = match($filter) {
            'overdue' => $tasksQuery->where('due_date', '<', now())->get(),
            'today' => $tasksQuery->whereDate('due_date', today())->get(),
            'upcoming' => $tasksQuery->where('due_date', '>', now())->orderBy('due_date')->get(),
            'urgent' => $tasksQuery->where('priority', 'urgent')->get(),
            'high' => $tasksQuery->where('priority', 'high')->get(),
            'in-progress' => $tasksQuery->where('status', 'in-progress')->get(),
            'review' => $tasksQuery->where('status', 'review')->get(),
            'blocked' => $tasksQuery->where('status', 'blocked')->get(),
            default => $tasksQuery->orderBy('due_date')->orderBy('priority')->get(),
        };
        
        return view('tenant.manage.projects.tasks.index', [
            'username' => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer' => $this->viewer,
            'tasks' => $tasks,
            'activeFilter' => $filter,
            'context'         => 'delegated', // or 'delegated'

        ]);
    }
}