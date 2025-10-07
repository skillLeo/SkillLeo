<?php

namespace App\Services\Auth;

use App\Models\User;

class AuthRedirectService
{
    /**
     * Where should the user land after auth (absolute URL).
     */
    public function url(User $user): string
    {
        $stage = $this->normalizeStage($user->is_profile_complete);

        // Completed → public profile (hard redirect, username-safe fallback)
        if ($this->isCompleted($stage)) {
                return route('tenant.profile', ['username' => $user->username]);
            // If somehow username is missing, send to a safe home/dashboard
        }

        // Determine lane (tenant vs client)
        $lane = $this->lane($user);

        // Route maps
        $tenant = [
            'start'       => 'auth.account-type',
            'welcome'     => 'tenant.onboarding.welcome',
            'personal'    => 'tenant.onboarding.personal',
            'location'    => 'tenant.onboarding.location',
            'skills'      => 'tenant.onboarding.skills',
            'experience'  => 'tenant.onboarding.experience',
            'portfolio'   => 'tenant.onboarding.portfolio',
            'education'   => 'tenant.onboarding.education',
            'preferences' => 'tenant.onboarding.preferences',
            'review'      => 'tenant.onboarding.review',
            'publish'     => 'tenant.onboarding.publish', // note: your POST exists; GET guard via routes/mw
        ];

        $client = [
            'start'       => 'auth.account-type',
            'info'        => 'client.onboarding.info',
            'project'     => 'client.onboarding.project',
            'budget'      => 'client.onboarding.budget',
            'preferences' => 'client.onboarding.preferences',
            'review'      => 'client.onboarding.review',
            // "publish" is POST-only in your client routes; we don't map it for GET
        ];

        $map = $lane === 'client' ? $client : $tenant;

        // Fallback: if unknown stage, go to account-type (safe)
        $routeName = $map[$stage] ?? 'auth.account-type';
        return route($routeName);
    }

    /**
     * Preferred response from controllers after successful login/OTP/social/email-verify.
     * If completed, we IGNORE "intended" to avoid being stuck on a stale intended URL.
     */
    public function intendedResponse(User $user)
    {
        $stage = $this->normalizeStage($user->is_profile_complete);

        if ($this->isCompleted($stage)) {
            return redirect()->to($this->url($user)); // force profile
        }

        return redirect()->intended($this->url($user));
    }

    /* ------------------------------- helpers ------------------------------- */

    private function lane(User $user): string
    {
        // account_status 'client' → client lane; otherwise tenant
        return $user->account_status === 'client' ? 'client' : 'tenant';
    }

    private function normalizeStage(?string $value): string
    {
        $stage = strtolower(trim((string) $value));

        // Normalize common synonyms to our canonical values
        $completedSynonyms = ['completed', 'complete', 'done', 'finished', 'published', 'live'];
        if (in_array($stage, $completedSynonyms, true)) {
            return 'completed';
        }

        // if empty/null, treat as start (so user picks account type)
        if ($stage === '' || $stage === null) {
            return 'start';
        }

        return $stage;
    }

    private function isCompleted(string $stage): bool
    {
        return $stage === 'completed';
    }
}
