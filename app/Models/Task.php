<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        return $this->hasMany(Subtask::class)->orderBy('order');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function activity(): HasMany
    {
        return $this->hasMany(TaskActivity::class)->latest();
    }

    public function dependencies()
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'task_id',            // this task
            'depends_on_task_id'  // the task it waits on
        );
    }

    // ---------------------------------
    // SCOPES
    // ---------------------------------

    /**
     * Limit to tasks inside a given workspace owner's projects
     */
    public function scopeWithinWorkspace(Builder $query, User $owner): Builder
    {
        return $query->whereHas('project', function ($q) use ($owner) {
            $q->where('user_id', $owner->id);
        });
    }

    /**
     * Limit to tasks assigned to a specific user.
     *
     * Accepts:
     *  - int user id
     *  - App\Models\User object
     *  - null (falls back to Auth::id())
     *
     * This prevents "Too few arguments" crashes if something
     * accidentally calls ->assignedTo() with no param.
     */
    public function scopeAssignedTo(Builder $query, $user = null): Builder
    {
        // If caller passed a full User model
        if ($user instanceof User) {
            $user = $user->id;
        }

        // If caller passed nothing, default to current auth user
        if ($user === null) {
            $user = Auth::id();
        }

        // If still null (no auth), return empty result instead of fatal
        if ($user === null) {
            // Force false condition so we don't leak everything
            return $query->whereRaw('1 = 0');
        }

        return $query->where('assigned_to', $user);
    }

    // ---------------------------------
    // ACCESSORS
    // ---------------------------------

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== self::STATUS_DONE;
    }

    // ---------------------------------
    // HELPERS / PRESENTERS
    // ---------------------------------

    public static function statusOptions(): array
    {
        return [
            self::STATUS_TODO,
            self::STATUS_IN_PROGRESS,
            self::STATUS_REVIEW,
            self::STATUS_DONE,
            self::STATUS_BLOCKED,
            self::STATUS_POSTPONED,
        ];
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function isPostponed(): bool
    {
        return $this->status === self::STATUS_POSTPONED;
    }

    /**
     * Short "activity line" for tables and dashboards
     */
    public function latestActivitySummary(): ?string
    {
        $latest = $this->activity()->first();
        if (!$latest) {
            return null;
        }

        $actor = $latest->actor?->name ?? 'Unknown';
        $type  = str_replace('_', ' ', $latest->type);
        $time  = $latest->created_at->diffForHumans();

        return "{$actor} {$type} • {$time}";
    }
}
