<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    // ---------------------------------
    // STATUS CONSTANTS
    // ---------------------------------
    const STATUS_TODO        = 'todo';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_REVIEW      = 'review';
    const STATUS_DONE        = 'done';
    const STATUS_BLOCKED     = 'blocked';
    const STATUS_POSTPONED   = 'postponed';

    protected $fillable = [
        'project_id',
        'assigned_to',
        'reporter_id',
        'title',
        'notes',
        'status',
        'priority',
        'due_date',
        'order',
        
        // 🔥 CRITICAL: Add these missing fields
        'estimated_hours',
        'story_points',
        
        'postponed_until',
        'blocked_reason',
        'client_visible',
        'requires_client_approval',
        'approved_at',
        'completed_at',
        'submitted_for_review_at',
        'last_status_change_at',
    ];

    protected $casts = [
        'due_date'                 => 'date',
        'postponed_until'          => 'date',
        'approved_at'              => 'datetime',
        'completed_at'             => 'datetime',
        'submitted_for_review_at'  => 'datetime',
        'last_status_change_at'    => 'datetime',
        'client_visible'           => 'boolean',
        'requires_client_approval' => 'boolean',
        
        // 🔥 Add proper casting
        'estimated_hours'          => 'decimal:2',
        'story_points'             => 'integer',
    ];

    protected $appends = ['is_overdue'];

    // ---------------------------------
    // RELATIONSHIPS
    // ---------------------------------

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class);
    }

    // ---------------------------------
    // SCOPES
    // ---------------------------------

    public function scopeWithinWorkspace(Builder $query, $owner): Builder
    {
        return $query->whereHas('project', function ($q) use ($owner) {
            $q->where('user_id', $owner->id);
        });
    }

    // ---------------------------------
    // ACCESSORS
    // ---------------------------------

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return $this->due_date->isPast() && $this->status !== self::STATUS_DONE;
    }
}