<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageService;
use App\Models\Kandidat;
use App\Models\CalonDpm;
use App\Models\Party;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize-existing';
    protected $description = 'Migrate and optimize all existing images to new ImageService structure';

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    public function handle()
    {
        $this->info('Starting Image Optimization Migration...');

        DB::transaction(function () {
            // 1. KANDIDAT
            $this->processKandidats();

            // 2. CALON DPM
            $this->processCalonDpm();

            // 3. PARTIES
            $this->processParties();

            // 4. SETTINGS
            $this->processSettings();
        });

        $this->info('All images processed successfully!');
    }

    private function processKandidats()
    {
        $kandidats = Kandidat::all();
        $this->info("Processing " . $kandidats->count() . " candidates...");

        foreach ($kandidats as $k) {
            // FOTO KETUA
            if ($k->foto && Storage::disk('public')->exists($k->foto)) {
                // Ignore if already UUID (simple check: no '/')
                if (!str_contains($k->foto, '/')) {
                    $this->line("Skipping {$k->nama_ketua} (Already optimized)");
                } else {
                    $path = Storage::disk('public')->path($k->foto);
                    try {
                        $baseName = $this->imageService->importFromPath($path, $k->nama_ketua);
                        $k->foto = $baseName; // Store basename
                        $this->info("Optimized: {$k->nama_ketua}");
                    } catch (\Exception $e) {
                        $this->error("Failed {$k->nama_ketua}: " . $e->getMessage());
                    }
                }
            }

            // FOTO WAKIL
            if ($k->foto_wakil && Storage::disk('public')->exists($k->foto_wakil)) {
                if (!str_contains($k->foto_wakil, '/')) {
                    // skip
                } else {
                    $path = Storage::disk('public')->path($k->foto_wakil);
                    try {
                        $baseName = $this->imageService->importFromPath($path, $k->nama_wakil);
                        $k->foto_wakil = $baseName; // Store basename
                        $this->info("Optimized wakil: {$k->nama_wakil}");
                    } catch (\Exception $e) {
                         $this->error("Failed wakil {$k->nama_wakil}: " . $e->getMessage());
                    }
                }
            }

            $k->save();
        }
    }

    private function processCalonDpm()
    {
        $dpms = CalonDpm::all();
        $this->info("Processing " . $dpms->count() . " DPM candidates...");

        foreach ($dpms as $d) {
            if ($d->foto && Storage::disk('public')->exists($d->foto)) {
                if (!str_contains($d->foto, '/')) {
                    continue;
                }
                
                $path = Storage::disk('public')->path($d->foto);
                try {
                    $name = $d->nama ?? 'dpm-' . $d->id;
                    $baseName = $this->imageService->importFromPath($path, $name);
                    $d->foto = $baseName;
                    $d->save();
                    $this->info("Optimized DPM: {$name}");
                } catch (\Exception $e) {
                    $this->error("Failed DPM {$d->id}: " . $e->getMessage());
                }
            }
        }
    }

    private function processParties()
    {
        $parties = Party::all();
        $this->info("Processing " . $parties->count() . " parties...");

        foreach ($parties as $p) {
            if ($p->logo_path && Storage::disk('public')->exists($p->logo_path)) {
                if (!str_contains($p->logo_path, '/')) {
                    continue;
                }

                $path = Storage::disk('public')->path($p->logo_path);
                try {
                    $baseName = $this->imageService->importFromPath($path, $p->name);
                    $p->logo_path = $baseName; // Store basename
                    $p->save();
                    $this->info("Optimized Party: {$p->name}");
                } catch (\Exception $e) {
                    $this->error("Failed Party {$p->name}: " . $e->getMessage());
                }
            }
        }
    }

    private function processSettings()
    {
        $keys = ['app_logo', 'letter_signature_path', 'letter_stamp_path'];
        $this->info("Processing Settings...");

        foreach ($keys as $key) {
            $setting = Setting::where('key', $key)->first();
            if ($setting && $setting->value) {
                // Check if already optimized (contains 'images/medium')
                if (str_contains($setting->value, 'images/medium')) {
                    continue;
                }

                // Remove 'storage/' prefix if present to find file on disk
                $relativePath = str_replace('storage/', '', $setting->value);
                
                if (Storage::disk('public')->exists($relativePath)) {
                    $path = Storage::disk('public')->path($relativePath);
                    try {
                        $baseName = $this->imageService->importFromPath($path, $key);
                        // For Settings, store the Full Path to Medium variant
                        $setting->value = 'storage/images/medium/' . $baseName . '.webp';
                        $setting->save();
                        $this->info("Optimized Setting: {$key}");
                    } catch (\Exception $e) {
                        $this->error("Failed Setting {$key}: " . $e->getMessage());
                    }
                }
            }
        }
    }
}
