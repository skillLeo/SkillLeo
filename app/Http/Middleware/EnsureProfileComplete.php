<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function __construct(protected AuthRedirectService $redirects) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('auth.login');
        }

        // If not completed, send to the *exact* current step (tenant or client) via the redirect service.
        $stage = strtolower(trim((string) $user->is_profile_complete));
        if ($stage !== 'completed') {
            return redirect()->to($this->redirects->url($user));
        }

        return $next($request);
    }
}
