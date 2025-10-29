<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

class TaskActivityService
{
    /**
     * Build a lookup of ALL users involved in any "reassigned" activity
     * for this task. We parse the JSON stored in task_activities.body.
     *
     * Returns:
     *   Collection keyed by user_id:
     *   [
     *      1 => User { id:1, name:"Hassam Dev", avatar_url:"..." },
     *      3 => User { id:3, name:"Ali",        avatar_url:"..." },
     *   ]
     */
    public function loadActivityUsers(Task $task): Collection
    {
        $userIds = $task->activities
            ->where('type', 'reassigned')
            ->flatMap(function ($activity) {
                $parsed = $this->parseReassignPayload($activity->body);

                return [
                    $parsed['from_user_id'] ?? null,
                    $parsed['to_user_id']   ?? null,
                ];
            })
            ->filter()    // drop null
            ->unique()
            ->values();

        return User::whereIn('id', $userIds)
            ->get(['id', 'name', 'avatar_url'])
            ->keyBy('id'); // so we can do $reassignmentUsers[$id]
    }

    /**
     * Decode the body field for "reassigned" activities.
     * body is stored like:
     * {"from_user_id":"1","to_user_id":"3","note":"asdf"}
     *
     * Returns array [
     *   'from_user_id' => 1,
     *   'to_user_id'   => 3,
     *   'note'         => "asdf",
     * ]
     */
    public function parseReassignPayload(?string $body): array
    {
        if (!is_string($body) || trim($body) === '') {
            return [];
        }

        $decoded = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // not valid JSON → maybe someone changed schema later
        return [];
    }
}
