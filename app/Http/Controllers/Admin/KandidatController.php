<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KandidatService;

class KandidatController extends Controller
{
    protected $service;

    public function __construct(KandidatService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Stateless read
        $kandidats = \App\Models\Kandidat::orderBy('no_urut')->get();
        return view('admin.kandidat.index', [
            'title' => 'Manajemen Kandidat',
            'kandidats' => $kandidats
        ]);
    }

    public function create()
    {
        return view('admin.kandidat.create', [
            'title' => 'Tambah Kandidat'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ketua' => 'required|string|max:255',
            'prodi_ketua' => 'nullable|string|max:255',
            'fakultas_ketua' => 'nullable|string|max:255',
            'nama_wakil' => 'required|string|max:255',
            'prodi_wakil' => 'nullable|string|max:255',
            'fakultas_wakil' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto Ketua
            'foto_wakil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto Wakil
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'slogan' => 'nullable|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'status_aktif' => 'nullable|boolean',
            'tampilkan_di_landing' => 'nullable|boolean',
            'urutan_tampil' => 'nullable|integer',
        ]);

        // Handle boolean fields
        $validated['status_aktif'] = $request->has('status_aktif');
        $validated['tampilkan_di_landing'] = $request->has('tampilkan_di_landing');
        $validated['urutan_tampil'] = $request->input('urutan_tampil', 0);

        // Delegate to Service
        try {
            $this->service->create(
                $validated, 
                $request->file('foto'), 
                $request->file('foto_wakil')
            );
            return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan kandidat: ' . $e->getMessage());
        }
    }

    public function edit(\App\Models\Kandidat $kandidat)
    {
        return view('admin.kandidat.edit', [
            'title' => 'Edit Kandidat',
            'kandidat' => $kandidat
        ]);
    }

    public function update(Request $request, \App\Models\Kandidat $kandidat)
    {
        $validated = $request->validate([
            'no_urut' => 'required|integer|unique:kandidats,no_urut,' . $kandidat->id,
            'nama_ketua' => 'required|string|max:255',
            'prodi_ketua' => 'nullable|string|max:255',
            'fakultas_ketua' => 'nullable|string|max:255',
            'nama_wakil' => 'required|string|max:255',
            'prodi_wakil' => 'nullable|string|max:255',
            'fakultas_wakil' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto Ketua
            'foto_wakil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto Wakil
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'slogan' => 'nullable|string|max:255',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'status_aktif' => 'nullable|boolean',
            'tampilkan_di_landing' => 'nullable|boolean',
            'urutan_tampil' => 'nullable|integer',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');
        $validated['tampilkan_di_landing'] = $request->has('tampilkan_di_landing');
        $validated['urutan_tampil'] = $request->input('urutan_tampil', 0);

        try {
            $this->service->update(
                $kandidat,
                $validated,
                $request->file('foto'),
                $request->file('foto_wakil')
            );
            return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update kandidat: ' . $e->getMessage());
        }
    }

    public function destroy(\App\Models\Kandidat $kandidat)
    {
        try {
            $this->service->delete($kandidat);
            return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kandidat: ' . $e->getMessage());
        }
    }
}
