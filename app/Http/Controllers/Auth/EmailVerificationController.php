<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    public function notice(Request $request)
    {
        $email = $request->session()->get('verify_email');
        return view('auth.verify-notice', compact('email'));
    }

    public function verify(Request $request, string $id, string $hash)
    {
        $user = User::findOrFail($id);

        // Match hash
        if (! hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // Auto-login and redirect
        Auth::login($user, true);

        return redirect(
            $user->is_profile_complete ? '/'.($user->username ?? 'dashboard') : '/onboarding'
        )->with('status', 'Your email was verified. Welcome!');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('verify_user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            return redirect()->route('register')->withErrors([
                'email' => 'Session expired. Please sign up again.',
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('status', 'Already verified.');
        }

        $user->notify(new \App\Notifications\VerifyEmailLink());

        return back()->with('status', 'Verification email resent.');
    }
}
