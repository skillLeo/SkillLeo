<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Str;
use App\Models\UserSecurity;
use Illuminate\Http\Request;
use App\Models\TrustedDevice;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\PasswordChangeConfirmMail;
use Illuminate\Support\Facades\Validator;
use App\Services\Auth\DeviceTrackingService;

class AccountController extends Controller
{
  

    
    private function getUser($username)
    {
        if (Auth::check() && $username === Auth::user()->username) {
            return Auth::user()->load(['profile', 'devices', 'security']);
        }
        
        return User::with(['profile', 'devices', 'security'])
            ->where('username', $username)
            ->firstOrFail();
    }
 // AccountController@account
public function account($username)
{
    $user = $this->getUser($username);

    if ($user->id !== Auth::id()) {
        abort(403, 'Unauthorized access to account settings');
    }

    $security = UserSecurity::firstOrCreate(
        ['user_id' => $user->id],
        [
            'two_factor_email'   => false,
            'two_factor_phone'   => false,
            'two_factor_enabled' => false,
        ]
    );

    $devices = UserDevice::where('user_id', $user->id)
        ->whereNull('revoked_at')
        ->where('last_seen_at', '>=', now()->subDays(90))
        ->orderByDesc('last_activity_at')
        ->get();

    $currentDeviceId = request()->fingerprint();

    $loginHistory = UserDevice::where('user_id', $user->id)
        ->where('created_at', '>=', now()->subDays(30))
        ->orderByDesc('created_at')
        ->limit(50)
        ->get();

 
 
    

    return view('tenant.settings.account', [
        'user'                => $user,
        'username'            => $username,
        'activeSection'       => 'account',
        'security'            => $security,
        'devices'             => $devices,
        'currentDeviceId'     => $currentDeviceId,
        'loginHistory'        => $loginHistory,
        'twoFactorEnabled'    => $security->two_factor_enabled,
        'trustedDevicesCount' => $devices->where('is_trusted', true)->count(),
    ]);
}






    public function updatePassword(Request $request, $username)
    {
        $user = $this->getUser($username);
        if ($user->id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    
        $hasLocalPassword = !empty($user->password);
    
        // Validate
        $rules = [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // at least one lower, one upper, one digit, one symbol (any non-alphanumeric)
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            ],
            // we will carry forward where to redirect back after confirmation
            'intended' => ['nullable', 'string'],
        ];
        $messages = [
            'new_password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ];
        if ($hasLocalPassword) {
            $rules['current_password'] = ['required', 'string'];
        }
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // CASE A: social user (no local password) -> set immediately
        if (!$hasLocalPassword) {
            try {
                $user->update(['password' => Hash::make($request->new_password)]);
                UserSecurity::updateOrCreate(['user_id' => $user->id], ['last_verified_at' => now()]);
    
                return redirect()
                    ->route('tenant.settings.account', $user->username)
                    ->with('status', 'Password set successfully!');
            } catch (\Exception $e) {
                return back()->withErrors(['new_password' => 'Failed to update password. Please try again.'])
                             ->withInput();
            }
        }
    
        // CASE B: email/password user -> verify current, send confirmation link
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect'])
                         ->withInput();
        }
    
        try {
            $token  = (string) Str::uuid();
            $hashed = Hash::make($request->new_password);
    
            // Store new hashed password + user id + intended redirect for 30 minutes
            Cache::put("pwdchg:{$token}", [
                'user_id'  => $user->id,
                'hash'     => $hashed,
                'intended' => $this->sanitizeIntended($request->input('intended'), $user),
            ], now()->addMinutes(30));
    
            $url = URL::temporarySignedRoute(
                'tenant.settings.password.confirm',
                now()->addMinutes(30),
                ['token' => $token]
            );
    
            Mail::to($user->email)->send(new PasswordChangeConfirmMail($user, $url));
    
            return redirect()
                ->route('tenant.settings.account', $user->username)
                ->with('status', 'We emailed you a confirmation link to finalize the change.');
        } catch (\Exception $e) {
            return back()->withErrors(['new_password' => 'Failed to start password change. Please try again.'])
                         ->withInput();
        }
    }
    
    /**
     * Finalize password change from signed email link.
     * - Applies the new password
     * - Logs the user in
     * - Redirects to the intended page (from cache), safely
     */
    public function confirmPasswordChange(Request $request, string $token)
    {
        $payload = Cache::pull("pwdchg:{$token}");
        if (!$payload || empty($payload['user_id']) || empty($payload['hash'])) {
            return redirect()->route('auth.login')
                ->withErrors(['link' => 'This link is invalid or has expired.']);
        }
    
        $user = User::find($payload['user_id']);
        if (!$user) {
            return redirect()->route('auth.login')
                ->withErrors(['link' => 'User not found.']);
        }
    
        // Apply new password
        $user->forceFill(['password' => $payload['hash']])->save();
        UserSecurity::updateOrCreate(['user_id' => $user->id], ['last_verified_at' => now()]);
    
        // Log them in and regenerate session
        Auth::login($user, true);
        $request->session()->regenerate();
    
        // Safe redirect (same-origin only)
        $redirect = $payload['intended'] ?? route('tenant.settings.account', $user->username);
        if (!$this->isSafeRedirect($redirect, $request)) {
            $redirect = route('tenant.settings.account', $user->username);
        }
    
        return redirect($redirect)->with('status', 'Your password has been changed successfully.');
    }
    
    /**
     * Keep redirects same-origin and fall back to the account page.
     */
    private function sanitizeIntended(?string $intended, User $user): string
    {
        $fallback = route('tenant.settings.account', $user->username);
        if (!$intended) return $fallback;
    
        // If relative path, make it absolute on this host
        if (!Str::startsWith($intended, ['http://', 'https://'])) {
            return url($intended);
        }
        // If absolute, ensure same origin
        return Str::startsWith($intended, url('/')) ? $intended : $fallback;
    }
    
    private function isSafeRedirect(string $url, Request $request): bool
    {
        return Str::startsWith($url, url('/'));
    }
    










    public function updateProfile(Request $request, string $username)
    {
        // Make sure URL username matches the authed user
        $user = Auth::user();
        if (!$user || $user->username !== $username) {
            abort(403);
        }
    
        // Validate only the requested fields
        $validated = $request->validate([
            'username'         => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email'            => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users')->ignore($user->id)],
            'secondary_email'  => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:32'],
            'secondary_phone'  => ['nullable', 'string', 'max:32'],
        ]);
    
        try {
            DB::beginTransaction();
    
            // Handle email changes
            $emailChanged = $user->email !== $validated['email'];
    
            // Update Users table
            $user->fill([
                'username'          => $validated['username'],
                'email'             => $validated['email'],
                'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
            ])->save();
    
            // Update or create profile record
            $profile = $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['phone' => $validated['phone'] ?? null]
            );
    
            // Merge meta safely
            $meta = is_array($profile->meta ?? null) ? $profile->meta : [];
            $meta['secondary_email'] = $validated['secondary_email'] ?? null;
            $meta['secondary_phone'] = $validated['secondary_phone'] ?? null;
    
            $profile->forceFill(['meta' => $meta])->save();
    
            DB::commit();
    
            // Redirect back with flash messages
            $msg = 'Profile updated successfully.';
            $redirect = redirect()->route('tenant.settings.account', $user->username)
                                  ->with('success', $msg);
    
            if ($emailChanged) {
                $redirect->with('warning', 'Your primary email changed. Please verify the new email to keep your account fully active.');
            }
    
            return $redirect;
    
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return back()
                ->withInput()
                ->withErrors(['general' => config('app.debug') ? $e->getMessage() : 'Failed to update profile. Please try again.']);
        }

    }
    public function sendVerification(Request $request, $username)
    {
        $user = $this->getUser($username);

        if ($user->id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified.',
            ], 400);
        }

        try {
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Revoke a specific device
     */
// Single device remove (temporary sign-out for that device only)

public function revokeDevice(Request $request, string $username, $device)
{
    $user = Auth::user();
    if ($user->username !== $username) {
        abort(403, 'Unauthorized access.');
    }

    $userDevice = UserDevice::where('user_id', $user->id)
        ->where('id', $device)
        ->whereNull('revoked_at')
        ->first();

    if (!$userDevice) {
        return redirect()->route('tenant.settings.account', $username)
            ->with('error', 'Device not found or already revoked.');
    }

    if ($userDevice->is_current_device) {
        return redirect()->route('tenant.settings.account', $username)
            ->with('error', 'Cannot revoke current device.');
    }

    $userDevice->update([
        'revoked_at' => now(),
        'is_trusted' => false
    ]);

    Log::info('Device revoked', [
        'user_id' => $user->id,
        'device_id' => $device,
        'ip' => $userDevice->ip_address
    ]);

    return redirect()->route('tenant.settings.account', $username)
        ->with('success', 'Device has been revoked successfully.');
}

public function revokeOtherSessions(Request $request, string $username)
{
    $user = Auth::user();
    if ($user->username !== $username) {
        abort(403, 'Unauthorized access.');
    }

    $count = UserDevice::where('user_id', $user->id)
        ->where('is_current_device', false)
        ->whereNull('revoked_at')
        ->update([
            'revoked_at' => now(),
            'is_trusted' => false
        ]);

    Log::info('All other sessions revoked', [
        'user_id' => $user->id,
        'count' => $count
    ]);

    return redirect()->route('tenant.settings.account', $username)
        ->with('success', "Successfully revoked {$count} session(s).");
}


public function untrustDevice(Request $request, string $username, UserDevice $device)
{
    $user = Auth::user();
    if ($user->username !== $username || $device->user_id !== $user->id) {
        abort(403);
    }

    // 1) Remove the trust flag on the session device
    $device->update(['is_trusted' => false]);

    // 2) Also expire any TrustedDevice rows that correspond to this session/device
    // (adjust the matching logic to whatever you store)
    TrustedDevice::where('user_id', $user->id)
        ->when($device->id, fn($q) => $q->where('user_device_id', $device->id))
        ->orWhere(fn($q) =>
            $q->where('user_id', $user->id)
              ->where('device_fingerprint', $device->device_fingerprint)
        )
        ->update(['expires_at' => now()->subSecond()]);

    // account page uses fetch(..., Accept: application/json)
    return response()->json([
        'success' => true,
        'message' => 'Trust removed for this device.',
    ]);
}


    /**
     * Trust a device
     */
    public function trustDevice(Request $request, string $username, $device)
    {
        $user = Auth::user();
        if ($user->username !== $username) {
            abort(403, 'Unauthorized access.');
        }

        $userDevice = UserDevice::where('user_id', $user->id)
            ->where('id', $device)
            ->first();

        if (!$userDevice) {
            return redirect()->route('tenant.settings.account', $username)
                ->with('error', 'Device not found.');
        }

        if ($userDevice->is_trusted) {
            return redirect()->route('tenant.settings.account', $username)
                ->with('info', 'Device is already trusted.');
        }

        $userDevice->update(['is_trusted' => true]);

        Log::info('Device trusted', [
            'user_id' => $user->id,
            'device_id' => $device,
            'ip' => $userDevice->ip_address
        ]);

        return redirect()->route('tenant.settings.account', $username)
            ->with('success', 'Device has been marked as trusted.');
    }
    /**
     * Revoke all other sessions except current
     */
 
}