<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ImportDptJob;
use Illuminate\Support\LazyCollection;

class DptService
{
    /**
     * Get paginated DPT list with filters.
     * read-only, safe for Octane.
     */
    public function getPaginated(Request $request, int $perPage = 10)
    {
        $query = Mahasiswa::latest();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nim', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->status == 'sudah-milih') {
            $query->whereNotNull('voted_at');
        } elseif ($request->status == 'belum-milih') {
            $query->whereNull('voted_at');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data)
    {
        // Format password from DOB (DDMMYYYY)
        $dobString = date('dmY', strtotime($data['dob']));
        $data['password'] = Hash::make($dobString);

        $mahasiswa = Mahasiswa::create($data);

        // No intense auditing involved here usually? Or add if needed.
        return $mahasiswa;
    }

    public function update(Mahasiswa $mahasiswa, array $data)
    {
        if (!empty($data['dob'])) {
            $dobString = date('dmY', strtotime($data['dob']));
            $data['password'] = Hash::make($dobString);
        } else {
            unset($data['dob']);
        }

        $mahasiswa->update($data);
        return $mahasiswa;
    }

    public function delete(Mahasiswa $mahasiswa)
    {
        return $mahasiswa->delete();
    }

    public function deleteAll()
    {
        // Use query()->delete() instead of truncate() to safely trigger 'onDelete cascade' 
        // and avoid foreign key constraint violations from related tables like 'votes'.
        $deleted = Mahasiswa::query()->delete();
        
        // Reset the auto-increment ID to 1 to match expected truncate behavior.
        DB::statement('ALTER TABLE mahasiswas AUTO_INCREMENT = 1;');
        
        return $deleted;
    }

    /**
     * Handle CSV Import in an Octane-safe, Memory-efficient way.
     */
    public function import($filePath, $userId)
    {
        $rows = LazyCollection::make(function () use ($filePath) {
            $handle = fopen($filePath, 'r');
            
            // Auto-detect delimiter
            $firstLine = fgets($handle);
            $delimiter = ',';
            if ($firstLine !== false && strpos($firstLine, ';') !== false) {
                $delimiter = ';';
            }
            rewind($handle);

            while (($line = fgetcsv($handle, 0, $delimiter)) !== false) {
                yield $line;
            }
            fclose($handle);
        })->skip(1); // Skip header

        // Filter valid rows
        $validRows = $rows->filter(function ($row) {
            return !empty($row[0]) && isset($row[2]); // NIM and something else exist
        });

        // Dispatch in chunks
        // collect() converts LazyCollection to array which defeats the purpose if HUGE,
        // but ImportDptJob takes an array chunk.
        // Better: iterate the lazy collection and build chunks manually to keep RAM low.
        
        $jobs = [];
        $chunkSize = 50;
        $currentChunk = [];

        foreach ($validRows as $row) {
            $currentChunk[] = $row;
            if (count($currentChunk) >= $chunkSize) {
                $jobs[] = new ImportDptJob($currentChunk);
                $currentChunk = [];
            }
        }
        
        // Remaining
        if (!empty($currentChunk)) {
            $jobs[] = new ImportDptJob($currentChunk);
        }

        if (empty($jobs)) {
            throw new \Exception('File CSV kosong atau tidak ada data valid.');
        }

        $batch = Bus::batch($jobs)
            ->name('Import DPT')
            ->dispatch();

        AuditLog::create([
            'user_id' => $userId,
            'user_name' => Auth::user()?->name ?? 'System',
            'action' => 'IMPORT DPT',
            'details' => 'Memulai import DPT batch ID: ' . $batch->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return $batch;
    }
}
