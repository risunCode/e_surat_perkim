<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'active' => \App\Http\Middleware\CheckUserActive::class,
            'throttle.login' => \App\Http\Middleware\ThrottleLogins::class,
        ]);

        // Global security middlewares
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SanitizeInput::class,
            \App\Http\Middleware\CheckUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle PostTooLargeException with user-friendly message
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'File terlalu besar. Total ukuran semua file tidak boleh lebih dari 15MB.',
                    'errors' => [
                        'attachments' => ['Total ukuran file melebihi batas maksimal 15MB. Coba kurangi jumlah atau ukuran file.']
                    ]
                ], 413);
            }
            
            return back()->withErrors([
                'attachments' => 'Total ukuran file terlalu besar (maks 15MB). Coba kurangi jumlah atau ukuran file.'
            ])->withInput();
        });
    })->create();
