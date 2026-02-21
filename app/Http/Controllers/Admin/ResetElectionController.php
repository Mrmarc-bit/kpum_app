<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Mahasiswa;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResetElectionController extends Controller
{
    protected $service;

    public function __construct(\App\Services\ResetElectionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.reset.index', [
            'title' => 'Reset Election'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_text' => 'required|in:RESET ELECTION'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah. Tidak dapat melanjutkan reset.');
        }

        try {
            $this->service->nukeElectionData($user, $request->ip(), $request->userAgent());

            return redirect()->route('admin.dashboard')->with('success', '✅ Pemilihan berhasil di-reset! Semua data suara (Presma + DPM) telah dihapus dan mahasiswa dapat voting ulang.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Reset Election Failed', [
                'error' => $e->getMessage(),
                'user' => $user->name ?? null
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat mereset data: ' . $e->getMessage());
        }
    }
}
