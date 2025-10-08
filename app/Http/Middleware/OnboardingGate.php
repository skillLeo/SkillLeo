<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OnboardingGate
{
    public function __construct(protected AuthRedirectService $redirects) {}

    /**
     * Guard only GET pages in the onboarding flow.
     * @param string $lane 'tenant' | 'client'
     */
    public function handle(Request $request, Closure $next, string $lane): Response
    {
        // 🚧 Only gate GET/HEAD. Let POST be handled by OnboardingPostGate.
        if (! $request->isMethod('get') && ! $request->isMethod('head')) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user) return redirect()->route('auth.login');

        $stage = strtolower(trim((string) $user->is_profile_complete));

        if ($stage === 'completed') {
            return redirect()->to($this->redirects->url($user));
        }

        if ($stage === '' || $stage === 'start') {
            return redirect()->route('auth.account-type');
        }

        $tenantSeq = ['welcome','personal','location','skills','education','experience','portfolio','preferences','review','publish'];
        $clientSeq = ['info','project','budget','preferences','review'];

        $seq = $lane === 'client' ? $clientSeq : $tenantSeq;
        $currentStep = in_array($stage, $seq, true) ? $stage : $seq[0];

        $routeName = (string) $request->route()?->getName();
        $prefix    = $lane === 'client' ? 'client.onboarding.' : 'tenant.onboarding.';

        if (! Str::startsWith($routeName, $prefix)) {
            return $next($request);
        }

        $requested = Str::after($routeName, $prefix);  // e.g. "skills"
        if ($requested !== $currentStep) {
            return redirect()->route($prefix.$currentStep);
        }

        return $next($request);
    }
}
