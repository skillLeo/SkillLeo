<?php
// app/Http/Controllers/Auth/PreSignupController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SignupLinkMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, URL, Mail, DB};
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class PreSignupController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function sendLink(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:255'],
            'password' => ['required', Password::min(8)],
        ]);

        $data['email'] = strtolower(trim($data['email']));

        $token = (string) Str::uuid();

        Cache::put("signup:{$token}", [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'ip'       => $request->ip(),
            'ua'       => (string) $request->userAgent(),
        ], now()->addMinutes(60));

        $url = URL::temporarySignedRoute('register.confirm', now()->addMinutes(60), ['token' => $token]);

        Mail::to($data['email'])->send(new SignupLinkMail($data['name'], $url));

        $request->session()->put('signup_token', $token);
        $request->session()->put('signup_email', $data['email']);

        return redirect()->route('verification.notice')
            ->with('status', 'We emailed you a secure link to create your account.');
    }

    public function confirm(Request $request, string $token)
    {
        $payload = Cache::pull("signup:{$token}");
        if (! $payload) {
            return redirect(route('auth.login'))->withErrors(['link' => 'This link is invalid or has expired.']);
        }

        if (User::where('email', $payload['email'])->exists()) {
            return redirect(route('auth.login'))->with('status', 'Your account is already set up. Please sign in.');
        }

        $user = DB::transaction(function () use ($payload) {
            $user = $this->authService->registerEmail($payload);
            $user->forceFill(['email_verified_at' => now()])->save();
            return $user;
        });

        if ($user->account_status === 'pending_onboarding' || $user->is_profile_complete === 'start') {
            return redirect()->route('auth.account-type')->with('status', 'Welcome! Please complete your onboarding.');
        }

        return redirect()->intended(route('tenant.profile', ['username' => $user->username]));
    }
}
