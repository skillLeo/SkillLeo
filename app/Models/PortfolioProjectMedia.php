<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioProjectMedia extends Model
{
    protected $fillable = [
        'portfolio_project_id',
        'image_path',
        'image_disk',
        'position',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PortfolioProject::class, 'portfolio_project_id');
    }
}
