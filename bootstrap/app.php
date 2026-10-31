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
        $middleware->validateCsrfTokens(except: ['kkiapay/webhook']);
        $middleware->prependToPriorityList(\Illuminate\Routing\Middleware\ThrottleRequests::class, \App\Http\Middleware\VerifyKkiapayWebhook::class);
        $middleware->prependToPriorityList(\Illuminate\Routing\Middleware\ThrottleRequests::class, \Illuminate\Routing\Middleware\ValidateSignature::class);
        $middleware->web(append: [
            \App\Http\Middleware\ProtectSensitiveResponses::class,
            \App\Http\Middleware\TrackVisite::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
