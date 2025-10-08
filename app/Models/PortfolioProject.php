<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioProject extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'link_url',
        'image_path',
        'image_disk',
        'description',
        'position',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optional (if you use the media table later)
    public function media(): HasMany
    {
        return $this->hasMany(PortfolioProjectMedia::class)->orderBy('position');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        return \Storage::disk($this->image_disk)->url($this->image_path);
    }
}
