<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();

        // Custom Logout Response to redirect to home
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );

        // Custom Login Response
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        // Custom Authentication Logic - Smart Redirection for Students
        Fortify::authenticateUsing(function (Request $request) {
            // 1. Cek Admin/Panitia di tabel Users
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                
                // Audit Log: Panitia/Admin Login Success
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'user_name' => $user->name . ' (' . ucfirst($user->role) . ')',
                    'action' => 'LOGIN: SUCCESS',
                    'details' => ucfirst($user->role) . ' ' . $user->email . ' berhasil login.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return $user;
            }

            // 2. Cek apakah credentials cocok dengan Mahasiswa
            $mahasiswa = \App\Models\Mahasiswa::where('nim', $request->email)->first();
            if ($mahasiswa && \Illuminate\Support\Facades\Hash::check($request->password, $mahasiswa->password)) {
                // Audit Log: Student trying to login via Admin portal
                \App\Models\AuditLog::create([
                    'user_id' => null, // Guest context here
                    'user_name' => $mahasiswa->nama . ' (Mahasiswa)',
                    'action' => 'LOGIN: WRONG PORTAL',
                    'details' => 'Mahasiswa ' . $mahasiswa->nim . ' mencoba login via portal admin.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                // Jika kredensial mahasiswa valid, tolak dengan pesan guidance
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Akun Anda terdeteksi sebagai Mahasiswa. Silakan gunakan tombol "Masuk & Vote" di halaman depan untuk login dan memberikan suara Anda.'],
                ]);
            }

            // 3. Gagal authentication (Failed Login)
            
            // --- AUTO BLOCK LOGIC ---
            $ip = $request->ip();
            $blockRecord = \App\Models\BlockedIp::firstOrCreate(
                ['ip_address' => $ip],
                ['attempts' => 0]
            );
            
            // Increment attempts
            $blockRecord->increment('attempts');
            
            // Check threshold (e.g., 5 attempts)
            if ($blockRecord->attempts >= 5 && !app()->environment('local', 'testing')) {
                // Block for 30 minutes
                $blockRecord->update([
                    'blocked_until' => now()->addMinutes(30),
                    'reason' => 'Auto-blocked: Too many failed login attempts.'
                ]);

                // Audit Log: System blocked IP
                \App\Models\AuditLog::create([
                    'user_id' => null,
                    'user_name' => 'SYSTEM DEFENSE',
                    'action' => 'IP BLOCKED',
                    'details' => "IP $ip diblokir otomatis selama 30 menit (5x gagal login).",
                    'ip_address' => $ip,
                    'user_agent' => 'System'
                ]);
            }
            // ------------------------

            // Audit Log Failure
            \App\Models\AuditLog::create([
                'user_id' => null,
                'user_name' => 'Guest - System',
                'action' => 'LOGIN: FAILED (Admin/Panitia)',
                'details' => "Percobaan login gagal ($blockRecord->attempts x) untuk email: " . $request->email,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent()
            ]);

            return null;
        });
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn() => view('pages::auth.login'));
        Fortify::verifyEmailView(fn() => view('pages::auth.verify-email'));
        Fortify::twoFactorChallengeView(fn() => view('pages::auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn() => view('pages::auth.confirm-password'));
        Fortify::registerView(fn() => view('pages::auth.register'));
        Fortify::resetPasswordView(fn() => view('pages::auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn() => view('pages::auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('login-mahasiswa', function (Request $request) {
            // Gunakan 'nim' sebagai key limiter tanpa melihat IP agar di kampus/NAT tidak konflik
            $throttleKey = Str::transliterate(Str::lower($request->input('nim')));

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('verification', function (Request $request) {
            return Limit::perMinutes(10, 3)->by($request->ip());
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            return Limit::perMinutes(10, 3)->by($request->ip());
        });
    }
}
