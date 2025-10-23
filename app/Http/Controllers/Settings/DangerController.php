<?php
// app/Http/Controllers/Settings/DangerController.php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DangerController extends Controller
{
    public function __construct(protected OtpService $otp) {}

    private function me(string $username): User
    {
        abort_unless(Auth::check(), 401);
        $user = User::where('username', $username)->firstOrFail();
        abort_unless($user->id === Auth::id(), 403);
        return $user;
    }

    private function requiresPassword(User $user): bool
    {
        // “Passwordless” if null/empty; then we skip password prompts.
        return filled($user->password);
    }

    public function danger($username)
    {
        $user = $this->me($username);
        return view('tenant.settings.danger', [
            'user'            => $user,
            'username'        => $username,
            'activeSection'   => 'danger',
            'requiresPassword'=> $this->requiresPassword($user),
        ]);
    }

    public function hibernate(Request $request, $username)
    {
        $user = $this->me($username);
        $needsPwd = $this->requiresPassword($user);

        // Validation depends on whether the user has a password
        $rules = ['confirm' => ['accepted']];
        if ($needsPwd) {
            $rules['password'] = ['required', 'string'];
        }
        $data = $request->validate($rules);

        if ($needsPwd && !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        // Update exactly this user only
        $affected = DB::table('users')
            ->where('id', $user->id)
            ->limit(1)
            ->update([
                'is_active'     => 'hibernated',
                'hibernated_at' => now(),
                'updated_at'    => now(),
            ]);

        if ($affected !== 1) {
            return back()->withErrors(['general' => 'Could not hibernate account. Please try again.']);
        }

        // Revoke sessions/devices only for this user
        UserDevice::where('user_id', $user->id)->update(['revoked_at' => now(), 'is_trusted' => false]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('status', 'Account hibernated. Sign in anytime to reactivate.');
    }

    public function startDelete(Request $request, $username)
    {
        $user = $this->me($username);
        $needsPwd = $this->requiresPassword($user);

        $rules = [
            'phrase' => ['required', 'in:DELETE'],
        ];
        if ($needsPwd) {
            $rules['password'] = ['required', 'string'];
        }

        $data = $request->validate($rules);

        if ($needsPwd && !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        // Start a short OTP challenge for deletion
        $challenge = $this->otp->beginAction($user, $request->session()->getId(), 'account_delete', 300);

        return back()->with([
            'danger.challenge' => $challenge,
            'status' => 'We emailed a 6-digit code. Enter it below to confirm deletion.',
        ]);
    }

    public function confirmDelete(Request $request, $username)
    {
        $user = $this->me($username);

        $data = $request->validate([
            'challenge_id' => ['required', 'string'],
            'code'         => ['required', 'digits:6'],
        ]);

        $ok = $this->otp->verifyAction('account_delete', $data['challenge_id'], $data['code'], $request->session()->getId());
        if (!$ok) {
            return back()->withErrors(['code' => 'Invalid or expired code.'])->withInput();
        }

        $affected = DB::table('users')
            ->where('id', $user->id)
            ->limit(1)
            ->update([
                'is_active'             => 'pending_delete',
                'scheduled_deletion_at' => now()->addDays(30),
                'updated_at'            => now(),
            ]);

        if ($affected !== 1) {
            return back()->withErrors(['general' => 'Could not schedule deletion. Please try again.']);
        }

        UserDevice::where('user_id', $user->id)->update(['revoked_at' => now(), 'is_trusted' => false]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with(
            'status',
            'Your account is scheduled for deletion in 30 days. You can cancel before then.'
        );
    }

    public function cancelDeletion(Request $request, $username)
    {
        $user = $this->me($username);
        $needsPwd = $this->requiresPassword($user);

        $rules = [];
        if ($needsPwd) {
            $rules['password'] = ['required', 'string'];
        } else {
            $rules['confirm']  = ['accepted'];
        }
        $data = $request->validate($rules);

        if ($needsPwd && !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        $affected = DB::table('users')
            ->where('id', $user->id)
            ->limit(1)
            ->update([
                'is_active'             => 'active',
                'scheduled_deletion_at' => null,
                'updated_at'            => now(),
            ]);

        if ($affected !== 1) {
            return back()->withErrors(['general' => 'Could not cancel deletion. Please try again.']);
        }

        return redirect()->route('tenant.settings.danger', $user->username)
            ->with('status', 'Deletion cancelled. Your account is active again.');
    }
}
