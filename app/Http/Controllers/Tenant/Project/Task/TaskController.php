<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use App\Models\TaskAttachment;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Tenant\BaseTenantController;

class TaskController extends BaseTenantController
{
    /**
     * GET /tasks
     * Main tasks index page with tabs
     */
    public function index(Request $request)
    {
        // Load all tasks user has access to
        $query = Task::query()
            ->with([
                'project',
                'assignee',
                'reporter',
                'subtasks',
                'attachments',
            ])
            ->withinWorkspace($this->workspaceOwner);

        // User can see:
        // 1. Tasks assigned to them
        // 2. Tasks they created
        // 3. If they're the workspace owner, all tasks
        if ($this->viewer->id !== $this->workspaceOwner->id) {
            $query->where(function ($q) {
                $q->where('assigned_to', $this->viewer->id)
                  ->orWhere('reporter_id', $this->viewer->id);
            });
        }

        $allTasks = $query->orderBy('due_date')
                          ->orderBy('order')
                          ->get();

        return view('tenant.manage.projects.tasks.index', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'allTasks'        => $allTasks,
        ]);
    }

    /**
     * POST /tasks/{task}/status
     * Quick status update with cascading
     */
    public function quickStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTaskAction($task);

        $validated = $request->validate([
            'status'           => ['required', Rule::in(Task::statusOptions())],
            'cascade_subtasks' => ['sometimes', 'boolean'],
        ]);

        DB::beginTransaction();
        
        try {
            $oldStatus = $task->status;
            $newStatus = $validated['status'];
            $cascadeSubtasks = $validated['cascade_subtasks'] ?? false;

            $task->status = $newStatus;
            $task->last_status_change_at = now();

            switch ($newStatus) {
                case Task::STATUS_DONE:
                    if (!$task->approved_at) {
                        $task->approved_at = now();
                    }
                    $task->completed_at = now();
                    
                    if ($cascadeSubtasks && $task->subtasks->count() > 0) {
                        $this->cascadeCompleteSubtasks($task);
                    }
                    break;

                case Task::STATUS_IN_PROGRESS:
                    if ($oldStatus === Task::STATUS_DONE) {
                        $task->approved_at = null;
                        $task->completed_at = null;
                    }
                    break;
            }

            if ($newStatus !== Task::STATUS_POSTPONED) {
                $task->postponed_until = null;
            }
            if ($newStatus !== Task::STATUS_BLOCKED) {
                $task->blocked_reason = null;
            }

            $task->save();

            $activityBody = $this->getStatusChangeMessage($oldStatus, $newStatus);
            if ($cascadeSubtasks && $newStatus === Task::STATUS_DONE) {
                $activityBody .= " (including all subtasks)";
            }

            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'status_change',
                'body'     => $activityBody,
            ]);

            DB::commit();

            $progress = $this->projectProgressPayload($task->project_id);

            return response()->json([
                'success'         => true,
                'message'         => $this->getSuccessMessage($newStatus),
                'task_id'         => $task->id,
                'old_status'      => $oldStatus,
                'new_status'      => $newStatus,
                'cascaded'        => $cascadeSubtasks,
                'subtasks_count'  => $task->subtasks->count(),
                'progress'        => $progress,
                'completed_at'    => $task->completed_at?->toISOString(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task status update failed', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * POST /tasks/{task}/postpone-quick
     * Quick postpone with cascade
     */
    public function quickPostpone(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTaskAction($task);

        $validated = $request->validate([
            'postponed_until'  => ['required', 'date', 'after:today'],
            'reason'           => ['nullable', 'string', 'max:2000'],
            'cascade_subtasks' => ['sometimes', 'boolean'],
        ]);

        DB::beginTransaction();

        try {
            $cascadeSubtasks = $validated['cascade_subtasks'] ?? false;
            $postponedUntil = Carbon::parse($validated['postponed_until']);
            $reason = $validated['reason'] ?? null;

            $task->status = Task::STATUS_POSTPONED;
            $task->postponed_until = $postponedUntil;
            $task->blocked_reason = $reason;
            $task->last_status_change_at = now();
            $task->save();

            if ($cascadeSubtasks && $task->subtasks->count() > 0) {
                $incompleteSubtasks = $task->subtasks()->where('completed', false)->get();
                
                foreach ($incompleteSubtasks as $subtask) {
                    $subtask->postponed_until = $postponedUntil;
                    $subtask->save();
                }

                $cascadedCount = $incompleteSubtasks->count();
            }

            $activityBody = $reason ?? 'Task postponed';
            if ($cascadeSubtasks && isset($cascadedCount) && $cascadedCount > 0) {
                $activityBody .= " (including {$cascadedCount} subtasks)";
            }

            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'postponed',
                'body'     => $activityBody,
            ]);

            DB::commit();

            $progress = $this->projectProgressPayload($task->project_id);

            return response()->json([
                'success'          => true,
                'message'          => 'Task postponed successfully',
                'task_id'          => $task->id,
                'status'           => $task->status,
                'postponed_until'  => $postponedUntil->format('Y-m-d'),
                'reason'           => $reason,
                'cascaded'         => $cascadeSubtasks,
                'cascaded_count'   => $cascadedCount ?? 0,
                'progress'         => $progress,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task postpone failed', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to postpone task. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * DELETE /tasks/{task}
     * Delete a task (only creator can delete)
     */
    public function destroy(Task $task): JsonResponse
    {
        // Only creator or workspace owner can delete
        if ($task->reporter_id !== $this->viewer->id && 
            $task->project->user_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this task.',
            ], 403);
        }

        DB::beginTransaction();

        try {
            $projectId = $task->project_id;

            // Delete related records
            $task->subtasks()->delete();
            $task->attachments()->delete();
            $task->activity()->delete();
            $task->delete();

            DB::commit();

            $progress = $this->projectProgressPayload($projectId);

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',
                'progress' => $progress,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task deletion failed', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task. Please try again.',
            ], 500);
        }
    }

    // ... (keep all other existing methods from previous response)

    private function cascadeCompleteSubtasks(Task $task): void
    {
        $incompleteSubtasks = $task->subtasks()->where('completed', false)->get();
        
        foreach ($incompleteSubtasks as $subtask) {
            $subtask->completed = true;
            $subtask->completed_at = now();
            $subtask->save();
        }

        if ($incompleteSubtasks->count() > 0) {
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'subtasks_cascaded',
                'body'     => "Marked {$incompleteSubtasks->count()} subtasks as complete",
            ]);
        }
    }

    private function getStatusChangeMessage(string $oldStatus, string $newStatus): string
    {
        $statusLabels = [
            Task::STATUS_TODO        => 'To Do',
            Task::STATUS_IN_PROGRESS => 'In Progress',
            Task::STATUS_REVIEW      => 'In Review',
            Task::STATUS_DONE        => 'Done',
            Task::STATUS_BLOCKED     => 'Blocked',
            Task::STATUS_POSTPONED   => 'Postponed',
        ];

        $from = $statusLabels[$oldStatus] ?? $oldStatus;
        $to = $statusLabels[$newStatus] ?? $newStatus;

        return "Changed status from {$from} to {$to}";
    }

    private function getSuccessMessage(string $status): string
    {
        return match($status) {
            Task::STATUS_DONE        => 'Task completed successfully! ✓',
            Task::STATUS_IN_PROGRESS => 'Task marked as in progress',
            Task::STATUS_REVIEW      => 'Task submitted for review',
            Task::STATUS_BLOCKED     => 'Task marked as blocked',
            Task::STATUS_POSTPONED   => 'Task postponed',
            Task::STATUS_TODO        => 'Task moved to To Do',
            default                  => 'Task status updated',
        };
    }

    protected function projectProgressPayload(int $projectId): array
    {
        $project = Project::query()
            ->where('user_id', $this->workspaceOwner->id)
            ->where('id', $projectId)
            ->with(['team', 'tasks.subtasks'])
            ->first();

        if (!$project) {
            return [
                'project'     => null,
                'project_id'  => $projectId,
                'progressPct' => 0,
                'doneUnits'   => 0,
                'totalUnits'  => 0,
                'teamCount'   => 0,
                'dueDate'     => null,
                'type'        => null,
            ];
        }

        $allTasks = $project->tasks;
        $allSubs = $allTasks->flatMap->subtasks;

        $taskTotal = $allTasks->count();
        $taskDone = $allTasks->where('status', Task::STATUS_DONE)->count();

        $subTotal = $allSubs->count();
        $subDone = $allSubs->where('completed', true)->count();

        $totalUnits = $taskTotal + $subTotal;
        $doneUnits = $taskDone + $subDone;

        $progressPct = $totalUnits > 0
            ? round(($doneUnits / $totalUnits) * 100)
            : 0;

        return [
            'project'     => $project,
            'project_id'  => $project->id,
            'progressPct' => $progressPct,
            'doneUnits'   => $doneUnits,
            'totalUnits'  => $totalUnits,
            'teamCount'   => $project->team->count(),
            'dueDate'     => $project->due_date?->format('M d, Y') ?? 'No due date',
            'type'        => $project->type ?? 'taskflow',
        ];
    }

    protected function authorizeTaskAction(Task $task): void
    {
        $isAssignee = $task->assigned_to === $this->viewer->id;
        $isCreator = $task->reporter_id === $this->viewer->id;
        $isOwner = $task->project->user_id === $this->workspaceOwner->id;
        $inWorkspace = $task->project->user_id === $this->workspaceOwner->id;

        if (!$inWorkspace) {
            abort(403);
        }

        if ($isAssignee || $isCreator || $isOwner) {
            return;
        }

        if ($this->viewer->canSeeAllTasks($this->workspaceOwner)) {
            return;
        }

        abort(403);
    }





































































































































































































































    /**
     * GET /tasks/mine
     * Personal task board for current user
     */
    public function myTasks(Request $request)
    {
$tasks = Task::query()
    ->withinWorkspace($this->workspaceOwner)
    ->assignedTo($this->viewer)   // ← this now works
    ->where('status', '!=', Task::STATUS_DONE)
    ->orderBy('project_id')
    ->orderBy('order')
    ->get();


        $tasksByProject = $tasks->groupBy('project_id');

        $projectMeta = [];
        foreach ($tasksByProject as $projectId => $list) {
            $projectMeta[$projectId] = $this->projectProgressPayload($projectId);
        }

        return view('tenant.manage.projects.tasks.mine', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasksByProject'  => $tasksByProject,
            'projectMeta'     => $projectMeta,
        ]);
    }
 
    public function toggleSubtaskComplete(Request $request, Task $task, Subtask $subtask): JsonResponse
    {
        $this->authorizeTaskAction($task);

        abort_unless($subtask->task_id === $task->id, 403, 'Subtask does not belong to this task');

        $validated = $request->validate([
            'completed' => ['required', 'boolean'],
        ]);

        DB::beginTransaction();

        try {
            $wasCompleted = $subtask->completed;
            $isCompleted = $validated['completed'];

            $subtask->completed = $isCompleted;
            $subtask->completed_at = $isCompleted ? now() : null;
            $subtask->save();

            // Log activity
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'subtask_toggle',
                'body'     => ($isCompleted ? 'Completed' : 'Reopened') . ' subtask: ' . $subtask->title,
            ]);

            // Auto-complete parent task if all subtasks are done
            if ($isCompleted && $this->areAllSubtasksComplete($task)) {
                $task->status = Task::STATUS_DONE;
                $task->completed_at = now();
                $task->save();

                TaskActivity::create([
                    'task_id'  => $task->id,
                    'actor_id' => $this->viewer->id,
                    'type'     => 'auto_completed',
                    'body'     => 'Task automatically marked as done (all subtasks completed)',
                ]);
            }

            // Reopen parent task if it was done but now has incomplete subtasks
            if (!$isCompleted && $task->status === Task::STATUS_DONE) {
                $task->status = Task::STATUS_IN_PROGRESS;
                $task->completed_at = null;
                $task->save();

                TaskActivity::create([
                    'task_id'  => $task->id,
                    'actor_id' => $this->viewer->id,
                    'type'     => 'auto_reopened',
                    'body'     => 'Task reopened (subtask marked incomplete)',
                ]);
            }

            DB::commit();

            $progress = $this->projectProgressPayload($task->project_id);
            $subtaskStats = $this->getSubtaskStats($task);

            return response()->json([
                'success'        => true,
                'message'        => $isCompleted ? 'Subtask completed' : 'Subtask reopened',
                'task_id'        => $task->id,
                'subtask_id'     => $subtask->id,
                'completed'      => $subtask->completed,
                'task_status'    => $task->status,
                'subtask_stats'  => $subtaskStats,
                'progress'       => $progress,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subtask toggle failed', [
                'task_id' => $task->id,
                'subtask_id' => $subtask->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update subtask. Please try again.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * POST /tasks/reorder-mylist
     * Reorder tasks within a project
     */
    public function reorderMyList(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer'],
            'tasks'      => ['required', 'array', 'min:1'],
            'tasks.*'    => ['integer'],
        ]);

        DB::beginTransaction();

        try {
            $projectId = (int) $validated['project_id'];
            $taskIds = $validated['tasks'];
            $userId = $this->viewer->id;

            $position = 1;
            foreach ($taskIds as $taskId) {
                Task::query()
                    ->withinWorkspace($this->workspaceOwner)
                    ->where('assigned_to', $userId)
                    ->where('project_id', $projectId)
                    ->where('id', $taskId)
                    ->update(['order' => $position++]);
            }

            DB::commit();

            $progress = $this->projectProgressPayload($projectId);

            return response()->json([
                'success'  => true,
                'message'  => 'Task order updated',
                'progress' => $progress,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task reorder failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder tasks',
            ], 500);
        }
    }

    /**
     * POST /tasks/{task}/subtasks/reorder
     * Reorder subtasks within a task
     */
    public function reorderSubtasks(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTaskAction($task);

        $validated = $request->validate([
            'subtasks'   => ['required', 'array', 'min:1'],
            'subtasks.*' => ['integer'],
        ]);

        DB::beginTransaction();

        try {
            $position = 1;
            foreach ($validated['subtasks'] as $subtaskId) {
                Subtask::where('task_id', $task->id)
                    ->where('id', $subtaskId)
                    ->update(['order' => $position++]);
            }

            DB::commit();

            $progress = $this->projectProgressPayload($task->project_id);

            return response()->json([
                'success'  => true,
                'message'  => 'Subtask order updated',
                'progress' => $progress,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subtask reorder failed', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder subtasks',
            ], 500);
        }
    }

 
    /**
     * Check if all subtasks are complete
     */
    private function areAllSubtasksComplete(Task $task): bool
    {
        $totalSubtasks = $task->subtasks()->count();
        
        if ($totalSubtasks === 0) {
            return false;
        }

        $completedSubtasks = $task->subtasks()->where('completed', true)->count();
        
        return $completedSubtasks === $totalSubtasks;
    }

    /**
     * Get subtask completion statistics
     */
    private function getSubtaskStats(Task $task): array
    {
        $subtasks = $task->subtasks;
        $total = $subtasks->count();
        $completed = $subtasks->where('completed', true)->count();
        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        return [
            'total'      => $total,
            'completed'  => $completed,
            'remaining'  => $total - $completed,
            'percentage' => $percentage,
        ];
    }

  

   

    // ... (keep all other existing methods like allTasks, approvals, etc.)







































 







    /**
     * POST /tasks/reorder-mylist
     * Input:
     *  tasks: [ taskId_1, taskId_2, taskId_3, ... ] (new order in that project only)
     *  project_id: <int>
     *
     * Only reorders tasks that:
     *  - belong to that project
     *  - are assigned to the current viewer
     */
 

    /**
     * GET /tasks/all
     * Workspace-wide task table for owner / PM.
     */
    public function allTasks(Request $request)
    {
        // Permission: only workspace owner / PM etc.
        abort_unless($this->viewer->canSeeAllTasks($this->workspaceOwner), 403);
    
        //
        // 1. Build base query for tasks in this workspace
        //
        $query = Task::query()
            ->with([
                'project',      // for project name / key
                'assignee',     // for avatar + name
                'activity',     // for latestActivitySummary()
            ])
            ->withinWorkspace($this->workspaceOwner); // scope in Task model
    
        //
        // 2. Apply optional filters from the request
        //
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }
    
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->get('assigned_to'));
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
    
        if ($request->filled('due')) {
            switch ($request->get('due')) {
                case 'overdue':
                    $query->whereDate('due_date', '<', now())
                          ->where('status', '!=', Task::STATUS_DONE);
                    break;
    
                case 'today':
                    $query->whereDate('due_date', now()->toDateString());
                    break;
    
                case 'week':
                    $query->whereBetween('due_date', [
                        now()->startOfWeek()->toDateString(),
                        now()->endOfWeek()->toDateString(),
                    ]);
                    break;
            }
        }
    
        //
        // 3. Final task list (paginated)
        //
        $tasks = $query
            ->orderBy('due_date')
            ->orderBy('status')
            ->paginate(30)
            ->withQueryString(); // keep filters when paginating
    
    
        //
        // 4. Build dropdown data for filters
        //
    
        // All projects owned by this workspace owner (the "boss" account)
        $projectsForFilter = Project::query()
            ->where('user_id', $this->workspaceOwner->id)
            ->orderBy('key')
            ->get(['id', 'key', 'name']);
    
        // Distinct team members across all projects in this workspace
        // + includes anyone on project_team for any of those projects.
        // We select minimal fields to keep things light.
        $teamForFilter = User::query()
            ->select('users.id', 'users.name', 'users.avatar_url')
            ->join('project_team', 'project_team.user_id', '=', 'users.id')
            ->join('projects', 'projects.id', '=', 'project_team.project_id')
            ->where('projects.user_id', $this->workspaceOwner->id)
            ->distinct()
            ->orderBy('users.name')
            ->get();
    
        // Also make sure the workspace owner themselves shows up as an option:
        if ($teamForFilter->doesntContain('id', $this->workspaceOwner->id)) {
            $teamForFilter->push(
                (new User([
                    'id'         => $this->workspaceOwner->id,
                    'name'       => $this->workspaceOwner->name,
                    'avatar_url' => $this->workspaceOwner->avatar_url,
                ]))
            );
            // resort alphabetically after push
            $teamForFilter = $teamForFilter->sortBy('name')->values();
        }
    
        //
        // 5. Render view
        //
        return view('tenant.manage.projects.tasks.all', [
            'username'           => $this->workspaceOwner->username,
            'workspaceOwner'     => $this->workspaceOwner,
            'viewer'             => $this->viewer,
    
            'tasks'              => $tasks,
    
            // 👇 these were missing and caused the crash
            'projectsForFilter'  => $projectsForFilter,
            'teamForFilter'      => $teamForFilter,
        ]);
    }
    /**
     * GET /tasks/approvals
     * Review queue: tasks in "review" / waiting approval.
     * - owner / PM always see
     * - client sees only tasks requiring their approval
     */
    public function approvals(Request $request)
    {
        abort_unless($this->viewer->canApproveTasksFor($this->workspaceOwner), 403);

        $query = Task::query()
            ->with(['project', 'assignee', 'attachments', 'activity'])
            ->withinWorkspace($this->workspaceOwner)
            ->where('status', Task::STATUS_REVIEW)
            ->orderByDesc('submitted_for_review_at');

        // if viewer is client, restrict to client_approval tasks only
        if ($this->viewer->isClientFor($this->workspaceOwner)) {
            $query->where('requires_client_approval', true);
        }

        $tasks = $query->get();

        return view('tenant.manage.projects.tasks.approvals', [
            'username'        => $this->workspaceOwner->username,
            'workspaceOwner'  => $this->workspaceOwner,
            'viewer'          => $this->viewer,
            'tasks'           => $tasks,
        ]);
    }

    /**
     * POST /tasks/{task}/complete
     * Assignee submits work -> status = review, upload proof, log activity.
     */
    public function submitForReview(Request $request, Task $task)
    {
        $this->authorizeTaskAction($task);

        $validated = $request->validate([
            'summary' => ['nullable', 'string', 'max:2000'],
            'files.*' => ['nullable', 'file', 'max:12288'], // 12MB each
        ]);

        DB::transaction(function () use ($task, $validated, $request) {
            $task->status = Task::STATUS_REVIEW;
            $task->submitted_for_review_at = now();
            $task->last_status_change_at = now();
            $task->save();

            // upload attachments (if any)
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $upload) {
                    $path = $upload->store('task_files/'.$task->id, 'public');

                    TaskAttachment::create([
                        'task_id'     => $task->id,
                        'uploaded_by' => $this->viewer->id,
                        'type'        => str_starts_with($upload->getMimeType(), 'image/')
                                            ? 'image'
                                            : 'file',
                        'label'       => $upload->getClientOriginalName(),
                        'path_or_url' => $path,
                    ]);

                    TaskActivity::create([
                        'task_id'   => $task->id,
                        'actor_id'  => $this->viewer->id,
                        'type'      => 'attachment_upload',
                        'body'      => $upload->getClientOriginalName(),
                    ]);
                }
            }

            // log activity
            TaskActivity::create([
                'task_id'   => $task->id,
                'actor_id'  => $this->viewer->id,
                'type'      => 'submitted_for_review',
                'body'      => $validated['summary'] ?? null,
            ]);
        });

        return back()->with('status', 'Task submitted for review');
    }

    /**
     * POST /tasks/{task}/postpone
     * Assignee says "need more time".
     */
    public function postpone(Request $request, Task $task)
    {
        $this->authorizeTaskAction($task);

        $validated = $request->validate([
            'postponed_until' => ['required', 'date', 'after:today'],
            'reason'          => ['nullable', 'string', 'max:2000'],
        ]);

        $task->status = Task::STATUS_POSTPONED;
        $task->postponed_until = $validated['postponed_until'];
        $task->blocked_reason = $validated['reason'] ?? null;
        $task->last_status_change_at = now();
        $task->save();

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'postponed',
            'body'     => $validated['reason'] ?? null,
        ]);

        return back()->with('status', 'Task postponed');
    }

    /**
     * POST /tasks/{task}/block
     * Assignee says "blocked".
     */
    public function block(Request $request, Task $task)
    {
        $this->authorizeTaskAction($task);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $task->status = Task::STATUS_BLOCKED;
        $task->blocked_reason = $validated['reason'];
        $task->last_status_change_at = now();
        $task->save();

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'blocked',
            'body'     => $validated['reason'],
        ]);

        return back()->with('status', 'Task marked as blocked');
    }

    /**
     * POST /tasks/{task}/approve
     * Lead/PM/client approves final.
     */
    public function approve(Request $request, Task $task)
    {
        abort_unless($this->viewer->canApproveTasksFor($this->workspaceOwner), 403);

        $task->status = Task::STATUS_DONE;
        $task->approved_at = now();
        $task->last_status_change_at = now();
        $task->save();

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'approved',
            'body'     => 'Marked as complete',
        ]);

        return back()->with('status', 'Task approved and completed');
    }

    /**
     * POST /tasks/{task}/request-changes
     * Lead/PM/client asks for revisions.
     */
    public function requestChanges(Request $request, Task $task)
    {
        abort_unless($this->viewer->canApproveTasksFor($this->workspaceOwner), 403);

        $validated = $request->validate([
            'feedback' => ['required', 'string', 'max:4000'],
        ]);

        $task->status = Task::STATUS_IN_PROGRESS;
        $task->last_status_change_at = now();
        $task->save();

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'change_requested',
            'body'     => $validated['feedback'],
        ]);

        return back()->with('status', 'Changes requested');
    }

    /**
     * POST /tasks/{task}/remind
     * Send reminder email to assignee.
     */
    public function sendReminder(Request $request, Task $task)
    {
        abort_unless($this->viewer->canSeeAllTasks($this->workspaceOwner), 403);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $assignee = $task->assignee;
        if ($assignee && $assignee->email) {
            // simple mail. You would create a proper Mailable class
            Mail::raw(
                ($validated['message'] ?? "Gentle reminder on \"{$task->title}\" due {$task->due_date}") .
                "\n\nLink: " . route('tenant.manage.projects.tasks.mine', $this->workspaceOwner->username),
                function ($msg) use ($assignee) {
                    $msg->to($assignee->email)
                        ->subject('Task Reminder');
                }
            );
        }

        TaskActivity::create([
            'task_id'  => $task->id,
            'actor_id' => $this->viewer->id,
            'type'     => 'reminder_sent',
            'body'     => $validated['message'] ?? 'Reminder sent',
        ]);

        return back()->with('status', 'Reminder sent');
    }

    /**
     * GET /tasks/{task}/drawer
     * AJAX partial for the side drawer modal.
     */
    public function drawer(Task $task)
    {
        $this->authorizeTaskView($task);

        $task->load([
            'project',
            'assignee',
            'reporter',
            'subtasks',
            'attachments.uploader',
            'activity.actor',
        ]);

        return view('tenant.manage.projects.tasks.components.task-drawer', [
            'task'          => $task,
            'viewer'        => $this->viewer,
            'workspaceOwner'=> $this->workspaceOwner,
        ]);
    }

    // ----------------------------------------
    // Helper guards
    // ----------------------------------------

   
    protected function authorizeTaskView(Task $task): void
    {
        $inWorkspace = $task->project->user_id === $this->workspaceOwner->id;

        if (! $inWorkspace) {
            abort(403);
        }

        // client can only view tasks that are client_visible or require approval
        if ($this->viewer->isClientFor($this->workspaceOwner)) {
            if (!($task->client_visible || $task->requires_client_approval)) {
                abort(403);
            }
        }
    }
}
