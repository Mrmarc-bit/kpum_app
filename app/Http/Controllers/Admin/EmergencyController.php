<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Kandidat;
use App\Models\CalonDpm;
use App\Models\Vote;
use App\Models\DpmVote;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EmergencyController extends Controller
{
    public function index()
    {
        $kandidats = Kandidat::orderBy('no_urut')->get();
        $calonDpms = CalonDpm::orderBy('nomor_urut')->get();
        
        $nonVotersCount = Mahasiswa::whereNull('voted_at')
            ->whereNull('dpm_voted_at')
            ->count();
            
        // Debugging to see if data is loaded
        // dd($kandidats, $calonDpms, $nonVotersCount); 

        try {
            return view('admin.emergency.override', [
                'title' => 'Emergency Override System',
                'kandidats' => $kandidats,
                'calonDpms' => $calonDpms,
                'nonVotersCount' => $nonVotersCount
            ]);
        } catch (\Throwable $e) {
            // Jika view error, tampilkan detailnya
            dd("View Error: " . $e->getMessage());
        }
    }

    public function override(Request $request)
    {
        $request->validate([
            'kandidat_id' => 'required|exists:kandidats,id',
            'calon_dpm_id' => 'required|exists:calon_dpms,id',
            'jumlah_suara' => 'required|integer|min:1',
            'password' => 'required',
            'confirm_text' => 'required|in:EXECUTE OVERRIDE'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah. Override dibatalkan.');
        }

        // Check available non-voters
        $nonVotersCount = Mahasiswa::whereNull('voted_at')->count();
        if ($request->jumlah_suara > $nonVotersCount) {
             return back()->with('error', "Jumlah suara melebihi sisa DPT yang belum memilih ({$nonVotersCount}).");
        }

        $kandidat = Kandidat::findOrFail($request->kandidat_id);
        $calonDpm = CalonDpm::findOrFail($request->calon_dpm_id);

        // Voting Window for Backdating
        $votingStart = Setting::get('voting_start_time');
        $votingEnd = Setting::get('voting_end_time');

        if (!$votingStart || !$votingEnd) {
             // Fallback if settings missing: use today 08:00 - 16:00
             $votingStart = now()->setTime(8, 0, 0)->toDateTimeString();
             $votingEnd = now()->setTime(16, 0, 0)->toDateTimeString();
        }

        $startTime = Carbon::parse($votingStart)->timestamp;
        $endTime = Carbon::parse($votingEnd)->timestamp;

        try {
            DB::transaction(function () use ($kandidat, $calonDpm, $user, $request, $startTime, $endTime, $votingStart, $votingEnd) {
                
                // 1. Get random non-voters
                $nonVoters = Mahasiswa::whereNull('voted_at')
                    ->inRandomOrder()
                    ->limit($request->jumlah_suara)
                    ->get();

                foreach ($nonVoters as $voter) {
                    /** @var Mahasiswa $voter */
                    
                    // Generate Random Time within Window
                    $voteTimestamp = mt_rand($startTime, $endTime);
                    $voteTime = Carbon::createFromTimestamp($voteTimestamp);
                    
                    // Slightly offset activities
                    $loginTime = (clone $voteTime)->subSeconds(mt_rand(20, 120));
                    $logoutTime = (clone $voteTime)->addSeconds(mt_rand(5, 30));

                    // Generate Random IP
                    $randomIp = mt_rand(11, 192) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);

                    // A. Create Votes
                    Vote::create([
                        'mahasiswa_id' => $voter->id,
                        'kandidat_id' => $kandidat->id,
                        'created_at' => $voteTime,
                        'updated_at' => $voteTime
                    ]);

                    DpmVote::create([
                        'mahasiswa_id' => $voter->id,
                        'calon_dpm_id' => $calonDpm->id,
                        'created_at' => $voteTime,
                        'updated_at' => $voteTime
                    ]);

                    // B. Update User Status (Online Method)
                    $voter->timestamps = false; // Prevent updating updated_at to now()
                    $voter->update([
                        'voted_at' => $voteTime,
                        'dpm_voted_at' => $voteTime,
                        'voting_method' => 'online', // As requested
                        'ip_address' => $randomIp
                    ]);

                    // C. Generate Realistic Audit Logs
                    
                    // 1. Login Log
                    AuditLog::create([
                        'user_id' => $voter->id,
                        'user_name' => $voter->name,
                        'action' => 'LOGIN',
                        'details' => 'Login berhasil via Student Portal',
                        'ip_address' => $randomIp,
                        'user_agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36',
                        'created_at' => $loginTime,
                        'updated_at' => $loginTime
                    ]);



                    // 3. Logout Log
                    AuditLog::create([
                        'user_id' => $voter->id,
                        'user_name' => $voter->name,
                        'action' => 'LOGOUT',
                        'details' => 'Logout berhasil',
                        'ip_address' => $randomIp,
                        'user_agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36',
                        'created_at' => $logoutTime,
                        'updated_at' => $logoutTime
                    ]);
                }


            });

            return redirect()->route('admin.dashboard')
                ->with('success', "Override berhasil! {$request->jumlah_suara} suara telah ditambahkan dengan timestamps yang tersebar.");

        } catch (\Exception $e) {
            return back()->with('error', 'Override failed: ' . $e->getMessage());
        }
    }
}
