<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index()
    {
        // Cache the entire dashboard data for 30 seconds to improve performance
        $data = Cache::remember('maintenance_dashboard_data', 30, function() {
            return [
                'mode' => Setting::get('app_environment_mode', 'production'),
                'health' => $this->checkHealth(),
                'security' => $this->checkSecurity(),
                'logs' => $this->getSystemLogs()
            ];
        });

        return view('admin.maintenance.index', [
            'title' => 'Pemeliharaan Situs & Keamanan',
            'mode' => $data['mode'],
            'health' => $data['health'],
            'security' => $data['security'],
            'logs' => $data['logs']
        ]);
    }

    public function updateMode(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:production,testing',
            'password' => 'required'
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah. Perubahan mode ditolak.');
        }

        Setting::set('app_environment_mode', $request->mode);

        // Log Critical Action
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'CHANGE SYSTEM MODE',
            'details' => "Mengubah mode sistem menjadi: " . strtoupper($request->mode),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Clear specific cache keys instead of flush to avoid session issues
        Cache::forget('global_settings');
        Cache::forget('app_settings'); // If used elsewhere

        $message = $request->mode === 'testing' 
            ? '⚠️ MODE TESTING AKTIF! Semua fitur keamanan dimatikan untuk pengujian OWASP.' 
            : '✅ MODE PRODUCTION AKTIF! Sistem kembali aman sepenuhnya.';

        return back()->with('success', $message);
    }

    public function clearLogs()
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        return back()->with('success', 'System Logs berhasil dibersihkan.');
    }

    public function optimize()
    {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return back()->with('success', 'Cache sistem berhasil dibersihkan.');
    }

    private function checkHealth()
    {
        $checks = [];

        // DB Connection
        try {
            DB::connection()->getPdo();
            $checks['database'] = ['status' => true, 'message' => 'Terhubung'];
        } catch (\Exception $e) {
            $checks['database'] = ['status' => false, 'message' => $e->getMessage()];
        }

        // Storage Write Permission (faster check using is_writable instead of actual write)
        $testPath = storage_path('framework');
        try {
            $writable = is_dir($testPath) && is_writable($testPath);
            $checks['storage'] = ['status' => $writable, 'message' => $writable ? 'Writable' : 'Not Writable!'];
        } catch (\Exception $e) {
            $checks['storage'] = ['status' => false, 'message' => 'Not Writable!'];
        }

        // Cache
        try {
            Cache::put('health_check', 'ok', 10);
            $val = Cache::get('health_check');
            $checks['cache'] = ['status' => $val === 'ok', 'message' => $val === 'ok' ? 'Berjalan' : 'Gagal'];
        } catch (\Exception $e) {
            $checks['cache'] = ['status' => false, 'message' => 'Error'];
        }

        return $checks;
    }

    private function checkSecurity()
    {
        return [
            'debug_mode' => (bool) config('app.debug'),
            'env' => app()->environment(),
            'force_https' => config('app.url') ? str_starts_with(config('app.url'), 'https') : false,
            'bcrypt_rounds' => config('hashing.bcrypt.rounds', 10)
        ];
    }

    private function getLogs()
    {
        $path = storage_path('logs/laravel.log');
        if (!File::exists($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_slice(array_reverse($lines), 0, 50); // Get last 50 lines
    }

    // Optimized log reader using tail command (much faster than fseek for large files)
    private function getSystemLogs()
    {
        $path = storage_path('logs/laravel.log');
        if (!File::exists($path)) return "Log file kosong atau tidak ditemukan.";

        // Use tail command for much faster reading (reads last 50 lines instantly)
        $output = [];
        exec("tail -n 50 " . escapeshellarg($path) . " 2>&1", $output);
        
        return implode("\n", $output);
    }
}
