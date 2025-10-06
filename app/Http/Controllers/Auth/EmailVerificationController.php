<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (! hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        if ($user->account_status === 'pending_onboarding' || !$user->is_profile_complete==='start') {
            return redirect()->route('auth.account-type')->with('status', 'Welcome! Please complete your onboarding.');
        }
        
        return redirect()->intended(route('tenant.profile'));  
    }
}
