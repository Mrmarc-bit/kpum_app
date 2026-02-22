<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsKpps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check() || !in_array(Auth::guard('web')->user()->role, ['kpps', 'admin', 'super_admin'])) {
             // Jika admin mencoba akses, bolehkan? Biasanya admin bisa segalanya.
             // Tapi di sistem ini route group dipisah.
             // Kalau admin mau lihat dashboard kpps, biasanya admin punya dashboard sendiri.
             // Jadi reject aja.
             abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
