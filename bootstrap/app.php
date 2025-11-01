<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Sanctum middleware registration for API routes
         * This ensures Sanctum tokens are properly validated.
         */
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        // You can also add global middlewares if needed:
        // $middleware->web(prepend: [...]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
