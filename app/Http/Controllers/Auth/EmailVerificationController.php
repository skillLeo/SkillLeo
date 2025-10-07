<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthRedirectService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(protected AuthRedirectService $redirects) {}

    public function notice(Request $request)
    {
        $email = $request->session()->get('verify_email');
        return view('auth.verify-notice', compact('email'));
    }

    public function verify(Request $request, string $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()->to($this->redirects->url($user))
            ->with('status', 'Welcome! Please complete your onboarding.');
    }
}
