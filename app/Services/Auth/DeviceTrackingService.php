<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserDevice;
use App\Support\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceTrackingService
{
    /**
     * Record or update a device for the given user
     *
     * @param User $user
     * @param Request $request
     * @return UserDevice
     */
    public function recordDevice(User $user, Request $request): UserDevice
    {
        try {
            // Get device fingerprint
            $deviceId = Device::id($request);
            
            // Parse user agent details
            $deviceInfo = UserDevice::parseUserAgent($request->userAgent() ?? '');
            
            // Get location data (optional - you may have a service for this)
            $location = $this->getLocationData($request->ip());
            
            // Find or create device record
            $device = UserDevice::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'device_id' => $deviceId,
                ],
                [
                    'device_name' => $deviceInfo['device_name'],
                    'device_type' => $deviceInfo['device_type'],
                    'platform' => $deviceInfo['platform'],
                    'browser' => $deviceInfo['browser'],
                    'browser_version' => $deviceInfo['browser_version'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_seen_at' => now(),
                    'last_activity_at' => now(),
                    'location_country' => $location['country'] ?? null,
                    'location_city' => $location['city'] ?? null,
                    // Don't overwrite is_trusted or revoked_at on update
                ]
            );

            Log::info('Device recorded/updated', [
                'user_id' => $user->id,
                'device_id' => $deviceId,
                'device_type' => $deviceInfo['device_type'],
                'was_recently_created' => $device->wasRecentlyCreated,
            ]);

            return $device;

        } catch (\Exception $e) {
            Log::error('Failed to record device', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Rethrow to let caller handle
            throw $e;
        }
    }

    /**
     * Update device activity timestamp
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function updateActivity(User $user, Request $request): void
    {
        $deviceId = Device::id($request);
        
        UserDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->whereNull('revoked_at')
            ->update([
                'last_activity_at' => now(),
                'ip_address' => $request->ip(),
            ]);
    }

    /**
     * Get location data from IP (basic implementation)
     * You can enhance this with a proper GeoIP service
     *
     * @param string $ip
     * @return array
     */
    protected function getLocationData(string $ip): array
    {
        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return [
                'country' => 'XX',
                'city' => 'Local',
            ];
        }

        try {
            // Option 1: Use ip-api.com (free, no key needed, 45 req/min)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city");
            
            if ($response) {
                $data = json_decode($response, true);
                
                if ($data && $data['status'] === 'success') {
                    return [
                        'country' => $data['countryCode'] ?? null,
                        'city' => $data['city'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to get location data', [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        return [
            'country' => null,
            'city' => null,
        ];
    }

    /**
     * Check if device is trusted
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    public function isTrusted(User $user, Request $request): bool
    {
        $deviceId = Device::id($request);
        
        return UserDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->where('is_trusted', true)
            ->whereNull('revoked_at')
            ->exists();
    }

    /**
     * Mark current device as trusted
     *
     * @param User $user
     * @param Request $request
     * @return UserDevice|null
     */
    public function markCurrentAsTrusted(User $user, Request $request): ?UserDevice
    {
        $deviceId = Device::id($request);
        
        $device = UserDevice::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->whereNull('revoked_at')
            ->first();

        if ($device) {
            $device->markAsTrusted();
        }

        return $device;
    }
}