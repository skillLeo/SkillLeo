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