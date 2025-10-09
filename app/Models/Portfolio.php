<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'link_url',
        'image_path',
        'image_disk',
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

    public function media(): HasMany
    {
        return $this->hasMany(PortfolioMedia::class, 'portfolio_id')->orderBy('position');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) return null;
        $disk = $this->image_disk ?: 'public';
        return Storage::disk($disk)->url($this->image_path);
    }
}
