<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use App\Models\Subtask;
use App\Models\TaskActivity;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tenant\BaseTenantController;

class TaskActionController extends BaseTenantController
{
    /**
     * POST /{username}/manage/projects/tasks/{task}/status
     * Mark task done / postponed / blocked / cancelled (+ remark, files)
     */
    public function updateStatus(Request $request, string $username, Task $task)
    {
        // auth: only assignee or reporter
        if ($task->assigned_to !== $this->viewer->id &&
            $task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this task',
            ], 403);
        }

        $request->validate([
            'status'           => 'required|in:done,postponed,blocked,cancelled,review,in-progress,todo',
            'remark'           => 'required|string|min:5|max:2000',
            'postponed_until'  => 'nullable|required_if:status,postponed|date|after:today',
            'attachments.*'    => 'nullable|file|max:10240', // 10 MB
        ]);

        DB::transaction(function () use ($request, $task) {
            $updateData = [
                'status'                => $request->status,
                'last_status_change_at' => now(),
            ];

            // if "done", close task + all subtasks
            if ($request->status === 'done') {
                $updateData['completed_at'] = now();

                $task->subtasks()->update([
                    'completed'    => true,
                    'completed_at' => now(),
                ]);
            }

            // if "postponed", move due date
            if ($request->status === 'postponed' && $request->postponed_until) {
                $updateData['postponed_until'] = $request->postponed_until;
                $updateData['due_date']        = $request->postponed_until;
            }

            // if "blocked", stash reason in blocked_reason
            if ($request->status === 'blocked') {
                $updateData['blocked_reason'] = $request->remark;
            } else {
                $updateData['blocked_reason'] = null;
            }

            $task->update($updateData);

            // activity log
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => $request->status === 'done' ? 'completed' : $request->status,
                'body'     => $request->remark,
            ]);

            // attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task-attachments/' . $task->id, 'public');

                    TaskAttachment::create([
                        'task_id'     => $task->id,
                        'uploaded_by' => $this->viewer->id,
                        'type'        => str_starts_with($file->getClientMimeType(), 'image/')
                            ? 'image'
                            : 'file',
                        'label'       => $file->getClientOriginalName(),
                        'path_or_url' => $path,
                    ]);
                }
            }
        });

        $task->refresh();
        $completedCount = $task->subtasks()->where('completed', true)->count();
        $totalCount     = $task->subtasks()->count();

        $meta = $this->statusMeta($task->status);

        return response()->json([
            'success'                   => true,
            'message'                   => 'Task updated successfully',
            'task_id'                   => $task->id,

            // status info for UI
            'task_status'               => $task->status,
            'task_status_label'         => $meta['label'],
            'task_status_bg'            => $meta['bg'],
            'task_status_color'         => $meta['color'],

            // subtask/progress info for UI
            'completed_subtasks_count'  => $completedCount,
            'subtasks_count'            => $totalCount,
        ]);
    }


    /**
     * POST /{username}/manage/projects/tasks/{task}/remark
     * Add a remark / proof / comment without changing status
     */
    public function addRemark(Request $request, string $username, Task $task)
    {
        if ($task->assigned_to !== $this->viewer->id &&
            $task->reporter_id !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'remark'        => 'required|string|min:5|max:2000',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        DB::transaction(function () use ($request, $task) {
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'comment',
                'body'     => $request->remark,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task-attachments/' . $task->id, 'public');

                    TaskAttachment::create([
                        'task_id'     => $task->id,
                        'uploaded_by' => $this->viewer->id,
                        'type'        => str_starts_with($file->getClientMimeType(), 'image/')
                            ? 'image'
                            : 'file',
                        'label'       => $file->getClientOriginalName(),
                        'path_or_url' => $path,
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Remark added successfully',
        ]);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/subtasks/{subtask}/toggle
     * Simple checkbox toggle
     */
    public function toggleSubtask(Request $request, string $username, Task $task, Subtask $subtask)
    {
        // must be assignee
        if ($task->assigned_to !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // subtask must belong
        if ($subtask->task_id !== $task->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid subtask',
            ], 404);
        }

        // intended new state
        $markCompleted = !$subtask->completed;

        $completedCount = 0;
        $totalCount     = 0;
        $newStatus      = $task->status;
        $newStatusMeta  = $this->statusMeta($task->status);

        DB::transaction(function () use (
            $task,
            $subtask,
            $markCompleted,
            &$completedCount,
            &$totalCount,
            &$newStatus,
            &$newStatusMeta
        ) {
            // 1. toggle
            $subtask->update([
                'completed'    => $markCompleted,
                'completed_at' => $markCompleted ? now() : null,
            ]);

            // 2. activity log
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'subtask_' . ($markCompleted ? 'completed' : 'reopened'),
                'body'     => $subtask->title,
            ]);

            // 3. sync parent task status
            $subtaskWasReopened = !$markCompleted;
            [$completedCount, $totalCount, $newStatus, $newStatusMeta] =
                $this->syncTaskStatusFromSubtasks($task, $subtaskWasReopened);
        });

        $task->refresh();
        $subtask->refresh();

        return response()->json([
            'success'                  => true,
            'subtask'                  => $subtask,
            'completed_subtasks_count' => $completedCount,
            'subtasks_count'           => $totalCount,

            'task_status'              => $newStatus,
            'task_status_label'        => $newStatusMeta['label'],
            'task_status_bg'           => $newStatusMeta['bg'],
            'task_status_color'        => $newStatusMeta['color'],
        ]);
    }

    
    

    /**
     * POST /{username}/manage/projects/tasks/{task}/subtasks/{subtask}/complete-final
     * Last subtask done -> mark ALL done + close task
     * Requires remark + (optional) proof files
     */
    public function completeSubtaskAndTask(Request $request, string $username, Task $task, Subtask $subtask)
    {
        if ($task->assigned_to !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'status'        => 'required|in:done',
            'remark'        => 'required|string|min:5|max:2000',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        DB::transaction(function () use ($request, $task, $subtask) {
            // mark clicked subtask complete
            $subtask->update([
                'completed'    => true,
                'completed_at' => now(),
            ]);

            // mark ALL other subtasks complete
            $task->subtasks()
                ->where('id', '!=', $subtask->id)
                ->update([
                    'completed'    => true,
                    'completed_at' => now(),
                ]);

            // close the parent task
            $task->update([
                'status'                => 'done',
                'completed_at'          => now(),
                'last_status_change_at' => now(),
            ]);

            // log
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'completed',
                'body'     => $request->remark,
            ]);

            // attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('task-attachments/' . $task->id, 'public');

                    TaskAttachment::create([
                        'task_id'     => $task->id,
                        'uploaded_by' => $this->viewer->id,
                        'type'        => str_starts_with($file->getClientMimeType(), 'image/')
                            ? 'image'
                            : 'file',
                        'label'       => $file->getClientOriginalName(),
                        'path_or_url' => $path,
                    ]);
                }
            }
        });

        $task->refresh();
        $subtask->refresh();

        $completedCount = $task->subtasks()->where('completed', true)->count();
        $totalCount     = $task->subtasks()->count();
        $meta           = $this->statusMeta('done');

        return response()->json([
            'success'                   => true,
            'message'                   => 'Task completed successfully!',
            'task_id'                   => $task->id,

            'task_status'               => 'done',
            'task_status_label'         => $meta['label'],
            'task_status_bg'            => $meta['bg'],
            'task_status_color'         => $meta['color'],

            'completed_subtasks_count'  => $completedCount,
            'subtasks_count'            => $totalCount,
        ]);
    }
    /**
 * Sync the parent task status based on current subtask completion.
 *
 * Rules:
 * - If a previously completed task gets any subtask reopened,
 *   task goes back to "todo" and completed_at is cleared.
 *
 * - Otherwise:
 *      * 0 completed subtasks      -> "todo"
 *      * 1+ completed subtasks     -> "in-progress"
 *      * (100% completed is handled by completeSubtaskAndTask(), not here)
 *
 * Returns array [$completedCount, $totalCount, $newStatus]
 */
 






















/**
 * Central place to define how each status should look + label.
 * This keeps frontend and backend in sync.
 */
protected function statusMeta(string $status): array
{
    $map = [
        'todo' => [
            'label' => 'To Do',
            'bg'    => '#F4F5F7',
            'color' => '#6B778C',
        ],
        'in-progress' => [
            'label' => 'In Progress',
            'bg'    => '#DEEBFF',
            'color' => '#0052CC',
        ],
        'review' => [
            'label' => 'Review',
            'bg'    => '#FFFAE6',
            'color' => '#FF991F',
        ],
        'done' => [
            'label' => 'Done',
            'bg'    => '#E3FCEF',
            'color' => '#00875A',
        ],
        'blocked' => [
            'label' => 'Blocked',
            'bg'    => '#FFEBE6',
            'color' => '#DE350B',
        ],
        'postponed' => [
            'label' => 'Postponed',
            'bg'    => '#EAE6FF',
            'color' => '#8777D9',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'bg'    => '#F4F5F7',
            'color' => '#6B778C',
        ],
    ];

    return $map[$status] ?? [
        'label' => ucfirst(str_replace('-', ' ', $status)),
        'bg'    => '#F4F5F7',
        'color' => '#6B778C',
    ];
}


/**
 * Re-sync the parent task's status whenever a subtask is toggled.
 *
 * Rules:
 * - If task was "done" and any subtask gets reopened → task -> "todo", clear completed_at.
 * - Else:
 *      0 subtasks completed     => "todo"
 *      some (but not all) done  => "in-progress"
 *      all done                 => do NOT auto "done" here. (Full close = separate flow)
 *
 * Returns array:
 * [completedCount, totalCount, newStatus, newStatusMeta]
 */
protected function syncTaskStatusFromSubtasks(Task $task, bool $subtaskWasReopened): array
    {
        $completedCount = $task->subtasks()->where('completed', true)->count();
        $totalCount     = $task->subtasks()->count();

        $newStatus = $task->status;
        $updates   = [
            'last_status_change_at' => now(),
        ];

        // If task is currently "done" but user reopened (uncompleted) any subtask,
        // revert the task back to "todo"
        if ($subtaskWasReopened && $task->status === 'done') {
            $newStatus = 'todo';

            $updates['status']       = $newStatus;
            $updates['completed_at'] = null;

            $task->update($updates);

            return [
                $completedCount,
                $totalCount,
                $newStatus,
                $this->statusMeta($newStatus),
            ];
        }

        // Otherwise choose between "todo" / "in-progress"
        if ($completedCount === 0) {
            $newStatus = 'todo';
        } elseif ($completedCount < $totalCount) {
            $newStatus = 'in-progress';
        } else {
            // completedCount == totalCount:
            // keep whatever it already is, do not auto-force "done"
            $newStatus = $task->status;
        }

        if ($newStatus !== $task->status) {
            $updates['status'] = $newStatus;
            $task->update($updates);
        } else {
            // we still might need to save last_status_change_at if we touched it
            if (array_key_exists('completed_at', $updates)) {
                $task->update($updates);
            } else {
                $task->update([
                    'last_status_change_at' => now(),
                ]);
            }
        }

        return [
            $completedCount,
            $totalCount,
            $newStatus,
            $this->statusMeta($newStatus),
        ];
    }


}