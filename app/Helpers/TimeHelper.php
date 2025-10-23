<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Format timestamp for professional "last updated" display
     * 
     * @param Carbon|null $timestamp
     * @return string
     */
    public static function preciseTimeAgo($timestamp): string
    {
        if (!$timestamp) {
            return 'Never updated';
        }

        $now = Carbon::now();
        $diff = $timestamp->diffInSeconds($now);

        // Less than 1 minute (0-59 seconds)
        if ($diff < 60) {
            return 'Less than 1 min';
        }

        // 1-59 minutes
        $minutes = floor($diff / 60);
        if ($minutes < 60) {
            return $minutes == 1 ? '1 min' : "{$minutes} mins";
        }

        // 1-23 hours
        $hours = floor($diff / 3600);
        if ($hours < 24) {
            return $hours == 1 ? '1 hour' : "{$hours} hours";
        }

        // 1-6 days
        $days = floor($diff / 86400);
        if ($days < 7) {
            return $days == 1 ? '1 day' : "{$days} days";
        }

        // 1-3 weeks
        $weeks = floor($days / 7);
        if ($weeks < 4) {
            return $weeks == 1 ? '1 week' : "{$weeks} weeks";
        }

        // 1-11 months
        $months = floor($days / 30);
        if ($months < 12) {
            return $months == 1 ? '1 month' : "{$months} months";
        }

        // 1+ years
        $years = floor($days / 365);
        return $years == 1 ? '1 year' : "{$years} years";
    }
}