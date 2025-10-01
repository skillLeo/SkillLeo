<?php

namespace App\Http\Controllers;

class MarketingController extends Controller
{
    /**
     * Show the comprehensive marketing home page
     */
    public function home()
    {
        return view('marketing.home');
    }

    /**
     * Show detailed features page
     */
    public function features()
    {
        return view('marketing.features');
    }

    /**
     * Show pricing plans page
     */
    public function pricing()
    {
        return view('marketing.pricing');
    }

    /**
     * Show about us page
     */
    public function about()
    {
        return view('marketing.about');
    }

    /**
     * Show contact page
     */
    public function contact()
    {
        return view('marketing.contact');
    }
}