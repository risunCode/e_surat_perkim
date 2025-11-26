<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogoutSuccessListener
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        // Set session flag for logout success alert
        session()->flash('logged_out', true);
    }
}
