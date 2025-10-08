<?php

namespace App\Models;

use App\Models\Skill;
use App\Models\Tenant;
use App\Models\EmailOtp;
use App\Models\Education;
use App\Models\Experience;
use App\Models\UserDevice;
use App\Models\OAuthIdentity;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

// app/Models/User.php
protected $fillable = [
    'tenant_id',
    'name',
    'last_name',
    'email',
    'password',
    'avatar_url',
    'username',
    'locale',
    'timezone',
    'is_active',
    'account_status',
    'is_profile_complete',
    'country',     
    'state',       
    'city',        
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
    
 // app/Models/User.php

public function skills()
{
    return $this->belongsToMany(\App\Models\Skill::class, 'user_skills')
        ->withPivot(['level', 'position'])
        ->withTimestamps()
        ->orderBy('user_skills.position');
}
// app/Models/User.php
public function educations()
{
    return $this->hasMany(\App\Models\Education::class)->orderBy('position');
}

public function experiences()
{
    return $this->hasMany(\App\Models\Experience::class)->orderBy('position');
}

public function portfolioProjects()
{
    return $this->hasMany(\App\Models\PortfolioProject::class)->orderBy('position');
}

public function preference()
{
    return $this->hasOne(\App\Models\Preference::class);
}









}
