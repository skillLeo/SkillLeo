<?php

// app/Http/Middleware/EnsureDeviceIsActive.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureDeviceIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $device = Auth::user()->currentDevice();
            if ($device && $device->revoked_at) {
                Auth::logout(); // Force logout for revoked device
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your session on this device was revoked.',
                ]);
            }
        }
        return $next($request);
    }
}
