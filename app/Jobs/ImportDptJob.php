<?php

namespace App\Jobs;

use App\Models\Mahasiswa;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ImportDptJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $rows;

    /**
     * Create a new job instance.
     */
    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        foreach ($this->rows as $row) {
            // New CSV Format: NIM, Name, Email, DOB (27 November 2003), Prodi
            try {
                $nim = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $email = trim($row[2] ?? '');
                $dobRaw = trim($row[3] ?? '');
                $prodi = isset($row[4]) ? trim($row[4]) : null;

                // SECURITY: CSV INJECTION PREVENTION
                $nim = $this->sanitizeForCsv($nim);
                $name = $this->sanitizeForCsv($name);
                $email = $this->sanitizeForCsv($email);
                $dobRaw = $this->sanitizeForCsv($dobRaw);
                $prodi = $this->sanitizeForCsv($prodi);

                if (empty($nim) || empty($name) || empty($dobRaw))
                    continue;

                // XSS Safety: Skip row if it contains script tags in the name
                if (\Illuminate\Support\Str::contains($name, '<script', true)) {
                    \Illuminate\Support\Facades\Log::warning("Import DPT: Skipped row due to potential XSS in name: " . json_encode($row));
                    continue;
                }

                // Parse Indonesian Date: "27 November 2003" -> Y-m-d
                $dobDate = $this->parseIndonesianDate($dobRaw);
                if (!$dobDate)
                    continue; // Skip invalid date

                $dobYMD = $dobDate->format('Y-m-d');
                $password = $dobDate->format('dmY');

                // Auto-determine Fakultas from Prodi
                $fakultas = null;
                if ($prodi) {
                    if (isset(Mahasiswa::PRODI_LIST[$prodi])) {
                        $fakultas = Mahasiswa::PRODI_LIST[$prodi];
                    } else {
                        // Fuzzy Match: Check if CSV prodi is part of the Key (e.g. "Sistem Informasi" matches "Sistem Informasi (SI)")
                        foreach (Mahasiswa::PRODI_LIST as $key => $val) {
                            if (\Illuminate\Support\Str::contains(strtolower($key), strtolower($prodi))) {
                                $fakultas = $val;
                                $prodi = $key; // Normalize the Prodi name to the standard layout
                                break;
                            }
                        }
                    }
                }

                // Uppercase Name
                $name = strtoupper($name);

                Mahasiswa::updateOrCreate(
                    ['nim' => $nim],
                    [
                        'name' => $name,
                        'email' => $email ?: null,
                        'dob' => $dobYMD,
                        'password' => Hash::make($password),
                        'prodi' => $prodi,
                        'fakultas' => $fakultas
                    ]
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Import DPT Failed Row: " . json_encode($row) . " Error: " . $e->getMessage());
                continue;
            }
        }
    }

    private function parseIndonesianDate($dateString)
    {
        try {
            if (empty($dateString)) return null;

            // Map Indonesian month names to English
            $months = [
                'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
                'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
                'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
                'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December'
            ];

            // Normalize typical separators to dashes for consistent parsing
            $dateString = str_replace(['/', '.', '\\'], '-', trim($dateString));

            // Replace Indonesian month with English
            $englishDate = str_replace(array_keys($months), array_values($months), $dateString);
            
            // Handle explicit "DD-MM-YYYY" formats which might otherwise be parsed as "MM-DD-YYYY"
            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $englishDate)) {
                return \Carbon\Carbon::createFromFormat('d-m-Y', $englishDate)->startOfDay();
            }

            // Parse the date using Carbon fallback for written months/other generic formats
            return \Carbon\Carbon::parse($englishDate)->startOfDay();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Import DPT: Failed to parse date string: '{$dateString}' - Error: " . $e->getMessage());
            return null;
        }
    }

    private function sanitizeForCsv($field)
    {
        if ($field && in_array($field[0], ['=', '+', '-', '@'])) {
            return "'" . $field;
        }
        return $field;
    }
}
