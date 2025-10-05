<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SignupLinkMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, URL, Mail, Hash, DB};
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PreSignupController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    // Step 1: handle form, stash payload in cache, email signed link
    public function sendLink(Request $request)
    {
        Log::info('=== REGISTRATION START ===', ['email' => $request->email]);
    
        try {
            // 1) Validate
            $data = $request->validate([
                'name'     => ['required','string','max:120'],
                'email'    => ['required','email','max:255'],
                'password' => ['required', Password::min(8)],
                'intent'   => ['required','in:professional,client'],
            ]);
    
            \Log::info('Validation passed', ['email' => $data['email']]);
    
            $data['email'] = strtolower(trim($data['email']));
    
            // 2) Check existing user
            $existing = User::withoutGlobalScopes()
                ->whereRaw('LOWER(email) = ?', [$data['email']])
                ->first();
    
            if ($existing) {
                \Log::info('User exists, redirecting to account-exists');
                
                $hasPassword = !empty($existing->password);
                $providers = method_exists($existing, 'oauthIdentities')
                    ? $existing->oauthIdentities()->pluck('provider')->toArray()
                    : [];
                    
                if (!$hasPassword && empty($providers)) {
                    $providers = ['google','linkedin','github'];
                }
    
                return redirect()->route('register.existing', [
                    'email' => $data['email'],
                    'masked' => $this->maskEmail($data['email']),
                    'hasPassword' => $hasPassword ? '1' : '0',
                    'providers' => implode(',', $providers),
                ])->withInput(['email' => $data['email']]);
            }
    
            // 3) Create token
            $token = (string) Str::uuid();
            \Log::info('Token created', ['token' => $token]);
    
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'intent' => $data['intent'],
                'tenant_name' => $data['name'],
                'ip' => $request->ip(),
                'ua' => (string) $request->userAgent(),
            ];
    
            // 4) Store in cache
            $cacheKey = "signup:{$token}";
            Cache::put($cacheKey, $payload, now()->addMinutes(60));
            
            // Verify cache worked
            $cached = Cache::get($cacheKey);
            if (!$cached) {
                \Log::error('CACHE FAILED - payload not stored!');
                throw new \Exception('Cache storage failed');
            }
            \Log::info('Cache stored successfully');
    
            // 5) Create signed URL
            $url = URL::temporarySignedRoute(
                'register.confirm',
                now()->addMinutes(60),
                ['token' => $token]
            );
            \Log::info('Signed URL created', ['url' => $url]);
    
            // 6) Send email
            Mail::to($payload['email'])->send(new SignupLinkMail($payload['name'], $url));
            \Log::info('Email sent successfully');
    
            // 7) Store in session
            $request->session()->put('signup_token', $token);
            $request->session()->put('signup_email', $payload['email']);
    
            Log::info('=== REGISTRATION COMPLETE ===');
    
            return redirect()
                ->route('verification.notice')
                ->with('status', 'We emailed you a secure link to create your account.');
    
        } catch (\Exception $e) {
            Log::error('REGISTRATION ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withErrors(['email' => 'Registration failed. Please try again.'])
                ->withInput();
        }
    }

/**
 * Mask an email like c*****@gmail.com
 */
private function maskEmail(string $email): string
{
    [$local, $domain] = explode('@', $email, 2);
    if (strlen($local) <= 2) {
        $localMasked = substr($local, 0, 1) . str_repeat('*', max(0, strlen($local)-1));
    } else {
        $localMasked = substr($local, 0, 1) . str_repeat('*', strlen($local)-2) . substr($local, -1);
    }
    return $localMasked . '@' . $domain;
}


    // Optional resend (generates a fresh token & link)
    public function resend(Request $request)
    {
        $email = $request->session()->get('signup_email');
        if (! $email) {
            return back()->withErrors(['email' => 'Session expired. Please fill the form again.']);
        }

        return back()->withErrors(['email' => 'Please re-submit the signup form to resend the link.']);
    }

    // Step 2: user clicks link -> create the account
    public function confirm(Request $request, string $token)
    {
        $cacheKey = "signup:{$token}";
        $payload = Cache::pull($cacheKey); // pull deletes on read

        if (! $payload) {
            return redirect('/')->withErrors(['link' => 'This link is invalid or has expired.']);
        }

        // Safety: do not allow duplicates even if two clicks race
        if (User::where('email', $payload['email'])->exists()) {
            return redirect('/')->with('status', 'Your account is already set up. Please sign in.');
        }

        // Create the user now, verified immediately, inside a transaction
        $user = DB::transaction(function () use ($payload) {
            // AuthService will hash the password - pass plain password
            $user = $this->authService->registerEmail([
                'name'        => $payload['name'],
                'email'       => $payload['email'],
                'password'    => $payload['password'], // ✅ Plain password passed to service
                'intent'      => $payload['intent'],
                'tenant_name' => $payload['tenant_name'],
            ]);

            // Mark email as verified at creation time
            $user->forceFill(['email_verified_at' => now()])->save();

            return $user;
        });

        // Log them in and record the device
        Auth::login($user, true);
        $this->authService->recordLogin(
            $user,
            $payload['ip'] ?? $request->ip(),
            $payload['ua'] ?? $request->userAgent()
        );

        return redirect(
            $user->is_profile_complete ? '/'.($user->username ?? 'dashboard') : 'account-type'
        )->with('status', 'Your account is ready. Welcome!');
    }













    
}