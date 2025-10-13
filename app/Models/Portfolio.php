<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PortfolioTag;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'link_url',
        'image_path',
        'image_disk',
        'category',
        'position',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}