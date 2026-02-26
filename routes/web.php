<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Access Control System (REFACTORED):
| 1. GLOBAL: RestrictUrlAccess (Middleware) handles Guest vs Auth redirects
| 2. GROUPS: role.admin, role.panitia, role.mahasiswa
|
*/

// ============================================
// 1. PUBLIC ROUTES (Accessible by Guest)
// ============================================

// Landing Page
Route::get('/', function () {
    $kandidats = \App\Models\Kandidat::with('parties')->where('status_aktif', true)
        ->where('tampilkan_di_landing', true)
        ->orderBy('urutan_tampil')
        ->get();

    $calonDpms = \App\Models\CalonDpm::with('parties')->where('status_aktif', true)
        ->orderBy('urutan_tampil')
        ->get();

    $timelines = \App\Models\Timeline::orderBy('order')->orderBy('start_date')->get();
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    $parties = \App\Models\Party::all();

    return view('welcome', compact('kandidats', 'calonDpms', 'timelines', 'settings', 'parties'));
})->name('home');


// Calendar API
Route::get('/api/timelines', function () {
    return \App\Models\Timeline::orderBy('order')->orderBy('start_date')->get();
});

// Static Pages
Route::get('/privacy-policy', function () {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    return view('pages.privacy-policy', compact('settings'));
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    return view('pages.terms-of-service', compact('settings'));
})->name('terms-of-service');

Route::get('/contact-support', function () {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    return view('pages.contact-support', compact('settings'));
})->name('contact-support');

Route::get('/documentation', function () {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    return view('pages.documentation', compact('settings'));
})->name('documentation');

Route::get('/tentang-kpum', function () {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    return view('pages.about', compact('settings'));
})->name('about');

// Maintenance Page
Route::get('/maintenance', function () {
    return view('maintenance', [
        'message' => setting('maintenance_message', 'Website sedang dalam pemeliharaan sistem pemilu. Silakan kembali beberapa saat lagi.'),
        'endTime' => setting('maintenance_end_time')
    ]);
})->name('maintenance');

// Quick Count (Public if Enabled)
Route::get('/quick-count', [\App\Http\Controllers\QuickCountController::class, 'index'])->name('quick-count');
Route::get('/api/quick-count/data', [\App\Http\Controllers\QuickCountController::class, 'getData'])->name('api.quick-count.data');

// Cek DPT
Route::get('/cek-dpt', [\App\Http\Controllers\CheckDptController::class, 'index'])->name('check-dpt');
Route::post('/cek-dpt/search', [\App\Http\Controllers\CheckDptController::class, 'search'])
    ->middleware('throttle:check-dpt') // Rate limit dynamic based on mode
    ->name('check-dpt.search');


// ============================================
// 1.1 SITEMAP FOR SEO
// ============================================

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'lastmod' => now()->toAtomString(), 'priority' => '1.0'],
        ['loc' => route('check-dpt'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('quick-count'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('privacy-policy'), 'lastmod' => now()->toAtomString(), 'priority' => '0.3'],
        ['loc' => route('terms-of-service'), 'lastmod' => now()->toAtomString(), 'priority' => '0.3'],
        ['loc' => route('contact-support'), 'lastmod' => now()->toAtomString(), 'priority' => '0.5'],
        ['loc' => route('documentation'), 'lastmod' => now()->toAtomString(), 'priority' => '0.5'],
        ['loc' => route('about'), 'lastmod' => now()->toAtomString(), 'priority' => '0.7'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . $url['loc'] . '</loc>';
        $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
        $xml .= '<priority>' . $url['priority'] . '</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';

    return response($xml)->header('Content-Type', 'text/xml');
});


// ============================================
// 2. AUTHENTICATION ROUTES
// ============================================

// Student Auth (Using Standard Auth Controller or Custom if needed, but keeping it simple for now)
// We are using Fortify for standard login.
// If you have specific student login logic, keep it but ensure it uses Auth::login($user) from 'web' guard.
Route::get('/login-mahasiswa', [\App\Http\Controllers\Auth\StudentAuthController::class, 'showLoginForm'])->name('login.mahasiswa');

Route::middleware(['throttle:login-mahasiswa'])->group(function () {
    Route::post('/login-mahasiswa', [\App\Http\Controllers\Auth\StudentAuthController::class, 'login']);
});

// Logout handled by Fortify generally, but we keep this alias if old views use it
Route::match(['get', 'post'], '/logout-mahasiswa', [\App\Http\Controllers\Auth\StudentAuthController::class, 'logout'])->name('logout.mahasiswa');

// Fix for MethodNotAllowedHttpException: Allow GET logout (logs out both guards)
// SECURITY FIX: Changed from GET to POST to prevent CSRF attacks
// GET requests can be triggered by simply visiting a URL (e.g., <img src="/logout">)
// POST requires CSRF token, preventing unauthorized logout attacks
Route::match(['get', 'post'], '/logout', function () {
    \Illuminate\Support\Facades\Auth::guard('web')->logout();
    \Illuminate\Support\Facades\Auth::guard('mahasiswa')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
});


// ============================================
// 3. STUDENT ROUTES (Guard: mahasiswa)
// ============================================

Route::middleware(['role.mahasiswa'])->group(function () {


    // Voting Routes (portfolio view rendered automatically if voting hasn't started)
    Route::get('/bilik-suara', [\App\Http\Controllers\VoteController::class, 'index'])->name('student.dashboard');
    Route::get('/bilik-suara/kandidat/{kandidat}', [\App\Http\Controllers\VoteController::class, 'showKandidat'])->name('student.kandidat.show');

    // Apply strict throttling to voting action
    Route::post('/vote', [\App\Http\Controllers\VoteController::class, 'store'])
        ->middleware('throttle:vote')
        ->name('vote.store');

    Route::get('/bukti-pilih/download', [\App\Http\Controllers\ProofOfVoteController::class, 'download'])->name('vote.proof.download');
    Route::post('/bukti-pilih/email', [\App\Http\Controllers\VoteController::class, 'sendProofEmail'])->name('vote.proof.email');
});


// ============================================
// 4. ADMIN & PANITIA ROUTES (Guard: web)
// ============================================

Route::middleware([
    'auth:web',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Main Login Redirect
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        return match ($user->role) {
            'mahasiswa' => redirect()->route('student.dashboard'),
            'panitia' => redirect()->route('panitia.dashboard'),
            'admin', 'super_admin' => redirect()->route('admin.dashboard'),
            'kpps' => redirect()->route('kpps.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');





    // B. PANITIA ROUTES
    // --------------------------------------------
    Route::prefix('panitia')->name('panitia.')->middleware('role.panitia')->group(function () {
        Route::get('/', [\App\Http\Controllers\Panitia\DashboardController::class, 'index'])->name('dashboard');

        // Resources
        Route::resource('kandidat', \App\Http\Controllers\Panitia\KandidatController::class);
        // Additional DPT Routes (must be placed BEFORE resource to prevent route model binding conflict)
        Route::post('dpt/import', [\App\Http\Controllers\Panitia\DptController::class, 'import'])->name('dpt.import');
        Route::get('dpt/{mahasiswa}/download-letter', [\App\Http\Controllers\Panitia\DptController::class, 'downloadLetter'])->name('dpt.download-letter');
        Route::get('dpt/sample', [\App\Http\Controllers\Panitia\DptController::class, 'downloadSample'])->name('dpt.download-sample');
        Route::delete('dpt/destroy-all', [\App\Http\Controllers\Panitia\DptController::class, 'destroyAll'])->name('dpt.destroy-all');
        Route::get('dpt/batch/{batchId}', [\App\Http\Controllers\Panitia\DptController::class, 'batchStatus'])->name('dpt.batch.status');

        Route::resource('dpt', \App\Http\Controllers\Panitia\DptController::class)->parameters(['dpt' => 'mahasiswa']);
        Route::resource('timeline', \App\Http\Controllers\Panitia\TimelineController::class);
        Route::resource('calon_dpm', \App\Http\Controllers\Panitia\CalonDpmController::class);
        Route::resource('parties', \App\Http\Controllers\Panitia\PartyController::class);

        // Features

        Route::get('/analytics', [\App\Http\Controllers\Panitia\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/settings', [\App\Http\Controllers\Panitia\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Panitia\SettingsController::class, 'update'])->name('settings.update');
        // Letter Settings Group
        Route::get('/settings/letters/proof', [\App\Http\Controllers\Panitia\SettingsController::class, 'proof'])->name('settings.letters.proof');
        Route::post('/settings/letters/proof', [\App\Http\Controllers\Panitia\SettingsController::class, 'updateProof'])->name('settings.letters.proof.update');
        Route::get('/settings/letters/proof/sample', [\App\Http\Controllers\Panitia\SettingsController::class, 'downloadSampleProof'])->name('settings.letters.proof.sample');

        Route::get('/settings/letters/notification', [\App\Http\Controllers\Panitia\SettingsController::class, 'notification'])->name('settings.letters.notification');
        Route::post('/settings/letters/notification', [\App\Http\Controllers\Panitia\SettingsController::class, 'updateNotification'])->name('settings.letters.notification.update');
        Route::get('/settings/letters/notification/sample', [\App\Http\Controllers\Panitia\SettingsController::class, 'downloadSampleNotification'])->name('settings.letters.notification.sample');
        Route::post('/settings/letters/notification/clear-cache', [\App\Http\Controllers\Panitia\SettingsController::class, 'clearLetterCache'])->name('settings.letters.notification.clear-cache');
        Route::get('/audit', [\App\Http\Controllers\Panitia\AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/audit/print', [\App\Http\Controllers\Panitia\AuditLogController::class, 'print'])->name('audit.print');
        Route::delete('/audit/destroy', [\App\Http\Controllers\Panitia\AuditLogController::class, 'destroy'])->name('audit.destroy');
        Route::get('/reset', [\App\Http\Controllers\Panitia\ResetElectionController::class, 'index'])->name('reset.index');
        Route::post('/reset', [\App\Http\Controllers\Panitia\ResetElectionController::class, 'store'])->name('reset.store');

        // Reports Center (Panitia)
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/results', [\App\Http\Controllers\ReportController::class, 'downloadResults'])->name('reports.results');
        Route::get('/reports/ba', [\App\Http\Controllers\ReportController::class, 'downloadBeritaAcara'])->name('reports.ba');
        Route::get('/reports/audit', [\App\Http\Controllers\ReportController::class, 'downloadAuditLogs'])->name('reports.audit');

        // Asset Management (Panitia)
        Route::get('/assets', [\App\Http\Controllers\Panitia\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [\App\Http\Controllers\Panitia\AssetController::class, 'store'])->name('assets.store');
        Route::put('/assets/move/{asset}', [\App\Http\Controllers\Panitia\AssetController::class, 'move'])->name('assets.move');
        Route::put('/assets/{asset}/rename', [\App\Http\Controllers\Panitia\AssetController::class, 'rename'])->name('assets.rename');
        Route::delete('/assets/{asset}', [\App\Http\Controllers\Panitia\AssetController::class, 'destroy'])->name('assets.destroy');
        Route::post('/assets/folder', [\App\Http\Controllers\Panitia\AssetController::class, 'storeFolder'])->name('assets.folder.store');
        Route::put('/assets/folder/{folder}/rename', [\App\Http\Controllers\Panitia\AssetController::class, 'renameFolder'])->name('assets.folder.rename');
        Route::delete('/assets/folder/{folder}', [\App\Http\Controllers\Panitia\AssetController::class, 'destroyFolder'])->name('assets.folder.destroy');
        Route::get('/reports/download/{reportFile}', [\App\Http\Controllers\ReportController::class, 'downloadFile'])->name('reports.download');
        Route::delete('/reports/destroy/{reportFile}', [\App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');

        // Scanner Feature (Panitia)
        Route::get('/scanner', [\App\Http\Controllers\Panitia\ScannerController::class, 'index'])->name('scanner.index');
        Route::post('/scanner/verify', [\App\Http\Controllers\Panitia\ScannerController::class, 'verify'])->name('scanner.verify');
        // Manual Access Code Method
        Route::post('/scanner/check', [\App\Http\Controllers\Panitia\ScannerController::class, 'checkAccessCode'])->name('scanner.check-access');
        Route::post('/scanner/verify-manual', [\App\Http\Controllers\Panitia\ScannerController::class, 'verifyAccessCode'])->name('scanner.verify-manual');
        // Internal DPT Search
        Route::post('/scanner/search-dpt', [\App\Http\Controllers\Panitia\ScannerController::class, 'searchDpt'])->name('scanner.search-dpt');

        // Letters Download (Panitia)
        Route::get('/letters', [\App\Http\Controllers\Panitia\DptController::class, 'lettersIndex'])->name('letters.index');
        Route::post('/letters/download-batch', [\App\Http\Controllers\Panitia\DptController::class, 'downloadBatchLetters'])->name('dpt.download-batch-letters');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Panitia\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Panitia\ProfileController::class, 'update'])->name('profile.update');
    });


    // C. ADMIN ROUTES
    // --------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware('role.admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Resources
        Route::resource('kandidat', \App\Http\Controllers\Admin\KandidatController::class);
        Route::resource('calon_dpm', \App\Http\Controllers\Admin\CalonDpmController::class);
        Route::resource('parties', \App\Http\Controllers\Admin\PartyController::class);
        Route::resource('timeline', \App\Http\Controllers\Admin\TimelineController::class);

        // DPT Custom Routes (Must be before resource to avoid ID conflict)
        Route::post('dpt/generate-access-codes', [\App\Http\Controllers\Admin\DptController::class, 'generateAccessCodes'])->name('dpt.generate-access-codes');
        // Additional DPT Routes (must be placed BEFORE resource)
        Route::post('dpt/download-batch-letters', [\App\Http\Controllers\Admin\DptController::class, 'downloadBatchLetters'])->name('dpt.download-batch-letters');
        Route::get('dpt/{mahasiswa}/download-letter', [\App\Http\Controllers\Admin\DptController::class, 'downloadLetter'])->name('dpt.download-letter');

        Route::get('dpt/download-batch-letters', function() {
            return redirect()->route('admin.letters.index')->with('error', 'Akses langsung ditolak. Gunakan tombol download.');
        });

        Route::post('dpt/import', [\App\Http\Controllers\Admin\DptController::class, 'import'])->name('dpt.import');
        Route::get('dpt/sample', [\App\Http\Controllers\Admin\DptController::class, 'downloadSample'])->name('dpt.download-sample');
        Route::delete('dpt/destroy-all', [\App\Http\Controllers\Admin\DptController::class, 'destroyAll'])->name('dpt.destroy-all');
        Route::get('dpt/batch/{batchId}', [\App\Http\Controllers\Admin\DptController::class, 'batchStatus'])->name('dpt.batch.status');

        Route::resource('dpt', \App\Http\Controllers\Admin\DptController::class)
            ->parameters(['dpt' => 'mahasiswa'])
            ->except(['show']);

        // Features
        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getChartData'])->name('analytics.data');
        Route::get('/audit', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/audit/encryption', function() {
            return view('admin.audit.encryption');
        })->name('audit.encryption');
        Route::get('/audit/print', [\App\Http\Controllers\Admin\AuditLogController::class, 'print'])->name('audit.print');
        Route::delete('/audit/destroy', [\App\Http\Controllers\Admin\AuditLogController::class, 'destroy'])->name('audit.destroy');
        Route::delete('/audit/security/unblock/{id}', [\App\Http\Controllers\Admin\AuditLogController::class, 'unblock'])->name('security.unblock');


        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/letters/proof', [\App\Http\Controllers\Admin\SettingsController::class, 'proof'])->name('settings.letters.proof');
        Route::post('/settings/letters/proof', [\App\Http\Controllers\Admin\SettingsController::class, 'updateProof'])->name('settings.letters.proof.update');
        Route::get('/settings/letters/proof/sample', [\App\Http\Controllers\Admin\SettingsController::class, 'downloadSampleProof'])->name('settings.letters.proof.sample');

        Route::get('/settings/letters/notification', [\App\Http\Controllers\Admin\SettingsController::class, 'notification'])->name('settings.letters.notification');
        Route::post('/settings/letters/notification', [\App\Http\Controllers\Admin\SettingsController::class, 'updateNotification'])->name('settings.letters.notification.update');
        Route::get('/settings/letters/notification/sample', [\App\Http\Controllers\Admin\SettingsController::class, 'downloadSampleNotification'])->name('settings.letters.notification.sample');
        Route::post('/settings/letters/notification/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearLetterCache'])->name('settings.letters.notification.clear-cache');
        Route::get('/settings/maintenance', [\App\Http\Controllers\Admin\SettingsController::class, 'maintenance'])->name('settings.maintenance');
        Route::post('/settings/maintenance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateMaintenance'])->name('settings.maintenance.update');

        // Maintenance & Security Dashboard
        Route::get('/maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance/mode', [\App\Http\Controllers\Admin\MaintenanceController::class, 'updateMode'])->name('maintenance.mode.update');
        Route::post('/maintenance/logs', [\App\Http\Controllers\Admin\MaintenanceController::class, 'clearLogs'])->name('maintenance.clear-logs');
        Route::post('/maintenance/optimize', [\App\Http\Controllers\Admin\MaintenanceController::class, 'optimize'])->name('maintenance.optimize');

        // Scanner Feature
        Route::get('/scanner', [\App\Http\Controllers\Admin\ScannerController::class, 'index'])->name('scanner.index');
        Route::post('/scanner/verify', [\App\Http\Controllers\Admin\ScannerController::class, 'verify'])->name('scanner.verify');
        Route::post('/scanner/check', [\App\Http\Controllers\Admin\ScannerController::class, 'checkAccessCode'])->name('scanner.check-access');
        Route::post('/scanner/verify-manual', [\App\Http\Controllers\Admin\ScannerController::class, 'verifyAccessCode'])->name('scanner.verify-manual');
        Route::post('/scanner/search-dpt', [\App\Http\Controllers\Admin\ScannerController::class, 'searchDpt'])->name('scanner.search-dpt');

        // Letters Download
        Route::get('/letters', [\App\Http\Controllers\Admin\DptController::class, 'lettersIndex'])->name('letters.index');
        // Lightweight JSON status endpoint untuk polling (hindari Cloudflare block pada fetch ke halaman HTML penuh)
        Route::get('/letters/status', function () {
            $jobs = \App\Models\ReportFile::where('type', 'letters')
                ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->whereIn('status', ['pending', 'processing', 'completed', 'failed'])
                ->latest()
                ->take(10)
                ->get(['id', 'status', 'progress', 'error_message', 'details', 'path', 'created_at']);
            return response()->json($jobs);
        })->name('admin.letters.status');


        // Reset
        Route::get('/reset', [\App\Http\Controllers\Admin\ResetElectionController::class, 'index'])->name('reset.index');
        Route::post('/reset', [\App\Http\Controllers\Admin\ResetElectionController::class, 'store'])->name('reset.store');

        // User Management (Admin Only)
        Route::put('users/{user}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verify'])->name('users.verify');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);


        Route::get('/sys/emergency-override', [\App\Http\Controllers\Admin\EmergencyController::class, 'index'])->name('emergency.override.index');
        Route::post('/sys/emergency-override/execute', [\App\Http\Controllers\Admin\EmergencyController::class, 'override'])->name('emergency.override.execute');

        // Security & Encryption Management
        Route::get('/security/encryption', function() {
            return view('admin.security.encryption');
        })->name('security.encryption');
        Route::post('/security/encryption/update', function(\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'encryption_level' => 'required|in:standard,high,blockchain'
            ]);

            \App\Models\Setting::set('encryption_level', $validated['encryption_level'], 'Vote encryption level');

            \Illuminate\Support\Facades\Cache::flush();

            $levelLabels = [
                'standard' => 'Standard (AES-256)',
                'high' => 'High (End-to-End)',
                'blockchain' => 'Blockchain Mode'
            ];

            return redirect()
                ->route('admin.security.encryption')
                ->with('success', '✅ Mode enkripsi berhasil diubah ke: ' . $levelLabels[$validated['encryption_level']]);
        })->name('security.encryption.update');

        // Reports Center
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/results', [\App\Http\Controllers\ReportController::class, 'downloadResults'])->name('reports.results');
        Route::get('/reports/ba', [\App\Http\Controllers\ReportController::class, 'downloadBeritaAcara'])->name('reports.ba');
        Route::get('/reports/audit', [\App\Http\Controllers\ReportController::class, 'downloadAuditLogs'])->name('reports.audit');

        // Asset Management (Admin)
        Route::get('/assets', [\App\Http\Controllers\Admin\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [\App\Http\Controllers\Admin\AssetController::class, 'store'])->name('assets.store');
        Route::put('/assets/move/{asset}', [\App\Http\Controllers\Admin\AssetController::class, 'move'])->name('assets.move');
        Route::put('/assets/{asset}/rename', [\App\Http\Controllers\Admin\AssetController::class, 'rename'])->name('assets.rename');
        Route::delete('/assets/{asset}', [\App\Http\Controllers\Admin\AssetController::class, 'destroy'])->name('assets.destroy');
        Route::post('/assets/folder', [\App\Http\Controllers\Admin\AssetController::class, 'storeFolder'])->name('assets.folder.store');
        Route::put('/assets/folder/{folder}/rename', [\App\Http\Controllers\Admin\AssetController::class, 'renameFolder'])->name('assets.folder.rename');
        Route::delete('/assets/folder/{folder}', [\App\Http\Controllers\Admin\AssetController::class, 'destroyFolder'])->name('assets.folder.destroy');
        Route::get('/reports/download/{reportFile}', [\App\Http\Controllers\ReportController::class, 'downloadFile'])->name('reports.download');
        Route::delete('/reports/destroy/{reportFile}', [\App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    });

    // D. KPPS ROUTES
    // --------------------------------------------
    Route::prefix('kpps')->name('kpps.')->middleware('role.kpps')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kpps\DashboardController::class, 'index'])->name('dashboard');

        // Scanner
        Route::get('/scanner', [\App\Http\Controllers\Kpps\ScannerController::class, 'index'])->name('scanner.index');
        Route::post('/scanner/verify', [\App\Http\Controllers\Kpps\ScannerController::class, 'verify'])->name('scanner.verify');
        Route::post('/scanner/check', [\App\Http\Controllers\Kpps\ScannerController::class, 'checkAccessCode'])->name('scanner.check-access');
        Route::post('/scanner/verify-manual', [\App\Http\Controllers\Kpps\ScannerController::class, 'verifyAccessCode'])->name('scanner.verify-manual');
        Route::post('/scanner/search-dpt', [\App\Http\Controllers\Kpps\ScannerController::class, 'searchDpt'])->name('scanner.search-dpt');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Kpps\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Kpps\ProfileController::class, 'update'])->name('profile.update');
    });

});

require __DIR__ . '/settings.php';
