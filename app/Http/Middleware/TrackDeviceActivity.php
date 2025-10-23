<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\DeviceTrackingService;

class TrackDeviceActivity
{
    public function __construct(
        protected DeviceTrackingService $deviceTracking
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track authenticated users
        if (Auth::check()) {
            $this->deviceTracking->updateActivity(Auth::user(), $request);
        }

        return $response;
    }
}