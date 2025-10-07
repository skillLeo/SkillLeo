<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OnboardingPostGate
{
    public function __construct(protected AuthRedirectService $redirects) {}

    /**
     * Guard only POST actions in the onboarding flow.
     * @param string $lane 'tenant' | 'client'
     */
    public function handle(Request $request, Closure $next, string $lane): Response
    {
        // 🚧 Only gate POST. Let GET be handled by OnboardingGate.
        if (! $request->isMethod('post')) {
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

        $tenantSeq = ['welcome','personal','location','skills','education','experience','preferences','review','publish'];
        $clientSeq = ['info','project','budget','preferences','review'];

        $seq = $lane === 'client' ? $clientSeq : $tenantSeq;
        $currentStep = in_array($stage, $seq, true) ? $stage : $seq[0];

        $routeName = (string) $request->route()?->getName();
        $prefix    = $lane === 'client' ? 'client.onboarding.' : 'tenant.onboarding.';

        if (! Str::startsWith($routeName, $prefix)) {
            return $next($request);
        }

        // allow helper posts (e.g. scratch)
        $allowList = [$prefix.'scratch'];
        if (in_array($routeName, $allowList, true)) {
            return $next($request);
        }

        // Accept only CURRENT step's .store (and publish post)
        // e.g. tenant.onboarding.personal.store | client.onboarding.publish
        $requested = Str::after($routeName, $prefix); // e.g. 'personal.store'
        $requested = Str::before($requested, '.store');
        $requested = Str::before($requested, '.publish');

        $isStoreOrPublish = Str::endsWith($routeName, '.store') || Str::endsWith($routeName, '.publish');

        if ($isStoreOrPublish && $requested === $currentStep) {
            return $next($request);
        }

        // otherwise push them back to their current step page
        return redirect()->route($prefix.$currentStep);
    }
}
