<?php
// app/Models/Project.php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use SoftDeletes;
protected $guarded = [];

    protected $casts = [
        'flags' => 'array',
        'start_date' => 'date',
        'due_date' => 'date',
        'budget' => 'decimal:2',
        'estimated_hours' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_team')
            ->withPivot(['role', 'tech_stack', 'position'])
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (Auth::check() && !$project->user_id) {
                $project->user_id = Auth::id();
            }
        });
    }
}