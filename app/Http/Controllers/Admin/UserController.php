<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use AuthorizesRequests; // ← CRITICAL: Enable authorization
    
    protected $service;

    public function __construct(\App\Services\UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // AUTHORIZATION: Prevent unauthorized user listing
        $this->authorize('viewAny', User::class);
        
        return view('admin.users.index', [
            'title' => 'Manajemen Pengguna',
            'users' => User::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        // AUTHORIZATION: Only Admin can create users
        $this->authorize('create', User::class);
        
        return view('admin.users.create', [
            'title' => 'Tambah Pengguna Baru'
        ]);
    }

    public function store(Request $request)
    {
        // AUTHORIZATION: Prevent unauthorized user creation
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'panitia', 'kpps'])],
        ]);

        $this->service->create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // AUTHORIZATION: Prevent IDOR on user edit
        $this->authorize('update', $user);
        
        return view('admin.users.edit', [
            'title' => 'Edit Pengguna',
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        // AUTHORIZATION: Prevent IDOR and privilege escalation
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'panitia', 'kpps'])],
        ]);
        
        // CRITICAL: Prevent role changes unless authorized
        if (isset($validated['role']) && $validated['role'] !== $user->role) {
            $this->authorize('changeRole', $user);
        }

        $this->service->update($user, $validated);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // AUTHORIZATION: Critical - prevent unauthorized deletion
        $this->authorize('delete', $user);
        
        // Additional check (also in policy, but double-check here)
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function verify(User $user)
    {
        // AUTHORIZATION: Only Admin can verify emails
        $this->authorize('verify', $user);
        
        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diverifikasi.');
    }
}
