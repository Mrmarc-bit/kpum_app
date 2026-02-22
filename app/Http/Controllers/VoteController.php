<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Vote;
use App\Models\CalonDpm;
use App\Models\DpmVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\VoteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Mahasiswa;

class VoteController extends Controller
{
    protected $voteService;

    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    public function index()
    {

        // 1. Check if CURRENTLY in a valid voting/simulation window
        $now = now();
        $isVotingActive = false;
        $isSimulation = false;
        
        // A. Check Official Time
        $votingStart = \App\Models\Setting::get('voting_start_time');
        $votingEnd = \App\Models\Setting::get('voting_end_time');
        
        if ($votingStart) {
            try {
                $startDateTime = \Carbon\Carbon::parse($votingStart);
                // If start time is past or now
                if ($now->greaterThanOrEqualTo($startDateTime)) {
                    // Check end time if set
                    if ($votingEnd) {
                        $endDateTime = \Carbon\Carbon::parse($votingEnd);
                        if ($now->lessThanOrEqualTo($endDateTime)) {
                            $isVotingActive = true;
                        }
                    } else {
                        // Open ended start
                        $isVotingActive = true; 
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Invalid voting time format', ['start' => $votingStart, 'end' => $votingEnd]);
            }
        }

        // B. Check Simulation Time (Override)
        if (!$isVotingActive) {
            $simStart = \App\Models\Setting::get('simulation_start_time');
            $simEnd = \App\Models\Setting::get('simulation_end_time');

            if ($simStart && $simEnd) {
                try {
                    $simStartDateTime = \Carbon\Carbon::parse($simStart);
                    $simEndDateTime = \Carbon\Carbon::parse($simEnd);
                    
                    if ($now->greaterThanOrEqualTo($simStartDateTime) && $now->lessThanOrEqualTo($simEndDateTime)) {
                        $isVotingActive = true;
                        $isSimulation = true;
                    }
                } catch (\Exception $e) {
                     Log::warning('Invalid simulation time format', ['start' => $simStart, 'end' => $simEnd]);
                }
            }
        }

        // If not active (neither official nor simulation), logout and redirect
        // However, we must be careful: if the official time hasn't started, we kick them out.
        // If official time is ENDED, we might want to show closed state instead of redirecting to login.
        // The previous logic was: if NOT started -> logout. If Started but Ended -> Show closed.
        
        // Refined Logic based on previous implementation preference:
        
        // 1. Determine "Has Started" status
        $hasStarted = false;
        
        // Check Official Start
        if ($votingStart) {
            $startDateTime = \Carbon\Carbon::parse($votingStart);
            if ($now->greaterThanOrEqualTo($startDateTime)) {
                $hasStarted = true;
            }
        }
        
        // Check Simulation Window (if in simulation, it "Has Started")
        if ($isSimulation) {
            $hasStarted = true;
        }

        if (!$hasStarted) {
            Auth::guard('mahasiswa')->logout();
            return redirect()->route('login.mahasiswa')->with('error', 'Pemilihan belum dimulai. Silakan tunggu waktu pemilihan dimulai.');
        }

        // 2. Determine "Is Closed" status
        $isVotingClosed = false;
        
        // Only check closed status if not in simulation (Simulation implies OPEN)
        if (!$isSimulation) {
            if ($votingEnd) {
                 try {
                    $endDateTime = \Carbon\Carbon::parse($votingEnd);
                    if ($now->greaterThan($endDateTime)) {
                        $isVotingClosed = true;
                    }
                } catch (\Exception $e) {}
            }
        }

        $kandidats = Kandidat::with('parties')->orderBy('no_urut')->get();
        
        /** @var Mahasiswa|null $user */
        $user = Auth::guard('mahasiswa')->user();
        if ($user) {
            $user = $user->fresh();
        } 
        
        $calonDpms = CalonDpm::with('parties')->where('status_aktif', true)
            ->orderBy('urutan_tampil')
            ->get();

        return view('dashboard', [
            'kandidats' => $kandidats,
            'calonDpms' => $calonDpms,
            'hasVotedPresma' => $user && !is_null($user->voted_at),
            'hasVotedDpm' => $user && !is_null($user->dpm_voted_at),
            'isVotingClosed' => $isVotingClosed,
            'isSimulation' => $isSimulation,
            'title' => 'Bilik Suara'
        ]);
    }

    public function store(Request $request)
    {
        // 1. Check Deadline
        $endTime = \App\Models\Setting::get('voting_end_time');
        if ($endTime) {
            try {
                $endDateTime = \Carbon\Carbon::parse($endTime);
                if (now()->greaterThan($endDateTime)) {
                    return back()->with('error', 'Mohon maaf, waktu pemilihan telah berakhir.');
                }
            } catch (\Exception $e) {
                // Ignore parsing error, allow vote as fail-open or fail-closed? 
                // Usually fail-safe means strictly logging it.
            }
        }

        // 2. Validate
        $validated = $request->validate([
            'kandidat_id' => 'required|integer',
            'type' => 'required|in:presma,dpm'
        ]);

        /** @var Mahasiswa|null $mahasiswa */
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa || !($mahasiswa instanceof Mahasiswa)) {
            return redirect()->route('login.mahasiswa')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            // 3. Delegate to Service
            if ($validated['type'] === 'presma') {
                $this->voteService->submitPresmaVote($mahasiswa, $validated['kandidat_id']);
            } else {
                $this->voteService->submitDpmVote($mahasiswa, $validated['kandidat_id']);
            }

            // 4. Post-Vote Actions (Email Proof)
            $freshMahasiswa = $mahasiswa->fresh();
            if ($freshMahasiswa->email && $freshMahasiswa->voted_at && $freshMahasiswa->dpm_voted_at) {
                // KIRIM ASYNC (Antrian) agar user tidak menunggu loading email
                try {
                    \App\Jobs\SendProofOfVoteJob::dispatch($freshMahasiswa)->onQueue('high');
                } catch (\Exception $e) {
                    Log::error('Gagal dispatch queue email bukti pilih: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Suara Anda berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Vote Failed', ['user' => $mahasiswa->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal merekam suara: ' . $e->getMessage());
        }
    }

    public function showKandidat(Kandidat $kandidat)
    {
        $kandidat->load('parties');
        return view('kandidat-detail', [
            'kandidat' => $kandidat,
            'title' => 'Visi & Misi Paslon #' . $kandidat->no_urut
        ]);
    }

    public function sendProofEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255'
        ]);

        /** @var Mahasiswa|null $mahasiswa */
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if (!$mahasiswa || !($mahasiswa instanceof Mahasiswa)) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        // Simpan email baru (jika user ingin update)
        $mahasiswa->forceFill(['email' => $validated['email']])->save();

        if ($mahasiswa->voted_at && $mahasiswa->dpm_voted_at) {
            // Dispatch Async (Antrian)
            try {
                \App\Jobs\SendProofOfVoteJob::dispatch($mahasiswa)->onQueue('high');
                return back()->with('success', 'Email bukti pilih sedang diproses dan akan dikirim ke ' . $mahasiswa->email);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Anda belum menyelesaikan proses pemilihan.');
    }
}
