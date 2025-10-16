<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TimezoneService
{
    /**
     * Detect timezone from location
     * 
     * @param string $country
     * @param string|null $state
     * @param string|null $city
     * @return string
     */
    public static function detectFromLocation(string $country, ?string $state = null, ?string $city = null): string
    {
        $cacheKey = 'timezone:' . md5(Str::lower($country . $state . $city));
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($country, $state, $city) {
            try {
                $timezoneMap = self::getTimezoneMap();
                $countryLower = Str::lower($country);
                
                if (!isset($timezoneMap[$countryLower])) {
                    return 'UTC';
                }
                
                $countryTimezone = $timezoneMap[$countryLower];
                
                // If single timezone for country
                if (is_string($countryTimezone)) {
                    return $countryTimezone;
                }
                
                // If multiple timezones, try to match by state/city
                if (is_array($countryTimezone) && ($state || $city)) {
                    $stateLower = Str::lower($state ?? '');
                    $cityLower = Str::lower($city ?? '');
                    
                    foreach ($countryTimezone as $pattern => $tz) {
                        $patternLower = Str::lower($pattern);
                        
                        if (
                            Str::contains($stateLower, $patternLower) ||
                            Str::contains($cityLower, $patternLower) ||
                            Str::contains($patternLower, $stateLower) ||
                            Str::contains($patternLower, $cityLower)
                        ) {
                            return $tz;
                        }
                    }
                    
                    // Return first timezone as fallback
                    return reset($countryTimezone);
                }
                
                return 'UTC';
                
            } catch (\Exception $e) {
                Log::warning('Timezone detection failed', [
                    'error' => $e->getMessage(),
                    'location' => "{$city}, {$state}, {$country}"
                ]);
                
                return 'UTC';
            }
        });
    }

    /**
     * Get viewer's timezone (authenticated user or default)
     * 
     * @return string
     */
    public static function getViewerTimezone(): string
    {
        try {
            // Try to get authenticated user's timezone
            $user = auth()->user();
            if ($user && $user->timezone && self::isValid($user->timezone)) {
                return $user->timezone;
            }
            
            // Fallback to config default or UTC
            return config('app.timezone', 'UTC');
        } catch (\Exception $e) {
            return 'UTC';
        }
    }

    /**
     * Get online status text based on last seen time
     * 
     * @param \Carbon\Carbon|null $lastSeenAt
     * @param string $timezone
     * @return string
     */
    public static function getOnlineStatusText($lastSeenAt, string $timezone = 'UTC'): string
    {
        if (!$lastSeenAt) {
            return 'Never';
        }

        try {
            $now = now()->timezone($timezone);
            $lastSeen = $lastSeenAt->timezone($timezone);
            
            $diffInMinutes = $now->diffInMinutes($lastSeen);
            
            if ($diffInMinutes < 5) {
                return 'Online now';
            } elseif ($diffInMinutes < 60) {
                return $diffInMinutes . ' minutes ago';
            } elseif ($diffInMinutes < 1440) {
                $hours = floor($diffInMinutes / 60);
                return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
            } elseif ($diffInMinutes < 10080) {
                $days = floor($diffInMinutes / 1440);
                return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
            } else {
                return $lastSeen->format('M d, Y');
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Format datetime for display in viewer's timezone
     * 
     * @param \Carbon\Carbon|null $datetime
     * @param string|null $timezone
     * @param string $format
     * @return string|null
     */
    public static function formatForDisplay($datetime, ?string $timezone = null, string $format = 'M d, Y \a\t g:i A'): ?string
    {
        if (!$datetime) {
            return null;
        }

        try {
            $tz = $timezone ?? self::getViewerTimezone();
            return $datetime->timezone($tz)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get human-readable time (e.g., "2 hours ago")
     * 
     * @param \Carbon\Carbon|null $datetime
     * @return string|null
     */
    public static function humanTime($datetime): ?string
    {
        if (!$datetime) {
            return null;
        }

        try {
            return $datetime->diffForHumans();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validate timezone string
     * 
     * @param string $timezone
     * @return bool
     */
    public static function isValid(string $timezone): bool
    {
        try {
            new \DateTimeZone($timezone);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all available timezones
     * 
     * @return array
     */
    public static function getAllTimezones(): array
    {
        return \DateTimeZone::listIdentifiers();
    }

    /**
     * Get timezone offset in hours
     * 
     * @param string $timezone
     * @return float
     */
    public static function getOffset(string $timezone): float
    {
        try {
            $tz = new \DateTimeZone($timezone);
            $offset = $tz->getOffset(new \DateTime());
            return $offset / 3600; // Convert seconds to hours
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Format timezone for display
     * 
     * @param string $timezone
     * @return string
     */
    public static function format(string $timezone): string
    {
        try {
            $tz = new \DateTimeZone($timezone);
            $offset = $tz->getOffset(new \DateTime());
            $hours = floor($offset / 3600);
            $minutes = abs($offset % 3600 / 60);
            
            $sign = $offset >= 0 ? '+' : '-';
            $formattedOffset = sprintf('%s%02d:%02d', $sign, abs($hours), $minutes);
            
            return "{$timezone} (UTC{$formattedOffset})";
        } catch (\Exception $e) {
            return $timezone;
        }
    }

    /**
     * Get comprehensive timezone mapping by country
     * 
     * @return array
     */
    private static function getTimezoneMap(): array
    {
        return [
            // South Asia
            'pakistan' => 'Asia/Karachi',
            'india' => 'Asia/Kolkata',
            'bangladesh' => 'Asia/Dhaka',
            'sri lanka' => 'Asia/Colombo',
            'nepal' => 'Asia/Kathmandu',
            'bhutan' => 'Asia/Thimphu',
            'afghanistan' => 'Asia/Kabul',
            
            // United States (by region keywords)
            'united states' => [
                'new york' => 'America/New_York',
                'boston' => 'America/New_York',
                'philadelphia' => 'America/New_York',
                'miami' => 'America/New_York',
                'atlanta' => 'America/New_York',
                'chicago' => 'America/Chicago',
                'houston' => 'America/Chicago',
                'dallas' => 'America/Chicago',
                'denver' => 'America/Denver',
                'phoenix' => 'America/Phoenix',
                'los angeles' => 'America/Los_Angeles',
                'san francisco' => 'America/Los_Angeles',
                'seattle' => 'America/Los_Angeles',
                'anchorage' => 'America/Anchorage',
                'honolulu' => 'Pacific/Honolulu',
            ],
            
            // United Kingdom & Ireland
            'united kingdom' => 'Europe/London',
            'england' => 'Europe/London',
            'scotland' => 'Europe/London',
            'wales' => 'Europe/London',
            'northern ireland' => 'Europe/London',
            'ireland' => 'Europe/Dublin',
            
            // Canada (by province)
            'canada' => [
                'newfoundland' => 'America/St_Johns',
                'nova scotia' => 'America/Halifax',
                'new brunswick' => 'America/Halifax',
                'ontario' => 'America/Toronto',
                'quebec' => 'America/Toronto',
                'manitoba' => 'America/Winnipeg',
                'saskatchewan' => 'America/Regina',
                'alberta' => 'America/Edmonton',
                'british columbia' => 'America/Vancouver',
                'yukon' => 'America/Whitehorse',
            ],
            
            // Australia (by state)
            'australia' => [
                'western' => 'Australia/Perth',
                'perth' => 'Australia/Perth',
                'south' => 'Australia/Adelaide',
                'adelaide' => 'Australia/Adelaide',
                'new south wales' => 'Australia/Sydney',
                'sydney' => 'Australia/Sydney',
                'victoria' => 'Australia/Melbourne',
                'melbourne' => 'Australia/Melbourne',
                'queensland' => 'Australia/Brisbane',
                'brisbane' => 'Australia/Brisbane',
                'tasmania' => 'Australia/Hobart',
            ],
            
            // Western Europe
            'germany' => 'Europe/Berlin',
            'france' => 'Europe/Paris',
            'spain' => 'Europe/Madrid',
            'portugal' => 'Europe/Lisbon',
            'italy' => 'Europe/Rome',
            'netherlands' => 'Europe/Amsterdam',
            'belgium' => 'Europe/Brussels',
            'switzerland' => 'Europe/Zurich',
            'austria' => 'Europe/Vienna',
            
            // Northern Europe
            'sweden' => 'Europe/Stockholm',
            'norway' => 'Europe/Oslo',
            'denmark' => 'Europe/Copenhagen',
            'finland' => 'Europe/Helsinki',
            'iceland' => 'Atlantic/Reykjavik',
            
            // Eastern Europe
            'poland' => 'Europe/Warsaw',
            'czech republic' => 'Europe/Prague',
            'hungary' => 'Europe/Budapest',
            'romania' => 'Europe/Bucharest',
            'bulgaria' => 'Europe/Sofia',
            'greece' => 'Europe/Athens',
            'ukraine' => 'Europe/Kiev',
            'russia' => 'Europe/Moscow',
            
            // Middle East
            'united arab emirates' => 'Asia/Dubai',
            'saudi arabia' => 'Asia/Riyadh',
            'qatar' => 'Asia/Qatar',
            'kuwait' => 'Asia/Kuwait',
            'bahrain' => 'Asia/Bahrain',
            'oman' => 'Asia/Muscat',
            'turkey' => 'Europe/Istanbul',
            'israel' => 'Asia/Jerusalem',
            'jordan' => 'Asia/Amman',
            'lebanon' => 'Asia/Beirut',
            'iran' => 'Asia/Tehran',
            'iraq' => 'Asia/Baghdad',
            
            // East Asia
            'china' => 'Asia/Shanghai',
            'japan' => 'Asia/Tokyo',
            'south korea' => 'Asia/Seoul',
            'north korea' => 'Asia/Pyongyang',
            'taiwan' => 'Asia/Taipei',
            'hong kong' => 'Asia/Hong_Kong',
            'mongolia' => 'Asia/Ulaanbaatar',
            
            // Southeast Asia
            'singapore' => 'Asia/Singapore',
            'malaysia' => 'Asia/Kuala_Lumpur',
            'thailand' => 'Asia/Bangkok',
            'vietnam' => 'Asia/Ho_Chi_Minh',
            'indonesia' => 'Asia/Jakarta',
            'philippines' => 'Asia/Manila',
            'myanmar' => 'Asia/Yangon',
            'cambodia' => 'Asia/Phnom_Penh',
            'laos' => 'Asia/Vientiane',
            'brunei' => 'Asia/Brunei',
            
            // South America
            'brazil' => 'America/Sao_Paulo',
            'argentina' => 'America/Argentina/Buenos_Aires',
            'chile' => 'America/Santiago',
            'colombia' => 'America/Bogota',
            'peru' => 'America/Lima',
            'venezuela' => 'America/Caracas',
            'ecuador' => 'America/Guayaquil',
            'bolivia' => 'America/La_Paz',
            'uruguay' => 'America/Montevideo',
            'paraguay' => 'America/Asuncion',
            
            // Central America & Caribbean
            'mexico' => 'America/Mexico_City',
            'guatemala' => 'America/Guatemala',
            'costa rica' => 'America/Costa_Rica',
            'panama' => 'America/Panama',
            'cuba' => 'America/Havana',
            'jamaica' => 'America/Jamaica',
            'dominican republic' => 'America/Santo_Domingo',
            
            // Africa
            'south africa' => 'Africa/Johannesburg',
            'nigeria' => 'Africa/Lagos',
            'kenya' => 'Africa/Nairobi',
            'egypt' => 'Africa/Cairo',
            'morocco' => 'Africa/Casablanca',
            'algeria' => 'Africa/Algiers',
            'tunisia' => 'Africa/Tunis',
            'ethiopia' => 'Africa/Addis_Ababa',
            'ghana' => 'Africa/Accra',
            'tanzania' => 'Africa/Dar_es_Salaam',
            
            // Oceania
            'new zealand' => 'Pacific/Auckland',
            'fiji' => 'Pacific/Fiji',
            'papua new guinea' => 'Pacific/Port_Moresby',
        ];
    }
}