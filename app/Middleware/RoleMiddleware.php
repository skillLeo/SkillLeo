<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->login('auth.register')
                ->with('error', 'Please login to continue.');
        }

        // Get user's intent/role
        $userRole = $request->user()->intent;

        // Map intent to role names
        $roleMap = [
            'professional' => 'tenant',
            'client' => 'client',
            'super_admin' => 'super_admin',
        ];

        $actualRole = $roleMap[$userRole] ?? $userRole;

        // Check if user has the required role
        if ($actualRole !== $role) {
            abort(403, 'Unauthorized access. You do not have permission to view this page.');
        }

        return $next($request);
    }
}