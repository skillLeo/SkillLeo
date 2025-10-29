<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

/**
 * Task Authorization Policy
 * 
 * Centralizes all task permission logic following Laravel conventions
 */
class TaskPolicy
{
    /**
     * Determine if the user can view the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        // Can view if:
        // - Assigned to the task
        // - Created the task
        // - Project owner
        // - Project team member
        return $task->assigned_to === $user->id
            || $task->reporter_id === $user->id
            || $task->project->user_id === $user->id
            || $task->project->team()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine if the user can update the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        // Can update if:
        // - Task creator (reporter)
        // - Project owner
        // - Has project manager role
        return $task->reporter_id === $user->id
            || $task->project->user_id === $user->id
            || $this->hasProjectManagerRole($user, $task);
    }

    /**
     * Determine if the user can delete the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function delete(User $user, Task $task): bool
    {
        // Can delete if:
        // - Task creator
        // - Project owner
        return $task->reporter_id === $user->id
            || $task->project->user_id === $user->id;
    }

    /**
     * Determine if the user can change task status (complete, postpone, block)
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function changeStatus(User $user, Task $task): bool
    {
        // Can change status if:
        // - Assigned to the task (can complete own work)
        // - Task creator (can manage)
        // - Project owner (can override)
        return $task->assigned_to === $user->id
            || $task->reporter_id === $user->id
            || $task->project->user_id === $user->id;
    }

    /**
     * Determine if the user can reassign the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function reassign(User $user, Task $task): bool
    {
        // Can reassign if:
        // - Task creator
        // - Project owner
        return $task->reporter_id === $user->id
            || $task->project->user_id === $user->id;
    }

    /**
     * Determine if the user can add comments to the task
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function comment(User $user, Task $task): bool
    {
        // Can comment if they can view the task
        return $this->view($user, $task);
    }

    /**
     * Determine if the user can toggle subtasks
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function toggleSubtask(User $user, Task $task): bool
    {
        // Can toggle subtasks if:
        // - Assigned to the task (working on it)
        // - Task creator (managing it)
        // - Project owner (overseeing it)
        return $task->assigned_to === $user->id
            || $task->reporter_id === $user->id
            || $task->project->user_id === $user->id;
    }

    /**
     * Check if user has project manager role for this task's project
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    protected function hasProjectManagerRole(User $user, Task $task): bool
    {
        // Check if user has 'project_manager' role in project_team
        return $task->project
            ->team()
            ->where('user_id', $user->id)
            ->where('role', 'project_manager')
            ->exists();
    }
}