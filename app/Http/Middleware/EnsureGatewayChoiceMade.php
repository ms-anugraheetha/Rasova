<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGatewayChoiceMade
{
    /**
     * Blocks access to storefront routes until the visitor has either logged in,
     * registered, or explicitly chosen "Continue as Guest" (tracked via a
     * long-lived cookie). Logging out or the cookie expiring brings this
     * gateway back for the next visit.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() || $request->cookie('rasova_guest_mode')) {
            return $next($request);
        }

        return redirect()->route('gateway');
    }
}