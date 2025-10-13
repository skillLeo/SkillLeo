<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TimezoneService
{
    /**
     * Detect timezone from location (country/state/city)
     */
    public static function detectFromLocation(?string $country, ?string $state = null, ?string $city = null): string
    {
        if (!$country) {
            return 'UTC';
        }

        // City-specific timezone mapping (major cities)
        $cityTimezones = [
            // Pakistan
            'Karachi' => 'Asia/Karachi',
            'Lahore' => 'Asia/Karachi',
            'Islamabad' => 'Asia/Karachi',
            'Sargodha' => 'Asia/Karachi',
            'Faisalabad' => 'Asia/Karachi',
            'Rawalpindi' => 'Asia/Karachi',
            'Multan' => 'Asia/Karachi',
            'Peshawar' => 'Asia/Karachi',
            'Quetta' => 'Asia/Karachi',
            
            // USA Major Cities
            'New York' => 'America/New_York',
            'Los Angeles' => 'America/Los_Angeles',
            'Chicago' => 'America/Chicago',
            'Houston' => 'America/Chicago',
            'Phoenix' => 'America/Phoenix',
            'Philadelphia' => 'America/New_York',
            'San Antonio' => 'America/Chicago',
            'San Diego' => 'America/Los_Angeles',
            'Dallas' => 'America/Chicago',
            'San Jose' => 'America/Los_Angeles',
            'Austin' => 'America/Chicago',
            'Jacksonville' => 'America/New_York',
            'San Francisco' => 'America/Los_Angeles',
            'Columbus' => 'America/New_York',
            'Indianapolis' => 'America/Indiana/Indianapolis',
            'Fort Worth' => 'America/Chicago',
            'Charlotte' => 'America/New_York',
            'Seattle' => 'America/Los_Angeles',
            'Denver' => 'America/Denver',
            'Boston' => 'America/New_York',
            'Miami' => 'America/New_York',
            'Atlanta' => 'America/New_York',
            'Detroit' => 'America/Detroit',
            'Las Vegas' => 'America/Los_Angeles',
            'Portland' => 'America/Los_Angeles',
            
            // UK
            'London' => 'Europe/London',
            'Manchester' => 'Europe/London',
            'Birmingham' => 'Europe/London',
            'Liverpool' => 'Europe/London',
            'Leeds' => 'Europe/London',
            'Glasgow' => 'Europe/London',
            'Edinburgh' => 'Europe/London',
            
            // India
            'Mumbai' => 'Asia/Kolkata',
            'Delhi' => 'Asia/Kolkata',
            'Bangalore' => 'Asia/Kolkata',
            'Hyderabad' => 'Asia/Kolkata',
            'Chennai' => 'Asia/Kolkata',
            'Kolkata' => 'Asia/Kolkata',
            'Pune' => 'Asia/Kolkata',
            'Ahmedabad' => 'Asia/Kolkata',
            
            // Canada
            'Toronto' => 'America/Toronto',
            'Vancouver' => 'America/Vancouver',
            'Montreal' => 'America/Montreal',
            'Calgary' => 'America/Edmonton',
            'Ottawa' => 'America/Toronto',
            'Edmonton' => 'America/Edmonton',
            'Winnipeg' => 'America/Winnipeg',
            
            // Australia
            'Sydney' => 'Australia/Sydney',
            'Melbourne' => 'Australia/Melbourne',
            'Brisbane' => 'Australia/Brisbane',
            'Perth' => 'Australia/Perth',
            'Adelaide' => 'Australia/Adelaide',
            
            // UAE
            'Dubai' => 'Asia/Dubai',
            'Abu Dhabi' => 'Asia/Dubai',
            'Sharjah' => 'Asia/Dubai',
            
            // Other Major Cities
            'Singapore' => 'Asia/Singapore',
            'Hong Kong' => 'Asia/Hong_Kong',
            'Tokyo' => 'Asia/Tokyo',
            'Shanghai' => 'Asia/Shanghai',
            'Beijing' => 'Asia/Shanghai',
            'Seoul' => 'Asia/Seoul',
            'Bangkok' => 'Asia/Bangkok',
            'Manila' => 'Asia/Manila',
            'Jakarta' => 'Asia/Jakarta',
            'Kuala Lumpur' => 'Asia/Kuala_Lumpur',
            'Dhaka' => 'Asia/Dhaka',
            'Istanbul' => 'Europe/Istanbul',
            'Moscow' => 'Europe/Moscow',
            'Paris' => 'Europe/Paris',
            'Berlin' => 'Europe/Berlin',
            'Madrid' => 'Europe/Madrid',
            'Rome' => 'Europe/Rome',
            'Amsterdam' => 'Europe/Amsterdam',
            'Brussels' => 'Europe/Brussels',
            'Vienna' => 'Europe/Vienna',
            'Zurich' => 'Europe/Zurich',
            'Stockholm' => 'Europe/Stockholm',
            'Copenhagen' => 'Europe/Copenhagen',
            'Oslo' => 'Europe/Oslo',
            'Dublin' => 'Europe/Dublin',
            'Lisbon' => 'Europe/Lisbon',
            'Athens' => 'Europe/Athens',
            'Cairo' => 'Africa/Cairo',
            'Johannesburg' => 'Africa/Johannesburg',
            'Lagos' => 'Africa/Lagos',
            'Nairobi' => 'Africa/Nairobi',
            'Buenos Aires' => 'America/Argentina/Buenos_Aires',
            'São Paulo' => 'America/Sao_Paulo',
            'Rio de Janeiro' => 'America/Sao_Paulo',
            'Mexico City' => 'America/Mexico_City',
            'Lima' => 'America/Lima',
            'Bogotá' => 'America/Bogota',
            'Santiago' => 'America/Santiago',
        ];

        // Check city first (case-insensitive)
        foreach ($cityTimezones as $cityName => $timezone) {
            if (strcasecmp($city ?? '', $cityName) === 0) {
                return $timezone;
            }
        }

        // Country-level timezone mapping (default for country)
        $countryTimezones = [
            'Pakistan' => 'Asia/Karachi',
            'United States' => 'America/New_York',
            'USA' => 'America/New_York',
            'United Kingdom' => 'Europe/London',
            'UK' => 'Europe/London',
            'India' => 'Asia/Kolkata',
            'Canada' => 'America/Toronto',
            'Australia' => 'Australia/Sydney',
            'United Arab Emirates' => 'Asia/Dubai',
            'UAE' => 'Asia/Dubai',
            'Germany' => 'Europe/Berlin',
            'France' => 'Europe/Paris',
            'China' => 'Asia/Shanghai',
            'Japan' => 'Asia/Tokyo',
            'Brazil' => 'America/Sao_Paulo',
            'Mexico' => 'America/Mexico_City',
            'Saudi Arabia' => 'Asia/Riyadh',
            'South Africa' => 'Africa/Johannesburg',
            'Singapore' => 'Asia/Singapore',
            'Malaysia' => 'Asia/Kuala_Lumpur',
            'Indonesia' => 'Asia/Jakarta',
            'Philippines' => 'Asia/Manila',
            'Bangladesh' => 'Asia/Dhaka',
            'Turkey' => 'Europe/Istanbul',
            'Italy' => 'Europe/Rome',
            'Spain' => 'Europe/Madrid',
            'Netherlands' => 'Europe/Amsterdam',
            'Switzerland' => 'Europe/Zurich',
            'Sweden' => 'Europe/Stockholm',
            'Norway' => 'Europe/Oslo',
            'Denmark' => 'Europe/Copenhagen',
            'Poland' => 'Europe/Warsaw',
            'Egypt' => 'Africa/Cairo',
            'Nigeria' => 'Africa/Lagos',
            'Kenya' => 'Africa/Nairobi',
            'Argentina' => 'America/Argentina/Buenos_Aires',
            'Chile' => 'America/Santiago',
            'Colombia' => 'America/Bogota',
            'Peru' => 'America/Lima',
            'Thailand' => 'Asia/Bangkok',
            'Vietnam' => 'Asia/Ho_Chi_Minh',
            'South Korea' => 'Asia/Seoul',
            'Taiwan' => 'Asia/Taipei',
            'Hong Kong' => 'Asia/Hong_Kong',
            'New Zealand' => 'Pacific/Auckland',
            'Ireland' => 'Europe/Dublin',
            'Portugal' => 'Europe/Lisbon',
            'Greece' => 'Europe/Athens',
            'Austria' => 'Europe/Vienna',
            'Belgium' => 'Europe/Brussels',
            'Czech Republic' => 'Europe/Prague',
            'Israel' => 'Asia/Jerusalem',
            'Qatar' => 'Asia/Qatar',
            'Kuwait' => 'Asia/Kuwait',
            'Oman' => 'Asia/Muscat',
            'Bahrain' => 'Asia/Bahrain',
            'Russia' => 'Europe/Moscow',
            'Ukraine' => 'Europe/Kiev',
            'Romania' => 'Europe/Bucharest',
            'Hungary' => 'Europe/Budapest',
            'Finland' => 'Europe/Helsinki',
            'Iran' => 'Asia/Tehran',
            'Iraq' => 'Asia/Baghdad',
            'Jordan' => 'Asia/Amman',
            'Lebanon' => 'Asia/Beirut',
            'Morocco' => 'Africa/Casablanca',
            'Algeria' => 'Africa/Algiers',
            'Tunisia' => 'Africa/Tunis',
            'Ethiopia' => 'Africa/Addis_Ababa',
            'Ghana' => 'Africa/Accra',
            'Tanzania' => 'Africa/Dar_es_Salaam',
            'Uganda' => 'Africa/Kampala',
            'Venezuela' => 'America/Caracas',
            'Ecuador' => 'America/Guayaquil',
            'Bolivia' => 'America/La_Paz',
            'Paraguay' => 'America/Asuncion',
            'Uruguay' => 'America/Montevideo',
            'Costa Rica' => 'America/Costa_Rica',
            'Panama' => 'America/Panama',
            'Guatemala' => 'America/Guatemala',
            'Honduras' => 'America/Tegucigalpa',
            'Nicaragua' => 'America/Managua',
            'El Salvador' => 'America/El_Salvador',
            'Dominican Republic' => 'America/Santo_Domingo',
            'Jamaica' => 'America/Jamaica',
            'Trinidad and Tobago' => 'America/Port_of_Spain',
        ];

        return $countryTimezones[$country] ?? 'UTC';
    }

    /**
     * Convert UTC datetime to user's timezone
     */
    public static function toUserTime($utcDateTime, ?string $userTimezone = null): ?Carbon
    {
        if (!$utcDateTime) {
            return null;
        }

        $timezone = $userTimezone ?? self::getUserTimezone();

        try {
            return Carbon::parse($utcDateTime)->setTimezone($timezone);
        } catch (\Exception $e) {
            return Carbon::parse($utcDateTime)->setTimezone('UTC');
        }
    }

    /**
     * Get human-readable relative time (like LinkedIn)
     */
    public static function humanTime($utcDateTime, ?string $userTimezone = null): ?string
    {
        if (!$utcDateTime) {
            return null;
        }

        $userTime = self::toUserTime($utcDateTime, $userTimezone);
        
        if (!$userTime) {
            return null;
        }

        $now = Carbon::now($userTimezone ?? self::getUserTimezone());
        $diffInMinutes = $now->diffInMinutes($userTime);
        $diffInHours = $now->diffInHours($userTime);
        $diffInDays = $now->diffInDays($userTime);

        // Just now (under 1 minute)
        if ($diffInMinutes < 1) {
            return 'Just now';
        }

        // Minutes (1-59 minutes)
        if ($diffInMinutes < 60) {
            return $diffInMinutes . 'm ago';
        }

        // Hours (1-23 hours)
        if ($diffInHours < 24) {
            return $diffInHours . 'h ago';
        }

        // Days (1-6 days)
        if ($diffInDays < 7) {
            return $diffInDays . 'd ago';
        }

        // Weeks (7-27 days)
        if ($diffInDays < 28) {
            $weeks = floor($diffInDays / 7);
            return $weeks . 'w ago';
        }

        // Format as date for older entries
        if ($diffInDays < 365) {
            return $userTime->format('M d'); // "Jan 15"
        }

        return $userTime->format('M d, Y'); // "Jan 15, 2024"
    }

    /**
     * Format datetime for display (like LinkedIn)
     */
    public static function formatForDisplay($utcDateTime, ?string $userTimezone = null, string $format = 'M d, Y g:i A'): ?string
    {
        $userTime = self::toUserTime($utcDateTime, $userTimezone);
        return $userTime?->format($format);
    }

    /**
     * Get current user's timezone
     */
    public static function getUserTimezone(): string
    {
        if (auth()->check()) {
            return auth()->user()->timezone ?? 'UTC';
        }

        // Try to get from session (for non-authenticated users)
        return session('viewer_timezone', 'UTC');
    }

    /**
     * Get viewer's timezone (for profile viewing)
     */
    public static function getViewerTimezone(): string
    {
        // Priority: Authenticated user > Session > Request header > UTC
        if (auth()->check()) {
            return auth()->user()->timezone ?? 'UTC';
        }

        return session('viewer_timezone', request()->input('timezone', 'UTC'));
    }

    /**
     * Store visitor timezone in session
     */
    public static function storeViewerTimezone(string $timezone): void
    {
        try {
            // Validate timezone
            new \DateTimeZone($timezone);
            session(['viewer_timezone' => $timezone]);
        } catch (\Exception $e) {
            session(['viewer_timezone' => 'UTC']);
        }
    }

    /**
     * Get online status text (like LinkedIn)
     */
    public static function getOnlineStatusText(?Carbon $lastSeenAt, ?string $viewerTimezone = null): string
    {
        if (!$lastSeenAt) {
            return 'Offline';
        }

        $now = Carbon::now('UTC');
        $diffInMinutes = $now->diffInMinutes($lastSeenAt);

        // Online (last 5 minutes)
        if ($diffInMinutes <= 5) {
            return 'Online';
        }

        // Recently active (5-30 minutes)
        if ($diffInMinutes <= 30) {
            return 'Active recently';
        }

        // Show last seen time
        return 'Last seen ' . self::humanTime($lastSeenAt, $viewerTimezone);
    }

    /**
     * Validate timezone string
     */
    public static function isValidTimezone(string $timezone): bool
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
     */
    public static function getAllTimezones(): array
    {
        return \DateTimeZone::listIdentifiers();
    }

    /**
     * Get timezone offset in hours
     */
    public static function getTimezoneOffset(string $timezone): string
    {
        try {
            $tz = new \DateTimeZone($timezone);
            $offset = $tz->getOffset(new \DateTime('now', new \DateTimeZone('UTC')));
            $hours = intdiv($offset, 3600);
            $minutes = abs(($offset % 3600) / 60);
            
            return sprintf('%+03d:%02d', $hours, $minutes);
        } catch (\Exception $e) {
            return '+00:00';
        }
    }
}