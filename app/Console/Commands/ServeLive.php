<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ServeLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve:live {--port=8080 : The port to serve the application on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application locally and expose it via Cloudflare Tunnel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $port = $this->option('port');
        $this->info("🚀 Starting KPUM Server & Cloudflare Tunnel...");
        $this->newLine();

        $this->info("🌍 Public URL: https://kpum.web.id");
        $this->info("🏠 Local URL : http://127.0.0.1:{$port}");
        $this->newLine();

        $this->comment("Press Ctrl+C to stop sharing.");

        // Start PHP Server in background
        $serve = popen("php artisan serve --port={$port} --host=0.0.0.0 2>&1", 'r');
        
        // Start Cloudflare Tunnel
        // Note: Using the tunnel name 'kpum-tunnel' we created earlier
        $tunnel = popen("cloudflared tunnel run --url http://127.0.0.1:{$port} kpum-tunnel 2>&1", 'r');

        // Keep the command running to monitor output (simple implementation)
        while (!feof($serve) && !feof($tunnel)) {
            $read = [$serve, $tunnel];
            $write = null;
            $except = null;
            
            // Wait for output on either stream
            if (stream_select($read, $write, $except, 1) > 0) {
                foreach ($read as $stream) {
                    $line = fgets($stream);
                    if ($line) {
                        // Filter out messy raw output if desired, or just print
                        if ($stream === $serve) {
                            $this->line("  [Laravel] " . trim($line));
                        } else {
                            // Suppress verbose cloudflare logs or show them
                            if (Str::contains($line, 'ERR') || Str::contains($line, 'WRN')) {
                                $this->error("  [Tunnel]  " . trim($line));
                            }
                        }
                    }
                }
            }
        }
    }
}
