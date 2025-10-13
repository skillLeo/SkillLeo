<?php


// app/Models/SoftSkill.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SoftSkill extends Model
{
    protected $fillable = ['name', 'slug', 'icon'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_soft_skills')
            ->withPivot(['level', 'position'])
            ->withTimestamps();
    }
}
