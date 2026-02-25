<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DptController extends Controller
{
    // PRODI_LIST moved to Mahasiswa Model

    protected $letterService;

    public function __construct(\App\Services\ProofOfVoteService $letterService)
    {
        $this->letterService = $letterService;
    }

    public function downloadLetter(Mahasiswa $mahasiswa)
    {
        ini_set('memory_limit', '512M');

        try {
            $pdf = $this->letterService->generateNotificationLetter($mahasiswa);
            return $pdf->download("Surat_Pemberitahuan_{$mahasiswa->nim}.pdf");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Panitia downloadLetter failed for ' . $mahasiswa->nim . ': ' . $e->getMessage());
            return response(
                '<div style="font-family:sans-serif; text-align:center; padding: 50px;">
                    <h1 style="color:red;">Gagal Mengunduh Surat (Error 500)</h1>
                    <p>PDF engine kehabisan memori saat memproses gambar/logo yang terlalu besar.</p>
                    <p><b>Solusi:</b> Kunjungi <b>Pengaturan Surat</b> lalu upload ulang Logo, Tanda Tangan, dan Stempel dengan ukuran yang lebih kecil (maks 500 KB per file).</p>
                    <div style="margin-top:20px; padding:15px; background:#f8d7da; color:#721c24; border-radius:5px; text-align:left; font-family:monospace; font-size:12px;">
                        <b>[Error]:</b> ' . htmlspecialchars($e->getMessage()) . '
                    </div>
                </div>',
            500);
        }
    }

    public function index(Request $request)
    {
        // Validate search input
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:sudah-milih,belum-milih',
        ]);

        $query = Mahasiswa::latest();

        // Search - sanitized and validated
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('nim', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter Status
        if ($request->status == 'sudah-milih') {
            $query->whereNotNull('voted_at');
        } elseif ($request->status == 'belum-milih') {
            $query->whereNull('voted_at');
        }

        $mahasiswas = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('panitia.dpt.table', compact('mahasiswas'))->render();
        }

        return view('panitia.dpt.index', [
            'title' => 'DPT Mahasiswa',
            'mahasiswas' => $mahasiswas,
            'prodiList' => Mahasiswa::PRODI_LIST
        ]);
    }

    public function create()
    {
        return view('panitia.dpt.create', [
            'title' => 'Tambah DPT Mahasiswa'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:mahasiswas,nim',
            'name' => 'required|string|max:255',
            'dob' => 'required|date', // User inputs Date, we hash it as password
            'prodi' => 'nullable|string',
            'fakultas' => 'nullable|string',
        ]);

        // Format DOB to DDMMYYYY for password
        $dobString = date('dmY', strtotime($validated['dob']));

        $validated['password'] = Hash::make($dobString);

        Mahasiswa::create($validated);

        return redirect()->route('panitia.dpt.index')->with('success', 'Mahasiswa berhasil ditambahkan ke DPT.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('panitia.dpt.edit', [
            'title' => 'Edit DPT Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodiList' => Mahasiswa::PRODI_LIST
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date', // Only if changing DOB
            'prodi' => 'nullable|string',
            'fakultas' => 'nullable|string',
        ]);

        if (!empty($validated['dob'])) {
            $dobString = date('dmY', strtotime($validated['dob']));
            $validated['password'] = Hash::make($dobString);
        } else {
            unset($validated['dob']); // Don't wipe DOB if not provided
        }

        $mahasiswa->update($validated);

        return redirect()->route('panitia.dpt.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('panitia.dpt.index')->with('success', 'Mahasiswa dihapus dari DPT.');
    }

    public function destroyAll()
    {
        Mahasiswa::query()->delete();

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'DELETE ALL DPT',
            'details' => 'Menghapus seluruh data DPT.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return redirect()->route('panitia.dpt.index')->with('success', 'Seluruh data DPT berhasil dihapus.');
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_dpt.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NIM', 'Nama Lengkap', 'Email', 'Tanggal Lahir (27 November 2003)', 'Prodi']);
            fputcsv($file, ['210001', 'Contoh Mahasiswa', 'email@contoh.com', '27 November 2003', 'Informatika (INF)']);
            fclose($file);
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240'
        ]);

        $file = $request->file('file');

        // Read file content
        $csvData = file_get_contents($file->getPathname());

        // Parse CSV
        $rows = array_map('str_getcsv', explode("\n", $csvData));

        // Remove header (row 0)
        $header = array_shift($rows);

        // Filter valid rows
        $rows = array_filter($rows, function ($row) {
            return !empty($row[0]) && isset($row[2]);
        });

        if (empty($rows)) {
            return redirect()->back()->with('error', 'File CSV kosong atau format salah.');
        }

        // Chunk data to jobs (e.g., 50 rows per job)
        $chunks = array_chunk($rows, 50);
        $jobs = [];

        foreach ($chunks as $chunk) {
            $jobs[] = new \App\Jobs\ImportDptJob($chunk);
        }

        $batch = \Illuminate\Support\Facades\Bus::batch($jobs)
            ->name('Import DPT')
            ->dispatch();

        if ($request->wantsJson()) {
            return response()->json(['batchId' => $batch->id]);
        }

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'IMPORT DPT',
            'details' => 'Memulai import DPT batch ID: ' . $batch->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return redirect()->route('panitia.dpt.index')->with('success', 'Import started. Batch ID: ' . $batch->id);
    }

    public function batchStatus($batchId)
    {
        $batch = \Illuminate\Support\Facades\Bus::findBatch($batchId);
        if (!$batch) {
            return response()->json(['error' => 'Batch not found'], 404);
        }

        return response()->json([
            'progress' => $batch->progress(),
            'finished' => $batch->finished(),
            'failed' => $batch->failedJobs > 0,
            'processedJobs' => $batch->processedJobs(),
            'totalJobs' => $batch->totalJobs
        ]);
    }

    public function downloadBatchLetters(Request $request)
    {
        $request->validate([
            'prodi' => 'required_without:fakultas|string',
            'fakultas' => 'required_without:prodi|string',
        ]);

        // Determine details string
        $details = 'Semua Data';
        if ($request->filled('prodi')) {
            $details = $request->prodi;
        } elseif ($request->filled('fakultas')) {
            $details = 'Fakultas ' . $request->fakultas;
        }

        // Create Tracking Record
        $reportFile = \App\Models\ReportFile::create([
            'user_id' => Auth::id(),
            'type' => 'letters',
            'status' => 'pending',
            'disk' => 'public',
            'path' => null, // Will be updated by job
            'details' => $details,
            'progress' => 0
        ]);

        // Dispatch Job
        \App\Jobs\GenerateLettersJob::dispatch($reportFile->id, $request->only(['prodi', 'fakultas']));

        return redirect()->route('panitia.letters.index')
            ->with('success', 'Permintaan download sedang diproses di latar belakang. Silakan cek tabel riwayat di bawah.');
    }

    public function lettersIndex()
    {
        $history = \App\Models\ReportFile::where('type', 'letters')
            ->latest()
            ->paginate(5);

        return view('panitia.letters.index', [
            'title' => 'Unduh Surat Pemberitahuan',
            'prodiList' => Mahasiswa::PRODI_LIST,
            'history' => $history
        ]);
    }
}
