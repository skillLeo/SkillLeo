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

    // ---------- Accessors ----------
    public function getDeviceDisplayNameAttribute(): string
    {
        $bits = array_filter([
            $this->browser,
            $this->platform,
            $this->device_type !== 'desktop' ? ucfirst($this->device_type) : null,
        ]);

        return implode(' on ', $bits) ?: 'Unknown Device';
    }

    public function getIsCurrentDeviceAttribute(): bool
    {
        return request()->fingerprint() === $this->device_id;
    }

    // ---------- Scopes / helpers ----------
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
        $this->forceFill(['revoked_at' => now(), 'is_trusted' => false])->save();
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
