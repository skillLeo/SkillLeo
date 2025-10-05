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

    public function submit(Request $request)
    {
        dd($request->password);

        $data = $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required', Password::min(8)],
            'intent'   => ['required','in:professional,client'],
        ]);

        // Create user (+ optional tenant if professional)
        $user = $this->authService->registerEmail([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'intent'      => $data['intent'],
            'tenant_name' => $data['name'],
        ]);

        // Send verification link
        $user->notify(new VerifyEmailLink());

        // Store id in session for resend page UX
        $request->session()->put('verify_user_id', $user->id);
        $request->session()->put('verify_email', $user->email);

        // Show “check your inbox”
        return redirect()->route('verification.notice')
            ->with('status', 'We sent a verification link to your email.');
    }

    public function register(Request $request)
    {
        return view('auth.register');
    }
}
