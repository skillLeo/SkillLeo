<?php
// app/Http/Controllers/Auth/GatewayController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class GatewayController extends Controller
{
    public function accountType()
    {
        return view('auth.account-type');
    }

    public function otp()
    {
        return view('auth.otp');
    }
}
