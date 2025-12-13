<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Clean up expired sessions daily at midnight
Schedule::command('session:gc')->daily()->at('00:00');

// Clear expired cache entries weekly
Schedule::command('cache:prune-stale-tags')->weekly();

// Clear old notifications (older than 30 days) - custom command
Schedule::call(function () {
    \App\Models\Notification::where('created_at', '<', now()->subDays(30))
        ->where('is_read', true)
        ->delete();
})->weekly()->sundays()->at('01:00');
