<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;


use App\Http\Controllers\Controller;
use App\Models\OAuthIdentity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{






    private array $providers = ['google', 'linkedin-openid', 'github'];  // ← Use 'linkedin-openid'

    public function redirect(Request $request, string $provider)
    {
        abort_unless(in_array($provider, $this->providers, true), 404);
    
        $driver = $this->driver($provider, $request);
        
        // DEBUG
        Log::info('OAuth Redirect', [
            'provider' => $provider,
            'redirect_url' => $this->callbackUrl($provider),
        ]);
        
        return $driver->redirect();
    }
 

    public function callback(Request $request, string $provider)
    {
        abort_unless(in_array($provider, $this->providers, true), 404);

        if ($request->filled('error')) {
            $error = $request->query('error');
            $desc = $request->query('error_description', 'Authorization failed');

            Log::error("OAuth Error - {$provider}", [
                'error' => $error,
                'description' => $desc,
                'query' => $request->query()
            ]);

            return redirect()->route('auth.login')
                ->withErrors(['oauth' => ucfirst($provider) . ': ' . urldecode($desc)]);
        }

        try {
            // Fetch the user from the provider
            $social = $this->driver($provider, $request)->user();

            $providerUid = (string) ($social->getId() ?? '');
            if ($providerUid === '') {
                throw new \Exception(ucfirst($provider) . ' did not return a user ID.');
            }

            $email = $this->normalizedEmail($provider, $social);
            $name = $this->displayName($social);
            $nickname = $social->getNickname();
            $avatar = $social->getAvatar();
            $now = now();

            $user = DB::transaction(function () use (
                $provider,
                $providerUid,
                $email,
                $name,
                $nickname,
                $avatar,
                $social,
                $now
            ) {
                // 1) Already linked?
                $identity = OAuthIdentity::where([
                    'provider' => $provider,
                    'provider_user_id' => $providerUid,
                ])->first();

                if ($identity) {
                    $user = $identity->user;
                    $this->refreshUserProfile($user, $name, $email, $avatar);

                    $identity->update([
                        'provider_username' => $nickname ?? $identity->provider_username,
                        'avatar_url' => $avatar ?? $identity->avatar_url,
                        'access_token' => $social->token ?? null,
                        'refresh_token' => $social->refreshToken ?? null,
                        'token_expires_at' => isset($social->expiresIn) ? $now->copy()->addSeconds((int) $social->expiresIn) : null,
                        'provider_raw' => $social->user ?? [],
                    ]);

                    $user->update([
                        'last_login_at' => $now,
                        'login_count' => ($user->login_count ?? 0) + 1,
                    ]);

                    return $user;
                }

                // 2) Match by email (if the provider gave us one)
                $user = $email ? User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first() : null;

                // 3) Create user if needed
                if (!$user) {
                    $user = User::create([
                        'tenant_id' => null,
                        'name' => $name ?: ($nickname ?: ucfirst($provider) . ' User'),
                        'email' => $email ?: "{$provider}_{$providerUid}@users.noreply.local",
                        'avatar_url' => $avatar,
                        'email_verified_at' => $email ? $now : null,
                        'password' => null,
                        'username' => $this->uniqueUsername($nickname ?: ($name ?: 'user')),
                        'locale' => 'en',
                        'timezone' => 'UTC',
                        'is_active' => 'active',
                        'is_profile_complete' => 'start',
                        'account_status' => 'pending_onboarding',
                        'last_login_at' => $now,
                        'login_count' => 1,
                        'meta' => ['created_via' => $provider],
                    ]);
                } else {
                    $this->refreshUserProfile($user, $name, $email, $avatar);
                    $user->update([
                        'last_login_at' => $now,
                        'login_count' => ($user->login_count ?? 0) + 1,
                    ]);
                }

                // 4) Link identity
                OAuthIdentity::updateOrCreate(
                    ['provider' => $provider, 'provider_user_id' => $providerUid],
                    [
                        'user_id' => $user->id,
                        'provider_username' => $nickname,
                        'avatar_url' => $avatar,
                        'access_token' => $social->token ?? null,
                        'refresh_token' => $social->refreshToken ?? null,
                        'token_expires_at' => isset($social->expiresIn) ? $now->copy()->addSeconds((int) $social->expiresIn) : null,
                        'provider_raw' => $social->user ?? [],
                    ]
                );

                return $user;
            });

            // Log in & route users who aren't fully onboarded
            Auth::login($user, true);
            $request->session()->regenerate();

            if (
                in_array($user->account_status, ['pending_onboarding', 'onboarding_incomplete'], true) ||
                in_array($user->is_profile_complete, ['start', 'personal'], true)
            ) {
                return redirect()->route('auth.account-type')
                    ->with('status', 'Welcome! Please choose how you want to continue.');
            }

            if ($user->account_status === 'professional') {
                return redirect()->route('tenant.onboarding.welcome');
            }
            if ($user->account_status === 'client') {
                return redirect()->route('client.onboarding.info');
            }

            return redirect()->route('tenant.profile', ['username' => $user->username]);
        } catch (\Exception $e) {
            Log::error("OAuth Callback Error - {$provider}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'Authentication with ' . ucfirst($provider) . ' failed. Please try again.']);
        }
    }

    /* ---------------------- helpers ---------------------- */

    private function driver(string $provider, Request $request)
    {
        $driver = Socialite::driver($provider);

        // Ensure redirect URL matches the current host
        $driver->redirectUrl($this->callbackUrl($provider));

        if ($provider === 'linkedin-openid') {
            return $driver;
        }

        // Other providers can be stateless if configured
        if (filter_var(env('OAUTH_STATELESS', false), FILTER_VALIDATE_BOOLEAN)) {
            $driver->stateless();
        }

        return $driver;
    }

    private function callbackUrl(string $provider): string
    {
        $configured = config("services.$provider.redirect");
        if ($configured) {
            return $configured;
        }
        return rtrim(config('app.url'), '/') . "/auth/{$provider}/callback";
    }

    private function normalizedEmail(string $provider, SocialiteUser $s): ?string
    {
        $email = $s->getEmail();
        if (is_string($email) && $email !== '') {
            return strtolower(trim($email));
        }
        return null;
    }

    private function displayName(SocialiteUser $s): ?string
    {
        $name = $s->getName();
        if ($name) return $name;

        $raw = $s->user ?? [];

        // Try new OpenID Connect format
        if (isset($raw['name'])) {
            return $raw['name'];
        }

        // Try older format
        $first = $raw['given_name'] ?? $raw['localizedFirstName'] ?? null;
        $last = $raw['family_name'] ?? $raw['localizedLastName'] ?? null;

        return trim(($first ?: '') . ' ' . ($last ?: '')) ?: null;
    }

    private function refreshUserProfile(User $user, ?string $name, ?string $email, ?string $avatar): void
    {
        $changes = [];
        if (!$user->name && $name) $changes['name'] = $name;
        if (!$user->avatar_url && $avatar) $changes['avatar_url'] = $avatar;

        if (!$user->email_verified_at && $email && str_contains($email, '@')) {
            if (empty($user->email) || str_contains($user->email, '@users.noreply.local')) {
                $changes['email'] = strtolower($email);
            }
            $changes['email_verified_at'] = Carbon::now();
        }

        if ($changes) {
            $user->update($changes);
        }
    }

    private function uniqueUsername(string $seed): string
    {
        $base = Str::of($seed)->lower()->ascii()->slug('_');
        $base = (string) Str::limit($base ?: 'user', 40, '');
        $username = $base;
        $n = 0;

        while (User::where('username', $username)->exists()) {
            $n++;
            $username = Str::limit($base, 40, '') . "_{$n}";
            if ($n > 5000) {
                $username = 'user_' . Str::random(8);
                break;
            }
        }
        return $username;
    }
}
