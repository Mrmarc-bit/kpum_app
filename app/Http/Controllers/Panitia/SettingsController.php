<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'letter_signature_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'letter_stamp_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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

        return back()->with('success', 'Template surat bukti pilih berhasil diperbarui.');
    }

    public function downloadSampleProof(\App\Services\ProofOfVoteService $service)
    {
        $pdf = $service->generateSampleProof();
        return $pdf->stream('sample-bukti-pilih.pdf');
    }

    public function downloadSampleNotification(\App\Services\ProofOfVoteService $service)
    {
        $pdf = $service->generateNotificationSample();
        return $pdf->stream('sample-pemberitahuan.pdf');
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
            'letter_signature_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'letter_stamp_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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

        return back()->with('success', 'Template surat pemberitahuan berhasil diperbarui.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
