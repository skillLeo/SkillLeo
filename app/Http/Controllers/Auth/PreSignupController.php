<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SignupLinkMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PreSignupController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    // Step 1: handle form, stash payload in cache, email signed link
    public function sendLink(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:255'],
            'password' => ['required', Password::min(8)],
            'intent'   => ['required','in:professional,client'],
        ]);

        // Block if this email is already registered
        if (User::where('email', strtolower($data['email']))->exists()) {
            return back()->withErrors(['email' => 'This email is already registered.'])->withInput();
        }

        // Create a random signup token
        $token = (string) Str::uuid();

        // Hash the password NOW so we never keep a raw password anywhere
        $payload = [
            'name'        => $data['name'],
            'email'       => strtolower($data['email']),
            'password'    => Hash::make($data['password']),
            'intent'      => $data['intent'],
            'tenant_name' => $data['name'],
            'ip'          => $request->ip(),
            'ua'          => (string) $request->userAgent(),
        ];

        // Keep payload server-side in cache for 60 minutes
        Cache::put("signup:{$token}", $payload, now()->addMinutes(60));

        // Create a signed URL that expires in 60 minutes
        $url = URL::temporarySignedRoute(
            'register.confirm',
            now()->addMinutes(60),
            ['token' => $token]
        );

        // Email the link
        Mail::to($payload['email'])->send(new SignupLinkMail($payload['name'], $url));

        // Remember token/email in session for easy resend UX
        $request->session()->put('signup_token', $token);
        $request->session()->put('signup_email', $payload['email']);

        return redirect()->route('verification.notice') // show your “check your email” page
            ->with('status', 'We emailed you a secure link to create your account.');
    }

    // Optional resend (generates a fresh token & link)
    public function resend(Request $request)
    {
        $email = $request->session()->get('signup_email');
        if (! $email) {
            return back()->withErrors(['email' => 'Session expired. Please fill the form again.']);
        }

        // We don’t have the original name/intent/password here; ask user to re-enter OR
        // store a short recap in session when first submitting.
        // For simplicity, we ask them to re-submit the form:
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
            // Use your existing service to handle tenant creation + user fields
            $user = $this->authService->registerEmail([
                'name'        => $payload['name'],
                'email'       => $payload['email'],
                'password'    => $payload['password'], // already hashed
                'intent'      => $payload['intent'],
                'tenant_name' => $payload['tenant_name'],
            ]);

            // Mark email as verified at creation time
            $user->forceFill(['email_verified_at' => now()])->save();

            return $user;
        });

        // Log them in and record the device
        Auth::login($user, true);
        app(\App\Services\Auth\AuthService::class)->recordLogin(
            $user,
            $payload['ip'] ?? $request->ip(),
            $payload['ua'] ?? $request->userAgent()
        );

        return redirect(
            $user->is_profile_complete ? '/'.($user->username ?? 'dashboard') : 'tenant/onboarding/welcome'
        )->with('status', 'Your account is ready. Welcome!');
    }
}
