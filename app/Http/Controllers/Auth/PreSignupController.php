<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SignupLinkMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthRedirectService;
use App\Services\Auth\DeviceTrackingService;
use App\Services\Auth\OnlineStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, URL, Mail, DB, Auth};
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class PreSignupController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected AuthRedirectService $redirects,
        protected DeviceTrackingService $deviceTracking,
        protected OnlineStatusService $onlineStatus
    ) {}

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

        $url = URL::temporarySignedRoute('auth.register.confirm', now()->addMinutes(60), ['token' => $token]);

        Mail::to($data['email'])->send(new SignupLinkMail($data['name'], $url));

        $request->session()->put('signup_token', $token);
        $request->session()->put('signup_email', $data['email']);

        return redirect()->route('auth.verification.notice')
            ->with('status', 'We emailed you a secure link to create your account.');
    }

    public function confirm(Request $request, string $token)
    {
        $payload = Cache::pull("signup:{$token}");
        if (!$payload) {
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

        // 🔥 Track device for new signup
        $this->deviceTracking->recordDevice($user, $request);

        Auth::login($user);
        $request->session()->regenerate();

        // 🔥 Mark user as online after registration
        $this->onlineStatus->markOnline($user);

        return redirect()->to($this->redirects->url($user))
            ->with('status', 'Welcome! Please complete your onboarding.');
    }
}