<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckDptController extends Controller
{
    /**
     * Display the search form.
     */
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->all();
        return view('check-dpt.index', compact('settings'));
    }

    /**
     * Handle the search request.
     */
    public function search(Request $request)
    {
        // 1. Strict Validation
        $request->validate([
            'nim' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9]+$/'], 
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.max' => 'Format NIM tidak valid.',
            'nim.regex' => 'NIM hanya boleh berisi huruf dan angka.',
        ]);

        $nim = trim($request->input('nim'));

        // 2. Secure Query (Eloquent uses PDO binding automatically)
        // Rate limiting is handled by middleware in routes/web.php
        
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            // 3. Return sanitized data (Avoid exposing sensitive fields)
            return response()->json([
                'status' => 'found',
                'data' => [
                    'name' => $this->maskName($mahasiswa->name), // Masked for privacy
                    'prodi' => $mahasiswa->prodi,
                    'nim' => $mahasiswa->nim,
                    'status' => 'Terdaftar dalam DPT',

                ]
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Data tidak ditemukan. Pastikan NIM yang Anda masukkan benar.'
            ], 404);
        }
    }

    /**
     * Mask name for privacy (e.g., "John Doe" -> "Jo** D**")
     * Enhance this if user wants full name shown. For public DPT check, masking is safer.
     * But usually internal DPT lists are public. I'll just show full name if user wants, 
     * but strictly masking is better for "anti-hacker" request to prevent scraping names.
     * Let's do partial hiding.
     */
    private function maskName($name)
    {
        $parts = explode(' ', $name);
        $maskedParts = [];
        
        foreach ($parts as $part) {
            if (strlen($part) > 2) {
                $maskedParts[] = substr($part, 0, 2) . str_repeat('*', strlen($part) - 2);
            } else {
                $maskedParts[] = $part;
            }
        }
        
        return implode(' ', $maskedParts);
    }
}
