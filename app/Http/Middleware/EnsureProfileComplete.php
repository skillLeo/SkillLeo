<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('auth.login');
        }

        if (! $user->is_profile_complete) {
            if ($user->intent === 'professional') {
                return redirect()->route('tenant.onboarding.welcome');
            } elseif ($user->intent === 'client') {
                return redirect()->route('client.onboarding.info');
            }

            return redirect()->route('auth.account-type');
        }

        return $next($request);
    }
}
