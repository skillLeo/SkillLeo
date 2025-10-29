<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\TaskAttachment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Tenant\BaseTenantController;




class TaskController extends BaseTenantController
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
            ->where('assigned_to', $this->viewer->id)
            ->where('status', '!=', 'done'); // Don't show completed tasks

        $tasks = $this->applyTaskFilter(clone $baseQuery, $filter)->get();

        return view('tenant.manage.projects.tasks.index', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasks'           => $tasks,
            'activeFilter'    => $filter,
            'context'         => 'mine',        // 🔥 IMPORTANT
        ]);
    }

    /**
     * GET /{username}/manage/projects/tasks/assigned-out
     * Tasks I CREATED (tracking others' work)
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
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasks'           => $tasks,
            'activeFilter'    => $filter,
            'context'         => 'delegated',   // 🔥 IMPORTANT
        ]);
    }

    /**
     * GET /{username}/manage/projects/tasks/{task}
     * View full task details
     */
    public function show(string $username, Task $task)
    {
        // access check
        $hasAccess =
            $task->assigned_to === $this->viewer->id ||
            $task->reporter_id === $this->viewer->id ||
            $task->project->user_id === $this->workspaceOwner->id;

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        // load full relations we actually need in the modal
        $task->load([
            'project:id,name,key,user_id',
            'assignee:id,name,avatar_url',
            'reporter:id,name,avatar_url',
            'subtasks:id,task_id,title,completed,order,completed_at',
            'attachments:id,task_id,uploaded_by,type,label,path_or_url,created_at',
            'activities.actor:id,name,avatar_url',
        ]);

        return response()->json([
            'success' => true,
            'task' => [
                'id'              => $task->id,
                'project_id'      => $task->project_id,

                'title'           => $task->title,
                'notes'           => $task->notes,
                'priority'        => $task->priority,
                'status'          => $task->status,

                'due_date'        => $task->due_date?->format('Y-m-d'),
                'estimated_hours' => $task->estimated_hours,
                'story_points'    => $task->story_points,

                'assigned_to'     => $task->assigned_to,
                'reporter_id'     => $task->reporter_id,

                // give modal what it needs to render
                'assignee' => $task->assignee ? [
                    'id'         => $task->assignee->id,
                    'name'       => $task->assignee->name,
                    'avatar_url' => $task->assignee->avatar_url,
                ] : null,

                'subtasks' => $task->subtasks->map(function ($st) {
                    return [
                        'id'        => $st->id,
                        'title'     => $st->title,
                        'completed' => (bool) $st->completed,
                        'order'     => $st->order,
                    ];
                })->values(),
            ],
        ]);
    }


    /**
     * POST /{username}/manage/projects/tasks
     * Create new task
     */
    public function store(Request $request, string $username)
    {
        $validated = $request->validate([
            'project_id'       => 'required|exists:projects,id',
            'title'            => 'required|string|max:255',
            'notes'            => 'nullable|string|max:5000',
            'priority'         => 'required|in:low,medium,high,urgent',
            'assigned_to'      => 'nullable|exists:users,id',
            'due_date'         => 'nullable|date',
            'estimated_hours'  => 'nullable|numeric|min:0',
            'story_points'     => 'nullable|integer|min:0',
            'subtasks'         => 'nullable|array',
            'subtasks.*.title' => 'required_with:subtasks|string|max:255',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        if ($project->user_id !== $this->workspaceOwner->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $task = DB::transaction(function () use ($validated, $project) {
            $task = Task::create([
                'project_id'      => $project->id,
                'title'           => $validated['title'],
                'notes'           => $validated['notes'] ?? null,
                'priority'        => $validated['priority'],
                'status'          => 'todo',
                'assigned_to'     => $validated['assigned_to'] ?? null,
                'reporter_id'     => $this->viewer->id,
                'due_date'        => $validated['due_date'] ?? null,
                'estimated_hours' => $validated['estimated_hours'] ?? null,
                'story_points'    => $validated['story_points'] ?? 0,
            ]);

            if (!empty($validated['subtasks'])) {
                foreach ($validated['subtasks'] as $index => $subtaskData) {
                    Subtask::create([
                        'task_id'   => $task->id,
                        'title'     => $subtaskData['title'],
                        'order'     => $index + 1,
                        'completed' => false,
                    ]);
                }
            }

            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'created',
                'body'     => 'Task created',
            ]);

            return $task;
        });

        $task->load(['project', 'assignee', 'reporter', 'subtasks']);

        return response()->json([
            'success' => true,
            'message' => '✅ Task created successfully',
            'task'    => $task,
        ], 201);
    }

    // make sure we can validate assigned_to


    







    public function update(
        \App\Http\Requests\UpdateTaskRequest $request, 
        string $username, 
        \App\Models\Task $task,
        \App\Services\TaskService $taskService
    ) {
        try {
            // Service handles:
            // - change detection
            // - core field updates
            // - subtask sync (create/update/delete)
            // - activity logging
            // - transaction
            $updatedTask = $taskService->updateTask(
                $task,
                $request->validated(),
                $this->viewer->id // whoever is performing the update
            );
    
            return response()->json([
                'success' => true,
                'message' => '✅ Task updated successfully',
                'task'    => $this->formatTaskResponse($updatedTask),
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task update failed', [
                'task_id' => $task->id,
                'user_id' => $this->viewer->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
    

    /**
     * Format task data for JSON response
     * 
     * Ensures frontend receives exactly what it needs to update UI
     *
     * @param Task $task
     * @return array
     */
    protected function formatTaskResponse(Task $task): array
    {
        return [
            'id' => $task->id,
            'project_id' => $task->project_id,
            
            // Core fields
            'title' => $task->title,
            'notes' => $task->notes,
            'priority' => $task->priority,
            'status' => $task->status,
            
            // Dates
            'due_date' => $task->due_date?->format('Y-m-d'),
            'created_at' => $task->created_at?->toIso8601String(),
            'updated_at' => $task->updated_at?->toIso8601String(),
            
            // Estimation
            'estimated_hours' => $task->estimated_hours,
            'story_points' => $task->story_points,
            
            // Assignment
            'assigned_to' => $task->assigned_to,
            'reporter_id' => $task->reporter_id,
            
            // Assignee details for avatar/name update
            'assignee' => $task->assignee ? [
                'id' => $task->assignee->id,
                'name' => $task->assignee->name,
                'avatar_url' => $task->assignee->avatar_url,
            ] : null,
            
            // Reporter details
            'reporter' => $task->reporter ? [
                'id' => $task->reporter->id,
                'name' => $task->reporter->name,
                'avatar_url' => $task->reporter->avatar_url,
            ] : null,
            
            // Subtasks with proper ordering
            'subtasks' => $task->subtasks
                ->map(fn($st) => [
                    'id' => $st->id,
                    'title' => $st->title,
                    'completed' => (bool) $st->completed,
                    'completed_at' => $st->completed_at?->toIso8601String(),
                    'order' => $st->order,
                ])
                ->values()
                ->toArray(),
            
            // Computed properties for UI
            'is_overdue' => $task->due_date && $task->due_date->isPast() && $task->status !== 'done',
            'completion_percentage' => $this->calculateCompletionPercentage($task),
        ];
    }

    /**
     * Calculate task completion percentage
     *
     * @param Task $task
     * @return int Percentage (0-100)
     */
    protected function calculateCompletionPercentage(Task $task): int
    {
        if ($task->status === 'done') {
            return 100;
        }
        
        $totalSubtasks = $task->subtasks->count();
        
        if ($totalSubtasks === 0) {
            return 0;
        }
        
        $completedSubtasks = $task->subtasks->where('completed', true)->count();
        
        return (int) round(($completedSubtasks / $totalSubtasks) * 100);
    }










    

    /**
     * DELETE /{username}/manage/projects/tasks/{task}
     * Delete task (only reporter can delete)
     */
    public function destroy(string $username, Task $task)
    {
        if ($task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the task creator can delete this task',
            ], 403);
        }

        DB::transaction(function () use ($task) {
            // delete subtasks
            $task->subtasks()->delete();

            // delete attachments + files
            foreach ($task->attachments as $attachment) {
                if ($attachment->path_or_url) {
                    Storage::disk('public')->delete($attachment->path_or_url);
                }
                $attachment->delete();
            }

            // delete activity log
            $task->activities()->delete();

            // soft delete / hard delete
            $task->delete();
        });

        return response()->json([
            'success' => true,
            'message' => '✅ Task deleted successfully',
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/assign
     * Reassign task
     */
    public function reassignTask(Request $request, string $username, Task $task)
    {
        if ($task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only the task creator can reassign this task',
            ], 403);
        }

        $request->validate([
            'assign_to' => 'required|string|in:me,teammate',
            'user_id'   => 'required_if:assign_to,teammate|exists:users,id',
            'note'      => 'nullable|string|max:500',
        ]);

        $oldAssignee   = $task->assignee;
        $newAssigneeId = null;

        if ($request->assign_to === 'me') {
            $newAssigneeId = $this->viewer->id;
        } else {
            $newAssigneeId = $request->user_id;

            $isInTeam = $task->project
                ->team()
                ->where('user_id', $newAssigneeId)
                ->exists();

            if (!$isInTeam && $newAssigneeId !== $task->project->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected user is not in the project team',
                ], 400);
            }
        }

        DB::transaction(function () use ($task, $newAssigneeId, $oldAssignee, $request) {
            $task->update([
                'assigned_to'          => $newAssigneeId,
                'last_status_change_at' => now(),
            ]);

            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'reassigned',
                'body'     => $request->note
                    ?? "Reassigned from " . ($oldAssignee->name ?? 'Unassigned')
                    . " to " . ($task->fresh()->assignee->name ?? 'Unknown'),
            ]);
        });

        $task->load('assignee');

        return response()->json([
            'success' => true,
            'message' => '✅ Task reassigned successfully',
            'task'    => [
                'id'          => $task->id,
                'assigned_to' => $task->assigned_to,
                'assignee'    => [
                    'id'          => $task->assignee->id,
                    'name'        => $task->assignee->name,
                    'avatar_url'  => $task->assignee->avatar_url,
                ],
            ],
        ]);
    }

    /**
     * Centralized filters
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
