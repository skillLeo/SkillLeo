<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keep generated URLs consistent (useful for local dev / proxies)
        if ($root = config('app.url')) {
            URL::forceRootUrl($root);
        }

        // 🔥 Add fingerprint() method to Request
        Request::macro('fingerprint', function () {
            $components = [
                $this->userAgent(),
                $this->header('Accept-Language'),
                $this->header('Accept-Encoding'),
            ];

            return hash('sha256', implode('|', array_filter($components)));
        });

        /**
         * @preciseAgo($timestamp)
         * Professional time display:
         * - Less than 1 min (0-59 seconds)
         * - 1 min, 2 mins, ... 59 mins
         * - 1 hour, 2 hours, ... 23 hours
         * - 1 day, 2 days, ... 6 days
         * - 1 week, 2 weeks, 3 weeks
         * - 1 month, 2 months, ... 11 months
         * - 1 year, 2 years, etc.
         */
        Blade::directive('preciseAgo', function ($expression) {
            // Support: @preciseAgo($ts)  OR  @preciseAgo($ts, $tz)
            $parts  = explode(',', $expression, 2);
            $tsExp  = trim($parts[0] ?? 'null');
            $tzExp  = trim($parts[1] ?? 'null'); // string like 'Asia/Karachi' from $user->timezone
        
            $template = <<<'PHP'
        <?php
            $ts = %s;
            $tz = %s;
        
            if (!$ts) {
                echo 'Never updated';
            } else {
                if (!($ts instanceof \Carbon\CarbonInterface)) {
                    $ts = \Carbon\Carbon::parse($ts);
                }
        
                // Resolve timezone (user -> app -> UTC)
                $resolvedTz = $tz ?: config('app.user_timezone') ?: config('app.timezone', 'UTC');
        
                try {
                    $ts  = $ts->clone()->setTimezone($resolvedTz);
                } catch (\Throwable $e) {
                    $resolvedTz = config('app.timezone', 'UTC');
                    $ts = $ts->clone()->setTimezone($resolvedTz);
                }
        
                $now = \Carbon\Carbon::now($resolvedTz);
        
                // If the stored timestamp is in the future due to skew, treat as "just now"
                $diff = $ts->greaterThan($now) ? 0 : $ts->diffInSeconds($now);
        
                if ($diff < 60) {
                    echo $diff . ' ' . \Illuminate\Support\Str::plural('second', $diff) . ' ago';
                } elseif ($diff < 3600) {
                    $m = intdiv($diff, 60);
                    echo $m . ' ' . \Illuminate\Support\Str::plural('minute', $m) . ' ago';
                } elseif ($diff < 86400) {
                    $h = intdiv($diff, 3600);
                    echo $h . ' ' . \Illuminate\Support\Str::plural('hour', $h) . ' ago';
                } elseif ($diff < 604800) {
                    $d = intdiv($diff, 86400);
                    echo $d . ' ' . \Illuminate\Support\Str::plural('day', $d) . ' ago';
                } elseif ($diff < 2592000) { // ~30 days
                    $w = intdiv($diff, 604800);
                    echo $w . ' ' . \Illuminate\Support\Str::plural('week', $w) . ' ago';
                } elseif ($diff < 31536000) { // ~12 months
                    $mo = intdiv($diff, 2592000);
                    echo $mo . ' ' . \Illuminate\Support\Str::plural('month', $mo) . ' ago';
                } else {
                    $y = intdiv($diff, 31536000);
                    echo $y . ' ' . \Illuminate\Support\Str::plural('year', $y) . ' ago';
                }
            }
        ?>
        PHP;
        
            return sprintf($template, $tsExp, $tzExp);
        });

    }}