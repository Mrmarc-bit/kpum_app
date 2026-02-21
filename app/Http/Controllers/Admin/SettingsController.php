<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'letter_signature_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'letter_stamp_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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
        return view('admin.settings.letters.notification', compact('settings'));
    }

    public function updateNotification(Request $request) 
    {
        $request->validate([
            'letter_signature_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'letter_stamp_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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
