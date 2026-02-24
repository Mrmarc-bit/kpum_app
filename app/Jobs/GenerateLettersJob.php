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
use Illuminate\Support\Str;
use ZipArchive;

class GenerateLettersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportFileId;
    protected $filters;

    public $timeout = 1800; // 30 minutes
    public $tries   = 1;    // No retry — would reset progress to 0

    public function __construct($reportFileId, array $filters)
    {
        $this->reportFileId = $reportFileId;
        $this->filters      = $filters;
    }

    public function handle(ProofOfVoteService $letterService): void
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

            // ── Build Query: only fetch columns needed ─────────────────
            $query = Mahasiswa::select([
                'id', 'nim', 'name', 'prodi', 'fakultas',
                'access_code', 'notification_letter_path',
            ]);

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

            // ── Prepare ZIP destination ────────────────────────────────
            $timestamp   = date('Y-m-d_H-i-s');
            $safeProdi   = !empty($this->filters['prodi']) ? Str::slug($this->filters['prodi']) : 'all';
            $zipFileName = "Surat_Pemberitahuan_{$safeProdi}_{$timestamp}.zip";
            $zipPath     = "reports/{$zipFileName}";

            if (!Storage::disk('public')->exists('reports')) {
                Storage::disk('public')->makeDirectory('reports');
            }
            $fullZipPath = Storage::disk('public')->path($zipPath);

            // ── Pre-load letter settings (images as base64) once ───────
            $batchSettings = $letterService->getBatchSettings();

            // ── FASE 1: Generate / collect PDFs (progress 0–80%) ──────
            $processedCount = 0;
            $allPdfPaths    = [];

            // Chunk 100 = fewer DB round-trips than 50
            $query->chunk(100, function ($students) use (
                $letterService, $batchSettings,
                &$processedCount, $totalStudents, $reportFile, &$allPdfPaths
            ) {
                foreach ($students as $student) {
                    /** @var \App\Models\Mahasiswa $student */
                    try {
                        $prodi      = Str::slug($student->prodi ?? 'unknown');
                        $path       = "letters/{$prodi}/{$student->nim}.pdf";
                        $letterPath = $student->notification_letter_path ?? $path;

                        // Re-use cached PDF if it already exists on disk
                        if (!Storage::disk('public')->exists($letterPath)) {
                            $pdf = $letterService->generateNotificationLetter($student, $batchSettings);
                            Storage::disk('public')->put($path, $pdf->output());
                            $student->update(['notification_letter_path' => $path]);
                            $letterPath = $path;
                            unset($pdf);
                        }

                        $diskPath = Storage::disk('public')->path($letterPath);
                        if (file_exists($diskPath)) {
                            // Safe archive name: NIM_Name.pdf (no slashes, no special chars)
                            $safeName = preg_replace('/[^A-Za-z0-9_\-.]/', '_', "{$student->nim}_{$student->name}");
                            $allPdfPaths["{$safeName}.pdf"] = $diskPath;
                        }

                    } catch (\Throwable $e) {
                        Log::error("Letter failed for NIM {$student->nim}: " . $e->getMessage());
                    }

                    $processedCount++;

                    // Reduce DB writes: update every 25 students
                    if ($processedCount % 25 === 0) {
                        $pct = min(78, intval(($processedCount / $totalStudents) * 78));
                        $reportFile->update(['progress' => $pct]);
                    }

                    // Free memory every 100 students
                    if ($processedCount % 100 === 0) {
                        gc_collect_cycles();
                    }
                }
            });

            $reportFile->update(['progress' => 80]);

            // ── FASE 2: Build ZIP (progress 80–100%) ──────────────────
            if (count($allPdfPaths) === 0) {
                throw new \Exception('Tidak ada file PDF yang berhasil digenerate.');
            }

            $zipCreated = false;

            // Strategy A: system `zip -j0` (store-only, fastest for PDFs)
            // -j  = junkpaths (strip directory structure — filenames only)
            // -0  = no compression (PDFs are already compressed internally)
            // -@  = read filenames from stdin (avoids ARG_MAX limit)
            // Result: 3–5x faster than default compression for 500+ PDFs
            if (function_exists('exec')) {
                $fileListPath = sys_get_temp_dir() . "/zip_list_{$this->reportFileId}.txt";
                file_put_contents($fileListPath, implode("\n", array_values($allPdfPaths)));

                $cmd  = "zip -j0 " . escapeshellarg($fullZipPath);
                $cmd .= " -@ < " . escapeshellarg($fileListPath);
                $cmd .= " 2>&1";

                exec($cmd, $cmdOutput, $returnCode);
                @unlink($fileListPath);

                if ($returnCode === 0 && file_exists($fullZipPath) && filesize($fullZipPath) > 0) {
                    $zipCreated = true;
                    Log::info("ZIP created via system zip (store-only) for {$processedCount} files → {$zipFileName}");
                } else {
                    Log::warning("System zip failed (code {$returnCode}): " . implode(' ', $cmdOutput));
                }
            }

            // Strategy B: PHP ZipArchive fallback (addFile = disk-based, not RAM)
            if (!$zipCreated) {
                $reportFile->update(['progress' => 85]);
                $zip = new ZipArchive;

                if ($zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                    throw new \Exception("Gagal membuat file ZIP: {$fullZipPath}");
                }

                foreach ($allPdfPaths as $archiveName => $diskPath) {
                    $zip->addFile($diskPath, $archiveName);
                }

                $zip->close();
                Log::info("ZIP created via PHP ZipArchive for {$processedCount} files → {$zipFileName}");
            }

            // ── Mark as completed ──────────────────────────────────────
            $reportFile->update([
                'status'   => 'completed',
                'progress' => 100,
                'path'     => $zipPath,
                'disk'     => 'public',
            ]);

            Log::info("GenerateLettersJob DONE: {$processedCount} surat, file: {$zipFileName}");

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
