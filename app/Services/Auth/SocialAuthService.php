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
        return strlen($token) <= 255 ? $token : null; // trim overly long tokens if your columns are 255
    }

    public function findOrCreate(string $provider, SocialiteUser $pUser): User
    {
        $providerUid  = (string) $pUser->getId();
        $email        = strtolower(trim($pUser->getEmail() ?? ''));
        $name         = $pUser->getName() ?: ($pUser->user['localizedFirstName'] ?? 'User');
        $avatar       = $pUser->getAvatar();
        $accessToken  = $this->safeToken($pUser->token ?? null);
        $refreshToken = $this->safeToken($pUser->refreshToken ?? null);
        $expiresAt    = isset($pUser->expiresIn) ? now()->addSeconds((int) $pUser->expiresIn) : null;

        return DB::transaction(function () use ($provider, $providerUid, $email, $name, $avatar, $accessToken, $refreshToken, $expiresAt, $pUser) {

            // already linked?
            $identity = OAuthIdentity::with('user')
                ->where('provider', $provider)
                ->where('provider_uid', $providerUid)
                ->first();

            if ($identity) {
                $identity->forceFill([
                    'email'         => $email ?: $identity->email,
                    'access_token'  => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expires_at'    => $expiresAt,
                    'profile'       => ['name'=>$name,'avatar'=>$avatar,'raw'=>$pUser->user ?? []],
                ])->save();

                $identity->user->update([
                    'name'       => $identity->user->name ?: $name,
                    'avatar_url' => $identity->user->avatar_url ?: $avatar,
                ]);

                return $identity->user;
            }

            // link to existing email if present
            $user = $email ? User::whereRaw('LOWER(email) = ?', [$email])->first() : null;

            if (! $user) {
                // provider may not share email (e.g., GitHub private email)
                if (! $email) {
                    $email = "{$provider}_{$providerUid}@users.noreply.local";
                }

                $user = User::create([
                    'name'                => $name,
                    'email'               => $email,
                    'password'            => Hash::make(Str::random(40)), // never NULL
                    'avatar_url'          => $avatar,
                    'is_active'              => 'active',
                    'is_profile_complete' => 'start',
                    'account_status'      => 'pending_onboarding', 

                ]);

                if ($pUser->getEmail()) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }
            }

            OAuthIdentity::updateOrCreate(
                [
                    'provider'     => $provider,
                    'provider_uid' => $providerUid,
                    'user_id'      => $user->id,
                ],
                [
                    'email'         => $email,
                    'access_token'  => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expires_at'    => $expiresAt,
                    'profile'       => ['name'=>$name,'avatar'=>$avatar,'raw'=>$pUser->user ?? []],
                ]
            );

            return $user;
        });
    }
}
