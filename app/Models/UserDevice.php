<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = ['user_id','name','ip','user_agent','last_seen_at','revoked_at'];
    protected $casts = ['last_seen_at'=>'datetime','revoked_at'=>'datetime'];

    public function user(){ return $this->belongsTo(User::class); }
}
