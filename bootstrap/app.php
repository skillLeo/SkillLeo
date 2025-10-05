<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'profile.complete' => \App\Http\Middleware\EnsureProfileComplete::class,
        ]);

        // Redirect unauthenticated users to login
        $middleware->redirectGuestsTo('/login');
        
        // CRITICAL FIX: Don't use redirectUsersTo at all
        // Let the routes and their middleware handle redirects naturally
        // This was causing premature redirects during registration/OAuth
        
        // Priority middleware (execution order)
        $middleware->priority([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
        ]);

        // Throttle middleware defaults
        $middleware->throttleApi();
        
        // Global web middleware
        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();