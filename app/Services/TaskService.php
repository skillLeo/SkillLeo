<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Subtask;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    /**
     * Update a task (full audit trail)
     *
     * @param Task $task
     * @param array $data validated request data
     * @param int $actorUserId who is making the change
     * @return Task
     * @throws \Exception
     */
    public function updateTask(Task $task, array $data, int $actorUserId): Task
    {
        return DB::transaction(function () use ($task, $data, $actorUserId) {
            // 1. detect changes
            $changes = $this->detectChanges($task, $data);

            // 2. cache user names for assignment logs
            $userCache = $this->buildUserCache($task, $data);

            // 3. update core fields
            $this->updateTaskFields($task, $data);

            // 4. sync subtasks if provided
            if (array_key_exists('subtasks', $data)) {
                $this->syncSubtasks($task, $data['subtasks'], $actorUserId);
            }

            // 5. log activity entries
            $this->logChangesToActivity($task, $changes, $actorUserId, $userCache);

            // 6. reload relationships for response
            $task->load([
                'project:id,name,key,user_id',
                'assignee:id,name,avatar_url',
                'reporter:id,name,avatar_url',
                'subtasks' => fn($q) => $q->orderBy('order')->orderBy('id'),
                'attachments',
            ]);

            return $task;
        });
    }

    /**
     * figure out which simple fields are changing
     */
    protected function detectChanges(Task $task, array $data): array
    {
        $trackableFields = [
            'title',
            'notes',
            'priority',
            'due_date',
            'estimated_hours',
            'story_points',
            'assigned_to',
        ];
        
        $changes = [];
        
        foreach ($trackableFields as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            
            $oldValue = $task->{$field};
            $newValue = $data[$field];

            if ($oldValue instanceof \DateTime) {
                $oldValue = $oldValue->format('Y-m-d');
            }
            if ($newValue instanceof \DateTime) {
                $newValue = $newValue->format('Y-m-d');
            }
            
            if ($oldValue != $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }
        
        return $changes;
    }

    /**
     * Get user info for activity logs ("reassigned to Sarah")
     */
    protected function buildUserCache(Task $task, array $data): array
    {
        $userIds = collect([
            $task->assigned_to,
            $data['assigned_to'] ?? null,
        ])->filter()->unique()->values();
        
        if ($userIds->isEmpty()) {
            return [];
        }
        
        return User::whereIn('id', $userIds)
            ->get(['id', 'name'])
            ->keyBy('id')
            ->toArray();
    }

    /**
     * update fillable fields
     */
    protected function updateTaskFields(Task $task, array $data): void
    {
        $fillableFields = [
            'title',
            'notes',
            'priority',
            'due_date',
            'estimated_hours',
            'story_points',
            'assigned_to',
        ];
        
        $updateData = [];
        
        foreach ($fillableFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (!empty($updateData)) {
            $task->update($updateData);
        }
    }

    /**
     * Subtask sync (create / update / delete)
     */
    protected function syncSubtasks(Task $task, array $subtasksData, int $actorUserId): void
    {
        $existingSubtaskIds = [];
        
        foreach ($subtasksData as $index => $subtaskData) {
            $subtaskId = $subtaskData['id'] ?? null;
            $title = trim($subtaskData['title'] ?? '');
            $completed = (bool)($subtaskData['completed'] ?? false);
            
            if (empty($title)) {
                continue;
            }
            
            if ($subtaskId) {
                // update existing
                $subtask = Subtask::where('task_id', $task->id)
                    ->where('id', $subtaskId)
                    ->first();
                
                if ($subtask) {
                    $wasCompleted = $subtask->completed;
                    
                    $subtask->update([
                        'title' => $title,
                        'completed' => $completed,
                        'completed_at' => $completed 
                            ? ($subtask->completed_at ?? now()) 
                            : null,
                        'order' => $index + 1,
                    ]);
                    
                    // log completion toggle change if any
                    if (!$wasCompleted && $completed) {
                        $this->logSubtaskCompletion($task, $subtask, $actorUserId, true);
                    } elseif ($wasCompleted && !$completed) {
                        $this->logSubtaskCompletion($task, $subtask, $actorUserId, false);
                    }
                    
                    $existingSubtaskIds[] = $subtask->id;
                }
            } else {
                // create new subtask
                $newSubtask = Subtask::create([
                    'task_id' => $task->id,
                    'title' => $title,
                    'completed' => $completed,
                    'completed_at' => $completed ? now() : null,
                    'order' => $index + 1,
                ]);
                
                TaskActivity::create([
                    'task_id' => $task->id,
                    'actor_id' => $actorUserId,
                    'type' => 'subtask_created',
                    'body' => "Created subtask: {$title}",
                ]);
                
                $existingSubtaskIds[] = $newSubtask->id;
            }
        }
        
        // delete subtasks removed from payload
        $deletedCount = Subtask::where('task_id', $task->id)
            ->whereNotIn('id', $existingSubtaskIds)
            ->delete();
        
        if ($deletedCount > 0) {
            TaskActivity::create([
                'task_id' => $task->id,
                'actor_id' => $actorUserId,
                'type' => 'subtask_deleted',
                'body' => "Removed {$deletedCount} subtask(s)",
            ]);
        }
    }

    protected function logSubtaskCompletion(Task $task, Subtask $subtask, int $actorUserId, bool $completed): void
    {
        $type = $completed ? 'subtask_completed' : 'subtask_reopened';
        $action = $completed ? 'completed' : 'reopened';
        
        TaskActivity::create([
            'task_id'   => $task->id,
            'actor_id'  => $actorUserId,
            'type'      => $type,
            'body'      => "Subtask {$action}: {$subtask->title}",
        ]);
    }

    /**
     * Add activity entries like:
     *  - "Priority changed from High to Low"
     *  - "Reassigned from Alice to Bob"
     */
    protected function logChangesToActivity(Task $task, array $changes, int $actorUserId, array $userCache): void
    {
        foreach ($changes as $field => $change) {
            $type = 'updated';
            $body = $this->generateChangeDescription($field, $change, $userCache);
            
            if ($field === 'assigned_to') {
                $type = 'reassigned';
            }
            
            TaskActivity::create([
                'task_id' => $task->id,
                'actor_id' => $actorUserId,
                'type' => $type,
                'body' => $body,
            ]);
        }
    }

    /**
     * Turn a change into a human-readable log line
     */
    protected function generateChangeDescription(string $field, array $change, array $userCache): string
    {
        $old = $change['old'];
        $new = $change['new'];
        
        switch ($field) {
            case 'assigned_to':
                $oldName = $old ? ($userCache[$old]['name'] ?? "User #{$old}") : 'Unassigned';
                $newName = $new ? ($userCache[$new]['name'] ?? "User #{$new}") : 'Unassigned';
                return "Reassigned from {$oldName} to {$newName}";
                
            case 'priority':
                return "Priority changed from " . ucfirst($old) . " to " . ucfirst($new);
                
            case 'due_date':
                $oldDate = $old ? date('M j, Y', strtotime($old)) : 'No date';
                $newDate = $new ? date('M j, Y', strtotime($new)) : 'No date';
                return "Due date changed from {$oldDate} to {$newDate}";
                
            case 'estimated_hours':
                $oldHours = $old ?? 'Not set';
                $newHours = $new ?? 'Not set';
                return "Estimated hours changed from {$oldHours} to {$newHours}";
                
            case 'story_points':
                $oldPoints = $old ?? '0';
                $newPoints = $new ?? '0';
                return "Story points changed from {$oldPoints} to {$newPoints}";
                
            case 'title':
                return "Title updated";
                
            case 'notes':
                return "Description updated";
                
            default:
                $label = ucwords(str_replace('_', ' ', $field));
                return "{$label} changed";
        }
    }
}
