<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Agent\Agent;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'device_type',
        'platform',
        'browser',
        'browser_version',
        'ip_address',
        'user_agent',
        'is_trusted',
        'last_seen_at',
        'last_activity_at',
        'location_country',
        'location_city',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_trusted' => 'boolean',
        'created_at' => 'datetime',
    ];

    protected $appends = ['device_display_name', 'is_current_device'];

    /**
     * Get the user that owns the device
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a friendly display name for the device
     */
    public function getDeviceDisplayNameAttribute(): string
    {
        $parts = array_filter([
            $this->browser,
            $this->platform,
            $this->device_type !== 'desktop' ? ucfirst($this->device_type) : null,
        ]);

        return implode(' on ', $parts) ?: 'Unknown Device';
    }

    /**
     * Check if this is the current device
     */
    public function getIsCurrentDeviceAttribute(): bool
    {
        return request()->fingerprint() === $this->device_id;
    }

    /**
     * Update last activity timestamp
     */
    public function touchActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Mark device as trusted
     */
    public function markAsTrusted(): void
    {
        $this->update(['is_trusted' => true]);
    }

    /**
     * Scope: Only active devices (seen in last 90 days)
     */
    public function scopeActive($query)
    {
        return $query->where('last_seen_at', '>=', now()->subDays(90));
    }

    /**
     * Scope: Current session devices
     */
    public function scopeCurrent($query)
    {
        return $query->where('device_id', request()->fingerprint());
    }

    /**
     * Parse user agent and extract device information
     */
    public static function parseUserAgent(string $userAgent): array
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        return [
            'device_type' => $agent->isDesktop() ? 'desktop' : 
                           ($agent->isTablet() ? 'tablet' : 
                           ($agent->isMobile() ? 'mobile' : 'unknown')),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'device_name' => $agent->device() ?: $agent->platform(),
        ];
    }
}