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
        if (! $user || ! $user->password || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }
        if (is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Email not verified. Please verify OTP.','next'=>route('otp.view',['email'=>$user->email])], 403);
        }

        Auth::login($user, $credentials['remember'] ?? false);

        // Sanctum token for API/mobile (optional)
        $token = $user->createToken('web')->plainTextToken;

        app(AuthService::class)->recordLogin($user, $request->ip(), $request->userAgent());

        return response()->json([
            'message' => 'Logged in',
            'token' => $token,
            'user' => $user->only(['id','name','email','username','avatar_url','intent','is_profile_complete']),
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
