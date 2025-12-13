<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\AuthenticateSession;

class CustomAuthenticateSession extends AuthenticateSession
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (AuthenticationException $e) {
            // Session was invalidated (kicked by another login)
            return redirect()->route('auth', ['reason' => 'session_expired']);
        }
    }
}
