<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates incoming task update requests:
 * - core task fields
 * - subtasks array
 * - assignment rules
 */
class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');
        $user = $this->user();
        
        if (!$task || !$user) {
            return false;
        }
        
        // reporter OR project owner can update
        // (reporter_id is the creator field, not created_by)
        return $task->reporter_id === $user->id 
            || $task->project->user_id === $user->id;
    }

    public function rules(): array
    {
        $task = $this->route('task');
        
        return [
            // core fields
            'title' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            
            'notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
            
            'priority' => [
                'sometimes',
                'required',
                Rule::in(['low', 'medium', 'high', 'urgent']),
            ],
            
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            
            'estimated_hours' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99',
            ],
            
            'story_points' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
            
            // assignment
            // (still allowed on backend even though edit modal no longer changes it.
            //  your add modal or other flows can still send it.)
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id'),
                function ($attribute, $value, $fail) use ($task) {
                    if (!$value) {
                        return; // allowed to unassign
                    }

                    $isInTeam = $task->project
                        ->team()
                        ->where('user_id', $value)
                        ->exists();
                    
                    $isProjectOwner = $task->project->user_id === $value;
                    
                    if (!$isInTeam && !$isProjectOwner) {
                        $fail('The selected user is not part of this project team.');
                    }
                },
            ],
            
            // subtasks
            'subtasks' => [
                'sometimes',
                'array',
                'max:50',
            ],
            
            'subtasks.*.id' => [
                'nullable',
                'integer',
                Rule::exists('subtasks', 'id')->where(function ($query) use ($task) {
                    $query->where('task_id', $task->id);
                }),
            ],
            
            'subtasks.*.title' => [
                'required',
                'string',
                'min:1',
                'max:500',
            ],
            
            'subtasks.*.completed' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title cannot be empty.',
            'title.min' => 'Task title must be at least 3 characters.',
            'title.max' => 'Task title cannot exceed 255 characters.',
            
            'priority.required' => 'Priority level is required.',
            'priority.in' => 'Priority must be: low, medium, high, or urgent.',
            
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
            
            'estimated_hours.numeric' => 'Estimated hours must be a number.',
            'estimated_hours.min' => 'Estimated hours cannot be negative.',
            'estimated_hours.max' => 'Estimated hours seems unrealistic (max 999.99).',
            
            'story_points.integer' => 'Story points must be a whole number.',
            'story_points.max' => 'Story points cannot exceed 100.',
            
            'assigned_to.exists' => 'The selected user does not exist.',
            
            'subtasks.max' => 'You cannot have more than 50 subtasks per task.',
            'subtasks.*.title.required' => 'Each subtask must have a title.',
            'subtasks.*.title.max' => 'Subtask title cannot exceed 500 characters.',
            'subtasks.*.id.exists' => 'One of the subtasks does not belong to this task.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'task title',
            'notes' => 'task description',
            'priority' => 'priority level',
            'due_date' => 'due date',
            'estimated_hours' => 'estimated hours',
            'story_points' => 'story points',
            'assigned_to' => 'assignee',
            'subtasks' => 'subtasks',
        ];
    }
}
