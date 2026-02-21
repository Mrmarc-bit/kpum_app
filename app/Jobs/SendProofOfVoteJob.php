<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mahasiswa;
use App\Mail\ProofOfVoteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendProofOfVoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mahasiswa;

    /**
     * Create a new job instance.
     */
    public function __construct(Mahasiswa $mahasiswa)
    {
        $this->mahasiswa = $mahasiswa;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Re-check status to be absolutely sure
            if ($this->mahasiswa->voted_at && $this->mahasiswa->dpm_voted_at && $this->mahasiswa->email) {
                
                // Generate PDF service logic (Service assumed to exist or direct logic here)
                // For decoupling, better to resolve service or do simple generation here if needed.
                // Assuming Service exists as previously seen in Controller logic
                
                $pdf = app(\App\Services\ProofOfVoteService::class)->generatePdf($this->mahasiswa);
                $pdfBase64 = base64_encode($pdf->output());

                Mail::to($this->mahasiswa->email)->send(new ProofOfVoteMail($this->mahasiswa, $pdfBase64));
                
                Log::info('Bukti pilih berhasil dikirim', ['email' => $this->mahasiswa->email]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email bukti pilih [Job]', ['error' => $e->getMessage(), 'user_id' => $this->mahasiswa->id]);
            // Optional: Release job back to queue to retry?
            // $this->release(30);
        }
    }
}
