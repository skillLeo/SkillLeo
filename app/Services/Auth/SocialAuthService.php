<?php

namespace App\Services\Auth;

use App\Models\OAuthIdentity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialAuthService
{
    public function findOrCreate(string $provider, SocialiteUser $pUser): User
    {
        $providerUid = (string) $pUser->getId();
        $email       = strtolower($pUser->getEmail() ?? '');

        // 1) Already linked?
        $identity = OAuthIdentity::with('user')
            ->where('provider', $provider)
            ->where('provider_uid', $providerUid)
            ->first();

        if ($identity) {
            // update tokens
            $identity->forceFill([
                'access_token'  => $pUser->token ?? null,
                'refresh_token' => $pUser->refreshToken ?? null,
                'expires_at'    => isset($pUser->expiresIn) ? now()->addSeconds($pUser->expiresIn) : null,
                'email'         => $email ?: $identity->email,
                'profile'       => [
                    'name'   => $pUser->getName(),
                    'avatar' => $pUser->getAvatar(),
                ],
            ])->save();

            return $identity->user;
        }

        return DB::transaction(function () use ($provider, $providerUid, $pUser, $email) {
            // 2) User by email?
            $user = $email ? User::where('email', $email)->first() : null;

            if (!$user) {
                $user = User::create([
                    'name'     => $pUser->getName() ?: ($pUser->user['localizedFirstName'] ?? 'User'),
                    'email'    => $email,
                    'password' => null, // social login
                    'avatar_url' => $pUser->getAvatar(),
                ]);
            }

            OAuthIdentity::create([
                'user_id'       => $user->id,
                'provider'      => $provider,
                'provider_uid'  => $providerUid,
                'email'         => $email,
                'access_token'  => $pUser->token ?? null,
                'refresh_token' => $pUser->refreshToken ?? null,
                'expires_at'    => isset($pUser->expiresIn) ? now()->addSeconds($pUser->expiresIn) : null,
                'profile'       => [
                    'name'   => $pUser->getName(),
                    'avatar' => $pUser->getAvatar(),
                    'raw'    => $pUser->user ?? [],
                ],
            ]);

            return $user;
        });
    }
}
