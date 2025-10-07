<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenant
{
    public function __construct(protected AuthRedirectService $redirects) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow only professionals (tenant lane). Others are redirected to their proper lane/start/profile.
        if (! $user || $user->account_status !== 'professional') {
            if ($user) {
                return redirect()->to($this->redirects->url($user))
                    ->with('error', 'You must be a professional to access this area.');
            }
            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
