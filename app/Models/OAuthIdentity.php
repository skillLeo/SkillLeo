<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthIdentity extends Model
{
    // 👇 add this line so Eloquent doesn't look for "o_auth_identities"
    protected $table = 'oauth_identities';

    protected $fillable = [
        'user_id','provider','provider_uid','email','access_token','refresh_token','expires_at','profile'
    ];
    protected $casts = ['profile' => 'array', 'expires_at' => 'datetime'];

    public function user(){ return $this->belongsTo(User::class); }
}
