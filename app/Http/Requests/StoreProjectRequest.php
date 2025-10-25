<?php
// app/Http/Requests/StoreProjectRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:10|unique:projects,key',
            'type' => 'required|in:scrum,kanban,waterfall,custom',
            'category' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'estimated_hours' => 'nullable|numeric|min:0',
            'flags' => 'nullable|array',
            
            'tasks' => 'nullable|array',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.notes' => 'nullable|string',
            'tasks.*.priority' => 'required|in:low,medium,high,urgent',
            'tasks.*.due_date' => 'nullable|date',
            'tasks.*.estimated_hours' => 'nullable|numeric|min:0',
            'tasks.*.story_points' => 'nullable|integer|min:0',
            'tasks.*.flags' => 'nullable|array',
            'tasks.*.subtasks' => 'nullable|array',
            'tasks.*.subtasks.*.title' => 'required|string|max:255',
            'tasks.*.subtasks.*.completed' => 'boolean',
            'tasks.*.dependencies' => 'nullable|array',
            'tasks.*.assigned_to' => 'nullable|exists:users,id',
            
            'team' => 'nullable|array',
            'team.*.user_id' => 'required|exists:users,id',
            'team.*.role' => 'required|string',
            'team.*.tech_stack' => 'required|string',
            'team.*.position' => 'required|string',
            
            'client_id' => 'nullable|exists:clients,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Project name is required',
            'key.required' => 'Project key is required',
            'key.unique' => 'This project key is already taken',
            'due_date.after_or_equal' => 'Due date must be after or equal to start date',
            'tasks.*.title.required' => 'All tasks must have a title',
        ];
    }
}