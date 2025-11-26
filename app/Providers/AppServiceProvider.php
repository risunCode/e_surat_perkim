<?php

namespace App\Providers;

use App\Listeners\LoginSuccessListener;
use App\Listeners\LogoutSuccessListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
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
        // Register auth event listeners
        Event::listen(Login::class, LoginSuccessListener::class);
        Event::listen(Logout::class, LogoutSuccessListener::class);
    }
}
