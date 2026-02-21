<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // For AJAX requests, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }
            return redirect('/');
        }

        if (Auth::user()->role !== 'admin') {
            // For AJAX requests, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Access denied. Admin role required.'
                ], 403);
            }
            // Jika bukan admin coba akses halaman admin, kembalikan ke dashboard masing-masing
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return $next($request);
    }

    private function redirectBasedOnRole($role)
    {
        return match ($role) {
            'panitia' => redirect()->route('panitia.dashboard'),
            'mahasiswa' => redirect()->route('student.dashboard'),
            default => redirect('/'),
        };
    }
}
