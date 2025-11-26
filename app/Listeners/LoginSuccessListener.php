<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LoginSuccessListener
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Set session flag for login success alert
        session()->flash('login_success', true);
        
        // Store user marker for session expiry detection
        session()->put('_previous_user', $event->user->id);
    }
}
