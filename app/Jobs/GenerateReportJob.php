<?php

namespace App\Jobs;

use App\Models\ReportFile;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportFile;

    /**
     * Create a new job instance.
     */
    public function __construct(ReportFile $reportFile)
    {
        $this->reportFile = $reportFile;
    }

    /**
     * Execute the job.
     */
    public function handle(ReportService $reportService): void
    {
        try {
            $this->reportFile->update(['status' => 'processing']);

            $pdf = null;
            $filename = '';

            switch ($this->reportFile->type) {
                case 'results':
                    $pdf = $reportService->generateResultsPdf();
                    $filename = 'Laporan_Hasil_Pemilihan_' . date('Y-m-d_His') . '.pdf';
                    break;
                case 'audit':
                    $pdf = $reportService->generateAuditLogsPdf();
                    $filename = 'Laporan_Audit_Sistem_' . date('Y-m-d_His') . '.pdf';
                    break;
                case 'berita_acara':
                    $pdf = $reportService->generateBeritaAcaraPdf();
                    $filename = 'Berita_Acara_' . date('Y-m-d_His') . '.pdf';
                    break;
            }

            if ($pdf) {
                $path = 'reports/' . $filename;
                Storage::disk('public')->put($path, $pdf->output());

                $this->reportFile->update([
                    'status' => 'completed',
                    'path' => $path,
                    'disk' => 'public'
                ]);
            } else {
                throw new \Exception('Failed to generate PDF content');
            }

        } catch (\Exception $e) {
            $this->reportFile->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
