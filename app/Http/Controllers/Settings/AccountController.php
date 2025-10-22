<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccountController extends Controller
{

    private function getUser($username)
    {
        if (Auth::check() && $username === Auth::user()->username) {
            return Auth::user()->load('profile');
        }
        
        return User::with('profile')->where('username', $username)->firstOrFail();
    }

    public function account($username)
    {
        $user = $this->getUser($username);
        
        return view('tenant.settings.account', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'account',
            'twoFactorEnabled' => false,
            'trustedDevicesCount' => false,

        ]);
    }




}
