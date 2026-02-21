<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KandidatController extends Controller
{
    protected $service;

    public function __construct(\App\Services\KandidatService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $kandidats = \App\Models\Kandidat::orderBy('no_urut')->get();
        return view('panitia.kandidat.index', [
            'title' => 'Manajemen Kandidat',
            'kandidats' => $kandidats
        ]);
    }

    public function create()
    {
        return view('panitia.kandidat.create', [
            'title' => 'Tambah Kandidat'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ketua' => 'required|string|max:255',
            'prodi_ketua' => 'nullable|string|max:255',
            'nama_wakil' => 'required|string|max:255',
            'prodi_wakil' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        $this->service->create(
            $validated, 
            $request->file('foto'),
            null // Panitia form handling matches Admin, assuming wakil foto logic same or adaptable
        );

        return redirect()->route('panitia.kandidat.index')->with('success', 'Kandidat berhasil ditambahkan!');
    }

    public function edit(\App\Models\Kandidat $kandidat)
    {
        return view('panitia.kandidat.edit', [
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
            'nama_wakil' => 'required|string|max:255',
            'prodi_wakil' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        $this->service->update(
            $kandidat, 
            $validated, 
            $request->file('foto'),
            null
        );

        return redirect()->route('panitia.kandidat.index')->with('success', 'Kandidat berhasil diperbarui');
    }

    public function destroy(\App\Models\Kandidat $kandidat)
    {
        $this->service->delete($kandidat);

        return redirect()->route('panitia.kandidat.index')->with('success', 'Kandidat berhasil dihapus');
    }
}
