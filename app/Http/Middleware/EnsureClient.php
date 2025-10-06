<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->account_status !== 'client') {
            return redirect()->route('auth.account-type')
                ->with('error', 'You must be a client to access this area.');
        }

        return $next($request);
    }
}
