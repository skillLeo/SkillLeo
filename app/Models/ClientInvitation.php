<?php
// app/Models/ClientInvitation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientInvitation extends Model
{
    protected $fillable = [
        'inviter_id',
        'email',
        'name',
        'message',
        'token',
        'status',
        'expires_at',
        'accepted_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}