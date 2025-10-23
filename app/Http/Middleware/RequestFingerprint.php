<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestFingerprint
{
    /**
     * Handle an incoming request and add a fingerprint method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Add fingerprint method to request
        $request->macro('fingerprint', function () use ($request) {
            return $this->generateFingerprint($request);
        });

        return $next($request);
    }

    /**
     * Generate a unique device fingerprint from request data
     */
    protected function generateFingerprint(Request $request): string
    {
        $components = [
            $request->userAgent() ?? '',
            $request->header('Accept-Language') ?? '',
            $request->header('Accept-Encoding') ?? '',
            $request->header('Accept') ?? '',
        ];

        return hash('sha256', implode('|', array_filter($components)));
    }
}