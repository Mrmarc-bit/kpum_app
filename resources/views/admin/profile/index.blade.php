<x-layouts.admin title="Profil Saya">
    <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Profil Saya</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola informasi akun dan preferensi keamanan Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col items-center text-center relative overflow-hidden">
                    
                    <!-- Decorative Background -->
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-blue-500 to-indigo-600"></div>
                    
                    <!-- Avatar Upload -->
                    <div class="relative mt-12 mb-4 group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                        <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg ring-4 ring-blue-50/50 relative overflow-hidden">
                            @if(isset($user->avatar) && $user->avatar)
                                <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full rounded-full object-cover transition-transform group-hover:scale-110">
                            @else
                                <div id="avatarFallback" class="w-full h-full rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold transition-transform group-hover:scale-110">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <img id="avatarPreview" class="hidden w-full h-full rounded-full object-cover">
                            @endif
                            
                            <!-- Overlay Icon -->
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-full">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                        </div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-4 border-white rounded-full" title="Online"></div>
                    </div>

                    <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 font-medium mb-4">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider border border-blue-100">
                            {{ $user->role ?? 'Admin' }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-slate-50 text-slate-500 text-xs font-medium border border-slate-100">
                            Verified
                        </span>
                    </div>

                    <div class="mt-8 w-full border-t border-slate-100 pt-6">
                        <div class="grid grid-cols-2 gap-4 text-center">
                             <div>
                                <span class="block text-xl font-bold text-slate-800">{{ floor($user->created_at->diffInDays()) }}</span>
                                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Hari Bergabung</span>
                            </div>
                            <div>
                                <span class="block text-xl font-bold text-slate-800">Aktif</span>
                                <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Status</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips / Info Card -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Keamanan Akun
                        </h3>
                        <p class="text-indigo-100 text-sm leading-relaxed">
                            Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol untuk menjaga keamanan akun Anda.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Edit Informasi Profil</h3>
                        <span class="text-xs text-slate-400 italic">Terakhir diupdate: {{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                             @csrf
                            @method('PUT')
                            
                            <!-- Hidden File Input -->
                            <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                            
                            <!-- Personal Info Section -->
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs">1</span>
                                    Identitas Dasar
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-600">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50 focus:bg-white text-slate-800">
                                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-600">Alamat Email</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50 focus:bg-white text-slate-500">
                                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="border-slate-100 border-dashed my-6">

                            <!-- Password Section -->
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-xs">2</span>
                                    Ubah Password (Opsional)
                                </h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-slate-600">Password Baru</label>
                                            <input type="password" name="new_password" placeholder="Biarkan kosong jika tidak ingin mengubah"
                                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50 focus:bg-white">
                                            @error('new_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-slate-600">Konfirmasi Password Baru</label>
                                            <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru"
                                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50 focus:bg-white">
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-slate-600">Password Saat Ini (Konfirmasi)</label>
                                            <input type="password" name="current_password" placeholder="Wajib diisi jika mengubah password"
                                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50 focus:bg-white">
                                            @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Untuk keamanan, masukkan password lama Anda sebelum menyimpan perubahan password baru.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                                <button type="reset" class="px-5 py-2.5 text-slate-600 font-bold text-sm bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all">
                                    Reset
                                </button>
                                <button type="submit" class="px-6 py-2.5 text-white font-bold text-sm bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    var preview = document.getElementById('avatarPreview');
                    var fallback = document.getElementById('avatarFallback');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(fallback) fallback.classList.add('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layouts.admin>
