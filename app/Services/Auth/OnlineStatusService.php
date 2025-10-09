<?php

namespace App\Services\Auth;

use App\Models\User;
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
    public function markOnline(User $user): void
    {
        $cacheKey = $this->getOnlineCacheKey($user->id);
        
        // Store in cache with 5-minute expiry
        Cache::put($cacheKey, now()->timestamp, now()->addMinutes(self::CACHE_DURATION_MINUTES));
        
        // Update user's last_seen_at in database (throttled to once per minute)
        $this->updateLastSeen($user);
    }

    /**
     * Mark user as offline (call on logout)
     */
    public function markOffline(User $user): void
    {
        $cacheKey = $this->getOnlineCacheKey($user->id);
        Cache::forget($cacheKey);
        
        // Update last_seen_at immediately
        $user->update(['last_seen_at' => now()]);
    }

    /**
     * Check if user is currently online
     */
    public function isOnline(User $user): bool
    {
        $cacheKey = $this->getOnlineCacheKey($user->id);
        return Cache::has($cacheKey);
    }

    /**
     * Get user's online status
     * Returns: 'online', 'offline', or null if never seen
     */
    public function getStatus(User $user): string
    {
        if ($this->isOnline($user)) {
            return 'online';
        }

        return 'offline';
    }

    /**
     * Get last seen timestamp for user
     * Returns Carbon instance or null
     */
    public function getLastSeen(User $user): ?Carbon
    {
        // If online, return current time
        if ($this->isOnline($user)) {
            return now();
        }

        // Return last_seen_at from database
        return $user->last_seen_at;
    }

    /**
     * Get formatted last seen text (like WhatsApp)
     * Examples: "Online", "Last seen 5 minutes ago", "Last seen today at 3:45 PM"
     */
    public function getLastSeenText(User $user, bool $showExactTime = true): string
    {
        if ($this->isOnline($user)) {
            return 'Online';
        }

        $lastSeen = $user->last_seen_at;

        if (!$lastSeen) {
            return 'Offline';
        }

        $now = now();
        $diffInMinutes = $lastSeen->diffInMinutes($now);
        $diffInHours = $lastSeen->diffInHours($now);
        $diffInDays = $lastSeen->diffInDays($now);

        // Less than 1 minute ago
        if ($diffInMinutes < 1) {
            return 'Last seen just now';
        }

        // Less than 1 hour ago
        if ($diffInMinutes < 60) {
            return $diffInMinutes === 1 
                ? 'Last seen 1 minute ago' 
                : "Last seen {$diffInMinutes} minutes ago";
        }

        // Today
        if ($lastSeen->isToday()) {
            if ($showExactTime) {
                return 'Last seen today at ' . $lastSeen->format('g:i A');
            }
            return $diffInHours === 1 
                ? 'Last seen 1 hour ago' 
                : "Last seen {$diffInHours} hours ago";
        }

        // Yesterday
        if ($lastSeen->isYesterday()) {
            if ($showExactTime) {
                return 'Last seen yesterday at ' . $lastSeen->format('g:i A');
            }
            return 'Last seen yesterday';
        }

        // Within last week
        if ($diffInDays < 7) {
            if ($showExactTime) {
                return 'Last seen ' . $lastSeen->format('l \a\t g:i A'); // e.g., "Monday at 3:45 PM"
            }
            return $diffInDays === 1 
                ? 'Last seen 1 day ago' 
                : "Last seen {$diffInDays} days ago";
        }

        // More than a week ago
        if ($showExactTime) {
            return 'Last seen ' . $lastSeen->format('M j \a\t g:i A'); // e.g., "Jan 15 at 3:45 PM"
        }

        return 'Last seen ' . $lastSeen->diffForHumans();
    }

    /**
     * Get online users count
     */
    public function getOnlineUsersCount(): int
    {
        $pattern = $this->getOnlineCacheKey('*');
        $keys = Cache::store('redis')->keys($pattern) ?? [];
        return count($keys);
    }

    /**
     * Get list of online user IDs
     */
    public function getOnlineUserIds(): array
    {
        $pattern = $this->getOnlineCacheKey('*');
        $keys = Cache::store('redis')->keys($pattern) ?? [];
        
        return array_map(function ($key) {
            return (int) str_replace('user_online:', '', $key);
        }, $keys);
    }

    /**
     * Check if multiple users are online
     * Returns array of user_id => bool
     */
    public function areUsersOnline(array $userIds): array
    {
        $statuses = [];
        
        foreach ($userIds as $userId) {
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
        $throttleKey = "last_seen_update:{$user->id}";
        
        // Only update database once per minute to reduce load
        if (Cache::has($throttleKey)) {
            return;
        }

        Cache::put($throttleKey, true, now()->addMinute());
        
        $user->update(['last_seen_at' => now()]);
    }

    /**
     * Get cache key for user's online status
     */
    protected function getOnlineCacheKey(int|string $userId): string
    {
        return "user_online:{$userId}";
    }

    /**
     * Cleanup: Remove stale online statuses (optional, runs via scheduled job)
     */
    public function cleanupStaleStatuses(): int
    {
        // Cache handles expiry automatically, but we can force cleanup
        $threshold = now()->subMinutes(self::CACHE_DURATION_MINUTES);
        
        return User::where('last_seen_at', '<', $threshold)
            ->whereNotNull('last_seen_at')
            ->update(['last_seen_at' => null]);
    }
}