<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'client_name',
        'title',
        'location',
        'content',
        'image_path',
        'image_disk',
        'position',
    ];

    protected $casts = [
        'user_id'  => 'integer',
        'position' => 'integer',
    ];
}
