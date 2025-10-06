<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'avatar_url',
        'username',
        'locale',
        'timezone',
        'is_active',
        'account_status', 
        'is_profile_complete',
        'meta',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at'   => 'datetime',
        'is_profile_complete' => 'string',
        'meta'                => 'array',
        'last_login_at'       => 'datetime',
        'login_count'         => 'integer',
    ];
 
    // Relationships
    public function tenant()        { return $this->belongsTo(Tenant::class); }
    public function devices()       { return $this->hasMany(UserDevice::class); }
    public function emailOtps()     { return $this->hasMany(EmailOtp::class); }

  

    public function oauthIdentities()
    {
        return $this->hasMany(\App\Models\OAuthIdentity::class);
    }
    
 
    
}
