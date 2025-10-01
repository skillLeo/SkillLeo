<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'country',
        'state',
        'city',
        'skills',
        'experience',
        'portfolio',
        'education',
        'currency',
        'rate',
        'rate_unit',
        'availability',
        'hours_per_week',
        'remote_work',
        'open_to_work',
        'long_term',
        'is_public',
        'onboarding_completed',
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'array',
        'portfolio' => 'array',
        'education' => 'array',
        'remote_work' => 'boolean',
        'open_to_work' => 'boolean',
        'long_term' => 'boolean',
        'is_public' => 'boolean',
        'onboarding_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}