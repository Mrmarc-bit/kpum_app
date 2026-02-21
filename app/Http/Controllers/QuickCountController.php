<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kandidat;
use App\Models\CalonDpm;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class QuickCountController extends Controller
{
    public function index()
    {
        // Check if Quick Count feature is enabled
        $enabled = Setting::where('key', 'enable_quick_count')->value('value') === 'true';

        if (!$enabled && !auth()->guard('web')->check()) {
            return redirect('/')->with('error', 'Quick Count belum dibuka untuk publik.');
        }

        return view('quick-count', [
            'settings' => Setting::pluck('value', 'key')->all()
        ]);
    }

    public function getData()
    {
        // Security Check
        $enabled = Setting::where('key', 'enable_quick_count')->value('value') === 'true';
        if (!$enabled && !auth()->guard('web')->check()) {
            return response()->json(['error' => 'Feature disabled'], 403);
        }

        // RESOURCE PROTECTION: CACHE FOR 30 SECONDS
        // This prevents 1000 users from triggering 1000 decryption loops simultaneously.
        $data = \Illuminate\Support\Facades\Cache::remember('quick_count_data_array', 30, function () {
            
            // 1. Participation Stats
            $dptCount = \App\Models\Mahasiswa::count();
            $votesCount = \App\Models\Mahasiswa::whereNotNull('voted_at')->count();
            $participationRate = $dptCount > 0 ? round(($votesCount / $dptCount) * 100, 1) : 0;

            // 2. Decrypt & Tally Votes
            $presmaTally = $this->calculatePresmaTally();
            $dpmTally = $this->calculateDpmTally();

            // 3. Map Results
            $kandidats = $this->getPresmaResults($presmaTally);
            $calonDpms = $this->getDpmResults($dpmTally);

            return [
                'meta' => [
                    'updated_at' => now()->format('H:i:s'),
                    'total_dpt' => $dptCount,
                    'total_voted' => $votesCount,
                    'participation_rate' => $participationRate
                ],
                'presma' => $kandidats,
                'dpm' => $calonDpms
            ];
        });

        return response()->json($data);
    }

    private function calculatePresmaTally(): array
    {
        $presmaVotesRaw = \App\Models\Vote::all(['kandidat_id', 'encryption_meta']);
        $presmaTally = [];
        
        foreach ($presmaVotesRaw as $vote) {
            try {
                // HANDLE UNENCRYPTED/LEGACY DATA
                if (is_numeric($vote->kandidat_id) && is_null($vote->encryption_meta)) {
                    $decryptedId = (int) $vote->kandidat_id;
                } 
                // HANDLE ENCRYPTED DATA
                else {
                    $decryptedId = \App\Services\VoteEncryptionService::decryptVote(
                        $vote->kandidat_id, 
                        $vote->encryption_meta
                    );
                }
                
                if (!isset($presmaTally[$decryptedId])) {
                    $presmaTally[$decryptedId] = 0;
                }
                $presmaTally[$decryptedId]++;
            } catch (\Exception $e) {
                // Log error for debugging if needed, but keep going
            }
        }

        return $presmaTally;
    }

    private function calculateDpmTally(): array
    {
        $dpmVotesRaw = \App\Models\DpmVote::all(['calon_dpm_id', 'encryption_meta']);
        $dpmTally = [];

        foreach ($dpmVotesRaw as $vote) {
            try {
                if (is_numeric($vote->calon_dpm_id) && is_null($vote->encryption_meta)) {
                    $decryptedId = (int) $vote->calon_dpm_id;
                } else {
                    $decryptedId = \App\Services\VoteEncryptionService::decryptVote(
                        $vote->calon_dpm_id, 
                        $vote->encryption_meta
                    );
                }
                
                if (!isset($dpmTally[$decryptedId])) {
                    $dpmTally[$decryptedId] = 0;
                }
                $dpmTally[$decryptedId]++;
            } catch (\Exception $e) {
                // Ignore
            }
        }

        return $dpmTally;
    }

    private function getPresmaResults(array $presmaTally)
    {
        return Kandidat::where('status_aktif', true)
            ->orderBy('no_urut')
            ->get()
            ->map(function ($k) use ($presmaTally) {
                $myVotes = $presmaTally[$k->id] ?? 0;
                $totalValidVotes = array_sum($presmaTally);

                return [
                    'id' => $k->id,
                    'no_urut' => $k->no_urut,
                    'name' => $k->nama_ketua . ' & ' . $k->nama_wakil,
                    'foto' => $k->foto ? asset('storage/' . $k->foto) : null,
                    'votes' => $myVotes,
                    'percentage' => $totalValidVotes > 0 ? round(($myVotes / $totalValidVotes) * 100, 1) : 0,
                    'color' => $this->getChartColor($k->no_urut)
                ];
            });
    }

    private function getDpmResults(array $dpmTally)
    {
        $totalDpmVotes = array_sum($dpmTally);

        return CalonDpm::where('status_aktif', true)
            ->orderBy('urutan_tampil')
            ->get()
            ->map(function ($c) use ($dpmTally, $totalDpmVotes) {
                 $myVotes = $dpmTally[$c->id] ?? 0;
                return [
                    'id' => $c->id,
                    'name' => $c->nama,
                    'fakultas' => $c->fakultas,
                    'foto' => $c->foto ? asset('storage/' . $c->foto) : null,
                    'votes' => $myVotes,
                    'percentage' => $totalDpmVotes > 0 ? round(($myVotes / $totalDpmVotes) * 100, 1) : 0
                ];
            })
            ->sortByDesc('votes')
            ->values();
    }

    private function getChartColor($index)
    {
        $colors = [
            '#4F46E5', // Indigo
            '#E11D48', // Rose
            '#059669', // Emerald
            '#D97706', // Amber
            '#0EA5E9', // Sky
        ];
        return $colors[($index - 1) % count($colors)];
    }
}
