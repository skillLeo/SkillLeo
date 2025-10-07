<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthRedirectService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestOnly
{
    public function __construct(protected AuthRedirectService $redirects) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // If user is already logged in, go to intended or the correct URL for their stage.
            return $this->redirects->intendedResponse(Auth::user());
        }

        return $next($request);
    }
}
