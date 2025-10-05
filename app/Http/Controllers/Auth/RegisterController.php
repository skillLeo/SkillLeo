<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Notifications\VerifyEmailLink;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * Detect which OAuth providers this user has connected
     * Returns ONLY the providers actually linked to this account
     */
    private function detectProvidersForUser(User $user): array
    {
        $providers = [];

        // OPTION 1: If you store provider IDs directly on users table
        // Uncomment these if you have google_id, linkedin_id, github_id columns:
        /*
        if (!empty($user->google_id)) $providers[] = 'google';
        if (!empty($user->linkedin_id)) $providers[] = 'linkedin';
        if (!empty($user->github_id)) $providers[] = 'github';
        */

        // OPTION 2: If you have a separate oauth_identities table (RECOMMENDED)
        // This checks the oauthIdentities relationship
        if (method_exists($user, 'oauthIdentities')) {
            $linkedProviders = $user->oauthIdentities()
                ->whereIn('provider', ['google', 'linkedin', 'github'])
                ->pluck('provider')
                ->toArray();
            
            $providers = array_merge($providers, $linkedProviders);
        }

        // Remove duplicates and keep only valid providers
        $validProviders = ['google', 'linkedin', 'github'];
        $providers = array_values(array_unique(array_intersect($validProviders, $providers)));

        return $providers;
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:255'],
            'password' => ['required', Password::min(8)],
            'intent'   => ['required','in:professional,client'],
        ]);

        $email = strtolower(trim($data['email']));

        // Check if user already exists
        $existing = User::withoutGlobalScopes()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if ($existing) {
            $hasPassword = !empty($existing->password);
            $providers   = $this->detectProvidersForUser($existing);

            // CRITICAL LOGIC:
            // - If password exists → show ONLY password field (providers = [])
            // - If NO password → show ONLY the detected OAuth providers
            if ($hasPassword) {
                // User has password → ignore OAuth, show password login
                $providers = [];
            }
            // else: providers array already contains only linked providers

            return redirect()->route('register.existing', [
                'email'       => $email,
                'masked'      => $this->maskEmail($email),
                'hasPassword' => $hasPassword ? '1' : '0',
                'providers'   => implode(',', $providers), // e.g., 'google' or 'linkedin' or ''
            ])->withInput(['email' => $email]);
        }

        // If no existing user, proceed with registration
        // (Your original registration logic would go here)
        // For now, returning to show the flow is working
        return redirect()->route('register')
            ->withErrors(['email' => 'Registration logic not shown in this snippet']);
    }

    public function register(Request $request)
    {
        return view('auth.register');
    }

    public function existing(Request $request)
    {
        $email       = strtolower((string) $request->query('email'));
        $masked      = (string) ($request->query('masked') ?? $this->maskEmail($email));
        $hasPassword = (bool) $request->query('hasPassword', false);
        $providersParam = (string) $request->query('providers', '');
        
        // Convert comma-separated string to array and filter empty values
        $providers = array_filter(explode(',', $providersParam));

        // Double-check user still exists
        $user = User::withoutGlobalScopes()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Please try again.']);
        }

        return view('auth.account-exists', [
            'email'       => $email,
            'maskedEmail' => $masked,
            'hasPassword' => $hasPassword,
            'providers'   => $providers, // Array of only linked providers
        ]);
    }

    private function maskEmail(string $email): string
    {
        if (!str_contains($email, '@')) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);
        
        if (strlen($local) <= 2) {
            $localMasked = substr($local, 0, 1) . str_repeat('*', max(0, strlen($local) - 1));
        } else {
            $localMasked = substr($local, 0, 1) . str_repeat('*', strlen($local) - 2) . substr($local, -1);
        }
        
        return $localMasked . '@' . $domain;
    }
}