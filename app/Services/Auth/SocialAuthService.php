<?php

namespace App\Services\Auth;

use App\Models\OAuthIdentity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialAuthService
{
    private function safeToken(?string $token): ?string
    {
        if (!is_string($token) || $token === '') return null;
        return strlen($token) <= 255 ? $token : null;
    }

    public function findOrCreate(string $provider, SocialiteUser $pUser): User
    {
        $providerUserId = (string) $pUser->getId();
        $email          = strtolower(trim($pUser->getEmail() ?? ''));
        $name           = $pUser->getName() ?: ($pUser->user['localizedFirstName'] ?? 'User');
        $avatar         = $pUser->getAvatar();
        $accessToken    = $this->safeToken($pUser->token ?? null);
        $refreshToken   = $this->safeToken($pUser->refreshToken ?? null);
        $expiresAt      = isset($pUser->expiresIn) ? now()->addSeconds((int) $pUser->expiresIn) : null;

        return DB::transaction(function () use (
            $provider, $providerUserId, $email, $name, $avatar, $accessToken, $refreshToken, $expiresAt, $pUser
        ) {
            // 1) Already linked?
            $identity = OAuthIdentity::with('user')
                ->where('provider', $provider)
                ->where('provider_user_id', $providerUserId)   // <-- correct column
                ->first();

            if ($identity) {
                $identity->forceFill([
                    'access_token'     => $accessToken,
                    'refresh_token'    => $refreshToken,
                    'token_expires_at' => $expiresAt,           // <-- correct column
                    'provider_raw'     => $pUser->user ?? [],   // <-- correct column
                    'avatar_url'       => $avatar ?: $identity->avatar_url,
                ])->save();

                $identity->user->update([
                    'name'       => $identity->user->name ?: $name,
                    'avatar_url' => $identity->user->avatar_url ?: $avatar,
                ]);

                return $identity->user;
            }

            // 2) Link to existing email if present
            $user = $email ? User::whereRaw('LOWER(email) = ?', [$email])->first() : null;

            // 3) Create user if needed (fallback email when provider hides email)
            if (! $user) {
                if (! $email) {
                    $email = "{$provider}_{$providerUserId}@users.noreply.local";
                }

                $user = User::create([
                    'name'                => $name,
                    'email'               => $email,
                    'password'            => Hash::make(Str::random(40)),
                    'avatar_url'          => $avatar,
                    'is_active'           => 'active',
                    'is_profile_complete' => 'start',
                    'account_status'      => 'pending_onboarding',
                ]);

                if ($pUser->getEmail()) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }
            }

            // 4) Create/update identity
            OAuthIdentity::updateOrCreate(
                [
                    'provider'         => $provider,
                    'provider_user_id' => $providerUserId,   // <-- correct key
                ],
                [
                    'user_id'          => $user->id,
                    'avatar_url'       => $avatar,
                    'access_token'     => $accessToken,
                    'refresh_token'    => $refreshToken,
                    'token_expires_at' => $expiresAt,        // <-- correct column
                    'provider_raw'     => $pUser->user ?? [],// <-- correct column
                ]
            );

            return $user;
        });
    }
}
