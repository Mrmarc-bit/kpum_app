<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parties = Party::latest()->paginate(10);
        return view('panitia.parties.index', compact('parties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'logo' => 'required|image|max:2048|mimes:jpeg,png,jpg,svg', // Max 2MB
        ]);

        try {
            $path = $request->file('logo')->store('parties', 'public');

            Party::create([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'logo_path' => $path,
            ]);

            return redirect()->route('panitia.parties.index')->with('success', 'Partai berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan partai: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $party)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,svg',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'short_name' => $request->short_name,
            ];

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($party->logo_path && Storage::disk('public')->exists($party->logo_path)) {
                    Storage::disk('public')->delete($party->logo_path);
                }
                
                $data['logo_path'] = $request->file('logo')->store('parties', 'public');
            }

            $party->update($data);

            return redirect()->route('panitia.parties.index')->with('success', 'Data partai berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        try {
            if ($party->logo_path && Storage::disk('public')->exists($party->logo_path)) {
                Storage::disk('public')->delete($party->logo_path);
            }
            
            $party->delete();

            return redirect()->route('panitia.parties.index')->with('success', 'Partai berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus partai: ' . $e->getMessage());
        }
    }
}
