<?php
// app/Http/Controllers/Auth/OtpController.php
// app/Http/Controllers/Auth/OtpController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otp,
        protected AuthService $authService
    ) {}

    public function show(Request $request)
    {
        return view('auth.otp', [
            'email'    => $request->get('email'),
            'seconds'  => $this->otp->remainingSeconds((string) session('login.challenge_id')),
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate(['code' => ['required','digits:6']]);

        $challengeId = (string) $request->session()->get('login.challenge_id');
        $pendingId   = (int) $request->session()->get('login.pending_user_id');

        if (! $challengeId || ! $pendingId) {
            return back()->withErrors(['code' => 'Session expired. Please sign in again.']);
        }

        $ok = $this->otp->verify(
            $challengeId,
            $data['code'],
            $request->session()->getId(),
            $request->ip(),
            (string) $request->userAgent()
        );

        if (! $ok) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        $user = User::withoutGlobalScopes()->findOrFail($pendingId);
        $remember = (bool) session('login.remember');
        Auth::login($user, $remember);

        // optional login metadata
        $this->authService->recordLogin($user, $request->ip(), (string) $request->userAgent());

        // cleanup
        $request->session()->forget([
            'login.pending_user_id',
            'login.challenge_id',
            'login.remember',
            'login.started_at',
        ]);

        return redirect()->intended(route('tenant.profile'));
    }

    public function resend(Request $request)
    {
        $pendingId = (int) $request->session()->get('login.pending_user_id');
        $user = User::withoutGlobalScopes()->findOrFail($pendingId);
        $challengeId = $this->otp->resend($user, $request->session()->getId());
        $request->session()->put('login.challenge_id', $challengeId);

        return back()->with('status', 'New code sent.');
    }
}
