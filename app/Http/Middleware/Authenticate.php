<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Check if user was previously authenticated (session expired)
        if ($request->hasSession() && $request->session()->has('_previous_user')) {
            $request->session()->flash('session_expired', true);
        } else {
            // Guest trying to access protected page
            $request->session()->flash('auth_required', true);
            $request->session()->flash('intended_url', $request->url());
        }

        return route('login');
    }
}
