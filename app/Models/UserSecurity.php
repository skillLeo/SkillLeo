<?php

// app/Models/UserSecurity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSecurity extends Model
{
    protected $table = 'user_security';

    protected $fillable = [
        'user_id',
        'two_factor_email',
        'two_factor_phone',
        'two_factor_enabled',
        'recovery_code',
        'last_verified_at',
    ];

    protected $casts = [
        'two_factor_email'   => 'boolean',
        'two_factor_phone'   => 'boolean',
        'two_factor_enabled' => 'boolean',
        'last_verified_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
