<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsMahasiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('mahasiswa')->check()) {
            return redirect()->route('login.mahasiswa');
        }

        // If authenticated as mahasiswas, proceed.
        return $next($request);
    }

    private function redirectBasedOnRole($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'panitia' => redirect()->route('panitia.dashboard'),
            default => redirect('/'),
        };
    }
}
