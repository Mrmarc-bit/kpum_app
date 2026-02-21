<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsPanitia
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // For AJAX requests, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }
            return redirect('/');
        }

        // Allow Admin to access Panitia routes (Super User)
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        if (Auth::user()->role !== 'panitia') {
            // For AJAX requests, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Panitia or Admin role required.'
                ], 403);
            }
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return $next($request);
    }

    private function redirectBasedOnRole($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'mahasiswa' => redirect()->route('student.dashboard'),
            default => redirect('/'),
        };
    }
}
