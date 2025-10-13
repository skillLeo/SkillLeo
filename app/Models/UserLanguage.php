<?php

// app/Models/UserLanguage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'level',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional helper
    public function getLevelLabelAttribute(): string
    {
        return match ((int)$this->level) {
            4 => 'Native or Bilingual',
            3 => 'Professional Working',
            2 => 'Limited Working',
            default => 'Elementary',
        };
    }
}
