<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

class ProofOfVoteController extends Controller
{
    public function download(\App\Services\ProofOfVoteService $proofService)
    {
        /** @var \App\Models\Mahasiswa|null $user */
        $user = Auth::guard('mahasiswa')->user();

        if (!$user) {
            return redirect()->route('login.mahasiswa')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!$user->voted_at && !$user->dpm_voted_at) {
            return back()->with('error', 'Anda belum melakukan pemilihan.');
        }

        $pdf = $proofService->generatePdf($user);

        return $pdf->download('Bukti_Pilih_' . $user->nim . '.pdf');
    }
}
