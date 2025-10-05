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

class PreSignupController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    // Step 1: handle form, stash payload in cache, email signed link
    public function sendLink(Request $request)
    {
        // 1) Validate FIRST
        $data = $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:255'],
            'password' => ['required', Password::min(8)],
            'intent'   => ['required','in:professional,client'],
        ]);

        $data['email'] = strtolower($data['email']);

        // 2) Block if email exists
        if (User::where('email', $data['email'])->exists()) {
            return back()->withErrors(['email' => 'This email is already registered.'])->withInput();
        }

        // 3) Create token + payload
        // IMPORTANT: Store PLAIN password in cache (will be hashed later in AuthService)
        $token = (string) Str::uuid();

        $payload = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => $data['password'], // ✅ Store plain password here
            'intent'      => $data['intent'],
            'tenant_name' => $data['name'],
            'ip'          => $request->ip(),
            'ua'          => (string) $request->userAgent(),
        ];

        // 4) Cache payload for 60 mins (server-side)
        Cache::put("signup:{$token}", $payload, now()->addMinutes(60));

        // 5) Signed URL valid for 60 mins
        $url = URL::temporarySignedRoute(
            'register.confirm',
            now()->addMinutes(60),
            ['token' => $token]
        );

        // 6) Email link
        Mail::to($payload['email'])->send(new SignupLinkMail($payload['name'], $url));

        // 7) Save for resend UX
        $request->session()->put('signup_token', $token);
        $request->session()->put('signup_email', $payload['email']);

        return redirect()
            ->route('verification.notice')
            ->with('status', 'We emailed you a secure link to create your account.');
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