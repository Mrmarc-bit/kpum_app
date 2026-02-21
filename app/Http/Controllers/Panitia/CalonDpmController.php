<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalonDpm;
use Illuminate\Support\Facades\Storage;
use App\Services\CalonDpmService;

class CalonDpmController extends Controller
{
    protected $service;

    public function __construct(CalonDpmService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Stateless read is safe.
        $calons = CalonDpm::orderBy('urutan_tampil', 'asc')->get();
        return view('panitia.calon_dpm.index', [
            'title' => 'Manajemen Calon DPM',
            'calons' => $calons
        ]);
    }

    public function create()
    {
        return view('panitia.calon_dpm.create', [
            'title' => 'Tambah Calon DPM'
        ]);
    }

    public function store(Request $request)
    {
        // Validation Layer
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fakultas' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'nullable|boolean',
            'urutan_tampil' => 'nullable|integer',
            'nomor_urut' => 'nullable|string|unique:calon_dpms,nomor_urut',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');

        // Delegate execution to Service (Octane Safe via Redis Locks inside service)
        try {
            $this->service->create($validated, $request->file('foto'));
            return redirect()->route('panitia.calon_dpm.index')->with('success', 'Calon DPM berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan calon: ' . $e->getMessage());
        }
    }

    public function edit(CalonDpm $calonDpm)
    {
        return view('panitia.calon_dpm.edit', [
            'title' => 'Edit Calon DPM',
            'calon' => $calonDpm
        ]);
    }

    public function update(Request $request, CalonDpm $calonDpm)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fakultas' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'nullable|boolean',
            'urutan_tampil' => 'nullable|integer',
            'nomor_urut' => 'nullable|string|unique:calon_dpms,nomor_urut,' . $calonDpm->id,
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');
        $validated['urutan_tampil'] = $request->input('urutan_tampil', $calonDpm->urutan_tampil);

        // Allow clearing nomor_urut if explicitly empty in input? 
        // Logic handled in service, but we pass the raw intent here.
        if (!$request->filled('nomor_urut')) {
             $validated['nomor_urut'] = null; 
        }

        try {
            $this->service->update($calonDpm, $validated, $request->file('foto'));
            return redirect()->route('panitia.calon_dpm.index')->with('success', 'Calon DPM berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update calon: ' . $e->getMessage());
        }
    }

    public function destroy(CalonDpm $calonDpm)
    {
        try {
            $this->service->delete($calonDpm);
            return redirect()->route('panitia.calon_dpm.index')->with('success', 'Calon DPM berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus calon: ' . $e->getMessage());
        }
    }
}
