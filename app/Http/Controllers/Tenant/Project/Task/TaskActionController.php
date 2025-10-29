<?php

namespace App\Http\Controllers\Tenant\Project\Task;

use App\Models\Task;
use App\Models\User;
use App\Models\Subtask;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Tenant\BaseTenantController;

class TaskActionController extends BaseTenantController
{
    /**
     * POST /{username}/manage/projects/tasks/{task}/status
     * Update task status (done/postponed/blocked/cancelled)
     * Returns complete state for real-time UI update
     */
    public function updateStatus(Request $request, string $username, Task $task)
    {
        // Authorization check
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
            'attachments.*'    => 'nullable|file|max:10240',
        ]);

        $responseData = [];

        DB::transaction(function () use ($request, $task, &$responseData) {
            $updateData = [
                'status'                => $request->status,
                'last_status_change_at' => now(),
            ];

            // Handle "done" status
            if ($request->status === 'done') {
                $updateData['completed_at'] = now();

                // Mark ALL subtasks complete
                $task->subtasks()->update([
                    'completed'    => true,
                    'completed_at' => now(),
                ]);
            }

            // Handle "postponed" status
            if ($request->status === 'postponed' && $request->postponed_until) {
                $updateData['postponed_until'] = $request->postponed_until;
                $updateData['due_date']        = $request->postponed_until;
            }

            // Handle "blocked" status
            if ($request->status === 'blocked') {
                $updateData['blocked_reason'] = $request->remark;
            } else {
                $updateData['blocked_reason'] = null;
            }

            // Update task
            $task->update($updateData);

            // Log activity
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => $request->status === 'done' ? 'completed' : $request->status,
                'body'     => $request->remark,
            ]);

            // Handle attachments
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

            // Prepare response data
            $task->refresh();
            $completedCount = $task->subtasks()->where('completed', true)->count();
            $totalCount     = $task->subtasks()->count();
            $meta           = $this->statusMeta($task->status);

            $responseData = [
                'success'                   => true,
                'message'                   => 'Task updated successfully',
                'task_id'                   => $task->id,
                
                // Status information
                'task_status'               => $task->status,
                'task_status_label'         => $meta['label'],
                'task_status_bg'            => $meta['bg'],
                'task_status_color'         => $meta['color'],
                
                // Subtask progress
                'completed_subtasks_count'  => $completedCount,
                'subtasks_count'            => $totalCount,
                
                // Additional data for UI
                'completed_at'              => $task->completed_at?->toIso8601String(),
                'postponed_until'           => $task->postponed_until?->format('Y-m-d'),
                'blocked_reason'            => $task->blocked_reason,
            ];
        });

        return response()->json($responseData);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/remark
     * Add comment without changing status
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
     * Toggle subtask completion
     * RETURNS COMPLETE STATE FOR REAL-TIME UPDATE
     */
    public function toggleSubtask(Request $request, string $username, Task $task, Subtask $subtask)
    {
        // Authorization
        if ($task->assigned_to !== $this->viewer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Validation
        if ($subtask->task_id !== $task->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid subtask',
            ], 404);
        }

        $markCompleted = !$subtask->completed;
        $responseData = [];

        DB::transaction(function () use (
            $task,
            $subtask,
            $markCompleted,
            &$responseData
        ) {
            // 1. Toggle subtask
            $subtask->update([
                'completed'    => $markCompleted,
                'completed_at' => $markCompleted ? now() : null,
            ]);

            // 2. Log activity
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'subtask_' . ($markCompleted ? 'completed' : 'reopened'),
                'body'     => $subtask->title,
            ]);

            // 3. Sync parent task status
            $subtaskWasReopened = !$markCompleted;
            [$completedCount, $totalCount, $newStatus, $newStatusMeta] =
                $this->syncTaskStatusFromSubtasks($task, $subtaskWasReopened);

            // 4. Prepare complete response
            $subtask->refresh();
            $task->refresh();

            $responseData = [
                'success' => true,
                
                // Subtask data
                'subtask' => [
                    'id'           => $subtask->id,
                    'title'        => $subtask->title,
                    'completed'    => (bool) $subtask->completed,
                    'completed_at' => $subtask->completed_at?->toIso8601String(),
                ],
                
                // Subtask counts
                'completed_subtasks_count' => $completedCount,
                'subtasks_count'           => $totalCount,
                
                // Task status (may have changed)
                'task_status'              => $newStatus,
                'task_status_label'        => $newStatusMeta['label'],
                'task_status_bg'           => $newStatusMeta['bg'],
                'task_status_color'        => $newStatusMeta['color'],
                
                // Additional task data
                'task_completed_at'        => $task->completed_at?->toIso8601String(),
            ];
        });

        return response()->json($responseData);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/subtasks/{subtask}/complete-final
     * Complete last subtask and mark entire task as done
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

        $responseData = [];

        DB::transaction(function () use ($request, $task, $subtask, &$responseData) {
            // Mark this subtask complete
            $subtask->update([
                'completed'    => true,
                'completed_at' => now(),
            ]);

            // Mark ALL other subtasks complete
            $task->subtasks()
                ->where('id', '!=', $subtask->id)
                ->update([
                    'completed'    => true,
                    'completed_at' => now(),
                ]);

            // Close the parent task
            $task->update([
                'status'                => 'done',
                'completed_at'          => now(),
                'last_status_change_at' => now(),
            ]);

            // Log activity
            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $this->viewer->id,
                'type'     => 'completed',
                'body'     => $request->remark,
            ]);

            // Handle attachments
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

            // Prepare response
            $task->refresh();
            $subtask->refresh();
            $completedCount = $task->subtasks()->where('completed', true)->count();
            $totalCount     = $task->subtasks()->count();
            $meta           = $this->statusMeta('done');

            $responseData = [
                'success'                   => true,
                'message'                   => 'Task completed successfully!',
                'task_id'                   => $task->id,
                
                // Subtask that was clicked
                'subtask' => [
                    'id'           => $subtask->id,
                    'title'        => $subtask->title,
                    'completed'    => true,
                    'completed_at' => $subtask->completed_at->toIso8601String(),
                ],
                
                // Task status
                'task_status'               => 'done',
                'task_status_label'         => $meta['label'],
                'task_status_bg'            => $meta['bg'],
                'task_status_color'         => $meta['color'],
                
                // Subtask progress (should be 100%)
                'completed_subtasks_count'  => $completedCount,
                'subtasks_count'            => $totalCount,
                
                // Task completion time
                'task_completed_at'         => $task->completed_at->toIso8601String(),
            ];
        });

        return response()->json($responseData);
    }

    /**
     * POST /{username}/manage/projects/tasks/{task}/reassign
     * Reassign task to another user
     */
    public function reassign(Request $request, string $username, int $taskId)
    {
        $owner = User::where('username', $username)->firstOrFail();
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in.',
            ], 401);
        }

        $task = Task::query()
            ->with(['project', 'assignee'])
            ->where('id', $taskId)
            ->whereHas('project', function ($q) use ($owner) {
                $q->where('user_id', $owner->id);
            })
            ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found in this workspace.',
            ], 404);
        }

        // Permission check
        $callerIsOwner    = ($authUser->id === $owner->id);
        $callerIsReporter = ($task->reporter_id && $task->reporter_id == $authUser->id);
        $callerIsAssignee = ($task->assigned_to && $task->assigned_to == $authUser->id);

        if (!$callerIsOwner && !$callerIsReporter && !$callerIsAssignee) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to reassign this task.',
            ], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'note'    => 'nullable|string|max:500',
        ]);

        $newAssignee = User::find($validated['user_id']);
        
        if (!$newAssignee) {
            return response()->json([
                'success' => false,
                'message' => 'Assignee not found.',
            ], 404);
        }

        $oldAssigneeId = $task->assigned_to;

        DB::transaction(function () use ($task, $newAssignee, $oldAssigneeId, $validated, $authUser) {
            $task->assigned_to = $newAssignee->id;
            $task->touch();
            $task->save();

            TaskActivity::create([
                'task_id'  => $task->id,
                'actor_id' => $authUser->id,
                'type'     => 'reassigned',
                'body'     => json_encode([
                    'from_user_id' => $oldAssigneeId,
                    'to_user_id'   => $newAssignee->id,
                    'note'         => $validated['note'] ?? null,
                ]),
            ]);
        });

        $task->load('assignee');

        return response()->json([
            'success' => true,
            'message' => 'Task reassigned to ' . $newAssignee->name,
            'task' => [
                'id'          => $task->id,
                'assigned_to' => $task->assigned_to,
                'assignee'    => [
                    'id'         => $task->assignee->id,
                    'name'       => $task->assignee->name,
                    'email'      => $task->assignee->email,
                    'avatar_url' => $task->assignee->avatar_url ?? '/default-avatar.png',
                ],
            ],
        ]);
    }

    /**
     * Sync parent task status based on subtask completion
     * 
     * Returns: [completedCount, totalCount, newStatus, statusMeta]
     */
    protected function syncTaskStatusFromSubtasks(Task $task, bool $subtaskWasReopened): array
    {
        $completedCount = $task->subtasks()->where('completed', true)->count();
        $totalCount     = $task->subtasks()->count();

        $newStatus = $task->status;
        $updates   = ['last_status_change_at' => now()];

        // If task was "done" but user reopened a subtask, revert to "todo"
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

        // Determine status based on completion
        if ($completedCount === 0) {
            $newStatus = 'todo';
        } elseif ($completedCount < $totalCount) {
            $newStatus = 'in-progress';
        } else {
            // All complete but not forcing "done" (requires explicit action)
            $newStatus = $task->status;
        }

        if ($newStatus !== $task->status) {
            $updates['status'] = $newStatus;
        }

        $task->update($updates);

        return [
            $completedCount,
            $totalCount,
            $newStatus,
            $this->statusMeta($newStatus),
        ];
    }

    /**
     * Get status metadata (color, label, background)
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
}