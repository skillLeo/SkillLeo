<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

final class OAuthController extends Controller
{
    private function scopes(string $provider): array
    {
        return match ($provider) {
            'google'   => ['openid', 'email', 'profile'],
            'linkedin' => ['openid', 'profile', 'email'],
            'github'   => [],
            default    => [],
        };
    }

    private function driver(string $provider)
    {
        if ($provider === 'linkedin') {
            $driverName = 'linkedin-openid';
            $redirect   = config('services.linkedin-openid.redirect');
        } else {
            $driverName = $provider;
            $redirect   = config("services.{$provider}.redirect");
        }

        $driver = Socialite::driver($driverName)->redirectUrl($redirect);
        if ($provider === 'linkedin') {
            $driver->with(['redirect_uri' => $redirect]);
        }

        $scopes = $this->scopes($provider);
        if ($scopes) {
            $driver->scopes($scopes);
        }

        if (env('OAUTH_STATELESS', false)) {
            $driver->stateless();
        }
        
        return $driver;
    }

    public function redirect(string $provider): RedirectResponse
    {
        Log::info("=== OAUTH REDIRECT START ===", ['provider' => $provider]);
        
        abort_unless(in_array($provider, ['google','github','linkedin']), 404);
        
        try {
            $redirectUrl = $this->driver($provider)->redirect();
            Log::info("OAuth redirect successful", ['provider' => $provider]);
            return $redirectUrl;
        } catch (\Throwable $e) {
            Log::error("OAuth redirect error", [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->withErrors(['oauth' => "Unable to connect to ".ucfirst($provider)]);
        }
    }

    public function callback(string $provider, SocialAuthService $service)
    {
        Log::info("=== OAUTH CALLBACK START ===", [
            'provider' => $provider,
            'query_params' => request()->query()
        ]);
        
        abort_unless(in_array($provider, ['google','github','linkedin']), 404);

        try {
            // Get user from provider
            $pUser = $this->driver($provider)->user();
            $uid = (string) $pUser->getId();
            $email = strtolower($pUser->getEmail() ?? '');
            
            Log::info("Provider user retrieved", [
                'provider' => $provider,
                'uid' => $uid,
                'email' => $email
            ]);

            // 1) Check if identity already linked
            if ($identity = $service->findIdentity($provider, $uid)) {
                Log::info("Identity found, logging in user", ['user_id' => $identity->user->id]);
                Auth::login($identity->user, true);
                return redirect()->intended('/dashboard');
            }

            // 2) Check if in linking mode (user wants to connect this provider)
            if (Auth::check() && 
                session('oauth.mode') === 'link' && 
                session('oauth.link.user_id') === auth()->id()) {
                
                Log::info("Linking mode detected", ['user_id' => auth()->id()]);
                $service->linkIdentityToUser(auth()->user(), $provider, $pUser);
                session()->forget(['oauth.mode','oauth.link.user_id']);
                return redirect()->route('settings.connected-accounts')
                    ->with('status', ucfirst($provider).' connected.');
            }

            // 3) Check if email matches existing account
            if ($email && ($existing = User::whereRaw('LOWER(email)=?',[$email])->first())) {
                Log::info("Email matches existing user", [
                    'email' => $email,
                    'user_id' => $existing->id,
                    'has_password' => !empty($existing->password)
                ]);
                
                // Show account exists page
                return redirect()->route('register.existing', [
                    'email' => $email,
                    'masked' => $this->maskEmail($email),
                    'hasPassword' => $existing->password ? '1' : '0',
                    'providers' => implode(',', $service->detectProvidersForUser($existing)),
                ])->with('status', 'To connect '.ucfirst($provider).', please sign in first, then link it from Settings.');
            }

            // 4) Brand new user - create account
            Log::info("Creating new user from OAuth", [
                'provider' => $provider,
                'email' => $email
            ]);
            
            $user = $service->createUserWithIdentity($provider, $pUser);
            
            if (!$user->email_verified_at && $email) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
            
            Log::info("New user created, logging in", ['user_id' => $user->id]);
            Auth::login($user, true);
            
            return redirect('/account-type');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::warning("Invalid OAuth state", [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            return redirect('/register')->withErrors(['oauth' => 'Session expired. Please try again.']);
            
        } catch (\Throwable $e) {
            Log::error("OAuth callback error", [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/register')->withErrors(['oauth' => 'Login failed. Please try again.']);
        }
    }

    private function maskEmail(string $email): string
    {
        if (!str_contains($email,'@')) return $email;
        [$l,$d] = explode('@',$email,2);
        $l = strlen($l)<=2 
            ? substr($l,0,1).str_repeat('*',max(0,strlen($l)-1))
            : substr($l,0,1).str_repeat('*',strlen($l)-2).substr($l,-1);
        return $l.'@'.$d;
    }
}