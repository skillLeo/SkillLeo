<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Models\RecoveryCode;
    use App\Services\Auth\AuthService;
    use App\Services\Auth\OtpService;
    use App\Services\Auth\DeviceTrackingService;
    use App\Services\Auth\OnlineStatusService;
    use App\Services\TimezoneService;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Log;
    use App\Services\Auth\AuthRedirectService;
    use App\Support\Device;
    use App\Models\UserDevice;
    use PragmaRX\Google2FA\Google2FA;

    class AuthController extends Controller
    {
        public function __construct(
            protected AuthService $authService,
            protected OtpService $otpService,
            protected DeviceTrackingService $deviceTracking,
            protected OnlineStatusService $onlineStatus,
            protected AuthRedirectService $redirects
        ) {}





























        

        public function accountType()
        {
            return view('auth.account-type');
        }

        public function otp()
        {
            return view('auth.otp');
        }

        public function loginshow()
        {
            return view('auth.login', [
                'detectTimezone' => true,
            ]);
        }

        public function selectAccountType(Request $request)
        {
            $request->validate([
                'type' => ['required', 'in:freelancer,client'],
            ]);
        
            $user = $request->user();
        
            $redirects = [
                'freelancer' => route('tenant.onboarding.welcome'),
                'client'     => route('client.onboarding.info'),
            ];
        
            $statusMap = [
                'freelancer' => 'professional',
                'client'     => 'client',
            ];
        
            $user->update([
                'account_status'      => $statusMap[$request->type],
                'is_profile_complete' => 'welcome',
                'meta' => array_merge($user->meta ?? [], [
                    'account_type' => $request->type,
                ]),
            ]);
        
            return redirect($redirects[$request->type])
                ->with('status', 'Welcome! Lets complete your onboarding.');
        }


        












        public function submitLogin(Request $request)
        {
            $data = $request->validate([
                'website'  => ['nullable', 'size:0'],
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
                'remember' => ['sometimes', 'boolean'],
                'timezone' => ['nullable', 'string', 'timezone'],
            ]);
        
            if (!empty($data['timezone'])) {
                TimezoneService::storeViewerTimezone($data['timezone']);
            }
        
            $email = strtolower(trim($data['email']));
            $user  = User::where('email', $email)->first();
        
            if (!$user || !$user->password) {
                return back()->withErrors(['email' => 'Invalid credentials'])
                            ->withInput($request->except('password'));
            }
        
            if (!Hash::check($data['password'], $user->password)) {
                return back()->withErrors(['email' => 'Invalid credentials'])
                            ->withInput($request->except('password'));
            }
        
            // Lifecycle gates
            if ($user->is_active === 'pending_delete') {
                return redirect()->route('account.deletion.notice')
                    ->withErrors(['email' => 'Your account is scheduled for deletion. Cancel it to sign in.']);
            }
        
            if ($user->is_active === 'hibernated') {
                $user->forceFill(['is_active' => 'active', 'hibernated_at' => null])->save();
                session()->flash('status', 'Welcome back! Your account has been reactivated.');
            }
        
            $remember = (bool) ($data['remember'] ?? false);
        
            // 🔑 Check if current device is trusted FIRST
            $currentDeviceId = Device::id($request);
            $trustedDevice = UserDevice::where('user_id', $user->id)
                ->where('device_id', $currentDeviceId)
                ->where('is_trusted', true)
                ->whereNull('revoked_at')
                ->first();
        
            if ($trustedDevice) {
                // ✅ Trusted device - login immediately (NO 2FA)
                $trustedDevice->forceFill([
                    'ip_address'       => $request->ip(),
                    'user_agent'       => (string) $request->userAgent(),
                    'last_seen_at'     => now(),
                    'last_activity_at' => now(),
                ])->save();
        
                Auth::login($user, $remember);
                $request->session()->regenerate();
                $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());
                $this->onlineStatus->markOnline($user);
    
                // ✅ Send login notification if enabled
                $this->sendLoginNotificationIfEnabled($user, $request);
        
                Log::info('Trusted device login - bypassing 2FA', [
                    'user_id' => $user->id,
                    'device_id' => $currentDeviceId
                ]);
        
                return $this->redirects->intendedResponse($user);
            }
        
            // 🛡️ NOT trusted → Check if 2FA is enabled
            $userSecurity = $user->security;
            if ($userSecurity && $userSecurity->two_factor_enabled) {
                $request->session()->put('2fa.pending_user_id', $user->id);
                $request->session()->put('2fa.remember', $remember);
                $request->session()->put('2fa.started_at', now()->timestamp);
        
                Log::info('2FA required for login (untrusted device)', ['user_id' => $user->id]);
                return redirect()->route('auth.2fa.show');
            }
        
            // ✉️ No 2FA but not trusted → Email OTP
            $challengeId = $this->otpService->beginLogin(
                $user,
                $request->session()->getId(),
                $request->ip(),
                (string) $request->userAgent()
            );
        
            $request->session()->put('login.pending_user_id', $user->id);
            $request->session()->put('login.challenge_id', $challengeId);
            $request->session()->put('login.remember', $remember);
            $request->session()->put('login.started_at', now()->timestamp);
        
            return redirect()->route('auth.otp.show', ['email' => $user->email]);
        }


















        private function trustedDevice(User $user, Request $request): ?UserDevice
    {
        $currentDeviceId = Device::id($request); // keep consistent with your Device::id()
        return UserDevice::where('user_id', $user->id)
            ->where('device_id', $currentDeviceId)
            ->where('is_trusted', true)
            ->whereNull('revoked_at')
            ->first();
    }

        // ✅ Show 2FA verification page
        public function show2FA(Request $request)
        {
            $userId = $request->session()->get('2fa.pending_user_id');
            
            if (!$userId) {
                return redirect()->route('auth.login')
                    ->withErrors(['error' => 'Session expired. Please login again.']);
            }
            
            $user = User::find($userId);
            
            if (!$user || !$user->userSecurity || !$user->userSecurity->two_factor_enabled) {
                return redirect()->route('auth.login')
                    ->withErrors(['error' => 'Invalid session. Please login again.']);
            }
            
            return view('auth.2fa-verify');
        }

   // ✅ Verify 2FA code
   public function verify2FA(Request $request)
   {
       $request->validate([
           'code' => 'required|string|size:6|regex:/^[0-9]+$/',
       ]);
       
       $userId = $request->session()->get('2fa.pending_user_id');
       
       if (!$userId) {
           return redirect()->route('auth.login')
               ->withErrors(['error' => 'Session expired. Please login again.']);
       }
       
       $user = User::find($userId);
       
       if (!$user || !$user->security || !$user->security->two_factor_enabled) {
           return redirect()->route('auth.login')
               ->withErrors(['error' => 'Invalid session. Please login again.']);
       }
       
       // Get the encrypted secret
       $secret = decrypt($user->security->two_factor_secret);
       
       // Verify the code
       $google2fa = new Google2FA();
       $code = preg_replace('/[^0-9]/', '', $request->code);
       $valid = $google2fa->verifyKey($secret, $code, 2);
       
       if (!$valid) {
           Log::warning('2FA verification failed', [
               'user_id' => $user->id,
               'code_provided' => $code
           ]);
           
           return back()->withErrors(['error' => 'Invalid verification code. Please try again.']);
       }
       
       // ✅ 2FA verification successful
       $remember = $request->session()->get('2fa.remember', false);
       
       // Clear 2FA session
       $request->session()->forget(['2fa.pending_user_id', '2fa.remember', '2fa.started_at']);
       
       // Track device
       $this->deviceTracking->recordDevice($user, $request);
       
       // Login user
       Auth::login($user, $remember);
       $request->session()->regenerate();
       $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());
       $this->onlineStatus->markOnline($user);

       // ✅ Send login notification if enabled
       $this->sendLoginNotificationIfEnabled($user, $request);
       
       // Update last verified timestamp
       $user->security->update(['last_verified_at' => now()]);
       
       Log::info('2FA verification successful', ['user_id' => $user->id]);
       
       return redirect()->to($this->redirects->url($user));
   }

        // ✅ Show recovery code page
        public function show2FARecovery(Request $request)
        {
            $userId = $request->session()->get('2fa.pending_user_id');
            
            if (!$userId) {
                return redirect()->route('auth.login')
                    ->withErrors(['error' => 'Session expired. Please login again.']);
            }
            
            return view('auth.2fa-recovery');
        }

        // ✅ Verify recovery code
        public function verify2FARecovery(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string',
        ]);
        
        $userId = $request->session()->get('2fa.pending_user_id');
        
        if (!$userId) {
            return redirect()->route('auth.login')
                ->withErrors(['error' => 'Session expired. Please login again.']);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('auth.login')
                ->withErrors(['error' => 'Invalid session. Please login again.']);
        }
        
        // Clean the recovery code
        $code = strtoupper(trim($request->recovery_code));
        
        // Find unused recovery code
        $recoveryCodes = RecoveryCode::where('user_id', $user->id)
            ->where('used', false)
            ->get();
        
        $validCode = null;
        foreach ($recoveryCodes as $recoveryCode) {
            if ($recoveryCode->plain_code === $code) {
                $validCode = $recoveryCode;
                break;
            }
        }
        
        if (!$validCode) {
            Log::warning('Invalid recovery code attempt', ['user_id' => $user->id]);
            return back()->withErrors(['error' => 'Invalid or already used recovery code.']);
        }
        
        // Mark code as used
        $validCode->update(['used' => true, 'used_at' => now()]);
        
        // ✅ Recovery code verification successful
        $remember = $request->session()->get('2fa.remember', false);
        
        // Clear 2FA session
        $request->session()->forget(['2fa.pending_user_id', '2fa.remember', '2fa.started_at']);
        
        // Track device
        $this->deviceTracking->recordDevice($user, $request);
        
        // Login user
        Auth::login($user, $remember);
        $request->session()->regenerate();
        $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());
        $this->onlineStatus->markOnline($user);

        // ✅ Send login notification if enabled
        $this->sendLoginNotificationIfEnabled($user, $request);
        
        Log::info('2FA recovery code used', ['user_id' => $user->id]);
        
        return redirect()->to($this->redirects->url($user))
            ->with('warning', 'You used a recovery code. Please generate new recovery codes in your security settings.');
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
        // You can integrate services like ipinfo.io, ipapi.co, etc.
        // For now, returning a placeholder
        return 'Location Available';
    }



    
      
        public function logout(Request $request)
        {
            if (Auth::check() && isset($this->onlineStatus)) {
                $this->onlineStatus->markOffline(Auth::user());
            }
        
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        
            return redirect()->route('auth.login')
                ->with('status', 'Logged out successfully');
        }
    }