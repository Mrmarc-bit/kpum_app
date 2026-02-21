<?php

namespace App\Listeners;

use App\Models\Mahasiswa;
use App\Services\ProofOfVoteService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateNotificationLetterOnMahasiswaCreated
{
    protected $letterService;

    /**
     * Create the event listener.
     */
    public function __construct(ProofOfVoteService $letterService)
    {
        $this->letterService = $letterService;
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        try {
            $mahasiswa = $event->mahasiswa ?? $event;
            
            // Generate PDF
            $pdf = $this->letterService->generateNotificationLetter($mahasiswa);
            
            // Save to storage/app/public/letters/{prodi}/{nim}.pdf
            $prodi = \Illuminate\Support\Str::slug($mahasiswa->prodi ?? 'unknown');
            $path = "letters/{$prodi}/{$mahasiswa->nim}.pdf";
            
            Storage::disk('public')->put($path, $pdf->output());
            
            // Update mahasiswa record with letter path
            $mahasiswa->update(['notification_letter_path' => $path]);
            
            Log::info("Pre-generated letter for NIM: {$mahasiswa->nim}");
            
        } catch (\Throwable $e) {
            Log::error("Failed to pre-generate letter for NIM: " . ($mahasiswa->nim ?? 'unknown') . ". Error: " . $e->getMessage());
        }
    }
}
