<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class DeviceTrackingService
{
    /**
     * Register or update user device on authentication
     */
    public function recordDevice(User $user, Request $request): UserDevice
    {
        $deviceId = $this->generateDeviceFingerprint($request);
        $deviceInfo = $this->extractDeviceInfo($request);
        $location = $this->getLocationFromIP($request->ip());

        return UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_id' => $deviceId,
            ],
            array_merge($deviceInfo, $location, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_seen_at' => now(),
                'last_activity_at' => now(),
            ])
        );
    }

    /**
     * Update device activity (called on every request via middleware)
     */
    public function updateActivity(User $user, Request $request): void
    {
        $deviceId = $this->generateDeviceFingerprint($request);
        
        // Throttle updates to once per minute per device
        $cacheKey = "device_activity:{$user->id}:{$deviceId}";
        
        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, now()->addMinute());

        UserDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->update([
                'last_activity_at' => now(),
                'ip_address' => $request->ip(),
            ]);
    }

    /**
     * Generate unique device fingerprint
     */
    protected function generateDeviceFingerprint(Request $request): string
    {
        $components = [
            $request->userAgent(),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
        ];

        return hash('sha256', implode('|', $components));
    }

    /**
     * Extract device information from request
     */
    protected function extractDeviceInfo(Request $request): array
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());

        $deviceType = 'desktop';
        if ($agent->isMobile()) {
            $deviceType = 'mobile';
        } elseif ($agent->isTablet()) {
            $deviceType = 'tablet';
        }

        $platform = $agent->platform();
        $browser = $agent->browser();
        $device = $agent->device();

        // Generate friendly device name
        $deviceName = $this->generateDeviceName($agent, $deviceType);

        return [
            'device_name' => $deviceName,
            'device_type' => $deviceType,
            'platform' => $platform,
            'browser' => $browser,
            'browser_version' => $agent->version($browser),
        ];
    }

    /**
     * Generate human-readable device name
     */
    protected function generateDeviceName(Agent $agent, string $deviceType): string
    {
        $parts = [];

        // Add device/model if available
        if ($device = $agent->device()) {
            $parts[] = $device;
        }

        // Add platform
        if ($platform = $agent->platform()) {
            $parts[] = $platform;
        }

        // Add browser
        if ($browser = $agent->browser()) {
            $parts[] = $browser;
        }

        // Fallback to device type
        if (empty($parts)) {
            $parts[] = ucfirst($deviceType);
        }

        return implode(' - ', array_slice($parts, 0, 3));
    }

    /**
     * Get approximate location from IP address
     */
    protected function getLocationFromIP(?string $ip): array
    {
        if (!$ip || $ip === '127.0.0.1') {
            return [
                'location_country' => null,
                'location_city' => null,
            ];
        }

        // Use IP geolocation service (integrate with your preferred provider)
        // Example: ipapi.co, ip-api.com, MaxMind GeoIP2, etc.
        
        try {
            $cacheKey = "ip_location:{$ip}";
            
            return Cache::remember($cacheKey, now()->addDays(7), function () use ($ip) {
                // Example with ip-api.com (free tier)
                $response = @file_get_contents("http://ip-api.com/json/{$ip}");
                
                if ($response) {
                    $data = json_decode($response, true);
                    
                    if ($data && $data['status'] === 'success') {
                        return [
                            'location_country' => $data['countryCode'] ?? null,
                            'location_city' => $data['city'] ?? null,
                        ];
                    }
                }
                
                return ['location_country' => null, 'location_city' => null];
            });
        } catch (\Exception $e) {
            return ['location_country' => null, 'location_city' => null];
        }
    }

    /**
     * Check if device is new for the user
     */
    public function isNewDevice(User $user, Request $request): bool
    {
        $deviceId = $this->generateDeviceFingerprint($request);
        
        return !UserDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->exists();
    }

    /**
     * Get user's active devices
     */
    public function getActiveDevices(User $user, int $days = 90)
    {
        return UserDevice::where('user_id', $user->id)
            ->where('last_seen_at', '>=', now()->subDays($days))
            ->orderByDesc('last_activity_at')
            ->get();
    }

    /**
     * Revoke/remove a device
     */
    public function revokeDevice(User $user, int $deviceId): bool
    {
        return UserDevice::where('user_id', $user->id)
            ->where('id', $deviceId)
            ->delete();
    }

    /**
     * Clean up old inactive devices
     */
    public function cleanupInactiveDevices(int $daysInactive = 180): int
    {
        return UserDevice::where('last_seen_at', '<', now()->subDays($daysInactive))
            ->delete();
    }
}