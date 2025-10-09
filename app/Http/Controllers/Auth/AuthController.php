<?php
// app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use App\Services\Auth\DeviceTrackingService;
use App\Services\Auth\OnlineStatusService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
 
    public function __construct(
        protected AuthService $authService,
        protected OtpService $otpService,
        protected DeviceTrackingService $deviceTracking,
        protected OnlineStatusService $onlineStatus
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
        return view('auth.login');
    }

    /**
     * After choosing account type on auth.account-type, set the next step correctly:
     *  - freelancer/professional → 'personal'
     *  - client                → 'info'
     */
    public function selectAccountType(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:freelancer,client'],
        ]);
    
        $user = $request->user();
    
        // Where to send after choosing
        $redirects = [
            'freelancer' => route('tenant.onboarding.welcome'),
            'client'     => route('client.onboarding.info'),
        ];
    
        // Map UI type -> persisted account_status
        $statusMap = [
            'freelancer' => 'professional',
            'client'     => 'client',
        ];
    
        // ✅ As requested:
        //    - ALWAYS set is_profile_complete = 'personal'
        //    - Set account_status from the selection
        $user->update([
            'account_status'      => $statusMap[$request->type],
            'is_profile_complete' => 'welcome',
            'meta' => array_merge($user->meta ?? [], [
                'account_type' => $request->type,
            ]),
        ]);
    
        return redirect($redirects[$request->type])
            ->with('status', 'Welcome! Let’s complete your onboarding.');
    }
    


    public function submitLogin(Request $request)
    {
        $data = $request->validate([
            'website'  => ['nullable', 'size:0'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $email = strtolower(trim($data['email']));
        $user  = User::where('email', $email)->first();

        if (!$user || !$user->password) {
            Log::warning('Login failed: user not found or no password', ['email' => $email]);
            return back()->withErrors(['email' => 'Invalid credentials'])
                         ->withInput($request->except('password'));
        }

        if (!Hash::check($data['password'], $user->password)) {
            Log::notice('Login failed: password mismatch', ['user_id' => $user->id]);
            return back()->withErrors(['email' => 'Invalid credentials'])
                         ->withInput($request->except('password'));
        }

        $challengeId = $this->otpService->beginLogin(
            $user,
            $request->session()->getId(),
            $request->ip(),
            (string) $request->userAgent()
        );

        $request->session()->put('login.pending_user_id', $user->id);
        $request->session()->put('login.challenge_id', $challengeId);
        $request->session()->put('login.remember', (bool) ($data['remember'] ?? false));
        $request->session()->put('login.started_at', now()->timestamp);

        return redirect()->route('auth.otp.show', ['email' => $user->email]);
    }

    public function logout(Request $request)
    {
        // 🔥 Mark user as offline before logout
        if (Auth::check()) {
            $this->onlineStatus->markOffline(Auth::user());
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();

        return redirect()->route('auth.login')->with('status', 'Logged out successfully');
    }
}
