<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected DeviceTrackingService $deviceTracking
    ) {}

    public function registerEmail(array $data): User
    {
        return User::create([
            'name'                => $data['name'] ?? null,
            'email'               => strtolower($data['email']),
            'password'            => Hash::make($data['password']),
            'is_profile_complete' => 'start',
            'is_active'           => 'active',
            'account_status'      => 'pending_onboarding',
        ]);
    }

    /**
     * Record login with device tracking
     */
    public function recordLogin(User $user, ?string $ip = null, ?string $ua = null): void
    {
        $user->forceFill([
            'last_login_at' => now(),
            'login_count'   => ($user->login_count ?? 0) + 1,
        ])->save();

        // Device tracking is now handled by DeviceTrackingService
        // This method is kept for backward compatibility
    }
}