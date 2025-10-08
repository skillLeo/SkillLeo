<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    protected $fillable = [
        'user_id',
        'company',
        'company_id',
        'title',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'is_current',
        'location_city',
        'location_country',
        'description',
        'position',
        'meta',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(ExperienceSkill::class)->orderBy('position');
    }
}
