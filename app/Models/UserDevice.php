<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Agent\Agent;
use App\Support\Device;

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
        'revoked_at',
    ];

    protected $casts = [
        'last_seen_at'     => 'datetime',
        'last_activity_at' => 'datetime',
        'revoked_at'       => 'datetime',
        'is_trusted'       => 'boolean',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    protected $appends = ['device_display_name', 'is_current_device'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ────────────────────────────────────────────────────────────────
    public function getDeviceDisplayNameAttribute(): string
    {
        $parts = array_filter([
            $this->browser,
            $this->platform,
            $this->device_type && $this->device_type !== 'desktop'
                ? ucfirst($this->device_type)
                : null,
        ]);

        return $parts ? implode(' on ', $parts) : ($this->device_name ?: 'Unknown Device');
    }

    /**
     * ✅ FIXED: Check if this device record matches the current request's device
     */
    public function getIsCurrentDeviceAttribute(): bool
    {
        // Use the same Device::id() method that DeviceTrackingService uses
        $currentDeviceId = Device::id(request());
        
        // Also ensure it's the same user
        $currentUserId = auth()->id();
        
        return $this->device_id === $currentDeviceId 
            && $this->user_id === $currentUserId
            && is_null($this->revoked_at);
    }

    // ── Scopes / Helpers ────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->whereNull('revoked_at')
            ->where('last_seen_at', '>=', now()->subDays(90));
    }

    public function scopeNotRevoked($query)
    {
        return $query->whereNull('revoked_at');
    }

    public function revoke(): void
    {
        $this->forceFill([
            'revoked_at' => now(),
            'is_trusted' => false,
        ])->save();
    }

    public function markAsTrusted(): void
    {
        $this->forceFill(['is_trusted' => true])->save();
    }

    public static function parseUserAgent(string $userAgent): array
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        return [
            'device_type'     => $agent->isDesktop() ? 'desktop'
                                  : ($agent->isTablet() ? 'tablet'
                                  : ($agent->isMobile() ? 'mobile' : 'unknown')),
            'platform'        => $agent->platform(),
            'browser'         => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'device_name'     => $agent->device() ?: ($agent->platform() ?: 'Device'),
        ];
    }
}