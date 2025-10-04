<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles; // enable after installing

class User extends Authenticatable
{
    use HasApiTokens, Notifiable; // , HasRoles;

    protected $fillable = [
        'tenant_id','name','email','password','avatar_url','username',
        'locale','timezone','status','is_profile_complete','intent','meta'
    ];

    protected $hidden = ['password','remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_profile_complete' => 'bool',
        'meta' => 'array',
        'last_login_at' => 'datetime',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function oauthIdentities() { return $this->hasMany(OAuthIdentity::class); }
    public function devices() { return $this->hasMany(UserDevice::class); }
}
