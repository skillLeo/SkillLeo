<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (! Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Please login to access this page.');
        }

        if ($role && method_exists(Auth::user(), 'hasRole') && ! Auth::user()->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}
