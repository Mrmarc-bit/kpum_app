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
        ini_set('memory_limit', '1024M');
        set_time_limit(1800);

        try {
            $reportFile = ReportFile::find($this->reportFileId);

            if (!$reportFile) {
                Log::error("GenerateLettersJob: ReportFile ID {$this->reportFileId} not found.");
                return;
            }

            $prodiName = $this->filters['prodi'] ?? 'Semua Prodi';
            $reportFile->update(['status' => 'processing', 'details' => $prodiName, 'progress' => 0]);

            // Build Query
            $query = Mahasiswa::query();
            if (!empty($this->filters['prodi'])) {
                $query->where('prodi', $this->filters['prodi']);
            }
            if (!empty($this->filters['fakultas'])) {
                $query->where('fakultas', $this->filters['fakultas']);
            }

            $totalStudents = $query->count();
            if ($totalStudents === 0) {
                throw new \Exception('Tidak ada data mahasiswa dengan filter tersebut.');
            }

            // Prepare ZIP path
            $timestamp = date('Y-m-d_H-i-s');
            $safeProdi = !empty($this->filters['prodi']) ? \Illuminate\Support\Str::slug($this->filters['prodi']) : 'all';
            $zipFileName = "Surat_Pemberitahuan_{$safeProdi}_{$timestamp}.zip";
            $zipPath = "reports/{$zipFileName}";

            if (!Storage::disk('public')->exists('reports')) {
                Storage::disk('public')->makeDirectory('reports');
            }
            $fullZipPath = Storage::disk('public')->path($zipPath);

            // =========================================================
            // FASE 1: Pastikan semua PDF sudah ada di disk (0% - 80%)
            // =========================================================
            $processedCount = 0;
            $allPdfPaths = []; // Kumpulkan semua path untuk ZIP
            $batchSettings = $letterService->getBatchSettings(); // Pre-load sekali saja

            $query->chunk(50, function($students) use ($letterService, $batchSettings, &$processedCount, $totalStudents, $reportFile, &$allPdfPaths) {
                foreach ($students as $student) {
                    $pdf = null;
                    try {
                        $prodi = \Illuminate\Support\Str::slug($student->prodi ?? 'unknown');
                        $path = "letters/{$prodi}/{$student->nim}.pdf";
                        $letterPath = $student->notification_letter_path ?? $path;

                        // Generate jika belum ada
                        if (!Storage::disk('public')->exists($letterPath)) {
                            $pdf = $letterService->generateNotificationLetter($student, $batchSettings);
                            Storage::disk('public')->put($path, $pdf->output());
                            $student->update(['notification_letter_path' => $path]);
                            $letterPath = $path;
                            unset($pdf);
                        }

                        $diskPath = Storage::disk('public')->path($letterPath);
                        if (file_exists($diskPath)) {
                            $allPdfPaths["{$student->nim}_{$student->name}.pdf"] = $diskPath;
                        }

                    } catch (\Throwable $e) {
                        Log::error("Letter failed for NIM {$student->nim}: " . $e->getMessage());
                        unset($pdf);
                    }

                    $processedCount++;

                    // Progress 0-80% untuk fase generate
                    if ($processedCount % 10 === 0) {
                        $pct = min(80, intval(($processedCount / $totalStudents) * 80));
                        $reportFile->update(['progress' => $pct]);
                    }
                    if ($processedCount % 50 === 0) {
                        gc_collect_cycles();
                    }
                }
            });

            $reportFile->update(['progress' => 82]);

            // =========================================================
            // FASE 2: Buat ZIP secepat mungkin (80% - 100%)
            // Coba system zip command dulu (10-50x lebih cepat!)
            // =========================================================
            $zipCreated = false;

            if (count($allPdfPaths) > 0 && function_exists('exec')) {
                // Tulis daftar file ke temp file untuk zip -@
                $fileListPath = sys_get_temp_dir() . "/zip_list_{$this->reportFileId}.txt";
                file_put_contents($fileListPath, implode("\n", array_values($allPdfPaths)));

                // zip -j: jangan simpan path (hanya filename), baca dari stdin
                $cmd  = "cd " . escapeshellarg(dirname($fullZipPath));
                $cmd .= " && zip -j " . escapeshellarg($fullZipPath);
                $cmd .= " -@ < " . escapeshellarg($fileListPath);
                $cmd .= " 2>&1";

                exec($cmd, $output, $returnCode);
                @unlink($fileListPath);

                if ($returnCode === 0 && file_exists($fullZipPath)) {
                    $zipCreated = true;
                    Log::info("ZIP created with system zip command in {$processedCount} files.");
                } else {
                    Log::warning("System zip failed (code {$returnCode}), fallback to PHP ZipArchive.");
                }
            }

            // Fallback: PHP ZipArchive dengan addFile() (tidak load ke RAM)
            if (!$zipCreated) {
                $reportFile->update(['progress' => 85]);
                $zip = new ZipArchive;
                if ($zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                    throw new \Exception("Gagal membuat file ZIP: " . $fullZipPath);
                }
                foreach ($allPdfPaths as $archiveName => $diskPath) {
                    $zip->addFile($diskPath, $archiveName);
                }
                $zip->close();
            }

            $reportFile->update(['progress' => 100]);

            // Update Report Status
            $reportFile->update([
                'status'   => 'completed',
                'progress' => 100,
                'path'     => $zipPath,
                'disk'     => 'public',
            ]);

        } catch (\Throwable $e) {
            Log::error("GenerateLettersJob CRITICAL FAILURE: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            if (isset($reportFile)) {
                $reportFile->update([
                    'status'        => 'failed',
                    'error_message' => substr($e->getMessage(), 0, 250),
                ]);
            }
            throw $e;
        }
    }
}
