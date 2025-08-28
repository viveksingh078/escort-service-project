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
        // Register all middleware aliases in a single call
        $middleware->alias([
            'admin.guest' => \App\Http\Middleware\AdminRedirect::class,
            'admin.auth'  => \App\Http\Middleware\AdminAuthenticate::class,
            'fan.guest'   => \App\Http\Middleware\FanRedirect::class,
            'fan.auth'    => \App\Http\Middleware\FanAuthenticate::class,
            'escort.guest' => \App\Http\Middleware\EscortRedirect::class,
            'escort.auth'  => \App\Http\Middleware\EscortAuthenticate::class,
        ]);

        // Use this for default guard redirect only
        $middleware->redirectTo(
            guests: '/login',
            users: '/dashboard'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
