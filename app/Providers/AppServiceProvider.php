<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ImageService as Singleton
        $this->app->singleton(\App\Services\ImageService::class, function ($app) {
            return new \App\Services\ImageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
        $this->registerPolicies(); // ← ADD THIS

        // Global View Composer for Settings (Short-lived cache for freshness)
        View::composer('*', function ($view) {
            $settings = \Illuminate\Support\Facades\Cache::remember('global_settings', 300, function () {
                return \App\Models\Setting::all()->pluck('value', 'key');
            });
            $view->with('settings', $settings);
        });
    }

    /**
     * Register Authorization Policies
     *
     * CRITICAL SECURITY: This prevents IDOR attacks on all resources
     */
    protected function registerPolicies(): void
    {
        Gate::policy(
            \App\Models\Mahasiswa::class,
            \App\Policies\MahasiswaPolicy::class
        );

        Gate::policy(
            \App\Models\User::class,
            \App\Policies\UserPolicy::class
        );

        Gate::policy(
            \App\Models\Kandidat::class,
            \App\Policies\KandidatPolicy::class
        );

        Gate::policy(
            \App\Models\Asset::class,
            \App\Policies\AssetPolicy::class
        );

        Gate::policy(
            \App\Models\Vote::class,
            \App\Policies\VotePolicy::class
        );

        // Also register for DpmVote (same policy as Vote)
        if (class_exists(\App\Models\DpmVote::class)) {
            Gate::policy(
                \App\Models\DpmVote::class,
                \App\Policies\VotePolicy::class
            );
        }

        // Log for debugging
        if (config('app.debug')) {
            Log::info('✅ Authorization Policies registered successfully');
        }
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        // Force HTTPS if APP_URL starts with https
        if (str_starts_with(config('app.url') ?? '', 'https://')) {
            URL::forceScheme('https');
        }

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(function () {
            return Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }

    protected function configureRateLimiting(): void
    {
        // 1. GLOBAL RATE LIMIT (Prevent Server Overload)
        // Adjust based on server capacity. 100 req/min per IP is reasonable for normal users.
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });

        // 2. API RATE LIMIT
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 3. LOGIN THROTTLE (Prevent Brute Force)
        // Very strict: 5 attempts per minute
        RateLimiter::for('login', function (Request $request) {
            // Relaxed for Campus NAT: 100 attempts per minute
            return Limit::perMinute(100)->by('login'.$request->ip());
        });

        // 4. CHECK DPT (Prevent Scraping & Brute Force)
        RateLimiter::for('check-dpt', function (Request $request) {
            // BYPASS IN TESTING MODE
            if (\App\Models\Setting::get('app_environment_mode') === 'testing') {
                return Limit::none();
            }
            // Strict Mode: Max 5 requests per minute per IP to prevent NIM scraping
            return Limit::perMinute(5)->by($request->ip());
        });

        // 5. VOTE SUBMISSION (Critical Security)
        // Prevent spam voting or vote manipulation attempts
        RateLimiter::for('vote', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
