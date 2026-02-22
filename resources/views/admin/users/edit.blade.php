<x-layouts.admin :title="$title">
    <div class="max-w-4xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Edit Pengguna</h2>
            <p class="text-slate-600">Perbarui informasi akun pengguna.</p>
        </div>

        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/50 shadow-sm p-8">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 text-sm">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 text-sm">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-2">Peran (Role)</label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 text-sm bg-white">
                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="panitia" {{ old('role', $user->role) == 'panitia' ? 'selected' : '' }}>Panitia</option>
                        <option value="kpps" {{ old('role', $user->role) == 'kpps' ? 'selected' : '' }}>KPPS</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="border-t border-slate-100 pt-6 mt-6">
                    <h3 class="text-md font-bold text-slate-800 mb-4">Ubah Password (Opsional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 text-sm">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-lg shadow-blue-500/30 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
