<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\OAuthIdentity;
use App\Models\User;
use App\Services\Auth\AuthRedirectService;
use App\Services\Auth\DeviceTrackingService;
use App\Services\Auth\OnlineStatusService;
use App\Services\TimezoneService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function __construct(
        protected AuthRedirectService $redirects,
        protected DeviceTrackingService $deviceTracking,
        protected OnlineStatusService $onlineStatus
    ) {}

    private const DRIVER_PROVIDERS = ['google', 'github', 'linkedin-openid'];
    private const URL_PROVIDERS = ['google', 'github', 'linkedin', 'linkedin-openid'];

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
            'driver' => $driverName,
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
            $desc = $request->query('error_description', 'Authorization failed');
            Log::error("OAuth Error - {$driverName}", ['error' => $error, 'desc' => $desc]);
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => ucfirst($provider) . ': ' . urldecode($desc)]);
        }

        try {
            $social = $this->driver($driverName, $request)->user();
            $providerUid = (string) ($social->getId() ?? '');
            
            if ($providerUid === '') {
                throw new \Exception(ucfirst($provider) . ' did not return a user ID.');
            }

            $email = $this->normalizedEmail($driverName, $social);
            $name = $this->displayName($social);
            $nickname = $social->getNickname();
            $avatar = $social->getAvatar();
            $now = now();

            // ✅ Get timezone from request (captured by frontend JS)
            $timezone = $request->input('timezone', 'UTC');

            $user = DB::transaction(function () use (
                $driverName, $providerUid, $email, $name, $nickname, $avatar, $social, $now, $timezone
            ) {
                $identity = OAuthIdentity::where([
                    'provider' => $driverName,
                    'provider_user_id' => $providerUid,
                ])->first();

                if ($identity) {
                    // Existing user - update profile
                    $user = $identity->user;
                    $this->refreshUserProfile($user, $name, $email, $avatar);
                    
                    $identity->update([
                        'provider_username' => $nickname ?? $identity->provider_username,
                        'avatar_url' => $avatar ?? $identity->avatar_url,
                        'access_token' => $social->token ?? null,
                        'refresh_token' => $social->refreshToken ?? null,
                        'token_expires_at' => isset($social->expiresIn) 
                            ? $now->copy()->addSeconds((int) $social->expiresIn) 
                            : null,
                        'provider_raw' => $social->user ?? [],
                    ]);
                    
                    $user->update([
                        'last_login_at' => $now,
                        'login_count' => ($user->login_count ?? 0) + 1,
                    ]);
                    
                    return $user;
                }

                // Check if user exists by email
                $user = $email ? User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first() : null;

                if (!$user) {
                    // ✅ Create new user with timezone
                    $user = User::create([
                        'tenant_id' => null,
                        'name' => $name ?: ($nickname ?: 'User'),
                        'email' => $email ?: "oauth_{$providerUid}@users.noreply.local",
                        'avatar_url' => $avatar,
                        'email_verified_at' => $email ? $now : null,
                        'password' => null,
                        'username' => $this->uniqueUsername($nickname ?: ($name ?: 'user')),
                        'locale' => 'en',
                        'timezone' => $timezone, // ✅ Set detected timezone
                        'is_active' => 'active',
                        'is_profile_complete' => 'start',
                        'account_status' => 'pending_onboarding',
                        'last_login_at' => $now,
                        'login_count' => 1,
                        'meta' => ['created_via' => $driverName],
                    ]);
                } else {
                    // Existing user - update profile
                    $this->refreshUserProfile($user, $name, $email, $avatar);
                    $user->update([
                        'last_login_at' => $now,
                        'login_count' => ($user->login_count ?? 0) + 1,
                    ]);
                }

                // ✅ Store timezone in session
                TimezoneService::storeViewerTimezone($timezone);

                // Create OAuth identity
                OAuthIdentity::updateOrCreate(
                    [
                        'provider' => $driverName, 
                        'provider_user_id' => $providerUid
                    ],
                    [
                        'user_id' => $user->id,
                        'provider_username' => $nickname,
                        'avatar_url' => $avatar,
                        'access_token' => $social->token ?? null,
                        'refresh_token' => $social->refreshToken ?? null,
                        'token_expires_at' => isset($social->expiresIn) 
                            ? $now->copy()->addSeconds((int) $social->expiresIn) 
                            : null,
                        'provider_raw' => $social->user ?? [],
                    ]
                );

                return $user;
            });

            // Track device for OAuth login
            $this->deviceTracking->recordDevice($user, $request);

            // Login user
            Auth::login($user, true);
            $request->session()->regenerate();

            // Mark user as online after OAuth login
            $this->onlineStatus->markOnline($user);

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
        if ($configured) {
            return $configured;
        }
        
        return rtrim(config('app.url'), '/') . "/auth/{$driverName}/callback";
    }

    private function normalizedEmail(string $provider, SocialiteUser $s): ?string
    {
        $email = $s->getEmail();
        return (is_string($email) && $email !== '') ? strtolower(trim($email)) : null;
    }

    private function displayName(SocialiteUser $s): ?string
    {
        if ($name = $s->getName()) {
            return $name;
        }
        
        $raw = $s->user ?? [];
        
        if (isset($raw['name'])) {
            return $raw['name'];
        }
        
        $first = $raw['given_name'] ?? $raw['localizedFirstName'] ?? null;
        $last  = $raw['family_name'] ?? $raw['localizedLastName'] ?? null;
        
        return trim(($first ?: '') . ' ' . ($last ?: '')) ?: null;
    }

    private function refreshUserProfile(User $user, ?string $name, ?string $email, ?string $avatar): void
    {
        $changes = [];
        
        if (!$user->name && $name) {
            $changes['name'] = $name;
        }
        
        if (!$user->avatar_url && $avatar) {
            $changes['avatar_url'] = $avatar;
        }
        
        if (!$user->email_verified_at && $email && str_contains($email, '@')) {
            if (empty($user->email) || str_contains($user->email, '@users.noreply.local')) {
                $changes['email'] = strtolower($email);
            }
            $changes['email_verified_at'] = Carbon::now();
        }
        
        if ($changes) {
            $user->update($changes);
        }
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
}