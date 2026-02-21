<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Cookie;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request as FacadeRequest;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika user sudah login sebagai mahasiswa, langsung arahkan ke bilik suara
        if (Auth::guard('mahasiswa')->check()) {
            return redirect()->route('student.dashboard');
        }

        // Retrieve saved NIM from cookie if available
        $savedNim = Cookie::get('saved_nim');

        return view('auth.student-login', [
            'title' => 'Login Mahasiswa',
            'savedNim' => $savedNim
        ]);
    }

    public function login(Request $request)
    {
        // Strict validation with regex to prevent SQL injection patterns
        $credentials = $request->validate([
            'nim' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9]+$/'], // Only alphanumeric
            'password' => ['required', 'string', 'min:8', 'max:20'], // DOB format DDMMYYYY
            'access_code' => ['required', 'string'], // Unique Access Code
            'remember' => ['nullable'], // Validate checkbox
        ]);

        // Additional sanitization - strip any potential SQL injection chars
        $credentials['nim'] = preg_replace('/[^a-zA-Z0-9]/', '', $credentials['nim']);

        // Remove 'remember' from credentials array (used separately)
        $remember = $request->boolean('remember');
        unset($credentials['remember']);

        // Handle "Remember NIM" cookie manually (Persistent Autofill)
        if ($remember) {
            // Queue cookie for 30 days (43200 minutes)
            Cookie::queue('saved_nim', $credentials['nim'], 43200);
        } else {
            // If user unchecked remember, forget the saved NIM
            Cookie::queue(Cookie::forget('saved_nim'));
        }

        // Attempt login with NIM, Password, AND Access Code
        if (Auth::guard('mahasiswa')->attempt($credentials, $remember)) {
            $user = Auth::guard('mahasiswa')->user();
            \Illuminate\Support\Facades\Log::info('Mahasiswa Login SUCCESS: ' . $credentials['nim']);
            
            // Audit Log: Student Login Success
            AuditLog::create([
                'user_id' => $user->id, // Assuming student ID maps to user_id or handle polymorphic
                'user_name' => $user->name . ' (Mahasiswa)',
                'action' => 'LOGIN: SUCCESS',
                'details' => 'Mahasiswa ' . $user->nim . ' berhasil login.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // NEW: Check if voting or simulation has started
            $votingStart = \App\Models\Setting::get('voting_start_time');
            $simStart = \App\Models\Setting::get('simulation_start_time');
            $simEnd = \App\Models\Setting::get('simulation_end_time');

            $isAllowedIn = false;
            $now = now();

            // 1. Check Official Voting Time
            if ($votingStart) {
                try {
                    $startDateTime = \Carbon\Carbon::parse($votingStart);
                    if ($now->greaterThanOrEqualTo($startDateTime)) {
                        $isAllowedIn = true;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Invalid voting_start_time format', ['value' => $votingStart]);
                }
            } else {
                // If no start time set, assume open (or handle otherwise)
                // Assuming stricter rule: if no start time, closed? Or open? 
                // Usually system needs a start time. Let's assume open if not set for safety or closed?
                // Based on previous code: if($startTime) check. So if null, it skipped check -> allowed.
                $isAllowedIn = true; 
            }

            // 2. Check Simulation Window (Override if not allowed yet)
            if (!$isAllowedIn && $simStart && $simEnd) {
                try {
                    $simStartDateTime = \Carbon\Carbon::parse($simStart);
                    $simEndDateTime = \Carbon\Carbon::parse($simEnd);

                    if ($now->between($simStartDateTime, $simEndDateTime)) {
                        $isAllowedIn = true;
                        // Optional: Flash message that this is simulation
                        session()->flash('info', 'Anda sedang berada dalam Mode Simulasi.');
                    }
                } catch (\Exception $e) {
                     \Illuminate\Support\Facades\Log::warning('Invalid simulation time format');
                }
            }

            if (!$isAllowedIn) {
                // Log the login attempt but deny access
                Auth::guard('mahasiswa')->logout();
                
                return back()->with('error', 'Pemilihan belum dimulai. Silakan tunggu waktu pemilihan dimulai.');
            }

            return redirect('/bilik-suara');
        }

        \Illuminate\Support\Facades\Log::info('Mahasiswa Login FAILED: ' . $credentials['nim']);

        // Audit Log: Student Login FAILED
        AuditLog::create([
            'user_id' => null, // Guest
            'user_name' => 'Guest - System',
            'action' => 'LOGIN: FAILED (Mahasiswa)',
            'details' => 'Percobaan login gagal untuk NIM: ' . $credentials['nim'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors([
            'nim' => 'NIM, Tanggal Lahir, atau Kode Akses salah.',
        ])->onlyInput('nim');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('mahasiswa')->user();
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->nama . ' (Mahasiswa)',
                'action' => 'LOGOUT',
                'details' => 'Mahasiswa ' . $user->nim . ' logout.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        Auth::guard('mahasiswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
