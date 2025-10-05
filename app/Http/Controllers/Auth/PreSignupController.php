<?php
// ===========================================================================
// PreSignupController.php - Email Registration with Link
// ===========================================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SignupLinkMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, URL, Mail, DB, Log};
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class PreSignupController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function sendLink(Request $request)
    {
        Log::info('Registration started', ['email' => $request->email]);

        // Validate
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', Password::min(8)],
            'intent' => ['required', 'in:professional,client'],
        ]);

        $data['email'] = strtolower(trim($data['email']));

        // Check if user exists
        $existing = User::whereRaw('LOWER(email) = ?', [$data['email']])->first();

        if ($existing) {
            Log::info('User exists, redirecting to account-exists');
            
            $hasPassword = !empty($existing->password);
            $providers = method_exists($existing, 'oauthIdentities')
                ? $existing->oauthIdentities()->pluck('provider')->toArray()
                : [];

            return redirect()->route('register.existing', [
                'email' => $data['email'],
                'masked' => $this->maskEmail($data['email']),
                'hasPassword' => $hasPassword ? '1' : '0',
                'providers' => implode(',', $providers),
            ])->withInput(['email' => $data['email']]);
        }

        // Create token and cache payload
        $token = (string) Str::uuid();

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // plain, will be hashed by AuthService
            'intent' => $data['intent'],
            'tenant_name' => $data['name'],
            'ip' => $request->ip(),
            'ua' => (string) $request->userAgent(),
        ];

        Cache::put("signup:{$token}", $payload, now()->addMinutes(60));
        Log::info('Token cached', ['token' => $token]);

        // Create signed URL
        $url = URL::temporarySignedRoute(
            'register.confirm',
            now()->addMinutes(60),
            ['token' => $token]
        );

        // Send email
        Mail::to($payload['email'])->send(new SignupLinkMail($payload['name'], $url));
        Log::info('Registration email sent');

        // Store in session
        $request->session()->put('signup_token', $token);
        $request->session()->put('signup_email', $payload['email']);

        return redirect()
            ->route('verification.notice')
            ->with('status', 'We emailed you a secure link to create your account.');
    }

    public function confirm(Request $request, string $token)
    {
        $cacheKey = "signup:{$token}";
        $payload = Cache::pull($cacheKey);

        if (!$payload) {
            return redirect('/login')->withErrors(['link' => 'This link is invalid or has expired.']);
        }

        // Check if user already exists
        if (User::where('email', $payload['email'])->exists()) {
            return redirect('/login')->with('status', 'Your account is already set up. Please sign in.');
        }

        // Create user
        $user = DB::transaction(function () use ($payload) {
            $user = $this->authService->registerEmail([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'password' => $payload['password'],
                'intent' => $payload['intent'],
                'tenant_name' => $payload['tenant_name'],
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();

            return $user;
        });

        // Login user
        Auth::login($user, true);
        $this->authService->recordLogin(
            $user,
            $payload['ip'] ?? $request->ip(),
            $payload['ua'] ?? $request->userAgent()
        );

        return redirect('/account-type')->with('status', 'Your account is ready. Welcome!');
    }

    private function maskEmail(string $email): string
    {
        if (!str_contains($email, '@')) return $email;
        [$local, $domain] = explode('@', $email, 2);
        $localMasked = strlen($local) <= 2
            ? substr($local, 0, 1) . str_repeat('*', max(0, strlen($local) - 1))
            : substr($local, 0, 1) . str_repeat('*', strlen($local) - 2) . substr($local, -1);
        return $localMasked . '@' . $domain;
    }
}
