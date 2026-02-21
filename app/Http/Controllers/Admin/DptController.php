<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\DptService;

class DptController extends Controller
{
    use AuthorizesRequests; // ← CRITICAL: Enable authorization
    
    protected $service;
    protected $letterService;

    public function __construct(DptService $service, \App\Services\ProofOfVoteService $letterService)
    {
        $this->service = $service;
        $this->letterService = $letterService;
    }

    public function generateAccessCodes()
    {
        // AUTHORIZATION CHECK: Prevent unauthorized access
        $this->authorize('generateAccessCodes', Mahasiswa::class);
        
        // Chunk update to avoid memory issues
        $qrService = new \App\Services\QrCodeService();
        $count = 0;
        
        Mahasiswa::whereNull('access_code')->chunkById(100, function ($students) use ($qrService, &$count) {
            foreach ($students as $student) {
                /** @var Mahasiswa $student */
                $student->update(['access_code' => $qrService->generateAccessCode()]);
                $count++;
            }
        }, 'id');

        return redirect()->back()->with('success', "Berhasil generate kode akses untuk $count mahasiswa.");
    }

    public function downloadLetter(Mahasiswa $mahasiswa)
    {
        // AUTHORIZATION CHECK: Prevent IDOR on letter download
        $this->authorize('downloadLetter', $mahasiswa);
        
        $pdf = $this->letterService->generateNotificationLetter($mahasiswa);
        return $pdf->download("Surat_Pemberitahuan_{$mahasiswa->nim}.pdf");
    }

    public function lettersIndex()
    {
        $history = \App\Models\ReportFile::where('type', 'letters')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('admin.letters.index', [
            'title' => 'Unduh Surat Pemberitahuan',
            'prodiList' => Mahasiswa::PRODI_LIST,
            'history' => $history
        ]);
    }

    public function downloadBatchLetters(Request $request)
    {
        // AUTHORIZATION CHECK: Prevent unauthorized batch downloads
        $this->authorize('downloadBatchLetters', Mahasiswa::class);
        
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

        // Create Tracking Record (Fast!)
        $reportFile = \App\Models\ReportFile::create([
            'user_id' => Auth::id(),
            'type' => 'letters',
            'status' => 'pending',
            'disk' => 'public',
            'path' => null,
            'details' => $details,
            'progress' => 0
        ]);

        // Dispatch to Queue (Background Processing - Non-Blocking!)
        \App\Jobs\GenerateLettersJob::dispatch($reportFile->id, $request->only(['prodi', 'fakultas']));

        return redirect()->route('admin.letters.index')
            ->with('success', '⚡ Download sedang diproses di background. Halaman akan auto-refresh setiap 2 detik.');
    }

    public function index(Request $request)
    {
        // Validation
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:sudah-milih,belum-milih',
        ]);

        if ($request->ajax()) {
            $mahasiswas = $this->service->getPaginated($request);
            return view('admin.dpt.table', compact('mahasiswas'))->render();
        }

        return view('admin.dpt.index', [
            'title' => 'DPT Mahasiswa',
            'mahasiswas' => $this->service->getPaginated($request),
            'prodiList' => Mahasiswa::PRODI_LIST
        ]);
    }

    public function create()
    {
        // AUTHORIZATION CHECK
        $this->authorize('create', Mahasiswa::class);
        
        return view('admin.dpt.create', [
            'title' => 'Tambah DPT Mahasiswa'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:mahasiswas,nim',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'prodi' => 'nullable|string',
            'fakultas' => 'nullable|string',
        ]);

        $this->service->create($validated);

        return redirect()->route('admin.dpt.index')->with('success', 'Mahasiswa berhasil ditambahkan ke DPT.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        // AUTHORIZATION CHECK: Prevent IDOR
        $this->authorize('update', $mahasiswa);
        
        return view('admin.dpt.edit', [
            'title' => 'Edit DPT Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodiList' => Mahasiswa::PRODI_LIST
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // AUTHORIZATION CHECK: Prevent IDOR
        $this->authorize('update', $mahasiswa);
        
        $validated = $request->validate([
            'nim' => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'prodi' => 'nullable|string',
            'fakultas' => 'nullable|string',
        ]);

        $this->service->update($mahasiswa, $validated);

        return redirect()->route('admin.dpt.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        // AUTHORIZATION CHECK: Critical - prevent unauthorized deletion
        $this->authorize('delete', $mahasiswa);
        
        $this->service->delete($mahasiswa);
        return redirect()->route('admin.dpt.index')->with('success', 'Mahasiswa dihapus dari DPT.');
    }

    public function destroyAll()
    {
        // AUTHORIZATION CHECK: ULTRA CRITICAL - bulk delete
        $this->authorize('deleteAll', Mahasiswa::class);
        
        // Careful with this in production
        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'DELETE ALL DPT',
            'details' => 'Menghapus seluruh data DPT.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        $this->service->deleteAll();

        return redirect()->route('admin.dpt.index')->with('success', 'Seluruh data DPT berhasil dihapus.');
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
        // AUTHORIZATION CHECK: Prevent unauthorized mass import
        $this->authorize('import', Mahasiswa::class);
        
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240'
        ]);

        try {
            $batch = $this->service->import($request->file('file')->getPathname(), Auth::id());
            
            if ($request->wantsJson()) {
                return response()->json(['batchId' => $batch->id]);
            }

            return redirect()->route('admin.dpt.index')->with('success', 'Import started. Batch ID: ' . $batch->id);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
}
