<?php

namespace App\Jobs;

use App\Models\Mahasiswa;
use App\Models\ReportFile;
use App\Services\ProofOfVoteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GenerateLettersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportFileId;
    protected $filters;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1800; // 30 minutes

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1; // Jangan retry — retry menyebabkan progress reset ke 0 (membingungkan user)

    /**
     * Create a new job instance.
     *
     * @param int $reportFileId
     * @param array $filters
     */
    public function __construct($reportFileId, array $filters)
    {
        $this->reportFileId = $reportFileId;
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ProofOfVoteService $letterService)
    {
        // Increase limits for this job
        ini_set('memory_limit', '1024M'); // Increased further
        set_time_limit(1200); // 20 minutes

        try {
            $reportFile = ReportFile::find($this->reportFileId);
            
            if (!$reportFile) {
                Log::error("GenerateLettersJob: ReportFile ID {$this->reportFileId} not found.");
                return;
            }

            // Store details (e.g., "Prodi: Informatika")
            $prodiName = $this->filters['prodi'] ?? 'Semua Prodi';
            $reportFile->update([
                'status' => 'processing',
                'details' => $prodiName,
                'progress' => 0
            ]);

            // Build Query
            $query = Mahasiswa::query();
            
            if (!empty($this->filters['prodi'])) {
                $query->where('prodi', $this->filters['prodi']);
            }
            
            if (!empty($this->filters['fakultas'])) {
                $query->where('fakultas', $this->filters['fakultas']);
            }

            $totalStudents = $query->count();
            $processedCount = 0;
            
            if ($totalStudents === 0) {
                throw new \Exception('Tidak ada data mahasiswa dengan filter tersebut.');
            }

            // Prepare ZIP
            $timestamp = date('Y-m-d_H-i-s');
            // Use Str::slug instead of str_slug helper for compatibility
            $safeProdi = !empty($this->filters['prodi']) ? \Illuminate\Support\Str::slug($this->filters['prodi']) : 'all';
            $zipFileName = "Surat_Pemberitahuan_{$safeProdi}_{$timestamp}.zip";
            $zipPath = "reports/{$zipFileName}"; // Save in public/reports
            
            // Ensure directory exists
            if (!Storage::disk('public')->exists('reports')) {
                Storage::disk('public')->makeDirectory('reports');
            }

            $fullZipPath = Storage::disk('public')->path($zipPath);

            $zip = new ZipArchive;
            if ($zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception("Gagal membuat file ZIP di server path: " . $fullZipPath);
            }

            // Preload Settings
            $batchSettings = $letterService->getBatchSettings();
            
            // Process in Smaller Chunks (better progress feedback!)
            $query->chunk(10, function($students) use ($zip, $letterService, $batchSettings, &$processedCount, $totalStudents, $reportFile) {
                foreach ($students as $student) {
                    /** @var \App\Models\Mahasiswa $student */
                    $pdf = null;
                    try {
                        $prodi = \Illuminate\Support\Str::slug($student->prodi ?? 'unknown');
                        $path = "letters/{$prodi}/{$student->nim}.pdf";

                        // Pastikan PDF sudah ada di disk (generate kalau belum)
                        if (!($student->notification_letter_path && Storage::disk('public')->exists($student->notification_letter_path))) {
                            // Generate & simpan ke disk dulu
                            $pdf = $letterService->generateNotificationLetter($student, $batchSettings);
                            Storage::disk('public')->put($path, $pdf->output());
                            $student->update(['notification_letter_path' => $path]);
                            unset($pdf); // Bebaskan memory PDF SEGERA
                        }

                        // addFile() baca dari disk — TIDAK load ke RAM
                        $diskPath = Storage::disk('public')->path(
                            $student->notification_letter_path ?? $path
                        );
                        if (file_exists($diskPath)) {
                            $zip->addFile($diskPath, "{$student->nim}_{$student->name}.pdf");
                        }

                    } catch (\Throwable $e) {
                        Log::error("Failed to add letter for NIM: {$student->nim}. Error: " . $e->getMessage());
                        unset($pdf);
                    }

                    $processedCount++;

                    // Update Progress every 10 records
                    if ($processedCount % 10 === 0 && $totalStudents > 0) {
                        $percentage = min(99, intval(($processedCount / $totalStudents) * 100));
                        $reportFile->update(['progress' => $percentage]);
                    }

                    // Force GC setiap 20 records
                    if ($processedCount % 20 === 0) {
                        gc_collect_cycles();
                    }
                }
            });

            $zip->close();

            // Update Report Status
            $reportFile->update([
                'status' => 'completed',
                'progress' => 100,
                'path' => $zipPath,
                'disk' => 'public'
            ]);

        } catch (\Throwable $e) {
            Log::error("GenerateLettersJob CRITICAL FAILURE: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            if (isset($reportFile)) {
                $reportFile->update([
                    'status' => 'failed',
                    'error_message' => substr($e->getMessage(), 0, 250) // Truncate error message
                ]);
            }
            
            // Rethrow so queue marks it as failed
            throw $e;
        }
    }
}
