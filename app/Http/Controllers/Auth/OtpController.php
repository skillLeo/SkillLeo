<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Auth\OtpService;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Services\Auth\AuthRedirectService;
use App\Services\Auth\OnlineStatusService;
use App\Services\Auth\DeviceTrackingService;
use App\Mail\LoginNotificationMail;
use Jenssegers\Agent\Agent;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otp,
        protected AuthService $authService,
        protected AuthRedirectService $redirects,
        protected DeviceTrackingService $deviceTracking,
        protected OnlineStatusService $onlineStatus
    ) {}

    public function beginAction(User $user, string $sessionId, string $action, int $ttl = 300): string
    {
        $challengeId = Str::uuid()->toString();
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $hash = password_hash($code, PASSWORD_BCRYPT);
    
        $payload = [
            'user_id'   => $user->id,
            'hash'      => $hash,
            'expires_at'=> now()->addSeconds($ttl)->getTimestamp(),
            'attempts'  => 0,
            'max_attempts' => 5,
            'session'   => $sessionId,
            'action'    => $action,
        ];
    
        Cache::put("otp:{$action}:{$challengeId}", $payload, $ttl + 60);
    
        // Email the code
        Mail::to($user->email)->queue(new \App\Mail\OtpCodeMail($user->name ?? 'there', $code, $ttl));
    
        return $challengeId;
    }
    
    public function verifyAction(string $action, string $challengeId, string $code, string $sessionId): bool
    {
        $key = "otp:{$action}:{$challengeId}";
        $payload = Cache::get($key);
        if (!$payload) return false;
        if ($payload['session'] !== $sessionId) return false;
        if (time() > $payload['expires_at']) { 
            Cache::forget($key); 
            return false; 
        }
        if ($payload['attempts'] >= ($payload['max_attempts'] ?? 5)) { 
            Cache::forget($key); 
            return false; 
        }
    
        $payload['attempts']++;
        Cache::put($key, $payload, 60);
    
        $ok = password_verify($code, $payload['hash']);
        if ($ok) Cache::forget($key);
        return $ok;
    }

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
        $request->session()->regenerate();
        $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());

        // Mark user as online
        $this->onlineStatus->markOnline($user);

        // ✅ Send login notification if enabled
        $this->sendLoginNotificationIfEnabled($user, $request);

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

    /**
     * ✅ Send login notification email if enabled
     */
    protected function sendLoginNotificationIfEnabled(User $user, Request $request): void
    {
        try {
            $security = $user->security;
            
            // Check if login notifications are enabled
            if (!$security || !$security->login_notifications) {
                return;
            }

            $agent = new Agent();
            $agent->setUserAgent($request->header('User-Agent'));

            $loginDetails = [
                'device' => $agent->device() ?: ($agent->isMobile() ? 'Mobile Device' : 'Desktop'),
                'browser' => $agent->browser() . ' ' . $agent->version($agent->browser()),
                'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
                'ip' => $request->ip(),
                'location' => $this->getLocationFromIp($request->ip()),
                'timestamp' => now(),
            ];

            Mail::to($user->email)->queue(new LoginNotificationMail($user, $loginDetails));
            
            Log::info('Login notification sent', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send login notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get location from IP address
     */
    protected function getLocationFromIp(string $ip): string
    {
        // Integrate services like ipinfo.io, ipapi.co, etc.
        return 'Location Available';
    }
}