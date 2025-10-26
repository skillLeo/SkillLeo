<?php
// app/Models/Subtask.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtask extends Model
{
    protected $fillable = [
        'task_id',
        'title',
        'completed',
        'postponed_until',
        'completed_at',
        'order',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'postponed_until' => 'date',
        'completed_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}