<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Map the middleware role parameter to the actual intent values
        $roleMap = [
            'tenant' => 'professional',
            'professional' => 'professional',
            'client' => 'client',
            'super_admin' => 'super_admin',
        ];

        // Get the expected intent value
        $expectedIntent = $roleMap[$role] ?? $role;

        // Check if user has the required role
        if ($user->intent !== $expectedIntent) {
            // Log unauthorized attempt
            \Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_intent' => $user->intent,
                'required_role' => $role,
                'route' => $request->path(),
            ]);

            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}