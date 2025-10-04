<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

final class OAuthController extends Controller
{
    private function scopes(string $provider): array
    {
        return match ($provider) {
            'google'   => ['openid', 'email', 'profile'],
            'linkedin' => ['openid', 'profile', 'email'], // OIDC scopes
            'github'   => [],
            default    => [],
        };
    }

    private function driver(string $provider)
    {
        // Map 'linkedin' -> 'linkedin-openid' driver + its own config key
        if ($provider === 'linkedin') {
            $driverName = 'linkedin-openid';
            $redirect   = config('services.linkedin-openid.redirect');
        } else {
            $driverName = $provider;
            $redirect   = config("services.{$provider}.redirect");
        }

        $driver = Socialite::driver($driverName)->redirectUrl($redirect);

        // Be explicit: ensure redirect_uri is present on the outbound request
        if ($provider === 'linkedin') {
            $driver->with(['redirect_uri' => $redirect]);
        }

        $scopes = $this->scopes($provider);
        if (!empty($scopes)) {
            $driver->scopes($scopes);
        }

        if (env('OAUTH_STATELESS', false)) {
            $driver->stateless();
        }

        return $driver;
    }

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google', 'github', 'linkedin']), 404);

        try {
            return $this->driver($provider)->redirect();
        } catch (\Throwable $e) {
            Log::error("OAuth redirect error for {$provider}: " . $e->getMessage());
            return redirect('/')->withErrors(['oauth' => 'Unable to connect to ' . ucfirst($provider)]);
        }
    }

    public function callback(string $provider, SocialAuthService $service)
    {
        abort_unless(in_array($provider, ['google', 'github', 'linkedin']), 404);

        if (request()->has('error')) {
            $msg = request('error_description') ?? request('error') ?? 'Login cancelled';
            Log::warning("OAuth error for {$provider}: {$msg}");
            return redirect('/')->withErrors(['oauth' => $msg]);
        }

        try {
            $pUser = $this->driver($provider)->user();

            if (!$pUser->getEmail()) {
                return redirect('/')->withErrors(['oauth' => 'Email not provided by ' . ucfirst($provider)]);
            }

            $user = $service->findOrCreate($provider, $pUser);

            if (!$user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            Auth::login($user, true);

            return redirect($user->is_profile_complete
                ? '/' . ($user->username ?? 'dashboard')
                : '/account-type');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error("Invalid state for {$provider}: " . $e->getMessage());
            return redirect('/')->withErrors(['oauth' => 'Session expired. Please try again.']);
        } catch (\Throwable $e) {
            Log::error("OAuth callback error for {$provider}: " . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
            return redirect('/')->withErrors(['oauth' => 'Login failed. Please try again.']);
        }
    }
}
