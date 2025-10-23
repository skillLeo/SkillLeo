<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\DeviceTrackingService;

class EnsureDeviceIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $u = Auth::user();

            if ($u->is_active === 'hibernated') {
                // shouldn’t be inside the app while hibernated
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('auth.login')
                    ->withErrors(['email' => 'Your account is hibernated. Sign in to reactivate.']);
            }

            if ($u->is_active === 'pending_delete') {
                return redirect()->route('account.deletion.notice');
            }
        }
        return $next($request);
    }
}
