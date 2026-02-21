<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartyController extends Controller
{
    protected \App\Services\ImageService $imageService;

    public function __construct(\App\Services\ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parties = Party::latest()->paginate(10);
        return view('admin.parties.index', compact('parties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'logo' => 'required|image|max:2048|mimes:jpeg,png,jpg,svg', // Max 2MB, limit types
        ]);

        try {
            // Secure upload
            $path = $this->imageService->upload($request->file('logo'), $request->name);

            Party::create([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'logo_path' => $path,
            ]);

            return redirect()->route('admin.parties.index')->with('success', 'Partai berhasil ditambahkan.');

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
                if ($party->logo_path) {
                    $this->imageService->delete($party->logo_path);
                }
                
                $data['logo_path'] = $this->imageService->upload($request->file('logo'), $request->name);
            }

            $party->update($data);

            return redirect()->route('admin.parties.index')->with('success', 'Data partai berhasil diperbarui.');

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
            if ($party->logo_path) {
                $this->imageService->delete($party->logo_path);
            }
            
            $party->delete();

            return redirect()->route('admin.parties.index')->with('success', 'Partai berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus partai: ' . $e->getMessage());
        }
    }
}
