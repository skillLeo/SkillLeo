<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReassignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');
        $user = $this->user();

        if (!$task || !$user) {
            return false;
        }

        // defer to TaskPolicy@reassign logic
        return $user->can('reassign', $task);
    }

    public function rules(): array
    {
        $task = $this->route('task');

        return [
            'assigned_to' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id'),
                function ($attribute, $value, $fail) use ($task) {
                    // allow unassign
                    if (!$value) {
                        return;
                    }

                    // the user must be:
                    // - project owner OR
                    // - in the project team
                    $project = $task->project;

                    $inTeam = $project->team()
                        ->where('user_id', $value)
                        ->exists();

                    $isOwner = ((int) $project->user_id === (int) $value);

                    if (!$inTeam && !$isOwner) {
                        $fail('This user is not a member of the project team.');
                    }
                },
            ],

            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'assigned_to.exists' => 'That user was not found.',
            'comment.max'        => 'Comment is too long (2000 char max).',
        ];
    }
}
