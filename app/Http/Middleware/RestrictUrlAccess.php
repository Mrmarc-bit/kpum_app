<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RestrictUrlAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek multi-guard: web (Admin/Panitia) dan mahasiswa (Mahasiswa)
        $user = Auth::guard('web')->user();
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $path = $request->path();
        $role = $user ? $user->role : ($mahasiswa ? 'mahasiswa' : 'GUEST');

        // ====================================================
        // 1. BELUM LOGIN (GUEST)
        // ====================================================
        if (!$user && !$mahasiswa) {
            // URL yang DIPERBOLEHKAN untuk Guest
            if (
                $request->is('/') ||
                $request->is('login') ||
                $request->is('login-mahasiswa') ||
                $request->is('livewire/*') ||
                $request->is('register') ||
                $request->is('assets/*') ||
                $request->is('storage/*') ||
                $request->is('build/*') ||
                $request->is('_debugbar/*') ||
                $request->is('maintenance') ||
                $request->is('up') ||
                $request->is('api/*') || // API routes (untuk Quick Count, dll)
                $request->is('quick-count') || // Quick Count page
                $request->is('cek-dpt') ||
                $request->is('cek-dpt/*') ||
                $request->is('privacy-policy') ||
                $request->is('terms-of-service') ||
                $request->is('contact-support') ||
                $request->is('documentation') ||
                $request->is('sitemap*') ||
                $request->is('robots*')
            ) {
                return $next($request);
            }

            Log::info('Guest accessing restricted URL, redirect to /');
            return redirect('/');
        }

        // ====================================================
        // 2. SUDAH LOGIN (USER)
        // ====================================================

        // A. ADMIN & SUPER ADMIN
        if ($role === 'admin' || $role === 'super_admin') {
            if ($request->is('panitia/*') || $request->is('bilik-suara*')) {
                Log::info('Admin accessing forbidden area, redirect dashboard');
                return redirect()->route('admin.dashboard');
            }

            if ($request->is('login') || $request->is('/')) {
                Log::info('Admin at login/home, redirect dashboard');
                return redirect()->route('admin.dashboard');
            }

            return $next($request);
        }

        // B. PANITIA
        if ($role === 'panitia') {
            if (!$request->is('panitia*') && !$this->isCommonUrl($request)) {
                Log::info('Panitia forbidden, redirect dashboard');
                return redirect()->route('panitia.dashboard');
            }

            if ($request->is('login') || $request->is('/')) {
                Log::info('Panitia at login/home, redirect dashboard');
                return redirect()->route('panitia.dashboard');
            }

            return $next($request);
        }

        // C. KPPS
        if ($role === 'kpps') {
            if (!$request->is('kpps*') && !$this->isCommonUrl($request)) {
                Log::info('KPPS forbidden, redirect dashboard');
                return redirect()->route('kpps.dashboard');
            }

            if ($request->is('login') || $request->is('/')) {
                Log::info('KPPS at login/home, redirect dashboard');
                return redirect()->route('kpps.dashboard');
            }

            return $next($request);
        }

        // C. MAHASISWA
        if ($mahasiswa) {
            if ((!$request->is('bilik-suara*') && !$request->is('vote') && !$request->is('bukti-pilih*') && !$request->is('login-mahasiswa')) && !$this->isCommonUrl($request)) {
                Log::info('Mahasiswa forbidden, redirect dashboard');
                return redirect()->route('student.dashboard');
            }

            // Allow mahasiswa to access landing page and login page
            // (e.g., after logout they should be able to see homepage)
            return $next($request);
        }

        // Default fail-safe
        Log::warning('Unknown role, logging out');
        Auth::logout();
        return redirect('/');
    }

    /**
     * URL umum yang boleh diakses semua user terautentikasi
     */
    private function isCommonUrl(Request $request): bool
    {
        return $request->is('logout') ||
            $request->is('logout-mahasiswa') ||
            $request->is('user/profile-information') ||
            $request->is('sanctum/*') ||
            $request->is('livewire/*') ||
            $request->is('storage/*') ||
            $request->is('assets/*') ||
            $request->is('build/*') ||
            $request->is('privacy-policy') ||
            $request->is('terms-of-service') ||
            $request->is('contact-support') ||
            $request->is('documentation');
    }
}
