<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'set.locale' => SetLocale::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'graphql',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Handle unauthenticated requests globally
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => 'failed',
                'message' => 'You are not authorized to perform this action. Please login first.',
            ], 401);
        });
        //
    })->create();
