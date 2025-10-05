<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected OtpService $otp
    ) {}

    // GET /login
    public function create() { return view('auth.login'); }

    // POST /login
    public function store(Request $request)
    {
        $data = $request->validate([
            'website'  => ['nullable','size:0'],
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['sometimes','boolean'],
        ]);

        $email = strtolower(trim($data['email']));
        $user  = User::withoutGlobalScopes()->whereRaw('LOWER(email)=?',[$email])->first();

        if (!$user || !$user->password || !Hash::check($data['password'], $user->password)) {
            Log::notice('Login failed', ['email'=>$email, 'uid'=>$user?->id]);
            return back()->withErrors(['email'=>'Invalid credentials'])->withInput($request->except('password'));
        }

        // 2FA
        $challengeId = $this->otp->beginLogin($user, $request->session()->getId(), $request->ip(), (string)$request->userAgent());

        $request->session()->put('login.pending_user_id', $user->id);
        $request->session()->put('login.challenge_id',    $challengeId);
        $request->session()->put('login.remember',        (bool) ($data['remember'] ?? false));
        $request->session()->put('login.started_at',      now()->timestamp);

        return redirect()->route('otp.create', ['email'=>$user->email]);
    }

    // DELETE /login
    public function destroy(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()?->delete();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect()->route('login.create')->with('status','Logged out');
    }
}
