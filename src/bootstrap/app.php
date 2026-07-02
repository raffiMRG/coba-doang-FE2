<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.backend' => \App\Http\Middleware\EnsureBackendAuthenticated::class,
        ]);

        // nginx is a local, trusted process (not a third-party proxy) that
        // always sets X-Forwarded-Proto/Port itself (see nginx map blocks),
        // so trusting it for every request lets Laravel generate correct
        // absolute/asset URLs across all three access paths (LAN, Tailscale,
        // public Cloudflare Tunnel domain) without a hardcoded APP_URL.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
