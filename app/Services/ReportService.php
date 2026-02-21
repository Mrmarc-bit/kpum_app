<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\CalonDpm;
use App\Models\DpmVote;
use App\Models\Kandidat;
use App\Models\Mahasiswa;
use App\Models\Setting;
use App\Models\Vote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportService
{
    /**
     * Generate PDF for Full Election Results.
     * Caching not applied because reports need absolute fresh data and are generated occasionally.
     */
    public function generateResultsPdf()
    {
        $presmaCandidates = Kandidat::orderBy('no_urut')->get();
        $dpmCandidates = CalonDpm::orderBy('urutan_tampil')->get();
        
        // Manual Counting for Presma (Decryption Required)
        // Using cursor or chunking might be better for millions, but for PDF generation (usually offline/admin task), memory is less critical than speed.
        // However, we should be safe.
        $presmaCounts = $this->countVotes(Vote::cursor());
        
        foreach ($presmaCandidates as $kandidat) {
            $kandidat->votes_count = $presmaCounts[$kandidat->id] ?? 0;
        }

        // Manual Counting for DPM
        $dpmCounts = $this->countVotes(DpmVote::cursor(), true);

        foreach ($dpmCandidates as $calon) {
            $calon->dpm_votes_count = $dpmCounts[$calon->id] ?? 0;
        }
        
        $totalPresmaVotes = Vote::count();
        $totalDpmVotes = DpmVote::count();
        $totalDpt = Mahasiswa::count();

        $stats = [
            'total_dpt' => $totalDpt,
            'total_presma_votes' => $totalPresmaVotes,
            'total_dpm_votes' => $totalDpmVotes,
            'presma_percentage' => $totalDpt > 0 ? ($totalPresmaVotes / $totalDpt) * 100 : 0,
        ];

        $letterSettings = $this->getLetterSettings();
        $letterSettings['date'] = now()->translatedFormat('l, d F Y');

        $pdf = Pdf::loadView('reports.results', compact('presmaCandidates', 'dpmCandidates', 'stats', 'letterSettings'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    public function generateAuditLogsPdf()
    {
        // Limit to latest 500 for PDF readability and memory safety
        $logs = AuditLog::latest()->take(500)->get();
        $letterSettings = $this->getLetterSettings();

        $pdf = Pdf::loadView('reports.audit_logs', compact('logs', 'letterSettings'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf;
    }

    public function generateBeritaAcaraPdf()
    {
        $presmaWinner = Kandidat::withCount('votes')->orderByDesc('votes_count')->first();
        
        $totalDpt = Mahasiswa::count();
        $totalSah = Vote::count();
        $totalGolput = $totalDpt - $totalSah;

        $data = [
            'day' => now()->translatedFormat('l'),
            'date' => now()->translatedFormat('d'),
            'month' => now()->translatedFormat('F'),
            'year' => now()->translatedFormat('Y'),
            'full_date' => now()->translatedFormat('d F Y'),
            'presma_winner' => $presmaWinner,
            'total_dpt' => $totalDpt,
            'total_votes' => $totalSah,
            'total_abstain' => $totalGolput,
        ];

        $letterSettings = $this->getLetterSettings();

        $pdf = Pdf::loadView('reports.berita_acara', compact('data', 'letterSettings'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    private function countVotes($cursor, $isDpm = false)
    {
        $counts = [];
        foreach ($cursor as $vote) {
            try {
                $column = $isDpm ? 'calon_dpm_id' : 'kandidat_id';
                $value = $vote->$column;

                if (empty($vote->encryption_meta) && is_numeric($value)) {
                    $decryptedId = $value;
                } else {
                    $decryptedId = VoteEncryptionService::decryptVote($value, $vote->encryption_meta);
                }

                if (!isset($counts[$decryptedId])) {
                    $counts[$decryptedId] = 0;
                }
                $counts[$decryptedId]++;
            } catch (\Exception $e) {
                // Log only occasionally or aggregate errors if needed
            }
        }
        return $counts;
    }

    private function getLetterSettings()
    {
        // Helper to get base64 image (Stateless helper)
        $getBase64Image = function ($path) {
            if (!extension_loaded('gd') || empty($path)) return null;

            $fullPath = public_path($path);
            if (!file_exists($fullPath)) {
                $cleanPath = str_replace('storage/', '', $path);
                $storagePath = storage_path('app/public/' . $cleanPath);
                if (file_exists($storagePath)) $fullPath = $storagePath;
            }

            if (file_exists($fullPath)) {
                try {
                    $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($fullPath);
                    return 'data:image/' . $type . ';base64,' . base64_encode($data);
                } catch (\Exception $e) { return null; }
            }
            return null;
        };

        return [
            'header' => Setting::get('letter_header', 'KOMISI PEMILIHAN UMUM MAHASISWA'),
            'sub_header' => Setting::get('letter_sub_header', 'UNIVERSITAS CONTOSO'),
            'address' => Setting::get('letter_address', 'Jl. Kampus Raya No. 1, Gedung Student Center Lt. 2'),
            'footer' => Setting::get('letter_footer', 'Dokumen ini dibuat secara otomatis oleh sistem e-voting.'),
            
            'signature_base64' => $getBase64Image(Setting::get('letter_signature_path')),
            'logo_base64' => $getBase64Image(Setting::get('app_logo')),
            
            'signature_name' => Setting::get('letter_signature_name', 'Ketua KPUM'),
        ];
    }
}
