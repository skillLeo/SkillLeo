<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountTypeSelected
{
    public function __construct(protected AuthRedirectService $redirects) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('auth.account-type') || $request->routeIs('auth.account-type.set')) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user) {
            // If onboarding hasn't started or is pending, send to the right page (account-type/start).
            $isPending = $user->account_status === 'pending_onboarding';
            $stage     = strtolower(trim((string) $user->is_profile_complete));

            if ($isPending || $stage === 'start' || $stage === '') {
                return redirect()->to($this->redirects->url($user))
                    ->with('status', 'Please complete your account setup.');
            }
        }

        return $next($request);
    }
}
