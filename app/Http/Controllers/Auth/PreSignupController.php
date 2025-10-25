<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SignupLinkMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthRedirectService;
use App\Services\Auth\DeviceTrackingService;
use App\Services\Auth\OnlineStatusService;
use App\Services\TimezoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

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
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', Password::min(8)],
            'timezone' => ['nullable', 'string', 'timezone'],
        ]);

        $data['email'] = strtolower(trim($data['email']));

        // Check if user already exists
        if (User::where('email', $data['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'This email is already registered.'])
                ->withInput($request->except('password'));
        }

        $token = (string) Str::uuid();

        // ✅ Store timezone in cache payload
        Cache::put("signup:{$token}", [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'timezone' => $data['timezone'] ?? 'UTC',
            'ip'       => $request->ip(),
            'ua'       => (string) $request->userAgent(),
        ], now()->addMinutes(60));

        $url = URL::temporarySignedRoute(
            'auth.register.confirm', 
            now()->addMinutes(60), 
            ['token' => $token]
        );

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
            return redirect(route('auth.login'))
                ->withErrors(['link' => 'This link is invalid or has expired.']);
        }

        if (User::where('email', $payload['email'])->exists()) {
            return redirect(route('auth.login'))
                ->with('status', 'Your account is already set up. Please sign in.');
        }

        $user = DB::transaction(function () use ($payload, $request) {
            // Register user via AuthService
            $user = $this->authService->registerEmail($payload);
            
            // ✅ Set timezone and verify email
            $user->forceFill([
                'email_verified_at' => now(),
                'timezone' => $payload['timezone'] ?? 'UTC',
            ])->save();

            // ✅ Create user_security record with login_notifications enabled by default
            $user->security()->updateOrCreate(
                ['user_id' => $user->id],
                ['login_notifications' => true]
            );
            
            return $user;
        });

        // ✅ Send welcome email
        $this->sendWelcomeEmail($user, $request, $payload);

        // Track device for new signup
        $this->deviceTracking->recordDevice($user, $request);

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        // ✅ Store timezone in session
        if (!empty($payload['timezone'])) {
            TimezoneService::storeViewerTimezone($payload['timezone']);
        }

        // Mark user as online after registration
        $this->onlineStatus->markOnline($user);

        Log::info('New user registered via email', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return redirect()->to($this->redirects->url($user))
            ->with('status', 'Welcome! Please complete your onboarding.');
    }

    /**
     * ✅ Send welcome email
     */
    protected function sendWelcomeEmail(User $user, Request $request, array $payload): void
    {
        try {
            $agent = new Agent();
            $agent->setUserAgent($payload['ua'] ?? $request->header('User-Agent'));

            $registrationDetails = [
                'device' => $agent->device() ?: ($agent->isMobile() ? 'Mobile Device' : 'Desktop'),
                'ip' => $payload['ip'] ?? $request->ip(),
                'timestamp' => now(),
            ];

            Mail::to($user->email)->queue(new WelcomeMail($user, $registrationDetails));
            
            Log::info('Welcome email sent', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}