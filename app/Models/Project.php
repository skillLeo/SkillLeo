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

 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
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
















    public function user()
    {
        // owner (workspace owner / seller)
        return $this->belongsTo(User::class, 'user_id');
    }

 

    public function tasks()
    {
        return $this->hasMany(Task::class)
            ->orderBy('order')
            ->orderBy('id');
    }


 

public function notes(): HasMany
{
    return $this->hasMany(ProjectNote::class);
}

public function media(): HasMany
{
    return $this->hasMany(ProjectMedia::class);
}



public function team()
{
    // pivot table like project_user or project_team, etc.
    // adjust table/keys to match your schema
    return $this->belongsToMany(User::class, 'project_team', 'project_id', 'user_id')
                ->withPivot('role'); // e.g. role = 'project_manager'
}
}