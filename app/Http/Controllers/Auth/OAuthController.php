<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    private function scopes(string $provider): array
    {
        return match ($provider) {
            'google'   => ['openid', 'email', 'profile'],
            'linkedin' => ['profile', 'email', 'openid'], // LinkedIn v2 API scopes
            'github'   => [],
            default    => [],
        };
    }

    private function driver(string $provider)
    {
        $redirect = config("services.{$provider}.redirect");

        $driver = Socialite::driver($provider)->redirectUrl($redirect);

        $scopes = $this->scopes($provider);
        if (!empty($scopes)) {
            $driver->scopes($scopes);
        }

        // Use stateless mode to avoid session state issues
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
            return redirect('/login')->withErrors(['oauth' => 'Unable to connect to ' . ucfirst($provider)]);
        }
    }

    public function callback(string $provider, SocialAuthService $service)
    {
        abort_unless(in_array($provider, ['google', 'github', 'linkedin']), 404);

        // Handle OAuth errors from provider
        if (request()->has('error')) {
            $msg = request('error_description') ?? request('error') ?? 'Login cancelled';
            Log::warning("OAuth error for {$provider}: {$msg}");
            return redirect('/login')->withErrors(['oauth' => $msg]);
        }

        try {
            $pUser = $this->driver($provider)->user();

            if (!$pUser->getEmail()) {
                return redirect('/login')->withErrors(['oauth' => 'Email not provided by ' . ucfirst($provider)]);
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
            return redirect('/login')->withErrors(['oauth' => 'Session expired. Please try again.']);
        } catch (\Throwable $e) {
            Log::error("OAuth callback error for {$provider}: " . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->withErrors(['oauth' => 'Login failed. Please try again.']);
        }
    }
}