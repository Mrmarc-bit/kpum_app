<x-layouts.admin :title="$title">
    
    <div class="space-y-8 pb-20">

        <!-- Header & Mode Switcher -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pemeliharaan Situs</h1>
                <p class="text-slate-500 mt-2 font-medium">
                    Kontrol mode lingkungan, cek kesehatan sistem, dan audit keamanan.
                    <span class="block text-xs text-slate-400 mt-1 font-mono">Last Updated: {{ now()->format('H:i:s d/m/Y') }}</span>
                </p>
            </div>

            <!-- Global Mode Switcher -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border-2 {{ $mode == 'testing' ? 'border-amber-400 bg-amber-50' : 'border-green-400 bg-green-50' }}">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $mode == 'testing' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600' }} flex items-center justify-center">
                        @if($mode == 'testing')
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider {{ $mode == 'testing' ? 'text-amber-600' : 'text-green-600' }}">Status Sistem Saat Ini</p>
                        <h3 class="text-xl font-black {{ $mode == 'testing' ? 'text-amber-800' : 'text-green-800' }}">
                            {{ $mode == 'testing' ? 'MODE PENGUJIAN (UNSECURE)' : 'MODE PRODUCTION (SECURE)' }}
                        </h3>
                    </div>
                    <button onclick="document.getElementById('switch-mode-modal').showModal()" 
                        class="ml-4 px-4 py-2 rounded-lg font-bold text-sm shadow-sm transition-all {{ $mode == 'testing' ? 'bg-white text-amber-600 hover:bg-amber-100' : 'bg-white text-green-600 hover:bg-green-100' }}">
                        Ubah Mode
                    </button>
                </div>
                @if($mode == 'testing')
                    <p class="mt-2 text-xs font-bold text-amber-700 bg-amber-200/50 px-3 py-1.5 rounded-lg border border-amber-300">
                        ⚠️ PERINGATAN: Rate Limiting, IP Block, & Brute Force Protection DIMATIKAN! Gunakan hanya untuk testing OWASP.
                    </p>
                @endif
            </div>
        </div>

        <!-- 3 Columns Layout -->
        <!-- 2 Columns Layout for Health & Security -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- 1. Health Check -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 p-6 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-black text-lg text-slate-800">Kesehatan Sistem</h3>
                </div>

                <!-- DB Status -->
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-sm font-bold text-slate-600">Database</span>
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $health['database']['status'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $health['database']['status'] ? 'CONNECTED' : 'ERROR' }}
                    </span>
                </div>

                <!-- Storage Status -->
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-sm font-bold text-slate-600">Storage Permission</span>
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $health['storage']['status'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $health['storage']['status'] ? 'WRITABLE' : 'READONLY' }}
                    </span>
                </div>

                <!-- Cache Status -->
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-sm font-bold text-slate-600">Cache Driver</span>
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $health['cache']['status'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $health['cache']['status'] ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </div>

                <form action="{{ route('admin.maintenance.optimize') }}" method="POST" class="mt-4">
                    @csrf
                    <button class="w-full py-3 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl font-bold text-sm transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Bersihkan Cache
                    </button>
                </form>
            </div>

            <!-- 2. Security Audit -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 p-6 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="font-black text-lg text-slate-800">Audit Keamanan</h3>
                </div>

                 <!-- Debug Mode -->
                 <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-sm font-bold text-slate-600">APP_DEBUG</span>
                    <span class="px-2 py-1 rounded text-xs font-bold {{ !$security['debug_mode'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $security['debug_mode'] ? 'TRUE (RISK)' : 'FALSE (SAFE)' }}
                        <span class="opacity-50 text-[10px] ml-1">({{ var_export($security['debug_mode'], true) }})</span>
                    </span>
                </div>

                <!-- HTTPS -->
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-sm font-bold text-slate-600">HTTPS Enforced</span>
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $security['force_https'] ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $security['force_https'] ? 'YES' : 'NO' }}
                    </span>
                </div>

                <!-- Environment -->
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-sm font-bold text-slate-600">APP_ENV</span>
                    <span class="px-2 py-1 rounded text-xs font-bold font-mono text-slate-700 bg-slate-200">
                        {{ $security['env'] }}
                    </span>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-500 leading-relaxed">
                    Pastikan APP_DEBUG bernilai <b>FALSE</b> saat Production untuk mencegah kebocoran stack trace jika terjadi error.
                </div>
            </div>

        </div>

        <!-- 3. System Logs (Full Width) -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 p-6 space-y-4 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="font-black text-lg text-slate-800">Error Logs (Terminal View)</h3>
                </div>
                <form action="{{ route('admin.maintenance.clear-logs') }}" method="POST" onsubmit="return confirm('Hapus semua log?')">
                    @csrf
                    <button class="text-xs font-bold text-red-500 hover:text-red-700 hover:underline">Clear Logs</button>
                </form>
            </div>

            <div class="w-full bg-slate-900 rounded-xl p-6 overflow-y-auto h-[500px] font-mono text-xs text-green-400 leading-relaxed whitespace-pre-wrap selection:bg-green-900 selection:text-white border border-slate-800 shadow-inner">
                {{ $logs ? $logs : 'No logs found.' }}
            </div>
        </div>
    </div>

    <!-- Modal Switch Mode -->
    <dialog id="switch-mode-modal" class="backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm bg-transparent p-0 w-full max-w-lg rounded-[2rem] shadow-2xl">
        <form method="POST" action="{{ route('admin.maintenance.mode.update') }}" class="bg-white rounded-[2rem] p-0 shadow-2xl border border-white/50 overflow-hidden">
            @csrf
            
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-black text-xl text-slate-800 tracking-tight">Ubah Mode Lingkungan</h3>
                <p class="text-sm text-slate-500 mt-1">Pilih mode operasi untuk sistem ini.</p>
            </div>

            <div class="p-8 space-y-6">
                
                <!-- Option Production -->
                <label class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ $mode == 'production' ? 'border-green-500 bg-green-50/30' : 'border-slate-200' }}">
                    <input type="radio" name="mode" value="production" class="mt-1 w-4 h-4 text-green-600 border-slate-300 focus:ring-green-500" {{ $mode == 'production' ? 'checked' : '' }}>
                    <div class="ml-3">
                        <span class="block font-black text-slate-800">Production Mode (Aman)</span>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Semua fitur keamanan AKTIF. Rate limiting, IP blocking, dan proteksi brute force berjalan normal. Gunakan untuk operasional harian.
                        </p>
                    </div>
                </label>

                <!-- Option Testing -->
                <label class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ $mode == 'testing' ? 'border-amber-500 bg-amber-50/30' : 'border-slate-200' }}">
                    <input type="radio" name="mode" value="testing" class="mt-1 w-4 h-4 text-amber-600 border-slate-300 focus:ring-amber-500" {{ $mode == 'testing' ? 'checked' : '' }}>
                    <div class="ml-3">
                        <span class="block font-black text-amber-700">Testing Mode (Unsecure)</span>
                        <p class="text-xs text-amber-600 mt-1 leading-relaxed">
                            Mematikan sebagian fitur keamanan untuk mempermudah penetration testing (OWASP).
                            <br><span class="font-bold">PERINGATAN: Jangan aktifkan saat pemilihan berlangsung!</span>
                        </p>
                    </div>
                </label>

                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Konfirmasi Password Admin</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-bold" placeholder="Masukkan password Anda">
                </div>

            </div>

            <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('switch-mode-modal').close()" class="px-6 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-200 transition-all text-sm">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </dialog>

</x-layouts.admin>
