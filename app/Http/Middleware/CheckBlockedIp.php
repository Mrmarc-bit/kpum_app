<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check Maintenance/Testing Mode
        // If TESTING mode is active, bypass security checks for pentesting
        if (\App\Models\Setting::get('app_environment_mode') === 'testing') {
            return $next($request);
        }

        $ip = $request->ip();
        $blocked = \App\Models\BlockedIp::where('ip_address', $ip)->first();

        if ($blocked) {
            // Check if permanent block or temporary block is still active
            if ($blocked->is_permanent || ($blocked->blocked_until && now()->lessThan($blocked->blocked_until))) {
                
                // Logging the blocked attempt (optional, to see if they keep trying)
                \Illuminate\Support\Facades\Log::warning("Blocked IP detected: $ip - Reason: $blocked->reason");

                return response()->json([
                    'message' => 'Akses Anda diblokir sementara karena aktivitas mencurigakan. Silakan hubungi administrator jika ini kesalahan.',
                    'reason' => $blocked->reason
                ], 403);
            }
        }

        return $next($request);
    }
}
