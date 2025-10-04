<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function view(Request $request)
    {
        // email provided via query (?email=)
        return view('auth.otp', ['email' => $request->query('email')]);
    }

    public function send(Request $request)
    {
        $data = $request->validate(['email' => ['required','email']]);
        $user = User::where('email', strtolower($data['email']))->first();

        if (! $user) {
            return response()->json(['message'=>'No account found for that email'], 404);
        }

        $this->otpService->createAndSend($user, $request->ip());
        return response()->json(['message'=>'OTP sent']);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'otp' => ['required','digits:6'],
        ]);

        $user = User::where('email', strtolower($data['email']))->first();
        if (! $user) return response()->json(['message'=>'User not found'], 404);

        if (! $this->otpService->verify($user, $data['otp'])) {
            return response()->json(['message'=>'Invalid or expired code'], 422);
        }

        Auth::login($user, true);
        return response()->json([
            'message' => 'Email verified',
            'redirect' => $user->is_profile_complete ? url('/'.($user->username ?? 'dashboard')) : url('/onboarding'),
        ]);
    }
}
