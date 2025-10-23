<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Str;
use App\Models\RecoveryCode;
use App\Models\UserSecurity;
use Illuminate\Http\Request;
use App\Models\TrustedDevice;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SecurityController extends Controller
{
    public function security($username)
    {
        $user = Auth::user();
        if ($user->username !== $username) abort(403);

        $userSecurity = $user->userSecurity ?? UserSecurity::create([
            'user_id' => $user->id,
            'two_factor_enabled' => false,
            'two_factor_email' => false,
            'two_factor_phone' => false,
        ]);

        // TOTP secret (session)
        $totpSecret = session('totp_secret_' . $user->id);
        if (!$totpSecret) {
            $google2fa  = new Google2FA();
            $totpSecret = $google2fa->generateSecretKey();
            session(['totp_secret_' . $user->id => $totpSecret]);
        }

        // Recovery codes
        $recoveryCodes = RecoveryCode::where('user_id', $user->id)
            ->where('used', false)
            ->pluck('plain_code')
            ->toArray();
        if (empty($recoveryCodes) && $userSecurity->two_factor_enabled) {
            $recoveryCodes = $this->generateDummyCodes();
        }

        // Trusted devices
        $trustedDevicesCount = TrustedDevice::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->count();

        $trustedDevices = TrustedDevice::where('user_id', $user->id)
            ->orderByDesc('last_used_at')
            ->get();

        // Flash flags
        $setupStep         = session('setup_step');
        $showRecoveryCodes = session('show_recovery_codes', false);
        $newRecoveryCodes  = session('new_recovery_codes', []);
        
        $devices = UserDevice::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->where('last_seen_at', '>=', now()->subDays(90))
            ->orderByDesc('last_activity_at')
            ->get();

        $trustedDevicesCount = $devices->where('is_trusted', true)->count();

        return view('tenant.settings.security', [
            'user'                   => $user,
            'activeSection'          => 'security',
            'username'               => $username,
            'twoFactorEnabled'       => $userSecurity->two_factor_enabled,
            'emailOtpEnabled'        => $userSecurity->two_factor_email,
            'phoneOtpEnabled'        => $userSecurity->two_factor_phone,
            'maskedPhone'            => $userSecurity->phone_number ? '***-***-' . substr($userSecurity->phone_number, -4) : null,
            'recoveryCodesRemaining' => count($recoveryCodes),
            'recoveryCodesDate'      => $userSecurity->updated_at ? $userSecurity->updated_at->format('M d, Y') : now()->format('M d, Y'),
            'trustedDevicesCount'    => $trustedDevicesCount,
            'trustedDevices'         => $trustedDevices,
            'totpSecret'             => $totpSecret,
            'recoveryCodes'          => $recoveryCodes,
            'newRecoveryCodes'       => $newRecoveryCodes,
            'appPasswords'           => [],
            'setupStep'              => $setupStep,
            'showRecoveryCodes'      => $showRecoveryCodes,
            'userSecurity'           => $userSecurity,
            'devices'                => $devices,
        ]);
    }

    // ==================== Trust/Untrust Device Methods ====================
    
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

    public function untrustDevice(Request $request, string $username, $device)
    {
        $user = Auth::user();
        if ($user->username !== $username) {
            abort(403, 'Unauthorized access.');
        }

        $userDevice = UserDevice::where('user_id', $user->id)
            ->where('id', $device)
            ->first();

        if (!$userDevice) {
            return redirect()->route('tenant.settings.security', $username)
                ->with('error', 'Device not found.');
        }

        if (!$userDevice->is_trusted) {
            return redirect()->route('tenant.settings.security', $username)
                ->with('info', 'Device is not trusted.');
        }

        $userDevice->update(['is_trusted' => false]);

        Log::info('Device untrusted', [
            'user_id' => $user->id,
            'device_id' => $device,
            'ip' => $userDevice->ip_address
        ]);

        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Trust has been removed from this device.');
    }

    public function untrustAllDevices(Request $request, string $username)
    {
        $user = Auth::user();
        if ($user->username !== $username) {
            abort(403, 'Unauthorized access.');
        }

        $count = UserDevice::where('user_id', $user->id)
            ->where('is_trusted', true)
            ->whereNull('revoked_at')
            ->update(['is_trusted' => false]);

        Log::info('All devices untrusted', [
            'user_id' => $user->id,
            'count' => $count
        ]);

        return redirect()->route('tenant.settings.security', $username)
            ->with('success', "Trust removed from {$count} device(s).");
    }

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

    // ==================== Advanced Security Settings ====================

    public function toggle2FANewLocation(Request $request, $username)
    {
        $request->validate(['enabled' => 'required|boolean']);
        
        $user = Auth::user();
        if ($user->username !== $username) {
            abort(403, 'Unauthorized access.');
        }

        $userSecurity = $user->userSecurity ?? UserSecurity::create(['user_id' => $user->id]);
        $userSecurity->update(['require_2fa_new_location' => $request->enabled]);
        
        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Setting updated successfully.');
    }

    public function toggle2FASensitive(Request $request, $username)
    {
        $request->validate(['enabled' => 'required|boolean']);
        
        $user = Auth::user();
        if ($user->username !== $username) {
            abort(403, 'Unauthorized access.');
        }

        $userSecurity = $user->userSecurity ?? UserSecurity::create(['user_id' => $user->id]);
        $userSecurity->update(['require_2fa_sensitive_actions' => $request->enabled]);
        
        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Setting updated successfully.');
    }

    public function toggleLoginNotifications(Request $request, $username)
    {
        $request->validate(['enabled' => 'required|boolean']);
        
        $user = Auth::user();
        if ($user->username !== $username) {
            abort(403, 'Unauthorized access.');
        }

        $userSecurity = $user->userSecurity ?? UserSecurity::create(['user_id' => $user->id]);
        $userSecurity->update(['login_notifications' => $request->enabled]);
        
        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Setting updated successfully.');
    }

    // ==================== 2FA Methods ====================

    public function enable2FAStep1(Request $request, $username)
    {
        $user = Auth::user();
        if ($user->username !== $username) abort(403);
    
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
    
        session(['totp_secret_' . $user->id => $secret]);
        session(['setup_step' => 'scan']);
    
        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Scan the QR code with your authenticator app');
    }

    public function enable2FAVerify(Request $request, $username)
    {
        $request->validate([
            'code' => 'required|string|size:6|regex:/^[0-9]+$/',
        ]);
    
        $user = Auth::user();
        if ($user->username !== $username) abort(403);
    
        $secret = session('totp_secret_' . $user->id);
        if (!$secret) {
            return redirect()->route('tenant.settings.security', $username)
                ->with('error', 'Session expired. Please start the setup again.');
        }
    
        $google2fa = new Google2FA();
        $code = preg_replace('/[^0-9]/', '', $request->code);
        $valid = $google2fa->verifyKey($secret, $code, 2);
    
        if (!$valid) {
            Log::warning('2FA verification failed', [
                'user_id' => $user->id,
                'code_provided' => $code
            ]);
    
            return redirect()->route('tenant.settings.security', $username)
                ->with('error', 'Invalid verification code. Please try again.')
                ->with('setup_step', 'verify');
        }
    
        $userSecurity = $user->userSecurity ?? UserSecurity::create(['user_id' => $user->id]);
        $userSecurity->update([
            'two_factor_enabled' => true,
            'two_factor_secret'  => encrypt($secret),
            'last_verified_at'   => now(),
        ]);
    
        $codes = $this->generateRecoveryCodes($user->id);
    
        session()->forget(['totp_secret_' . $user->id, 'setup_step', 'show_recovery_codes', 'new_recovery_codes']);
    
        Log::info('2FA enabled successfully', ['user_id' => $user->id]);
    
        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Two-factor authentication enabled successfully!')
            ->with('one_time_codes', $codes);
    }

    public function disable2FA(Request $request, $username)
    {
        $user = Auth::user();
        if ($user->username !== $username) abort(403);

        $userSecurity = $user->userSecurity;

        if (!$userSecurity) {
            return redirect()->route('tenant.settings.security', $username)
                ->with('error', 'Two-factor authentication is not enabled.');
        }

        $userSecurity->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
        ]);

        RecoveryCode::where('user_id', $user->id)->delete();

        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    public function regenerateRecoveryCodes(Request $request, $username)
    {
        $user = Auth::user();
        if ($user->username !== $username) abort(403);

        $codes = $this->generateRecoveryCodes($user->id);

        session(['show_recovery_codes' => true, 'new_recovery_codes' => $codes]);

        return redirect()->route('tenant.settings.security', $username)
            ->with('success', 'Recovery codes regenerated successfully.');
    }

    // ==================== Helper Methods ====================
    
    private function generateRecoveryCodes($userId)
    {
        RecoveryCode::where('user_id', $userId)->delete();

        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $code = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
            RecoveryCode::create([
                'user_id'    => $userId,
                'code'       => bcrypt($code),
                'plain_code' => $code,
                'used'       => false,
            ]);
            $codes[] = $code;
        }
        return $codes;
    }

    private function generateDummyCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        }
        return $codes;
    }
}