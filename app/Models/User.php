<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'tenant_id','name','last_name','email','password','avatar_url','username',
        'locale','timezone','is_active','account_status','is_profile_complete',
        'country','state','city','meta',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at'   => 'datetime',
        'is_profile_complete' => 'string',
        'meta'                => 'array',
        'last_login_at'       => 'datetime',
        'login_count'         => 'integer',
    ];

    public function getRouteKeyName(): string { return 'username'; }

    // --- Content relations ---------------------------------------------------
    public function educations(): HasMany
    {
        return $this->hasMany(\App\Models\Education::class)->orderBy('position');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(\App\Models\Experience::class)->orderBy('position');
    }

    // ✅ NEW name, NEW model
    public function portfolios(): HasMany
    {
        return $this->hasMany(\App\Models\Portfolio::class)->orderBy('position');
    }

    public function preference(): HasOne
    {
        return $this->hasOne(\App\Models\Preference::class);
    }

    // Skills many-to-many stays as you had it
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Skill::class, 'user_skills')
            ->withPivot(['level', 'position'])
            ->withTimestamps()
            ->orderBy('user_skills.position');
    }

   
}
