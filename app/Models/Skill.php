<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot(['level', 'position'])
            ->withTimestamps();
    }
}

