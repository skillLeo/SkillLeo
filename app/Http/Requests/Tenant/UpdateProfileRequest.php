<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user(); // must be logged in
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            // Basic
            'name'     => ['required', 'string', 'min:2', 'max:160'],
            'email'    => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($userId)],
            'headline' => ['nullable', 'string', 'max:120'],
            'about'    => ['nullable', 'string', 'max:2000'],

            // Location
            'location' => ['nullable', 'string', 'max:190'],
            'country'  => ['nullable', 'string', 'max:120'],
            'state'    => ['nullable', 'string', 'max:120'],
            'city'     => ['nullable', 'string', 'max:120'],
            'timezone' => ['nullable', 'timezone'],

            // Contact
            'phone'    => ['nullable', 'string', 'max:40'],

            // Socials
            'linkedin'  => ['nullable', 'url', 'max:255'],
            'twitter'   => ['nullable', 'url', 'max:255'],
            'facebook'  => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],

            // Avatar
            'avatar'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'remove_avatar' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already in use.',
            'avatar.mimes' => 'Avatar must be JPG, PNG, or WebP.',
            'avatar.max'   => 'Avatar may not be greater than 5MB.',
        ];
    }
}
