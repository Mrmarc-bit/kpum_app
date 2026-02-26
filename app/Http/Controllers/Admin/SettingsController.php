<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected $service;

    public function __construct(\App\Services\SettingsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function maintenance()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.maintenance', compact('settings'));
    }

    public function proof()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.letters.proof', compact('settings'));
    }

    public function updateProof(Request $request) 
    {
        $request->validate([
            'letter_signature_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
            'letter_stamp_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $data = $request->except('_token', '_method');

        if ($request->hasFile('letter_signature_path')) {
            $file = $request->file('letter_signature_path');
            if ($file->isValid()) {
                $filename = 'signature_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->put('settings/' . $filename, file_get_contents($file));
                $data['letter_signature_path'] = 'storage/settings/' . $filename;
            }
        }

        if ($request->hasFile('letter_stamp_path')) {
            $file = $request->file('letter_stamp_path');
            if ($file->isValid()) {
                $filename = 'stamp_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->put('settings/' . $filename, file_get_contents($file));
                $data['letter_stamp_path'] = 'storage/settings/' . $filename;
            }
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('global_settings');
        \Illuminate\Support\Facades\Cache::forget('global_settings_array');

        return back()->with('success', 'Template surat bukti pilih berhasil diperbarui.');
    }

    public function downloadSampleProof(\App\Services\ProofOfVoteService $service)
    {
        ini_set('memory_limit', '512M');
        try {
            $pdf = $service->generateSampleProof();
            return $pdf->stream('sample-bukti-pilih.pdf');
        } catch (\Exception $e) {
            return response(
                '<div style="font-family:sans-serif; text-align:center; padding: 50px;">
                    <h1 style="color:red;">Gagal Menampilkan PDF (Error 500)</h1>
                    <p>Sepertinya PDF engine kehabisan memori. Hal ini biasanya terjadi karena Anda baru saja mengunggah <b>Gambar/Logo/Tanda Tangan/Stempel yang ukurannya terlalu besar</b> (di atas 1MB).</p>
                    <p><b>Solusi:</b> Silakan kompres dan <b>Upload ulang Logo Website, Tanda Tangan, dan Stempel</b> dengan ukuran maksimal <b>500 KB</b> per gambarnya.</p>
                    <div style="margin-top:20px; padding:15px; background:#f8d7da; color:#721c24; border-radius:5px; text-align:left; font-family:monospace; font-size:12px;">
                        <b>[Error Debug]:</b> ' . $e->getMessage() . '
                    </div>
                </div>', 
            500);
        }
    }

    public function downloadSampleNotification(\App\Services\ProofOfVoteService $service)
    {
        ini_set('memory_limit', '512M');
        try {
            $pdf = $service->generateNotificationSample();
            return $pdf->stream('sample-pemberitahuan.pdf');
        } catch (\Exception $e) {
            return response(
                '<div style="font-family:sans-serif; text-align:center; padding: 50px;">
                    <h1 style="color:red;">Gagal Menampilkan PDF (Error 500)</h1>
                    <p>Sepertinya PDF engine kehabisan memori. Hal ini biasanya terjadi karena Anda baru saja mengunggah <b>Gambar/Logo/Tanda Tangan/Stempel yang ukurannya terlalu besar</b> (di atas 1MB).</p>
                    <p><b>Solusi:</b> Silakan kompres dan <b>Upload ulang Logo Website, Tanda Tangan, dan Stempel</b> dengan ukuran maksimal <b>500 KB</b> per gambarnya.</p>
                    <div style="margin-top:20px; padding:15px; background:#f8d7da; color:#721c24; border-radius:5px; text-align:left; font-family:monospace; font-size:12px;">
                        <b>[Error Debug]:</b> ' . $e->getMessage() . '
                    </div>
                </div>', 
            500);
        }
    }

    public function notification()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.letters.notification', compact('settings'));
    }

    public function updateNotification(Request $request) 
    {
        $request->validate([
            'letter_signature_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
            'letter_stamp_path'     => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $data = $request->except('_token', '_method');

        if ($request->hasFile('letter_signature_path')) {
            $file = $request->file('letter_signature_path');
            if ($file->isValid()) {
                $filename = 'signature_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->put('settings/' . $filename, file_get_contents($file));
                $data['letter_signature_path'] = 'storage/settings/' . $filename;
            }
        }

        if ($request->hasFile('letter_stamp_path')) {
            $file = $request->file('letter_stamp_path');
            if ($file->isValid()) {
                $filename = 'stamp_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->put('settings/' . $filename, file_get_contents($file));
                $data['letter_stamp_path'] = 'storage/settings/' . $filename;
            }
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('global_settings');
        \Illuminate\Support\Facades\Cache::forget('global_settings_array');

        // Auto-invalidate: hapus semua cache PDF notification letter
        // karena konten surat sudah berubah (tanda tangan, tanggal, dll)
        $this->invalidateNotificationLetterCache();

        return back()->with('success', '✅ Template berhasil diperbarui & cache PDF surat otomatis di-reset.');
    }

    /**
     * Manual cache reset — dipanggil dari tombol "Reset Cache Surat".
     */
    public function clearLetterCache()
    {
        $this->invalidateNotificationLetterCache();
        return back()->with('success', '🔄 Cache surat pemberitahuan berhasil direset. PDF akan digenerate ulang saat download berikutnya.');
    }

    /**
     * Hapus semua cached notification letter PDF:
     * 1. Null-kan notification_letter_path di DB (agar next download generate ulang)
     * 2. Hapus file fisik dari storage/public/letters/
     */
    private function invalidateNotificationLetterCache(): void
    {
        // Null-kan kolom di DB (batch update, sangat cepat)
        \App\Models\Mahasiswa::whereNotNull('notification_letter_path')
            ->update(['notification_letter_path' => null]);

        // Hapus semua file PDF dari folder storage/public/letters/
        $storage = \Illuminate\Support\Facades\Storage::disk('public');
        if ($storage->exists('letters')) {
            // Hapus semua file dalam folder letters (semua subfolder/prodi)
            $files = $storage->allFiles('letters');
            if (!empty($files)) {
                $storage->delete($files);
            }
        }

        Log::info('Notification letter cache invalidated by ' . Auth::user()?->name . ' (' . Auth::user()?->role . ')');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'contact_person' => 'nullable|string|max:20',
            'email_kpum' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'simulation_start_time' => 'nullable|date',
            'simulation_end_time' => 'nullable|date',
        ]);

        $data = $request->except('_token', '_method', 'app_logo');
        
        $this->service->updateSettings($data, $request->file('app_logo'));

        // Special message for encryption level changes (Logic retained for UX)
        $message = 'Pengaturan berhasil diperbarui.';
        if (isset($data['encryption_level'])) {
            $levelLabels = [
                'standard' => 'Standard (AES-256)',
                'high' => 'High (End-to-End)',
                'blockchain' => 'Blockchain Mode'
            ];
            $message = 'Pengaturan berhasil diperbarui! Mode enkripsi aktif: ' . ($levelLabels[$data['encryption_level']] ?? $data['encryption_level']);
        }

        return back()->with('success', $message);
    }

    public function updateMaintenance(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'nullable|in:0,1',
            'maintenance_message' => 'nullable|string|max:500',
            'maintenance_end_time' => 'nullable|date',
        ]);

        $isMaintenanceOn = $request->input('maintenance_mode', '0') === '1';
        
        $this->service->updateMaintenance(
            $isMaintenanceOn,
            $request->input('maintenance_message', ''),
            $request->input('maintenance_end_time')
        );

        return back()->with('success', 'Pengaturan maintenance berhasil diperbarui. Status: ' . ($isMaintenanceOn ? 'AKTIF' : 'NONAKTIF'));
    }
}
