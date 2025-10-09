<?php

namespace App\Http\Middleware;

use App\Services\Auth\DeviceTrackingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackDeviceActivity
{
    public function __construct(
        protected DeviceTrackingService $deviceTracking
    ) {}

    /**
     * Handle an incoming request and track device activity
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $this->deviceTracking->updateActivity(Auth::user(), $request);
        }

        return $next($request);
    }
}