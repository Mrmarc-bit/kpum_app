<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            '/logout',
            '/logout-mahasiswa',
        ]);
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('bilik-suara') || $request->is('vote')) {
                return route('login.mahasiswa');
            }
            return route('login');
        });

        // Force trust all proxies for HTTPS detection
        $middleware->trustProxies(at: '*');

        // 1. WEB MIDDLEWARE GROUP
        // Pastikan Session sudah start sebelum cek Auth
        $middleware->web(append: [
            \App\Http\Middleware\RestrictUrlAccess::class,
            \App\Http\Middleware\SanitizeQueryParameters::class,
            'throttle:global', // Global DDoS Protection (100 req/min)
        ]);

        // 2. Global maintenance mode check
        $middleware->append(\App\Http\Middleware\CheckMaintenanceMode::class);

        // 3. Global security headers
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->append(\App\Http\Middleware\CheckBlockedIp::class);

        // 4. REGISTER ALIASES: Untuk dipakai di routes/web.php
        $middleware->alias([
            'role.admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'role.panitia' => \App\Http\Middleware\EnsureUserIsPanitia::class,
            'role.mahasiswa' => \App\Http\Middleware\EnsureUserIsMahasiswa::class,
            'role.kpps' => \App\Http\Middleware\EnsureUserIsKpps::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
