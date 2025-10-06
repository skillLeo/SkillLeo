<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountTypeSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('auth.account-type') || $request->routeIs('auth.account-type.set')) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user && ($user->account_status === 'pending_onboarding' || $user->is_profile_complete === 'start')) {
            return redirect()->route('auth.account-type')->with('status', 'Please complete your account setup.');
        }

        return $next($request);
    }
}
