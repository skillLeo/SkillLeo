<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Get the authenticated user or user by username
     */
    private function getUser($username)
    {
        if (Auth::check() && $username === Auth::user()->username) {
            return Auth::user()->load('profile');
        }
        
        return User::with('profile')->where('username', $username)->firstOrFail();
    }

    /**
     * Settings home - redirects to account
     */
    public function index($username)
    {
        $user = $this->getUser($username);
        return redirect()->route('tenant.settings.account', $username);
    }

    /**
     * Account settings page
     */
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

    /**
     * Security settings page
     */
  

    /**
     * Privacy settings page
     */
    public function privacy($username)
    {
        $user = $this->getUser($username);
        
        return view('tenant.settings.privacy', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'privacy'
        ]);
    }

    /**
     * Notifications settings page
     */
    public function notifications($username)
    {
        $user = $this->getUser($username);
        
        // Mock notification preferences
        $preferences = [
            'messages' => ['email' => true, 'push' => true, 'in_app' => true],
            'orders' => ['email' => true, 'push' => true, 'in_app' => true],
            'projects' => ['email' => true, 'push' => false, 'in_app' => true],
            'invoices' => ['email' => true, 'push' => false, 'in_app' => true],
            'security' => ['email' => true, 'push' => true, 'in_app' => true],
            'marketing' => ['email' => false, 'push' => false, 'in_app' => false]
        ];
        
        return view('tenant.settings.notifications', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'notifications',
            'preferences' => $preferences,
            'digestFrequency' => 'weekly'
        ]);
    }

    /**
     * Appearance settings page
     */
    public function appearance($username)
    {
        $user = $this->getUser($username);
        
        return view('tenant.settings.appearance', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'appearance',
            'currentTheme' => 'system',
            'currentDensity' => 'cozy',
            'currentFontSize' => 'medium',
            'reduceMotion' => false
        ]);
    }

    /**
     * Billing settings page
     */
    public function billing($username)
    {
        $user = $this->getUser($username);
        
        // Mock billing data
        $subscription = [
            'plan' => 'Pro Plan',
            'price' => 29,
            'interval' => 'month',
            'next_billing_date' => '2025-02-15',
            'status' => 'active'
        ];
        
        $paymentMethods = [
            [
                'id' => 1,
                'type' => 'card',
                'brand' => 'Visa',
                'last4' => '4242',
                'exp_month' => 12,
                'exp_year' => 2026,
                'is_default' => true
            ]
        ];
        
        $invoices = [
            ['date' => '2025-01-01', 'number' => '#1001', 'amount' => 29, 'status' => 'paid'],
            ['date' => '2024-12-01', 'number' => '#1000', 'amount' => 29, 'status' => 'paid'],
            ['date' => '2024-11-01', 'number' => '#0999', 'amount' => 29, 'status' => 'paid']
        ];
        
        return view('tenant.settings.billing', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'billing',
            'subscription' => $subscription,
            'paymentMethods' => $paymentMethods,
            'invoices' => $invoices
        ]);
    }

    /**
     * Data & Apps settings page
     */
    public function data($username)
    {
        $user = $this->getUser($username);
        
        // Mock connected apps
        $connectedApps = [
            [
                'name' => 'GitHub',
                'icon' => 'fab fa-github',
                'connected_at' => '2024-12-15',
                'permissions' => 'Read repositories, Create issues'
            ],
            [
                'name' => 'Stripe',
                'icon' => 'fab fa-stripe',
                'connected_at' => '2024-11-20',
                'permissions' => 'Process payments'
            ]
        ];
        
        return view('tenant.settings.data', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'data',
            'connectedApps' => $connectedApps
        ]);
    }

    /**
     * Advanced settings page
     */
    public function advanced($username)
    {
        $user = $this->getUser($username);
        
        return view('tenant.settings.advanced', [
            'user' => $user,
            'username' => $username,
            'activeSection' => 'advanced',
            'betaFeaturesEnabled' => false,
            'developerModeEnabled' => false
        ]);
    }


}