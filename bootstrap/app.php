<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

return Application::configure(basePath: \dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(App\Http\Middleware\UseCloudflareConnectingIp::class);

        $middleware->trustProxies(
            at: '*',
            headers: SymfonyRequest::HEADER_X_FORWARDED_FOR
                     | SymfonyRequest::HEADER_X_FORWARDED_HOST
                     | SymfonyRequest::HEADER_X_FORWARDED_PORT
                     | SymfonyRequest::HEADER_X_FORWARDED_PROTO,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);
    })->create();
