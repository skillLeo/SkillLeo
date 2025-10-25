<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\OAuthIdentity;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\Auth\AuthRedirectService;
use App\Services\Auth\AuthService;
use App\Services\Auth\DeviceTrackingService;
use App\Services\Auth\OnlineStatusService;
use App\Services\Auth\OtpService;
use App\Services\TimezoneService;
use App\Support\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\WelcomeMail;
use App\Mail\LoginNotificationMail;
use Jenssegers\Agent\Agent;

class OAuthController extends Controller
{
    public function __construct(
        protected AuthRedirectService $redirects,
        protected DeviceTrackingService $deviceTracking,
        protected OnlineStatusService $onlineStatus,
        protected AuthService $authService,
        protected OtpService $otpService
    ) {}

    private const DRIVER_PROVIDERS = ['google', 'github', 'linkedin-openid'];
    private const URL_PROVIDERS    = ['google', 'github', 'linkedin', 'linkedin-openid'];

    private function asDriver(string $provider): string
    {
        return $provider === 'linkedin' ? 'linkedin-openid' : $provider;
    }

    public function redirect(Request $request, string $provider)
    {
        abort_unless(in_array($provider, self::URL_PROVIDERS, true), 404);
        $driverName = $this->asDriver($provider);
        abort_unless(in_array($driverName, self::DRIVER_PROVIDERS, true), 404);
        $driver = $this->driver($driverName, $request);

        Log::info('OAuth Redirect', [
            'url_provider' => $provider,
            'driver'       => $driverName,
            'redirect_url' => $this->callbackUrl($driverName),
        ]);

        return $driver->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        abort_unless(in_array($provider, self::URL_PROVIDERS, true), 404);
        $driverName = $this->asDriver($provider);
        abort_unless(in_array($driverName, self::DRIVER_PROVIDERS, true), 404);

        if ($request->filled('error')) {
            $error = $request->query('error');
            $desc  = $request->query('error_description', 'Authorization failed');
            Log::error("OAuth Error - {$driverName}", ['error' => $error, 'desc' => $desc]);
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => ucfirst($provider) . ': ' . urldecode($desc)]);
        }

        try {
            $social      = $this->driver($driverName, $request)->user();
            $providerUid = (string) ($social->getId() ?? '');
            if ($providerUid === '') {
                throw new \Exception(ucfirst($provider) . ' did not return a user ID.');
            }

            $email    = $this->normalizedEmail($driverName, $social);
            $name     = $this->displayName($social);
            $nickname = $social->getNickname();
            $avatar   = $social->getAvatar();
            $now      = now();
            $timezone = $request->input('timezone', 'UTC');

            $isNewUser = false;

            $user = DB::transaction(function () use (
                $driverName,
                $providerUid,
                $email,
                $name,
                $nickname,
                $avatar,
                $social,
                $now,
                $timezone,
                &$isNewUser
            ) {
                $identity = OAuthIdentity::where([
                    'provider'         => $driverName,
                    'provider_user_id' => $providerUid,
                ])->first();

                if ($identity) {
                    $user = $identity->user;
                    $this->refreshUserProfile($user, $name, $email, $avatar);

                    $identity->update([
                        'provider_username' => $nickname ?? $identity->provider_username,
                        'avatar_url'        => $avatar ?? $identity->avatar_url,
                        'access_token'      => $social->token ?? null,
                        'refresh_token'     => $social->refreshToken ?? null,
                        'token_expires_at'  => isset($social->expiresIn)
                            ? $now->copy()->addSeconds((int) $social->expiresIn)
                            : null,
                        'provider_raw'      => $social->user ?? [],
                    ]);

                    $user->update([
                        'last_login_at' => $now,
                        'login_count'   => ($user->login_count ?? 0) + 1,
                    ]);

                    return $user;
                }

                // find user by email or create
                $user = $email ? User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first() : null;

                if (!$user) {
                    $isNewUser = true; // ✅ Mark as new registration
                    
                    $user = User::create([
                        'tenant_id'           => null,
                        'name'                => $name ?: ($nickname ?: 'User'),
                        'email'               => $email ?: "oauth_{$providerUid}@users.noreply.local",
                        'avatar_url'          => $avatar,
                        'email_verified_at'   => $email ? $now : null,
                        'password'            => null,
                        'username'            => $this->uniqueUsername($nickname ?: ($name ?: 'user')),
                        'locale'              => 'en',
                        'timezone'            => $timezone,
                        'is_active'           => 'active',
                        'is_profile_complete' => 'start',
                        'account_status'      => 'pending_onboarding',
                        'last_login_at'       => $now,
                        'login_count'         => 1,
                        'meta'                => ['created_via' => $driverName],
                    ]);
                } else {
                    $this->refreshUserProfile($user, $name, $email, $avatar);
                    $user->update([
                        'last_login_at' => $now,
                        'login_count'   => ($user->login_count ?? 0) + 1,
                    ]);
                }

                TimezoneService::storeViewerTimezone($timezone);

                OAuthIdentity::updateOrCreate(
                    ['provider' => $driverName, 'provider_user_id' => $providerUid],
                    [
                        'user_id'           => $user->id,
                        'provider_username' => $nickname,
                        'avatar_url'        => $avatar,
                        'access_token'      => $social->token ?? null,
                        'refresh_token'     => $social->refreshToken ?? null,
                        'token_expires_at'  => isset($social->expiresIn)
                            ? $now->copy()->addSeconds((int) $social->expiresIn)
                            : null,
                        'provider_raw'      => $social->user ?? [],
                    ]
                );

                return $user;
            });

            // ✅ Send welcome email for new registrations
            if ($isNewUser) {
                $this->sendWelcomeEmail($user, $request);
            }

            // 🔐 Check trusted device FIRST
            $currentDeviceId = Device::id($request);
            $trustedDevice = UserDevice::where('user_id', $user->id)
                ->where('device_id', $currentDeviceId)
                ->where('is_trusted', true)
                ->whereNull('revoked_at')
                ->first();

            if ($trustedDevice) {
                // ✅ Trusted device - login immediately
                $trustedDevice->forceFill([
                    'ip_address'       => $request->ip(),
                    'user_agent'       => (string) $request->userAgent(),
                    'last_seen_at'     => now(),
                    'last_activity_at' => now(),
                ])->save();

                Auth::login($user, true);
                $request->session()->regenerate();
                $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());
                $this->onlineStatus->markOnline($user);

                // ✅ Send login notification if enabled
                $this->sendLoginNotificationIfEnabled($user, $request);

                Log::info('OAuth trusted device login', [
                    'user_id' => $user->id,
                    'provider' => $driverName,
                    'device_id' => $currentDeviceId
                ]);

                return redirect()->to($this->redirects->url($user));
            }

            // 🛡️ NOT trusted → Check if 2FA is enabled
            $userSecurity = $user->security;
            if ($userSecurity && $userSecurity->two_factor_enabled) {
                $request->session()->put('2fa.pending_user_id', $user->id);
                $request->session()->put('2fa.remember', true);
                $request->session()->put('2fa.started_at', now()->timestamp);

                Log::info('2FA required for OAuth login', [
                    'user_id'  => $user->id,
                    'provider' => $driverName,
                ]);

                return redirect()->route('auth.2fa.show');
            }

            // 📧 Email OTP for accounts WITH local password
            if ($this->hasLocalPassword($user) && $this->hasDeliverableEmail($user)) {
                $challengeId = $this->otpService->beginLogin(
                    $user,
                    $request->session()->getId(),
                    $request->ip(),
                    (string) $request->userAgent()
                );

                $request->session()->put('login.pending_user_id', $user->id);
                $request->session()->put('login.challenge_id', $challengeId);
                $request->session()->put('login.remember', true);
                $request->session()->put('login.started_at', now()->timestamp);

                Log::info('Email OTP required for OAuth login', [
                    'user_id' => $user->id,
                    'provider' => $driverName,
                ]);

                return redirect()->route('auth.otp.show', ['email' => $user->email]);
            }

            // 🚀 OAuth-only users - track device and login
            try {
                $device = $this->deviceTracking->recordDevice($user, $request);
                Log::info('Device tracked for OAuth-only login', [
                    'user_id' => $user->id,
                    'device_id' => $device->device_id,
                    'device_name' => $device->device_name,
                    'provider' => $driverName,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to track device for OAuth login', [
                    'user_id' => $user->id,
                    'provider' => $driverName,
                    'error' => $e->getMessage(),
                ]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();
            $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());
            $this->onlineStatus->markOnline($user);

            // ✅ Send login notification if enabled
            $this->sendLoginNotificationIfEnabled($user, $request);

            return redirect()->to($this->redirects->url($user));

        } catch (\Exception $e) {
            Log::error("OAuth Callback Error - {$driverName}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'Authentication with ' . ucfirst($provider) . ' failed.']);
        }
    }

    private function driver(string $driverName, Request $request)
    {
        $driver = Socialite::driver($driverName);
        $driver->redirectUrl($this->callbackUrl($driverName));

        if ($driverName !== 'linkedin-openid' && filter_var(env('OAUTH_STATELESS', false), FILTER_VALIDATE_BOOLEAN)) {
            $driver->stateless();
        }

        return $driver;
    }

    private function callbackUrl(string $driverName): string
    {
        $configured = config("services.$driverName.redirect");
        return $configured ?: rtrim(config('app.url'), '/') . "/auth/{$driverName}/callback";
    }

    private function normalizedEmail(string $provider, SocialiteUser $s): ?string
    {
        $email = $s->getEmail();
        return (is_string($email) && $email !== '') ? strtolower(trim($email)) : null;
    }

    private function displayName(SocialiteUser $s): ?string
    {
        if ($name = $s->getName()) return $name;
        $raw = $s->user ?? [];
        if (isset($raw['name'])) return $raw['name'];
        $first = $raw['given_name'] ?? $raw['localizedFirstName'] ?? null;
        $last  = $raw['family_name'] ?? $raw['localizedLastName'] ?? null;
        return trim(($first ?: '') . ' ' . ($last ?: '')) ?: null;
    }

    private function refreshUserProfile(User $user, ?string $name, ?string $email, ?string $avatar): void
    {
        $changes = [];
        if (!$user->name && $name)          $changes['name'] = $name;
        if (!$user->avatar_url && $avatar)  $changes['avatar_url'] = $avatar;

        if (!$user->email_verified_at && $email && str_contains($email, '@')) {
            if (empty($user->email) || str_contains($user->email, '@users.noreply.local')) {
                $changes['email'] = strtolower($email);
            }
            $changes['email_verified_at'] = Carbon::now();
        }

        if ($changes) $user->update($changes);
    }

    private function uniqueUsername(string $seed): string
    {
        $base = Str::of($seed)->lower()->ascii()->slug('_');
        $base = (string) Str::limit($base ?: 'user', 40, '');
        $username = $base;
        $n = 0;
        while (User::where('username', $username)->exists()) {
            $n++;
            $username = Str::limit($base, 40, '') . "_{$n}";
            if ($n > 5000) {
                $username = 'user_' . Str::random(8);
                break;
            }
        }
        return $username;
    }

    private function hasLocalPassword(User $user): bool
    {
        return !empty($user->password);
    }

    private function hasDeliverableEmail(User $user): bool
    {
        return !empty($user->email) && !str_ends_with($user->email, '@users.noreply.local');
    }

    /**
     * ✅ Send welcome email
     */
    protected function sendWelcomeEmail(User $user, Request $request): void
    {
        try {
            $agent = new Agent();
            $agent->setUserAgent($request->header('User-Agent'));

            $registrationDetails = [
                'device' => $agent->device() ?: ($agent->isMobile() ? 'Mobile Device' : 'Desktop'),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ];

            Mail::to($user->email)->queue(new WelcomeMail($user, $registrationDetails));
            
            Log::info('Welcome email sent', ['user_id' => $user->id]);
        } catch (\Exception $e) {Log::error('Failed to send welcome email', [
            'user_id' => $user->id,
            'error' => $e->getMessage()
        ]);
    }
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