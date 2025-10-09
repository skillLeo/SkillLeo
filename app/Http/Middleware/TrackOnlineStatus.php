<?php

namespace App\Http\Middleware;

use App\Services\Auth\OnlineStatusService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackOnlineStatus
{
    public function __construct(
        protected OnlineStatusService $onlineStatus
    ) {}

    /**
     * Handle an incoming request and mark user as online
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $this->onlineStatus->markOnline(Auth::user());
        }

        return $next($request);
    }
}