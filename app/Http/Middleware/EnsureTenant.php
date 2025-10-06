<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->account_status !== 'professional') {
            return redirect()->route('auth.account-type')
                ->with('error', 'You must be a professional to access this area.');
        }

        return $next($request);
    }
}
