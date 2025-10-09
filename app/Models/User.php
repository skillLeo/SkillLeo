<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Services\Auth\OnlineStatusService;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'last_name',
        'email',
        'password',
        'tagline',
        'bio',
        'avatar_url',
        'username',
        'locale',
        'timezone',
        'is_active',
        'account_status',
        'is_profile_complete',
        'country',
        'state',
        'city',
        'meta',
        'last_login_at',
        'last_seen_at',
        'login_count',
        'email_verified_at',
        // 🔥 NEW
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'   => 'datetime',
        'is_profile_complete' => 'string',
        'meta'                => 'array',
        'last_login_at'       => 'datetime',
        'last_seen_at'        => 'datetime', // 🔥 NEW
        'login_count'         => 'integer',
    ];

    protected $appends = [
        'is_online',
        'last_seen_text',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    // ============================================
    // Content Relations
    // ============================================

    public function educations(): HasMany
    {
        return $this->hasMany(\App\Models\Education::class)->orderBy('position');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(\App\Models\Experience::class)->orderBy('position');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(\App\Models\Portfolio::class)->orderBy('position');
    }

    public function preference(): HasOne
    {
        return $this->hasOne(\App\Models\Preference::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Skill::class, 'user_skills')
            ->withPivot(['level', 'position'])
            ->withTimestamps()
            ->orderBy('user_skills.position');
    }

    // ============================================
    // Device & Security Relations
    // ============================================

    public function devices(): HasMany
    {
        return $this->hasMany(\App\Models\UserDevice::class);
    }

    public function activeDevices(): HasMany
    {
        return $this->devices()
            ->where('last_seen_at', '>=', now()->subDays(90))
            ->orderByDesc('last_activity_at');
    }

    public function currentDevice()
    {
        if (!request()->fingerprint()) {
            return null;
        }

        return $this->devices()
            ->where('device_id', request()->fingerprint())
            ->first();
    }

    public function oauthIdentities(): HasMany
    {
        return $this->hasMany(\App\Models\OAuthIdentity::class);
    }

    // ============================================
    // Online Status Methods (🔥 NEW)
    // ============================================

    /**
     * Check if user is currently online
     */
    public function getIsOnlineAttribute(): bool
    {
        return app(OnlineStatusService::class)->isOnline($this);
    }

    /**
     * Get formatted last seen text
     */
    public function getLastSeenTextAttribute(): string
    {
        return app(OnlineStatusService::class)->getLastSeenText($this);
    }

    /**
     * Get online status (online/offline)
     */
    public function getOnlineStatusAttribute(): string
    {
        return app(OnlineStatusService::class)->getStatus($this);
    }

    /**
     * Mark user as online (useful for manual marking)
     */
    public function markOnline(): void
    {
        app(OnlineStatusService::class)->markOnline($this);
    }

    /**
     * Mark user as offline (useful for manual marking)
     */
    public function markOffline(): void
    {
        app(OnlineStatusService::class)->markOffline($this);
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function hasMultipleDevices(): bool
    {
        return $this->activeDevices()->count() > 1;
    }

    public function getLastSeenAttribute(): ?string
    {
        $device = $this->devices()
            ->orderByDesc('last_activity_at')
            ->first();

        if (!$device || !$device->last_activity_at) {
            return null;
        }

        return $device->last_activity_at->diffForHumans();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->last_name}");
    }

    public function isActive(): bool
    {
        return $this->is_active === 'active';
    }

    public function hasCompletedProfile(): bool
    {
        return in_array($this->is_profile_complete, ['complete', 'completed']);
    }

    public function needsOnboarding(): bool
    {
        return $this->account_status === 'pending_onboarding';
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function getLocationAttribute(): ?string
    {
        $parts = array_filter([$this->city, $this->state, $this->country]);
        return !empty($parts) ? implode(', ', $parts) : null;
    }

    public function recordLogin(): void
    {
        $this->forceFill([
            'last_login_at' => now(),
            'login_count'   => ($this->login_count ?? 0) + 1,
        ])->save();
    }

    public function hasOAuthProvider(string $provider): bool
    {
        return $this->oauthIdentities()
            ->where('provider', $provider)
            ->exists();
    }

    public function getAvatarAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        $name = urlencode($this->name ?: 'User');
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=random";
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', 'active');
    }

    public function scopeOnboarded($query)
    {
        return $query->where('account_status', '!=', 'pending_onboarding');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('username', 'like', "%{$term}%");
        });
    }

    /**
     * 🔥 Scope: Get only online users
     */
    public function scopeOnline($query)
    {
        $threshold = now()->subMinutes(5);
        return $query->where('last_seen_at', '>=', $threshold);
    }

    /**
     * 🔥 Scope: Get recently active users (last 24 hours)
     */
    public function scopeRecentlyActive($query)
    {
        return $query->where('last_seen_at', '>=', now()->subDay())
                     ->orderByDesc('last_seen_at');
    }

    public function recentDevices()
    {
        return $this->devices()
            ->where('last_seen_at', '>=', now()->subDays(30))
            ->orderByDesc('last_seen_at')
            ->limit(5)
            ->get();
    }

    public function activate(): void
    {
        $this->update(['is_active' => 'active']);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => 'inactive']);
    }

    public function completeProfile(): void
    {
        $this->update([
            'is_profile_complete' => 'complete',
            'account_status' => $this->account_status === 'pending_onboarding' 
                ? 'active' 
                : $this->account_status,
        ]);
    }
}