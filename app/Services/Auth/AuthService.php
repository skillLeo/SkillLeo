<?php

namespace App\Services\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function registerEmail(array $data): User
    {
        // Optional: Decide/assign tenant (for Professional create tenant; for Client join later)
        $tenant = null;
        if (($data['intent'] ?? null) === 'professional') {
            $tenant = Tenant::create([
                'name' => $data['tenant_name'] ?? ($data['name'] ?? 'New Tenant'),
                'slug' => Str::slug(($data['tenant_name'] ?? $data['name'] ?? Str::before($data['email'],'@'))).'-'.Str::random(4),
                'plan' => 'starter',
                'seats_limit' => 1,
            ]);
        }

        $user = User::create([
            'tenant_id' => $tenant?->id,
            'name' => $data['name'] ?? null,
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'intent' => $data['intent'] ?? null,
            'is_profile_complete' => false,
            'status' => 'active',
        ]);

        return $user;
    }

    public function recordLogin(User $user, string $ip = null, string $ua = null): void
    {
        $user->forceFill([
            'last_login_at' => now(),
            'login_count' => ($user->login_count ?? 0) + 1,
        ])->save();

        if ($ip || $ua) {
            $user->devices()->updateOrCreate(
                ['name' => substr(($ua ?? 'Unknown'), 0, 120)],
                ['ip' => $ip, 'user_agent' => $ua, 'last_seen_at' => now()]
            );
        }
    }
}
