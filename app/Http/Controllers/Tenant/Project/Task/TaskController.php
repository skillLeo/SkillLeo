<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\TaskActivity;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Controllers\Tenant\BaseTenantController;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Services\TaskActivityService;

class TaskController extends BaseTenantController
{
    protected TaskActivityService $activityService;


    /**
     * We MUST accept Request $request here and forward it to the parent,
     * because BaseTenantController::__construct(Request $request) expects it.
     */
    public function __construct(Request $request, TaskActivityService $activityService)
    {
        parent::__construct($request); // ✅ pass request up so viewer / workspaceOwner get set
        $this->activityService = $activityService;
    }

    /**
     * Download a single attachment
     * GET /{username}/manage/projects/tasks/{task}/attachments/{attachment}/download
     */
    public function downloadAttachment(string $username, Task $task, TaskAttachment $attachment)
    {
        if ($attachment->task_id !== $task->id) {
            abort(404);
        }

        $filePath = Storage::disk('public')->path($attachment->path_or_url);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $attachment->label);
    }












    public function editData(Request $request, string $username, Task $task)
    {
        $hasAccess =
            $task->assigned_to === $this->viewer->id ||
            $task->reporter_id === $this->viewer->id ||
            $task->project->user_id === $this->workspaceOwner->id ||
            $task->project->team()->where('user_id', $this->viewer->id)->exists();
    
        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
    
        $task->load([
            'assignee:id,name,avatar_url',
            'reporter:id,name,avatar_url',
            'subtasks' => function ($q) {
                $q->orderBy('order')->orderBy('id');
            },
        ]);
    
        return response()->json([
            'success' => true,
            'task'    => $this->formatTaskResponse($task),
        ]);
    }
    












    /**
     * GET /{username}/manage/projects/tasks/{task}
     * Full task detail page (blade view, not JSON)
     */
    public function show(string $username, Task $task)
    {
        // Authorization check
        $hasAccess =
            $task->assigned_to === $this->viewer->id ||
            $task->reporter_id === $this->viewer->id ||
            $task->project->user_id === $this->workspaceOwner->id ||
            $task->project->team()->where('user_id', $this->viewer->id)->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this task');
        }

        // Load all necessary relationships
        $task->load([
            'project' => function ($q) {
                $q->select('id', 'name', 'key', 'user_id', 'status', 'type', 'client_id')
                  ->with('user:id,name,avatar_url');
            },
            'assignee:id,name,email,avatar_url',
            'reporter:id,name,email,avatar_url',
            'subtasks' => function ($q) {
                $q->orderBy('order')->orderBy('id');
            },
            'attachments' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'activities' => function ($q) {
                $q->with('actor:id,name,avatar_url')
                  ->orderBy('created_at', 'desc')
                  ->limit(50);
            },
        ]);

        // Load any extra user info needed for "reassigned" activity, etc.
        $reassignmentUsers = $this->activityService->loadActivityUsers($task);
        // Progress %
        $totalSubtasks      = $task->subtasks->count();
        $completedSubtasks  = $task->subtasks->where('completed', true)->count();
        $progress           = $totalSubtasks > 0
            ? round(($completedSubtasks / $totalSubtasks) * 100)
            : 0;

        // Time tracking from activity log
        $timeSpent = $task->activities()
            ->where('type', 'time_logged')
            ->sum(DB::raw("CAST(JSON_EXTRACT(body, '$.hours') AS DECIMAL(10,2))"));

        // Status color for badge
        $statusColor = match ($task->status) {
            'todo'         => '#6B778C',
            'in-progress'  => '#0052CC',
            'review'       => '#FF991F',
            'done'         => '#00875A',
            'blocked'      => '#DE350B',
            'postponed'    => '#8777D9',
            default        => '#6B778C',
        };
        $dependencies = $task->dependencies()->with('project:id,key')->get();
// Separate attachments into images vs other docs
$images = $task->attachments
    ->where('type', 'image')
    ->values(); // reset collection keys for cleanliness

$files = $task->attachments
    ->where('type', '!=', 'image')
    ->values();

        return view('tenant.manage.projects.tasks.show', [
            'username'           => $this->workspaceOwner->username,
            'workspaceOwner'     => $this->workspaceOwner,
            'viewer'             => $this->viewer,
            'task'               => $task,
            'project'            => $task->project,
            'progress'           => $progress,
            'totalSubtasks'      => $totalSubtasks,
            'completedSubtasks'  => $completedSubtasks,
            'timeSpent'          => $timeSpent,
            'statusColor'        => $statusColor,
            'reassignmentUsers'  => $reassignmentUsers,
            'dependencies'  => $dependencies,
            'images'             => $images,
            'files'              => $files,
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/attachment
     * Upload task attachment (AJAX)
     */
    public function uploadAttachment(Request $request, string $username, Task $task)
    {
        $request->validate([
            'file'  => 'required|file|max:10240', // 10MB
            'label' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('task-attachments/' . $task->id, 'public');

        $attachment = TaskAttachment::create([
            'task_id'      => $task->id,
            'uploaded_by'  => $this->viewer->id,
            'type'         => str_starts_with($file->getClientMimeType(), 'image/') ? 'image' : 'file',
            'label'        => $request->label ?? $file->getClientOriginalName(),
            'path_or_url'  => $path,
        ]);

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'attachment_uploaded',
            'body'     => "Uploaded file: {$attachment->label}",
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'File uploaded successfully',
            'attachment' => $attachment->load('uploader:id,name,avatar_url'),
        ]);
    }

    /**
     * DELETE /{username}/manage/projects/tasks/{task}/attachments/{attachment}
     */
    public function deleteAttachment(string $username, Task $task, TaskAttachment $attachment)
    {
        if ($attachment->task_id !== $task->id) {
            abort(404);
        }

        // only uploader or task creator can delete
        if (
            $attachment->uploaded_by !== $this->viewer->id &&
            $task->reporter_id !== $this->viewer->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        Storage::disk('public')->delete($attachment->path_or_url);

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'attachment_deleted',
            'body'     => "Deleted file: {$attachment->label}",
        ]);

        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/comment
     * Add a comment + optional attachments
     */
    public function addComment(Request $request, string $username, Task $task)
    {
        $request->validate([
            'comment'        => 'required|string|min:1|max:2000',
            'attachments.*'  => 'nullable|file|max:10240', // 10MB each
        ]);

        DB::transaction(function () use ($request, $task) {
            // 1. activity row for the comment
            $activity = TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'comment',
                'body'     => $request->comment,
            ]);

            // 2. if there are files, store them and link to same timestamp
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task-attachments/' . $task->id, 'public');

                    TaskAttachment::create([
                        'task_id'     => $task->id,
                        'uploaded_by' => $this->viewer->id,
                        'type'        => str_starts_with($file->getClientMimeType(), 'image/') ? 'image' : 'file',
                        'label'       => $file->getClientOriginalName(),
                        'path_or_url' => $path,
                        'created_at'  => $activity->created_at, // align timestamps
                        'updated_at'  => $activity->created_at,
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
        ]);
    }

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
                'subtasks' => fn ($q) => $q->orderBy('order')->orderBy('id'),
                'attachments',
            ])
            ->withinWorkspace($this->workspaceOwner)
            ->where('assigned_to', $this->viewer->id)
            ->where('status', '!=', 'done'); // don't show completed tasks

        $tasks = $this->applyTaskFilter(clone $baseQuery, $filter)->get();

        return view('tenant.manage.projects.tasks.index', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasks'           => $tasks,
            'activeFilter'    => $filter,
            'context'         => 'mine', // for UI
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
                'subtasks' => fn ($q) => $q->orderBy('order')->orderBy('id'),
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
            'context'         => 'delegated', // for UI
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

        // only project owner (workspace owner) can create in that project
        if ($project->user_id !== $this->workspaceOwner->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $task = DB::transaction(function () use ($validated, $project) {
            // create main task
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

            // create subtasks if provided
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

            // log activity
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

    /**
     * PATCH /{username}/manage/projects/tasks/{task}
     * Update task (fields, subtasks, etc)
     */
    public function update(
        UpdateTaskRequest $request,
        string $username,
        Task $task,
        TaskService $taskService
    ) {
        try {
            // Service does:
            // - detect changes
            // - sync core fields
            // - sync subtasks
            // - log TaskActivity entries
            // - wrap in DB transaction
            $updatedTask = $taskService->updateTask(
                $task,
                $request->validated(),
                $this->viewer->id // actor performing update
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
            Log::error('Task update failed', [
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
     */
    protected function formatTaskResponse(Task $task): array
    {
        return [
            'id'               => $task->id,
            'project_id'       => $task->project_id,

            // Core fields
            'title'            => $task->title,
            'notes'            => $task->notes,
            'priority'         => $task->priority,
            'status'           => $task->status,

            // Dates
            'due_date'         => $task->due_date?->format('Y-m-d'),
            'created_at'       => $task->created_at?->toIso8601String(),
            'updated_at'       => $task->updated_at?->toIso8601String(),

            // Estimation
            'estimated_hours'  => $task->estimated_hours,
            'story_points'     => $task->story_points,

            // Assignment
            'assigned_to'      => $task->assigned_to,
            'reporter_id'      => $task->reporter_id,

            // Assignee (for avatar/name refresh)
            'assignee' => $task->assignee ? [
                'id'         => $task->assignee->id,
                'name'       => $task->assignee->name,
                'avatar_url' => $task->assignee->avatar_url,
            ] : null,

            // Reporter (task creator)
            'reporter' => $task->reporter ? [
                'id'         => $task->reporter->id,
                'name'       => $task->reporter->name,
                'avatar_url' => $task->reporter->avatar_url,
            ] : null,

            // Subtasks sorted
            'subtasks' => $task->subtasks
                ->map(fn ($st) => [
                    'id'            => $st->id,
                    'title'         => $st->title,
                    'completed'     => (bool) $st->completed,
                    'completed_at'  => $st->completed_at?->toIso8601String(),
                    'order'         => $st->order,
                ])
                ->values()
                ->toArray(),

            // Computed
            'is_overdue' => $task->due_date
                && $task->due_date->isPast()
                && $task->status !== 'done',

            'completion_percentage' => $this->calculateCompletionPercentage($task),
        ];
    }

    /**
     * % complete based on subtasks
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

            // delete activities
            $task->activities()->delete();

            // finally delete task
            $task->delete();
        });

        return response()->json([
            'success' => true,
            'message' => '✅ Task deleted successfully',
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/assign
     * Reassign task to me / teammate
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

            // confirm that new assignee is in project team OR is project owner
            $isInTeam = $task->project
                ->team()
                ->where('user_id', $newAssigneeId)
                ->exists();

            if (
                !$isInTeam &&
                $newAssigneeId !== $task->project->user_id
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected user is not in the project team',
                ], 400);
            }
        }

        DB::transaction(function () use ($task, $newAssigneeId, $oldAssignee, $request) {
            $task->update([
                'assigned_to'           => $newAssigneeId,
                'last_status_change_at' => now(),
            ]);

            // Log "reassigned" activity and include structured metadata
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'reassigned',
                'body'     => $request->note,
                'metadata' => [
                    'from_user_id' => $oldAssignee?->id,
                    'to_user_id'   => $newAssigneeId,
                    'note'         => $request->note,
                ],
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
                    'id'         => $task->assignee->id,
                    'name'       => $task->assignee->name,
                    'avatar_url' => $task->assignee->avatar_url,
                ],
            ],
        ]);
    }

    /**
     * Centralized filters for task lists
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
