<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CalonDpm;
use App\Models\DpmVote;
use App\Models\Kandidat;
use App\Models\Mahasiswa;
use App\Models\Setting;
use App\Models\Vote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    protected $service;

    public function __construct(\App\Services\ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Display the report center.
     */
    public function index()
    {
        $view = Auth::user()->role === 'panitia'
            ? 'panitia.reports.index'
            : 'admin.reports.index';

        // Hanya tampilkan laporan resmi (hasil, audit, berita acara)
        // JANGAN tampilkan 'letters' — itu milik halaman Unduh Surat Pemberitahuan
        $generatedReports = \App\Models\ReportFile::where('user_id', Auth::id())
            ->whereIn('type', ['results', 'audit', 'berita_acara'])
            ->latest()
            ->get();

        return view($view, [
            'title' => 'Pusat Laporan & Arsip',
            'generatedReports' => $generatedReports
        ]);
    }

    /**
     * Dispatch Job to Generate Full Election Results.
     */
    /**
     * Helper to process report synchronously.
     */
    private function processReport(\App\Models\ReportFile $report)
    {
        try {
            $report->update(['status' => 'processing']);

            $pdf = null;
            $filename = '';

            // Generate PDF based on type
            switch ($report->type) {
                case 'results':
                    $pdf = $this->service->generateResultsPdf();
                    $filename = 'Laporan_Hasil_Pemilihan_' . date('Y-m-d_His') . '.pdf';
                    break;
                case 'audit':
                    $pdf = $this->service->generateAuditLogsPdf();
                    $filename = 'Laporan_Audit_Sistem_' . date('Y-m-d_His') . '.pdf';
                    break;
                case 'berita_acara':
                    $pdf = $this->service->generateBeritaAcaraPdf();
                    $filename = 'Berita_Acara_' . date('Y-m-d_His') . '.pdf';
                    break;
            }

            if ($pdf) {
                $path = 'reports/' . $filename;
                Storage::disk('public')->put($path, $pdf->output());

                $report->update([
                    'status' => 'completed',
                    'path' => $path,
                    'disk' => 'public'
                ]);
            } else {
                throw new \Exception('Gagal membuat konten PDF.');
            }

        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            // Re-throw to show error to user
            throw $e;
        }
    }

    /**
     * Dispatch Job to Generate Full Election Results.
     */
    public function downloadResults()
    {
        $report = \App\Models\ReportFile::create([
            'user_id' => Auth::id(),
            'type' => 'results',
            'status' => 'pending'
        ]);

        $this->processReport($report);

        return back()->with('success', 'Laporan Hasil Pemilihan berhasil dibuat!');
    }

    /**
     * Dispatch Job to Generate Audit Logs.
     */
    public function downloadAuditLogs()
    {
        $report = \App\Models\ReportFile::create([
            'user_id' => Auth::id(),
            'type' => 'audit',
            'status' => 'pending'
        ]);

        $this->processReport($report);

        return back()->with('success', 'Laporan Audit Log berhasil dibuat!');
    }

    /**
     * Dispatch Job to Generate Berita Acara.
     */
    public function downloadBeritaAcara()
    {
        $report = \App\Models\ReportFile::create([
            'user_id' => Auth::id(),
            'type' => 'berita_acara',
            'status' => 'pending'
        ]);

        $this->processReport($report);

        return back()->with('success', 'Berita Acara berhasil dibuat!');
    }

    /**
     * Download the actual file.
     */
    public function downloadFile(\App\Models\ReportFile $reportFile)
    {
        // Security check: Owner, Admin, Super Admin, or Panitia (for shared administration)
        $user = Auth::user();
        $isPrivileged = $user && in_array($user->role, ['admin', 'super_admin', 'panitia']);
        $isOwner = $user && ($reportFile->user_id == $user->id);

        if (!$isPrivileged && !$isOwner) {
            abort(403);
        }

        if ($reportFile->status !== 'completed' || !Storage::disk('public')->exists($reportFile->path)) {
            return back()->with('error', 'File tidak ditemukan atau belum selesai diproses.');
        }

        return response()->download(Storage::disk('public')->path($reportFile->path));
    }

    /**
     * Delete report history.
     */
    public function destroy(\App\Models\ReportFile $reportFile)
    {
        $user = Auth::user();
        $isPrivileged = $user && in_array($user->role, ['admin', 'super_admin', 'panitia']);
        $isOwner = $user && ($reportFile->user_id == $user->id);

        Log::info("Attempting to delete ReportFile #{$reportFile->id}", [
            'request_user_id' => Auth::id(),
            'request_user_role' => $user->role ?? 'guest',
            'file_owner_id' => $reportFile->user_id,
            'is_privileged' => $isPrivileged,
            'is_owner' => $isOwner
        ]);

        if (!$isPrivileged && !$isOwner) {
            Log::warning("Unauthorized delete attempt for ReportFile #{$reportFile->id}");
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus riwayat ini.'], 403);
            }
            abort(403);
        }

        // Hapus file fisik dari storage
        if ($reportFile->path && Storage::disk('public')->exists($reportFile->path)) {
            Storage::disk('public')->delete($reportFile->path);
            Log::info("Physical file deleted: {$reportFile->path}");
        }

        $reportFile->delete();
        Log::info("ReportFile record #{$reportFile->id} deleted from database.");

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Riwayat berhasil dihapus.']);
        }

        return back()->with('success', 'Riwayat berhasil dihapus.');
    }
}
