<?php

// app/Http/Middleware/TrackUserDevice.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDevice;

class TrackUserDevice
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $deviceId = $request->fingerprint();
            $ua = (string) $request->userAgent();

            $parsed = UserDevice::parseUserAgent($ua);

            $device = $user->devices()->firstOrNew(['device_id' => $deviceId]);

            $device->fill([
                'device_name'      => $parsed['device_name'] ?? 'Unknown Device',
                'device_type'      => $parsed['device_type'] ?? 'unknown',
                'platform'         => $parsed['platform'] ?? null,
                'browser'          => $parsed['browser'] ?? null,
                'browser_version'  => $parsed['browser_version'] ?? null,
                'ip_address'       => $request->ip(),
                'user_agent'       => $ua,
            ]);

            // Optional: if you have a geoip() helper installed it will fill country/city
            try {
                if (function_exists('geoip')) {
                    $geo = geoip($request->ip());
                    $device->location_country = $geo->country ?? null;
                    $device->location_city    = $geo->city ?? null;
                }
            } catch (\Throwable $e) {
                // silently ignore if service not configured
            }

            $device->last_seen_at     = now();
            $device->last_activity_at = now();
            $device->save();
        }

        return $next($request);
    }
}
