<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskActionService
{
    /**
     * Reassign a task to another user (or unassign).
     *
     * @param Task $task
     * @param int|null $newAssigneeId
     * @param string|null $comment
     * @param int $actorUserId (the user performing this action)
     *
     * @return Task fresh task model with relationships
     *
     * This:
     *  - logs "Reassigned from Alice to Bob"
     *  - logs optional comment from modal
     *  - updates task.assigned_to
     */
    public function reassignTask(
        Task $task,
        ?int $newAssigneeId,
        ?string $comment,
        int $actorUserId
    ): Task {
        return DB::transaction(function () use ($task, $newAssigneeId, $comment, $actorUserId) {

            $oldAssigneeId = $task->assigned_to;
            $changed = ($oldAssigneeId != $newAssigneeId);

            // Update the task record if changed
            if ($changed) {
                $task->update([
                    'assigned_to' => $newAssigneeId,
                ]);

                // Build nice human-readable names
                $names = $this->lookupUserNames([$oldAssigneeId, $newAssigneeId]);
                $oldName = $oldAssigneeId
                    ? ($names[$oldAssigneeId] ?? ('User #'.$oldAssigneeId))
                    : 'Unassigned';
                $newName = $newAssigneeId
                    ? ($names[$newAssigneeId] ?? ('User #'.$newAssigneeId))
                    : 'Unassigned';

                TaskActivity::create([
                    'task_id'  => $task->id,
                    'actor_id' => $actorUserId,
                    'type'     => 'reassigned',
                    'body'     => "Reassigned from {$oldName} to {$newName}",
                ]);
            }

            // Optional extra message from modal
            if ($comment) {
                TaskActivity::create([
                    'task_id'  => $task->id,
                    'actor_id' => $actorUserId,
                    'type'     => 'handoff_note',
                    'body'     => $comment,
                ]);
            }

            // Reload for response
            $task->load([
                'project:id,user_id,key',
                'assignee:id,name,avatar_url',
                'reporter:id,name,avatar_url',
                'subtasks' => function ($q) {
                    $q->orderBy('order')->orderBy('id')
                      ->select('id','task_id','title','completed','order');
                },
            ]);

            return $task;
        });
    }

    /**
     * Helper to resolve user IDs -> display names once efficiently.
     */
    protected function lookupUserNames(array $ids): array
    {
        $ids = collect($ids)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return [];
        }

        return User::whereIn('id', $ids)
            ->pluck('name', 'id')
            ->toArray();
    }
}
