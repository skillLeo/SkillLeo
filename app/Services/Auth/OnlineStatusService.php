<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\TimezoneService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OnlineStatusService
{
    // User is considered "online" if active in last 5 minutes
    private const ONLINE_THRESHOLD_MINUTES = 5;

    // Cache duration for online status
    private const CACHE_DURATION_MINUTES = 5;

    /**
     * Mark user as online (call this on every request)
     */
    public function markOnline(?User $user): void
    {
        // safety: if somehow no user or no id, do nothing
        if (!$user || !$user->id) {
            return;
        }

        $cacheKey = $this->getOnlineCacheKey($user->id);

        // Store in cache with 5-minute expiry
        Cache::put(
            $cacheKey,
            now()->timestamp,
            now()->addMinutes(self::CACHE_DURATION_MINUTES)
        );

        // Update user's last_seen_at in DB (throttled to once per min)
        $this->updateLastSeen($user);
    }

    /**
     * Mark user as offline (call on logout)
     */
    public function markOffline(?User $user): void
    {
        if (!$user || !$user->id) {
            return;
        }

        $cacheKey = $this->getOnlineCacheKey($user->id);
        Cache::forget($cacheKey);

        // Update last_seen_at immediately
        $user->forceFill(['last_seen_at' => now()])->save();
    }

    /**
     * Check if user is currently online
     */
    public function isOnline(?User $user): bool
    {
        if (!$user || !$user->id) {
            // no user / no id -> definitely not online
            return false;
        }

        $cacheKey = $this->getOnlineCacheKey($user->id);
        return Cache::has($cacheKey);
    }

    /**
     * Get user's online status
     * Returns: 'online', 'active_recently', or 'offline'
     */
    public function getStatus(?User $user): string
    {
        if (!$user || !$user->id) {
            return 'offline';
        }

        if ($this->isOnline($user)) {
            return 'online';
        }

        if (!$user->last_seen_at) {
            return 'offline';
        }

        $diffInMinutes = now()->diffInMinutes($user->last_seen_at);

        if ($diffInMinutes <= 30) {
            return 'active_recently';
        }

        return 'offline';
    }

    /**
     * Get last seen timestamp for user
     * Returns Carbon instance or null
     */
    public function getLastSeen(?User $user): ?Carbon
    {
        if (!$user || !$user->id) {
            return null;
        }

        // If online, return current time
        if ($this->isOnline($user)) {
            return now();
        }

        // Return last_seen_at from database
        return $user->last_seen_at;
    }

    /**
     * ✅ Get formatted last seen text in viewer's timezone
     */
    public function getLastSeenText(?User $user, ?string $viewerTimezone = null): string
    {
        if (!$user) {
            return '';
        }

        return TimezoneService::getOnlineStatusText(
            $user->last_seen_at,
            $viewerTimezone
        );
    }

    /**
     * Get online users count (requires Redis)
     */
    public function getOnlineUsersCount(): int
    {
        // If you're not using Redis as the cache driver, skip.
        if (config('cache.default') !== 'redis') {
            return 0;
        }

        try {
            $pattern = $this->getOnlineCacheKey('*');
            $keys    = Cache::getRedis()->keys($pattern) ?? [];
            return count($keys);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get list of online user IDs (requires Redis)
     */
    public function getOnlineUserIds(): array
    {
        if (config('cache.default') !== 'redis') {
            return [];
        }

        try {
            $pattern = $this->getOnlineCacheKey('*');
            $keys    = Cache::getRedis()->keys($pattern) ?? [];

            return array_map(function ($key) {
                return (int) str_replace('user_online:', '', $key);
            }, $keys);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if multiple users are online
     * Returns array of user_id => bool
     */
    public function areUsersOnline(array $userIds): array
    {
        $statuses = [];

        foreach ($userIds as $userId) {
            // skip null / invalids defensively
            if (!$userId) {
                $statuses[$userId] = false;
                continue;
            }

            $cacheKey = $this->getOnlineCacheKey($userId);
            $statuses[$userId] = Cache::has($cacheKey);
        }

        return $statuses;
    }

    /**
     * Update last_seen_at in database (throttled)
     */
    protected function updateLastSeen(User $user): void
    {
        // still assume valid user here because markOnline() already guards
        $throttleKey = "last_seen_update:{$user->id}";

        // Only update database once per minute to reduce load
        if (Cache::has($throttleKey)) {
            return;
        }

        Cache::put($throttleKey, true, now()->addMinute());

        $user->forceFill(['last_seen_at' => now()])->save();
    }

    /**
     * Get cache key for user's online status
     */
    protected function getOnlineCacheKey(int|string $userId): string
    {
        // NOTE: we don't accept null here anymore, caller guarantees valid
        return "user_online:{$userId}";
    }

    /**
     * Cleanup: Remove stale online statuses (optional, runs via scheduled job)
     */
    public function cleanupStaleStatuses(): int
    {
        // Cache handles expiry automatically, but we can force cleanup stats
        $threshold = now()->subMinutes(self::CACHE_DURATION_MINUTES);

        return User::where('last_seen_at', '<', $threshold)
            ->whereNotNull('last_seen_at')
            ->count();
    }
}
