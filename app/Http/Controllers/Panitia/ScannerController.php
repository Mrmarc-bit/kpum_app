<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\QrCodeService;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScannerController extends Controller
{
    protected $qrService;

    public function __construct(QrCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function index()
    {
        return view('panitia.scanner.index', [
            'title' => 'Scanner Real-time'
        ]);
    }

    /**
     * Memproses verifikasi QR dan langsung mencatat kehadiran + status memilih
     */
    public function verify(Request $request) 
    {
        try {
            $request->validate(['qr_code' => 'required|string']);

            $data = $this->qrService->decryptSecureCode($request->qr_code);

            if (!$data || !isset($data['id'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'QR Code tidak valid atau rusak.'
                ], 422);
            }

            $mahasiswa = Mahasiswa::where('id', $data['id'])
                ->where('nim', $data['nim'])
                ->first();

            if (!$mahasiswa) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ], 404);
            }

            // 1. CEK STATUS VOTING (Prioritas: Mencegah Double Vote)
            // Jika sudah voted_at (Pemira) atau dpm_voted_at (DPM), maka tolak.
            if ($mahasiswa->voted_at || $mahasiswa->dpm_voted_at) {
                // Format waktu voting jika ada
                $historyTime = $mahasiswa->voted_at 
                    ? $mahasiswa->voted_at->format('H:i') 
                    : ($mahasiswa->dpm_voted_at ? $mahasiswa->dpm_voted_at->format('H:i') : '-');

                return response()->json([
                    'success' => false,
                    'is_duplicate' => true,
                    'message' => "DITOLAK! Mahasiswa tercatat SUDAH MEMILIH sebelumnya.",
                    'mahasiswa' => [
                        'name' => $mahasiswa->name,
                        'nim' => $mahasiswa->nim,
                        'time' => $historyTime . ' WIB'
                    ]
                ], 400);
            }

            // 2. CEK PRESENSI (Secondary Check)
            if (!is_null($mahasiswa->attended_at)) {
                return response()->json([
                    'success' => false,
                    'is_duplicate' => true,
                    'message' => 'QR Code ini sudah pernah dipindai sebelumnya.',
                    'mahasiswa' => [
                        'name' => $mahasiswa->name,
                        'nim' => $mahasiswa->nim,
                        'time' => $mahasiswa->attended_at->format('H:i') . ' WIB'
                    ]
                ], 400);
            }

            // PROSES OTOMATIS: Catat Kehadiran DAN Tandai sudah memilih (untuk in-person voting)
            // Jika Anda ingin membedakan hadir vs memilih, sesuaikan di bawah ini.
            // Sesuai permintaan user: QR sekali pakai & status berubah sudah memilih.
            $timestamp = Carbon::now();

            $mahasiswa->update([
                'attended_at' => $timestamp,
                'voted_at' => $mahasiswa->voted_at ?? $timestamp,
                'dpm_voted_at' => $mahasiswa->dpm_voted_at ?? $timestamp,
                'attendance_officer' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi Berhasil! Kehadiran dicatat.',
                'mahasiswa' => [
                    'name' => $mahasiswa->name,
                    'nim' => $mahasiswa->nim,
                    'prodi' => $mahasiswa->prodi,
                    'fakultas' => $mahasiswa->fakultas,
                    'time' => $timestamp->format('H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Scanner Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.'
            ], 500);
        }
    }
    /**
     * Cek data mahasiswa berdasarkan Kode Akses Unik (Manual Input)
     * Return data preview tanpa menyimpan kehadiran.
     */
    public function checkAccessCode(Request $request)
    {
        $request->validate(['access_code' => 'required|string']);

        $mahasiswa = Mahasiswa::where('access_code', $request->access_code)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Kode Akses tidak ditemukan.'
            ], 404);
        }

        // Cek Status (Optional: Beri warning jika sudah memilih, tapi tetap tampilkan data)
        return response()->json([
            'success' => true,
            'mahasiswa' => [
                'name' => $mahasiswa->name,
                'nim' => $mahasiswa->nim,
                'prodi' => $mahasiswa->prodi,
                'fakultas' => $mahasiswa->fakultas,
                'status_voted' => ($mahasiswa->voted_at || $mahasiswa->dpm_voted_at) ? true : false,
                'status_attended' => $mahasiswa->attended_at ? true : false
            ]
        ]);
    }

    /**
     * Verifikasi Manual dengan Kode Akses
     * Ditandai sebagai hadir offline / manual validation.
     */
    public function verifyAccessCode(Request $request)
    {
        try {
            $request->validate(['access_code' => 'required|string']);

            $mahasiswa = Mahasiswa::where('access_code', $request->access_code)->first();

            if (!$mahasiswa) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
            }

            // Cek Duplikasi
            if ($mahasiswa->voted_at || $mahasiswa->attended_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa ini SUDAH tercatat hadir/memilih sebelumnya.',
                    'mahasiswa' => [
                        'name' => $mahasiswa->name,
                        'nim' => $mahasiswa->nim
                    ]
                ], 400);
            }

            $timestamp = Carbon::now();

            $mahasiswa->update([
                'attended_at' => $timestamp,
                'voted_at' => $timestamp, // Sesuai logika verify QR, manual input juga dianggap sudah memilih (valid offline)
                'dpm_voted_at' => $timestamp,
                'attendance_officer' => Auth::id(),
                // 'voting_method' => 'manual_offline' // Jika ada kolom ini di masa depan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Validasi Manual Berhasil!',
                'mahasiswa' => [
                    'name' => $mahasiswa->name,
                    'nim' => $mahasiswa->nim,
                    'prodi' => $mahasiswa->prodi,
                    'time' => $timestamp->format('H:i') . ' WIB'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Manual Verify Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
    /**
     * Cek DPT Internal (Tanpa Sensor)
     * Digunakan untuk fitur "Cek DPT" di halaman scanner.
     */
    public function searchDpt(Request $request)
    {
        $request->validate(['nim' => 'required|string']);

        $nim = trim($request->nim);
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $mahasiswa->name, // Full name (Unsensored)
                    'nim' => $mahasiswa->nim,
                    'prodi' => $mahasiswa->prodi,
                    'status_voted' => ($mahasiswa->voted_at || $mahasiswa->dpm_voted_at) ? true : false,
                    'status_attended' => $mahasiswa->attended_at ? true : false,
                    'access_code' => $mahasiswa->access_code 
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
    }
}
