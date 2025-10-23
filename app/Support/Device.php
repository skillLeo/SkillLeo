<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Device
{
    /**
     * Generate a consistent device ID from the request
     * 
     * @param Request $request
     * @return string
     */
    public static function id(Request $request): string
    {
        // Build a fingerprint from:
        // 1. User agent
        // 2. Accept language
        // 3. Accept encoding
        // 4. Platform (from user agent parsing)
        
        $userAgent = $request->userAgent() ?? '';
        $acceptLanguage = $request->header('Accept-Language', '');
        $acceptEncoding = $request->header('Accept-Encoding', '');
        
        // Create a deterministic hash
        $fingerprint = md5(implode('|', [
            $userAgent,
            $acceptLanguage,
            $acceptEncoding,
        ]));
        
        return substr($fingerprint, 0, 64); // Matches your DB column size
    }

    /**
     * Alternative: Get device ID from session (more persistent)
     * Call this if you want session-based device tracking
     */
    public static function sessionId(Request $request): string
    {
        $sessionKey = 'device_id';
        
        if ($request->session()->has($sessionKey)) {
            return $request->session()->get($sessionKey);
        }
        
        $deviceId = self::id($request);
        $request->session()->put($sessionKey, $deviceId);
        
        return $deviceId;
    }

    /**
     * Get device information
     */
    public static function info(Request $request): array
    {
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($request->userAgent());

        return [
            'device_id' => self::id($request),
            'device_name' => $agent->device() ?: ($agent->platform() ?: 'Unknown Device'),
            'device_type' => $agent->isDesktop() ? 'desktop' 
                : ($agent->isTablet() ? 'tablet' 
                : ($agent->isMobile() ? 'mobile' : 'unknown')),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];
    }
}