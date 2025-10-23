<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ── Route middleware aliases ───────────────────────────────────────────────
        $middleware->alias([
            'tenant'                 => \App\Http\Middleware\EnsureTenant::class,
            'client'                 => \App\Http\Middleware\EnsureClient::class,
            'guest.only'             => \App\Http\Middleware\GuestOnly::class,
            'account.type'           => \App\Http\Middleware\EnsureAccountTypeSelected::class,
            'role'                   => \App\Http\Middleware\RoleMiddleware::class,
            'onboarding'             => \App\Http\Middleware\OnboardingGate::class,
            'onboarding.post'        => \App\Http\Middleware\OnboardingPostGate::class,
            'track.device.activity'  => \App\Http\Middleware\TrackDeviceActivity::class,
            'track.online.status'    => \App\Http\Middleware\TrackOnlineStatus::class,
                'device.active'        => \App\Http\Middleware\EnsureDeviceIsActive::class,
                'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,

            ]);

        // Where to send guests when "auth" middleware kicks in
        $middleware->redirectGuestsTo('/auth/login');

        // ── Web group (runs on every web request) ─────────────────────────────────
        // Put class names only (no key => value). Order: session auth, tracking, device gate.
        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\TrackOnlineStatus::class,
            \App\Http\Middleware\TrackDeviceActivity::class,
            \App\Http\Middleware\EnsureDeviceIsActive::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
