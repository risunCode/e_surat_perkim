<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecuritySetup
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->security_setup_completed) {
            // Allow access to security setup and logout routes
            if (!$request->routeIs('security.setup', 'security.store', 'logout')) {
                return redirect()->route('security.setup');
            }
        }

        return $next($request);
    }
}
