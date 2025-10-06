<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    /**
     * Show the main landing page
     * Simple conversion-focused page with auth buttons
     */
    public function index()
    {
        return view('landing.index');
    }
    public function dashboard()
    {
        return view('auth.account-type');
    }
}