<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function accountType(Request $request)
    {
        $user = $request->user();

        if ($user && $user->intent) {
            return $this->redirectBasedOnIntent($user);
        }

        return view('auth.account-type');
    }

    public function setAccountType(Request $request)
    {
        $validated = $request->validate([
            'intent' => ['required', 'in:professional,client'],
        ]);

        $user = $request->user();
        $user->update(['intent' => $validated['intent']]);

        if ($validated['intent'] === 'professional') {
            return redirect()->route('tenant.onboarding.welcome')
                ->with('success', 'Welcome! Let\'s set up your professional profile.');
        } else {
            return redirect()->route('client.onboarding.info')
                ->with('success', 'Welcome! Let\'s set up your client profile.');
        }
    }

    protected function redirectBasedOnIntent($user)
    {
        if (!$user->is_profile_complete) {
            if ($user->intent === 'professional') {
                return redirect()->route('tenant.onboarding.welcome');
            } elseif ($user->intent === 'client') {
                return redirect()->route('client.onboarding.info');
            }
        }

        return redirect($user->getDashboardRoute());
    }
}
