<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (!\App\Models\Setting::isMaintenanceMode()) {
            return $next($request);
        }

        // Check if user is authenticated and has admin or panitia role FIRST
        if (auth()->check()) {
            $user = auth()->user();

            // Allow admin and panitia to bypass maintenance
            if (in_array($user->role, ['admin', 'super_admin', 'panitia'])) {
                return $next($request);
            }
        }

        // Allow access to maintenance page itself
        if ($request->is('maintenance')) {
            return $next($request);
        }

        // Allow access to ONLY admin and panitia login routes
        // BLOCKS student login (/login-mahasiswa, /bilik-suara, etc)
        if ($request->is('login') || $request->is('admin*') || $request->is('panitia*')) {
            return $next($request);
        }

        // Redirect all other users (including students) to maintenance page
        return redirect()->route('maintenance');
    }
}
