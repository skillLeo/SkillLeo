<?php
// app/Http/Controllers/Auth/OAuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OAuthIdentity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class OAuthController extends Controller
{
    private array $providers = ['google', 'linkedin', 'github'];

    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, $this->providers, true), 404);

        $driver = Socialite::driver($provider);

        if ($provider === 'linkedin') {
            $driver->scopes(['r_liteprofile', 'r_emailaddress']);
        }

        if (filter_var(env('OAUTH_STATELESS', false), FILTER_VALIDATE_BOOLEAN)) {
            $driver->stateless();
        }

        return $driver->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        abort_unless(in_array($provider, $this->providers, true), 404);

        $driver = Socialite::driver($provider);

        if (filter_var(env('OAUTH_STATELESS', false), FILTER_VALIDATE_BOOLEAN)) {
            $driver->stateless();
        }

        $social = $driver->user();

        $providerUserId   = (string) ($social->getId() ?? '');
        $providerEmail    = $social->getEmail();
        $providerName     = $social->getName()
            ?: trim(($social->user['localizedFirstName'] ?? '') . ' ' . ($social->user['localizedLastName'] ?? ''));
        $providerNickname = $social->getNickname();
        $avatar           = $social->getAvatar();

        if (!$providerUserId) {
            abort(400, 'Provider did not return a user ID.');
        }

        $now = Carbon::now();

        $user = DB::transaction(function () use ($provider, $providerUserId, $providerEmail, $providerName, $providerNickname, $avatar, $social, $now) {
            $identity = OAuthIdentity::where([
                'provider'         => $provider,
                'provider_user_id' => $providerUserId,
            ])->first();

            if ($identity) {
                $user = $identity->user;
                $this->refreshUserProfile($user, $providerName, $providerEmail, $avatar);

                $identity->update([
                    'avatar_url'        => $avatar ?? $identity->avatar_url,
                    'provider_username' => $providerNickname ?? $identity->provider_username,
                    'access_token'      => $social->token ?? null,
                    'refresh_token'     => $social->refreshToken ?? null,
                    'token_expires_at'  => isset($social->expiresIn) ? $now->copy()->addSeconds((int) $social->expiresIn) : null,
                    'provider_raw'      => $social->user ?? [],
                ]);

                $user->update([
                    'last_login_at' => $now,
                    'login_count'   => ($user->login_count ?? 0) + 1,
                ]);

                return $user;
            }

            $user = $providerEmail ? User::where('email', $providerEmail)->first() : null;

            if (!$user) {
                $user = User::create([
                    'tenant_id'           => null,
                    'name'                => $providerName ?: ($providerNickname ?: 'User'),
                    'email'               => $providerEmail ?: "{$providerUserId}@{$provider}.oauth.local",
                    'avatar_url'          => $avatar,
                    'email_verified_at'   => $providerEmail ? $now : null,
                    'password'            => null,
                    'username'            => $this->uniqueUsername($providerNickname ?: ($providerName ?: 'user')),
                    'locale'              => 'en',
                    'timezone'            => 'UTC',
                    'is_active'           => 'active',
                    'is_profile_complete' => 'start',
                    'account_status'      => 'pending_onboarding',
                    'last_login_at'       => $now,
                    'login_count'         => 1,
                    'meta'                => ['created_via' => $provider],
                ]);
            } else {
                $this->refreshUserProfile($user, $providerName, $providerEmail, $avatar);
                $user->update([
                    'last_login_at' => $now,
                    'login_count'   => ($user->login_count ?? 0) + 1,
                ]);
            }

            OAuthIdentity::updateOrCreate(
                ['provider' => $provider, 'provider_user_id' => $providerUserId],
                [
                    'user_id'           => $user->id,
                    'provider_username' => $providerNickname,
                    'avatar_url'        => $avatar,
                    'access_token'      => $social->token ?? null,
                    'refresh_token'     => $social->refreshToken ?? null,
                    'token_expires_at'  => isset($social->expiresIn) ? $now->copy()->addSeconds((int) $social->expiresIn) : null,
                    'provider_raw'      => $social->user ?? [],
                ]
            );

            return $user;
        });

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
    }

    private function refreshUserProfile(User $user, ?string $name, ?string $email, ?string $avatar): void
    {
        $changes = [];

        if (!$user->name && $name) {
            $changes['name'] = $name;
        }
        if (!$user->avatar_url && $avatar) {
            $changes['avatar_url'] = $avatar;
        }
        if (!$user->email_verified_at && $email && str_contains($email, '@')) {
            $changes['email'] = $user->email ?: strtolower($email);
            $changes['email_verified_at'] = Carbon::now();
        }

        if ($changes) {
            $user->update($changes);
        }
    }

    private function uniqueUsername(string $seed): string
    {
        $base = str($seed)->lower()->ascii()->slug('_');
        $base = (string) str($base ?: 'user')->limit(40, '');
        $username = $base;
        $n = 0;

        while (User::where('username', $username)->exists()) {
            $n++;
            $username = str($base)->limit(40, '') . "_{$n}";
            if ($n > 5000) {
                $username = 'user_' . Str::random(8);
                break;
            }
        }
        return $username;
    }
}
