<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthRedirectService;
use App\Services\Auth\DeviceTrackingService;
use App\Services\Auth\OnlineStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otp,
        protected AuthService $authService,
        protected AuthRedirectService $redirects,
        protected DeviceTrackingService $deviceTracking,
        protected OnlineStatusService $onlineStatus
    ) {}

    public function show(Request $request)
    {
        return view('auth.otp', [
            'email'   => $request->get('email'),
            'seconds' => $this->otp->remainingSeconds((string) session('login.challenge_id')),
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'digits:6']
        ]);

        $challengeId = (string) $request->session()->get('login.challenge_id');
        $pendingId   = (int) $request->session()->get('login.pending_user_id');

        if (!$challengeId || !$pendingId) {
            return back()->withErrors(['code' => 'Session expired. Please sign in again.']);
        }

        $ok = $this->otp->verify(
            $challengeId,
            $data['code'],
            $request->session()->getId(),
            $request->ip(),
            (string) $request->userAgent()
        );

        if (!$ok) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        $user = User::withoutGlobalScopes()->findOrFail($pendingId);
        $remember = (bool) session('login.remember');

        // Track device BEFORE login
        $device = $this->deviceTracking->recordDevice($user, $request);
        $isNewDevice = $device->wasRecentlyCreated;

        // Login user
        Auth::login($user, $remember);
        $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());

        // Mark user as online immediately after login
        $this->onlineStatus->markOnline($user);

        // Clear session data
        $request->session()->forget([
            'login.pending_user_id',
            'login.challenge_id',
            'login.remember',
            'login.started_at',
        ]);

        if ($isNewDevice) {
            Log::info('New device login detected', [
                'user_id' => $user->id,
                'device_name' => $device->device_name,
                'ip' => $request->ip(),
            ]);
        }

        return $this->redirects->intendedResponse($user);
    }

    public function resend(Request $request)
    {
        $pendingId = (int) $request->session()->get('login.pending_user_id');
        $user = User::withoutGlobalScopes()->findOrFail($pendingId);
        
        $challengeId = $this->otp->resend($user, $request->session()->getId());
        $request->session()->put('login.challenge_id', $challengeId);

        return back()->with('status', 'New code sent.');
    }
}