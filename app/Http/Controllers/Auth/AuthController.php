<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected OtpService $otpService
    ) {}











    public function submitLogin(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['sometimes','boolean'],
        ]);

        $user = User::where('email', strtolower($data['email']))->first();

        // Basic credential check
        if (! $user || ! $user->password || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        // Create & email OTP, stash pending login in session
        $this->otpService->createAndSend($user, $request->ip());
        $request->session()->put('login.pending_user_id', $user->id);
        $request->session()->put('login.remember', (bool) ($data['remember'] ?? false));
        $request->session()->put('login.started_at', now()->timestamp);

        return redirect()->route('otp.show', ['email' => $user->email]);
    }













    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable','string','max:120'],
            'email' => ['required','email','max:255'],
            'password' => ['required', Password::min(8)],
            'intent' => ['nullable','in:professional,client'],
            'tenant_name' => ['nullable','string','max:160'],
        ]);

        // Ensure uniqueness within tenant scope (tenant null at this point)
        if (User::whereNull('tenant_id')->where('email', strtolower($data['email']))->exists()) {
            return response()->json(['message'=>'Email already registered.'], 422);
        }

        $user = $this->authService->registerEmail($data);
        $this->otpService->createAndSend($user, $request->ip());

        return response()->json([
            'message' => 'Registered. Please verify OTP sent to your email.',
            'next' => route('otp.view', ['email' => $user->email]),
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['sometimes','boolean'],
        ]);
    
        $user = User::where('email', strtolower($credentials['email']))->first();
        if (! $user || ! $user->password || ! \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }
    
        // Start OTP flow (always, like LinkedIn/Microsoft 2-step)
        $this->otpService->createAndSend($user, $request->ip());
    
        // Stash "pending login" in session
        $request->session()->put('login.pending_user_id', $user->id);
        $request->session()->put('login.remember', (bool) ($credentials['remember'] ?? false));
        $request->session()->put('login.started_at', now()->timestamp);
    
        return response()->json([
            'message' => 'We sent a 6-digit code to your email.',
            'next'    => route('otp.view', ['email' => $user->email]),
        ]);
    }
    

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()?->delete(); // revoke current API token
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return response()->json(['message'=>'Logged out']);
    }
}
