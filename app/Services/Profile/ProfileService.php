<?php

namespace App\Services\Profile;

use App\Models\User;

class ProfileService
{
    public function load(User $user): User
    {
        $user->load([
            'skills' => function ($q) {
                $q->select('skills.id', 'skills.name', 'skills.slug')
                  ->orderBy('user_skills.position');
            },
            'experiences'       => fn ($q) => $q->orderBy('position')->orderBy('id'),
            'experiences.skills'=> fn ($q) => $q->orderBy('position'),
            'educations'        => fn ($q) => $q->orderBy('position')->orderByDesc('is_current')->orderByDesc('end_year'),

            // ✅ use the new relation
            'portfolios'        => fn ($q) => $q->orderBy('position')->orderBy('id'),

            // singular in your model
            'preference',
        ]);

        return $user;
    }
}
