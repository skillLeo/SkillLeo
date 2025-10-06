<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthIdentity extends Model
{
    protected $table = 'o_auth_identities'; // match migration name

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'provider_username',
        'avatar_url',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'provider_raw',
    ];

    protected $casts = [
        'provider_raw' => 'array',
        'token_expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
