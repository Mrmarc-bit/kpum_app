<?php

namespace App\Services;

use App\Models\Kandidat;
use App\Models\CalonDpm;
use App\Models\Mahasiswa;
use App\Models\Vote;
use App\Models\DpmVote;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Services\VoteEncryptionService;

class DashboardService
{
    /**
     * Get Dashboard Statistics safely cached for high traffic.
     * Octane Strategy:
     * - Cache expensive "count(*)" queries for 15-30 seconds (or 5s for realtime-ish feel).
     * - Decryption is VERY CPU intensive, so result MUST be cached.
     */
    public function getStats()
    {
        // Cache heavy statistics for 30 seconds to prevent DB hammering from 1000 refresh/sec
        return Cache::remember('dashboard_stats_v2', 30, function () {
            
            $totalDpt = Mahasiswa::count();

            // --- VOTING METHOD STATS ---
            $offlineVoters = Mahasiswa::whereNotNull('attended_at')->count();
            
            // Online = Voted (Presma OR DPM) but NOT attended
            $onlineVoters = Mahasiswa::whereNull('attended_at')
                ->where(function ($q) {
                    $q->whereNotNull('voted_at')
                      ->orWhereNotNull('dpm_voted_at');
                })->count();

            // --- PRESMA STATS ---
            $sudahMemilihPresma = Mahasiswa::whereNotNull('voted_at')->count();
            $belumMemilihPresma = $totalDpt - $sudahMemilihPresma;
            $turnoutPresma = $totalDpt > 0 ? round(($sudahMemilihPresma / $totalDpt) * 100, 1) : 0;
            $totalKandidatPresma = Kandidat::count();

            // Calculate Presma Votes (Heavy Ops: Decryption)
            $presmaCounts = $this->calculateVotes(Vote::cursor()); 
            
            $kandidats = Kandidat::orderBy('no_urut')->get()->map(function ($k) use ($presmaCounts, $sudahMemilihPresma) {
                $votes = $presmaCounts[$k->id] ?? 0;
                $percentage = $sudahMemilihPresma > 0 ? round(($votes / $sudahMemilihPresma) * 100, 1) : 0;
                return [
                    'no_urut' => $k->no_urut,
                    'name' => $k->nama_ketua . ' & ' . $k->nama_wakil,
                    'votes' => $votes,
                    'percentage' => $percentage
                ];
            });

            // --- DPM STATS ---
            $sudahMemilihDpm = Mahasiswa::whereNotNull('dpm_voted_at')->count();
            $belumMemilihDpm = $totalDpt - $sudahMemilihDpm;
            $turnoutDpm = $totalDpt > 0 ? round(($sudahMemilihDpm / $totalDpt) * 100, 1) : 0;
            $totalCalonDpm = CalonDpm::count();

            // Calculate DPM Votes (Heavy Ops: Decryption)
            $dpmCounts = $this->calculateVotes(DpmVote::cursor(), true);

            $calonDpms = CalonDpm::orderBy('urutan_tampil')->get()->map(function ($c) use ($dpmCounts, $sudahMemilihDpm) {
                $votes = $dpmCounts[$c->id] ?? 0;
                $percentage = $sudahMemilihDpm > 0 ? round(($votes / $sudahMemilihDpm) * 100, 1) : 0;
                return [
                    'id' => $c->id,
                    'no_urut' => $c->urutan_tampil,
                    'name' => $c->nama,
                    'fakultas' => $c->fakultas ?? 'Umum',
                    'votes' => $votes,
                    'percentage' => $percentage
                ];
            });

            return [
                'totalDpt' => $totalDpt,
                'voters' => [
                    'online' => $onlineVoters,
                    'offline' => $offlineVoters
                ],
                'presma' => [
                    'sudah' => $sudahMemilihPresma,
                    'belum' => $belumMemilihPresma,
                    'turnout' => $turnoutPresma,
                    'totalCandidates' => $totalKandidatPresma,
                    'results' => $kandidats
                ],
                'dpm' => [
                    'sudah' => $sudahMemilihDpm,
                    'belum' => $belumMemilihDpm,
                    'turnout' => $turnoutDpm,
                    'totalCandidates' => $totalCalonDpm,
                    'results' => $calonDpms
                ]
            ];
        });
    }

    /**
     * Get recent activity feed.
     * Less aggressive caching (e.g. 5 seconds) or live depending on requirement.
     */
    public function getRecentActivities()
    {
        return Cache::remember('dashboard_activities', 5, function () {
            $limit = 10;
            $latestPresma = Mahasiswa::whereNotNull('voted_at')
                ->select('id', 'name', 'nim', 'voted_at', 'attended_at')
                ->orderBy('voted_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(fn($m) => [
                    'type' => 'Presma',
                    'name' => $m->name,
                    'timestamp' => $m->voted_at,
                    'time_diff' => Carbon::parse($m->voted_at)->diffForHumans(),
                    'method' => $m->attended_at ? 'Offline' : 'Online'
                ]);

            $latestDpm = Mahasiswa::whereNotNull('dpm_voted_at')
                ->select('id', 'name', 'nim', 'dpm_voted_at', 'attended_at')
                ->orderBy('dpm_voted_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(fn($m) => [
                    'type' => 'DPM',
                    'name' => $m->name,
                    'timestamp' => $m->dpm_voted_at,
                    'time_diff' => Carbon::parse($m->dpm_voted_at)->diffForHumans(),
                    'method' => $m->attended_at ? 'Offline' : 'Online'
                ]);

            return collect($latestPresma)->concat($latestDpm)
                ->sortByDesc('timestamp')
                ->take($limit)
                ->values(); // Reset keys for JSON/Array
        });
    }

    /**
     * Calculate votes from cursor to minimize memory usage.
     */
    private function calculateVotes($cursor, $isDpm = false)
    {
        $counts = [];
        foreach ($cursor as $vote) {
            try {
                $column = $isDpm ? 'calon_dpm_id' : 'kandidat_id';
                $value = $vote->$column;

                if (empty($vote->encryption_meta) && is_numeric($value)) {
                    $decryptedId = $value;
                } else {
                    $decryptedId = VoteEncryptionService::decryptVote($value, $vote->encryption_meta);
                }

                if (!isset($counts[$decryptedId])) {
                    $counts[$decryptedId] = 0;
                }
                $counts[$decryptedId]++;
            } catch (\Exception $e) {
                // Ignore tampered/invalid votes
            }
        }
        return $counts;
    }
}
