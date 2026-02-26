<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected \App\Services\ImageService $imageService;

    public function __construct(\App\Services\ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        // Pass flag to view that this is panitia access (hide security section)
        return view('panitia.settings.index', [
            'title' => 'Pengaturan Sistem',
            'isPanitia' => true
        ]);
    }

    public function proof()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('panitia.settings.letters.proof', [
            'title' => 'Template Surat Bukti Pilih',
            'isPanitia' => true,
            'settings' => $settings
        ]);
    }

    public function updateProof(Request $request) 
    {
        $request->validate([
            'letter_signature_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
            'letter_stamp_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $data = $request->except('_token', '_method');

        if ($request->hasFile('letter_signature_path')) {
            $baseName = $this->imageService->upload($request->file('letter_signature_path'), 'signature');
            $data['letter_signature_path'] = 'storage/images/medium/' . $baseName . '.webp';
        }

        if ($request->hasFile('letter_stamp_path')) {
            $baseName = $this->imageService->upload($request->file('letter_stamp_path'), 'stamp');
            $data['letter_stamp_path'] = 'storage/images/medium/' . $baseName . '.webp';
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
        return view('panitia.settings.letters.notification', [
            'title' => 'Template Pemberitahuan',
            'isPanitia' => true,
            'settings' => $settings
        ]);
    }

    public function updateNotification(Request $request) 
    {

        $request->validate([
            'letter_signature_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
            'letter_stamp_path' => 'nullable|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        $data = $request->except('_token', '_method');

        if ($request->hasFile('letter_signature_path')) {
            $baseName = $this->imageService->upload($request->file('letter_signature_path'), 'signature');
            $data['letter_signature_path'] = 'storage/images/medium/' . $baseName . '.webp';
        }

        if ($request->hasFile('letter_stamp_path')) {
            $baseName = $this->imageService->upload($request->file('letter_stamp_path'), 'stamp');
            $data['letter_stamp_path'] = 'storage/images/medium/' . $baseName . '.webp';
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('global_settings');
        \Illuminate\Support\Facades\Cache::forget('global_settings_array');

        // Auto-invalidate PDF cache karena konten surat berubah
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
     * Invalidate semua cached notification letter PDF.
     */
    private function invalidateNotificationLetterCache(): void
    {
        \App\Models\Mahasiswa::whereNotNull('notification_letter_path')
            ->update(['notification_letter_path' => null]);

        $storage = Storage::disk('public');
        if ($storage->exists('letters')) {
            $files = $storage->allFiles('letters');
            if (!empty($files)) {
                $storage->delete($files);
            }
        }

        Log::info('Letter cache invalidated by ' . Auth::user()?->name . ' (' . Auth::user()?->role . ')');
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

        // Panitia CANNOT update security-related settings
        // Filter out restricted keys
        $data = $request->except('_token', '_method', 'app_logo', 'admin_password', 'api_key', 'secret_key');

        // List of checkbox/boolean settings
        $booleanSettings = ['show_candidates', 'enable_quick_count'];

        if ($request->hasFile('app_logo')) {
            $baseName = $this->imageService->upload($request->file('app_logo'), 'logo');
            $data['app_logo'] = 'storage/images/medium/' . $baseName . '.webp';
        }



        foreach ($booleanSettings as $key) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = 'false';
            }
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('global_settings');

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
