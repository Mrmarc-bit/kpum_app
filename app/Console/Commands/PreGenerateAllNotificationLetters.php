<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use App\Services\ProofOfVoteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PreGenerateAllNotificationLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'letters:pre-generate {--prodi= : Filter by specific prodi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-generate notification letters for all mahasiswa (speeds up future downloads)';

    protected $letterService;

    public function __construct(ProofOfVoteService $letterService)
    {
        parent::__construct();
        $this->letterService = $letterService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Mahasiswa::query();
        
        if ($prodi = $this->option('prodi')) {
            $query->where('prodi', $prodi);
            $this->info("Filtering by prodi: {$prodi}");
        }
        
        $total = $query->count();
        $this->info("Found {$total} mahasiswa to process.");
        
        if ($total === 0) {
            $this->warn("No mahasiswa found.");
            return 0;
        }
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $success = 0;
        $failed = 0;
        
        $query->chunk(50, function($students) use ($bar, &$success, &$failed) {
            /** @var \App\Models\Mahasiswa $student */
            foreach ($students as $student) {
                try {
                    // Generate PDF
                    $pdf = $this->letterService->generateNotificationLetter($student);
                    
                    // Save to storage
                    $prodi = \Illuminate\Support\Str::slug($student->prodi ?? 'unknown');
                    $path = "letters/{$prodi}/{$student->nim}.pdf";
                    
                    Storage::disk('public')->put($path, $pdf->output());
                    
                    // Update mahasiswa record
                    $student->update(['notification_letter_path' => $path]);
                    
                    $success++;
                } catch (\Throwable $e) {
                    $this->error("\nFailed for NIM {$student->nim}: " . $e->getMessage());
                    $failed++;
                }
                
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("✅ Success: {$success}");
        if ($failed > 0) {
            $this->warn("⚠️  Failed: {$failed}");
        }
        
        $this->info("🚀 Next downloads will be INSTANT (using cached PDFs)!");
        
        return 0;
    }
}
