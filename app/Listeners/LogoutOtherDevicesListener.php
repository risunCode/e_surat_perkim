<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class LogoutOtherDevicesListener
{
    /**
     * Handle the event.
     * This runs after successful login to kick other sessions.
     */
    public function handle(Login $event): void
    {
        // Get current session ID after Laravel has set it up
        $currentSessionId = session()->getId();
        
        // Delete all other sessions for this user
        DB::table('sessions')
            ->where('user_id', $event->user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }
}
