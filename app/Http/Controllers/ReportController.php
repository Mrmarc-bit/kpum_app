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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

        $generatedReports = \App\Models\ReportFile::where('user_id', Auth::id())
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
        // Security check
        if ($reportFile->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
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
        if ($reportFile->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($reportFile->path && Storage::disk('public')->exists($reportFile->path)) {
            Storage::disk('public')->delete($reportFile->path);
        }

        $reportFile->delete();

        return back()->with('success', 'Riwayat dokumen berhasil dihapus.');
    }
}
