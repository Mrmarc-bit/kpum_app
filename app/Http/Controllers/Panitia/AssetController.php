<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetFolder;
use App\Services\AssetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Tampilkan daftar assets dan folders
     */
    public function index(Request $request)
    {
        $currentFolderId = $request->query('folder');
        $currentFolder = null;

        if ($currentFolderId) {
            $currentFolder = AssetFolder::with('parent')->findOrFail($currentFolderId);
        }

        // Get Folders inside current folder
        $folders = AssetFolder::where('parent_id', $currentFolderId)
            ->withCount('assets')
            ->latest()
            ->get();

        // Get Assets inside current folder
        $assets = Asset::where('folder_id', $currentFolderId)
            ->with('uploader')
            ->latest()
            ->paginate(20);

        return view('panitia.assets.index', compact('assets', 'folders', 'currentFolder'));
    }

    /**
     * Buat folder baru
     */
    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:asset_folders,id'
        ]);

        AssetFolder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Folder baru berhasil dibuat!');
    }

    /**
     * Upload asset baru
     */
    /**
     * Upload asset baru (Support Multiple)
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:5120', // 5MB max per file
            'folder_id' => 'nullable|exists:asset_folders,id',
        ]);

        $files = $request->file('files');
        
        if (!is_array($files)) {
            $files = [$files];
        }

        $successCount = 0;
        $errorCount = 0;
        $lastError = '';

        foreach ($files as $file) {
            try {
                $this->assetService->uploadAsset(
                    $file,
                    [
                        'folder_id' => $request->folder_id,
                        'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'description' => null
                    ],
                    Auth::id()
                );
                
                // Audit Log
                \App\Models\AuditLog::create([
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'action' => 'UPLOAD_ASSET',
                    'details' => "Mengunggah asset: " . $file->getClientOriginalName(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $lastError = $e->getMessage();
                Log::error('Asset upload failed', [
                    'user' => Auth::id(),
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($errorCount > 0 && $successCount == 0) {
            return back()->with('error', 'Gagal mengunggah semua file: ' . $lastError);
        } elseif ($errorCount > 0) {
             return back()->with('warning', "$successCount file berhasil, $errorCount gagal. Terakhir: $lastError");
        }

        return back()->with('success', "$successCount file berhasil diunggah!");
    }

    /**
     * Hapus asset
     */
    public function destroy(Asset $asset)
    {
        try {
            $assetName = $asset->name;
            
            $this->assetService->deleteAsset($asset);

            // Audit Log
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'DELETE_ASSET',
                'details' => "Menghapus asset: {$assetName}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Asset berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Asset delete failed', [
                'user' => Auth::id(),
                'asset_id' => $asset->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal menghapus asset.');
        }
    }

    /**
     * Hapus Folder
     */
    public function destroyFolder(AssetFolder $folder)
    {
        if ($folder->assets()->count() > 0 || $folder->children()->count() > 0) {
             return back()->with('error', 'Folder tidak kosong. Hapus isi folder terlebih dahulu.');
        }

        $folder->delete();
        return back()->with('success', 'Folder berhasil dihapus.');
    }

    /**
     * Pindahkan asset (AJAX)
     */
    public function move(Request $request, Asset $asset)
    {
        $request->validate([
            'folder_id' => 'nullable|exists:asset_folders,id'
        ]);

        $this->assetService->moveAsset($asset, $request->folder_id);

        return response()->json([
            'success' => true,
            'message' => 'File moved successfully'
        ]);
    }

    /**
     * Rename Asset
     */
    public function rename(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $asset->update(['name' => $request->name]);

        return back()->with('success', 'File berhasil diubah namanya.');
    }

    /**
     * Rename Folder
     */
    public function renameFolder(Request $request, AssetFolder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $folder->update(['name' => $request->name]);

        return back()->with('success', 'Folder berhasil diubah namanya.');
    }
}
