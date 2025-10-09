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
        $middleware->alias([
            'tenant'              => \App\Http\Middleware\EnsureTenant::class,
            'client'              => \App\Http\Middleware\EnsureClient::class,
            'guest.only'          => \App\Http\Middleware\GuestOnly::class,
            'account.type'        => \App\Http\Middleware\EnsureAccountTypeSelected::class,
            'role'                => \App\Http\Middleware\RoleMiddleware::class,
            'onboarding'          => \App\Http\Middleware\OnboardingGate::class,
            'onboarding.post'     => \App\Http\Middleware\OnboardingPostGate::class,
            
            // 🔥 Device & Online Status Tracking
            'track.device.activity' => \App\Http\Middleware\TrackDeviceActivity::class,
            'track.online.status'   => \App\Http\Middleware\TrackOnlineStatus::class,
        ]);

        $middleware->redirectGuestsTo('/auth/login');

        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            
            // 🔥 OPTION 1: Apply globally to all authenticated web routes (Recommended)
            \App\Http\Middleware\TrackOnlineStatus::class,
            
            // 🔥 OPTION 2: Or use selectively in route groups (comment above, use in routes)
            // \App\Http\Middleware\TrackDeviceActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();