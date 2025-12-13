<?php

namespace App\Providers;

use App\Listeners\LoginSuccessListener;
use App\Listeners\LogoutOtherDevicesListener;
use App\Listeners\LogoutSuccessListener;
use App\Models\Attachment;
use App\Models\Disposition;
use App\Models\Letter;
use App\Observers\AttachmentObserver;
use App\Observers\DispositionObserver;
use App\Observers\LetterObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure rate limiters
        $this->configureRateLimiting();
        // Register auth event listeners
        Event::listen(Login::class, LoginSuccessListener::class);
        Event::listen(Login::class, LogoutOtherDevicesListener::class);
        Event::listen(Logout::class, LogoutSuccessListener::class);

        // Register model observers for activity logging
        Letter::observe(LetterObserver::class);
        Disposition::observe(DispositionObserver::class);
        Attachment::observe(AttachmentObserver::class);
    }

    /**
     * Configure rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // API rate limiter - 60 requests per minute
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Notifications rate limiter - 30 requests per minute
        RateLimiter::for('notifications', function ($request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // File download rate limiter - 20 requests per minute
        RateLimiter::for('downloads', function ($request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });
    }
}
