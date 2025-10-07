<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClient
{
    public function __construct(protected AuthRedirectService $redirects) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow only clients. Others are redirected to their proper lane/start/profile.
        if (! $user || $user->account_status !== 'client') {
            if ($user) {
                return redirect()->to($this->redirects->url($user))
                    ->with('error', 'You must be a client to access this area.');
            }
            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
