<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KandidatService;
use App\Models\Party;

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
            'title'   => 'Tambah Kandidat',
            'parties' => Party::orderBy('name')->get(), // Untuk pilihan koalisi
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

        // Validate also party_ids (array of party ids for koalisi)
        $request->validate([
            'party_ids'   => 'nullable|array',
            'party_ids.*' => 'integer|exists:parties,id',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');
        $validated['tampilkan_di_landing'] = $request->has('tampilkan_di_landing');
        $validated['urutan_tampil'] = $request->input('urutan_tampil', 0);

        try {
            $kandidat = $this->service->create(
                $validated,
                $request->file('foto'),
                $request->file('foto_wakil')
            );

            // Sync koalisi partai (safe — table pivot sudah ada)
            $partyIds = $request->input('party_ids', []);
            $kandidat->parties()->sync($partyIds);

            return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan kandidat: ' . $e->getMessage());
        }
    }

    public function edit(\App\Models\Kandidat $kandidat)
    {
        return view('admin.kandidat.edit', [
            'title'     => 'Edit Kandidat',
            'kandidat'  => $kandidat->load('parties'), // load koalisi yang sudah ada
            'parties'   => Party::orderBy('name')->get(),
            'selectedPartyIds' => $kandidat->parties->pluck('id')->toArray(),
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

        // Validate party_ids
        $request->validate([
            'party_ids'   => 'nullable|array',
            'party_ids.*' => 'integer|exists:parties,id',
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

            // Sync koalisi partai — sync() otomatis delete yg lama & insert yg baru
            $partyIds = $request->input('party_ids', []);
            $kandidat->parties()->sync($partyIds);

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
