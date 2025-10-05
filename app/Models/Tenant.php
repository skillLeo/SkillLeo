<?php
// app/Models/Tenant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'plan',
        'seats_limit',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

// app/Models/OAuthIdentity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthIdentity extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_uid',
        'email',
        'access_token',
        'refresh_token',
        'expires_at',
        'profile',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'profile' => 'array',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
