<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Tenant\BaseTenantController;

class AdvancedTaskController extends BaseTenantController
{
    /**
     * GET /{username}/manage/projects/tasks/my-tasks
     * Tasks assigned TO me (I need to do them)
     */
    public function assignedToMe(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $baseQuery = Task::query()
            ->with([
                'project:id,name,key,user_id',
                'assignee:id,name,avatar_url',
                'reporter:id,name,avatar_url',
                'subtasks' => fn($q) => $q->orderBy('order')->orderBy('id'),
                'attachments',
            ])
            ->withinWorkspace($this->workspaceOwner)
            ->where('assigned_to', $this->viewer->id);

        $tasks = $this->applyTaskFilter(clone $baseQuery, $filter)->get();

        return view('tenant.manage.projects.tasks.index', [
            'username' => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer' => $this->viewer,
            'tasks' => $tasks,
            'activeFilter' => $filter,
            'context' => 'mine',
        ]);
    }

    /**
     * GET /{username}/manage/projects/tasks/assigned-out
     * Tasks I CREATED/REPORTED (tracking others' work)
     */
    public function assignedByMe(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $baseQuery = Task::query()
            ->with([
                'project:id,name,key,user_id',
                'assignee:id,name,avatar_url',
                'reporter:id,name,avatar_url',
                'subtasks' => fn($q) => $q->orderBy('order')->orderBy('id'),
                'attachments',
            ])
            ->withinWorkspace($this->workspaceOwner)
            ->where('reporter_id', $this->viewer->id);

        $tasks = $this->applyTaskFilter(clone $baseQuery, $filter)->get();

        return view('tenant.manage.projects.tasks.index', [
            'username' => $this->workspaceOwner->username,
            'workspaceOwner' => $this->workspaceOwner,
            'viewer' => $this->viewer,
            'tasks' => $tasks,
            'activeFilter' => $filter,
            'context' => 'delegated',
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/assign
     * Reassign task to another team member or self
     */
    public function reassignTask(Request $request, string $username, Task $task)
    {
        // Only reporter can reassign
        if ($task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the task creator can reassign this task',
            ], 403);
        }

        $request->validate([
            'assign_to' => 'required|string|in:me,teammate',
            'user_id' => 'required_if:assign_to,teammate|exists:users,id',
            'note' => 'nullable|string|max:500',
        ]);

        $oldAssignee = $task->assignee;
        $newAssigneeId = null;

        if ($request->assign_to === 'me') {
            $newAssigneeId = $this->viewer->id;
        } else {
            $newAssigneeId = $request->user_id;
            
            // Verify user is in project team
            $isInTeam = $task->project->team()->where('user_id', $newAssigneeId)->exists();
            if (!$isInTeam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected user is not in the project team',
                ], 400);
            }
        }

        DB::transaction(function () use ($task, $newAssigneeId, $oldAssignee, $request) {
            $task->update([
                'assigned_to' => $newAssigneeId,
                'last_status_change_at' => now(),
            ]);

            // Log activity
            TaskActivity::create([
                'task_id' => $task->id,
                'actor_id' => $this->viewer->id,
                'type' => 'reassigned',
                'body' => $request->note ?? 
                    "Reassigned from " . ($oldAssignee->name ?? 'Unassigned') . 
                    " to " . ($task->fresh()->assignee->name ?? 'Unknown'),
            ]);
        });

        $task->load('assignee');

        return response()->json([
            'success' => true,
            'message' => '✅ Task reassigned successfully',
            'task' => [
                'id' => $task->id,
                'assigned_to' => $task->assigned_to,
                'assignee' => [
                    'id' => $task->assignee->id,
                    'name' => $task->assignee->name,
                    'avatar_url' => $task->assignee->avatar_url,
                ],
            ],
        ]);
    }

    /**
     * DELETE /{username}/manage/projects/tasks/{task}
     * Delete task (only reporter can delete)
     */
    public function destroy(string $username, Task $task)
    {
        // Only reporter can delete
        if ($task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the task creator can delete this task',
            ], 403);
        }

        DB::transaction(function () use ($task) {
            // Delete subtasks
            $task->subtasks()->delete();
            
            // Delete attachments
            foreach ($task->attachments as $attachment) {
                if ($attachment->path_or_url) {
                    Storage::disk('public')->delete($attachment->path_or_url);
                }
                $attachment->delete();
            }
            
            // Delete activities
            $task->activities()->delete();
            
            // Delete task
            $task->delete();
        });

        return response()->json([
            'success' => true,
            'message' => '✅ Task deleted successfully',
        ]);
    }

    /**
     * PUT /{username}/manage/projects/tasks/{task}
     * Update task details (only reporter can edit)
     */
    public function update(Request $request, string $username, Task $task)
    {
        // Only reporter can edit
        if ($task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the task creator can edit this task',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string|max:5000',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'story_points' => 'nullable|integer|min:0',
        ]);

        $changes = [];
        foreach ($validated as $key => $value) {
            if ($task->{$key} != $value) {
                $changes[$key] = [
                    'old' => $task->{$key},
                    'new' => $value,
                ];
            }
        }

        if (empty($changes)) {
            return response()->json([
                'success' => true,
                'message' => 'No changes detected',
            ]);
        }

        DB::transaction(function () use ($task, $validated, $changes) {
            $task->update($validated);

            // Log each change
            foreach ($changes as $field => $change) {
                TaskActivity::create([
                    'task_id' => $task->id,
                    'actor_id' => $this->viewer->id,
                    'type' => 'updated',
                    'body' => ucfirst(str_replace('_', ' ', $field)) . 
                        " changed from '{$change['old']}' to '{$change['new']}'",
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => '✅ Task updated successfully',
            'task' => $task->fresh(),
            'changes' => $changes,
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks
     * Create new task
     */
    public function store(Request $request, string $username)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string|max:5000',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'story_points' => 'nullable|integer|min:0',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required_with:subtasks|string|max:255',
        ]);

        // Verify project belongs to workspace
        $project = Project::findOrFail($validated['project_id']);
        if ($project->user_id !== $this->workspaceOwner->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $task = DB::transaction(function () use ($validated, $project) {
            $task = Task::create([
                'project_id' => $project->id,
                'title' => $validated['title'],
                'notes' => $validated['notes'] ?? null,
                'priority' => $validated['priority'],
                'status' => 'todo',
                'assigned_to' => $validated['assigned_to'] ?? null,
                'reporter_id' => $this->viewer->id,
                'due_date' => $validated['due_date'] ?? null,
                'estimated_hours' => $validated['estimated_hours'] ?? null,
                'story_points' => $validated['story_points'] ?? 0,
            ]);

            // Create subtasks
            if (!empty($validated['subtasks'])) {
                foreach ($validated['subtasks'] as $index => $subtaskData) {
                    Subtask::create([
                        'task_id' => $task->id,
                        'title' => $subtaskData['title'],
                        'order' => $index + 1,
                        'completed' => false,
                    ]);
                }
            }

            // Log creation
            TaskActivity::create([
                'task_id' => $task->id,
                'actor_id' => $this->viewer->id,
                'type' => 'created',
                'body' => 'Task created',
            ]);

            return $task;
        });

        $task->load(['project', 'assignee', 'reporter', 'subtasks']);

        return response()->json([
            'success' => true,
            'message' => '✅ Task created successfully',
            'task' => $task,
            'redirect_to' => route('tenant.manage.projects.project.show', [
                $username,
                $project->id,
                'tab' => 'list'
            ]),
        ], 201);
    }

    /**
     * GET /{username}/manage/projects/tasks/{task}
     * View full task details
     */
    public function show(string $username, Task $task)
    {
        // Verify access
        $hasAccess = $task->assigned_to === $this->viewer->id ||
                     $task->reporter_id === $this->viewer->id ||
                     $task->project->user_id === $this->workspaceOwner->id;

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        $task->load([
            'project',
            'assignee',
            'reporter',
            'subtasks',
            'attachments.uploader',
            'activities.actor',
            'dependencies',
        ]);

        return view('tenant.manage.projects.tasks.detail', [
            'username' => $username,
            'task' => $task,
            'viewer' => $this->viewer,
            'canEdit' => $task->reporter_id === $this->viewer->id,
            'canComplete' => $task->assigned_to === $this->viewer->id,
        ]);
    }

    /**
     * Apply filters to task query
     */
    protected function applyTaskFilter(Builder $query, string $filter): Builder
    {
        switch ($filter) {
            case 'overdue':
                $query->whereNotNull('due_date')
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'done');
                break;

            case 'today':
                $query->whereDate('due_date', today());
                break;

            case 'upcoming':
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
                $query->orderBy('priority', 'asc')
                    ->orderBy('due_date', 'asc');
                break;
        }

        return $query;
    }
}