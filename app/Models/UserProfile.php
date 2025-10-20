<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'country',
        'state',
        'city',
        'headline',
        'about',
        'banner',
        'banner_preference',
        'social_links',
        'filter_preferences',
        'meta',
    ];

    protected $casts = [
        'social_links' => 'array',
        'banner_preference' => 'array',
        'meta' => 'array',
    'filter_preferences' => 'array', 
    ];

    /**
     * Get the user that owns the profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full location string
     */
    public function getLocationAttribute(): ?string
    {
        $parts = array_filter([$this->city, $this->state, $this->country]);
        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Get a specific social link
     */
    public function getSocialLink(string $platform): ?string
    {
        return $this->social_links[$platform] ?? null;
    }

    /**
     * Set a social link
     */
    public function setSocialLink(string $platform, ?string $url): void
    {
        $links = $this->social_links ?? [];
        
        if ($url) {
            $links[$platform] = $url;
        } else {
            unset($links[$platform]);
        }
        
        $this->social_links = $links;
    }

    /**
     * Check if profile has location data
     */
    public function hasLocation(): bool
    {
        return !is_null($this->country) || !is_null($this->state) || !is_null($this->city);
    }

    /**
     * Check if profile has any social links
     */
    public function hasSocialLinks(): bool
    {
        return !empty($this->social_links);
    }



    // Add to your Profile model



}