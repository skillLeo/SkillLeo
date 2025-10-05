<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected OtpService $otpService
    ) {}

    /**
     * Show login page
     */
    public function loginshow()
    {
        return view('auth.login');
    }

    /**
     * Handle login submission
     */
    public function submitLogin(Request $request)
    {
        // Validate
        $data = $request->validate([
            'website' => ['nullable', 'size:0'], // honeypot
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $email = strtolower(trim($data['email']));

        // Find user
        $user = User::where('email', $email)->first();

        // Check user exists and has password
        if (!$user || !$user->password) {
            Log::warning('Login failed: user not found or no password', ['email' => $email]);
            return back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput($request->except('password'));
        }

        // Verify password
        if (!Hash::check($data['password'], $user->password)) {
            Log::notice('Login failed: password mismatch', ['user_id' => $user->id]);
            return back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput($request->except('password'));
        }

        // Start OTP flow
        $challengeId = $this->otpService->beginLogin(
            $user,
            $request->session()->getId(),
            $request->ip(),
            (string) $request->userAgent()
        );

        // Store login state in session
        $request->session()->put('login.pending_user_id', $user->id);
        $request->session()->put('login.challenge_id', $challengeId);
        $request->session()->put('login.remember', (bool) ($data['remember'] ?? false));
        $request->session()->put('login.started_at', now()->timestamp);

        // Redirect to OTP page
        return redirect()->route('otp.show', ['email' => $user->email]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()?->delete();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/login')->with('status', 'Logged out successfully');
    }
    public function logout_get()
    {
        
        session()->invalidate();
        Auth::logout();
        session()->regenerateToken();
        return redirect('/login')->with('status', 'Logged out successfully');
    }
}