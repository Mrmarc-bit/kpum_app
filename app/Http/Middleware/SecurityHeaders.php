<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy - Enhanced
        // Content Security Policy
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "base-uri 'self'; " .
            "object-src 'none'; " .
            "form-action 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://static.cloudflareinsights.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https://grainy-gradients.vercel.app; " .
            "media-src 'self'; " .
            "connect-src 'self' https://unpkg.com https://cloudflareinsights.com; " .
            "frame-src 'self' https://www.google.com; " .
            "frame-ancestors 'self'; " .
            "upgrade-insecure-requests;"
        );

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy (Allow camera for scanner feature)
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(), payment=(), usb=()');

        // HSTS (Strict-Transport-Security) - 1 Year
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // X-XSS-Protection (Legacy but good defense in depth)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Remove X-Powered-By to hide PHP version
        // Note: This might not work if the server overrides it, but we try anyway.
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
