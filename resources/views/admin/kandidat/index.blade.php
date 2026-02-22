<x-layouts.admin :title="$title">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        <!-- New Candidate Action -->
        <a href="{{ route('admin.kandidat.create') }}"
            class="group relative flex flex-col items-center justify-center p-8 rounded-[2rem] border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/50 hover:bg-blue-50/50 transition-all duration-300 h-full min-h-[420px]">
            <div class="w-20 h-20 rounded-full bg-slate-100 group-hover:bg-blue-100 group-hover:text-blue-600 text-slate-400 flex items-center justify-center transition-all duration-300 mb-6 group-hover:scale-110 shadow-sm group-hover:shadow-md">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 group-hover:text-blue-700 transition-colors">Tambah Paslon Baru</h3>
            <p class="text-sm text-slate-500 mt-2 text-center font-medium max-w-[200px]">Buat profil kandidat Ketua & Wakil untuk periode ini</p>
        </a>

        @forelse($kandidats as $kandidat)
            <!-- Candidate Card -->
            <div class="group relative bg-white rounded-[2.5rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full ring-1 ring-slate-100">
                <div class="absolute top-5 right-5 z-20">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-800 shadow-sm ring-1 ring-slate-200">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Active
                    </span>
                </div>

                <!-- Image Area -->
                <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60 z-10 transition-opacity group-hover:opacity-40"></div>
                    @if($kandidat->foto)
                        <img src="{{ asset('storage/' . $kandidat->foto) }}" alt="Foto Paslon {{ $kandidat->no_urut }}"
                            class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-end justify-center bg-slate-200">
                            <svg class="w-32 h-32 text-slate-300 mb-8" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-6 left-6 z-20 text-white">
                        <div class="text-6xl font-black tracking-tighter opacity-90 drop-shadow-lg leading-none mb-1">
                            {{ sprintf('%02d', $kandidat->no_urut) }}
                        </div>
                        <div class="h-1 w-12 bg-indigo-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Info Area -->
                <div class="p-8 flex-1 flex flex-col bg-white relative">
                    <div class="flex gap-6 mb-8">
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Calon Ketua</p>
                            <h4 class="text-lg font-bold text-slate-900 leading-tight truncate" title="{{ $kandidat->nama_ketua }}">{{ $kandidat->nama_ketua }}</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1 truncate">{{ $kandidat->prodi_ketua }}</p>
                        </div>
                        <div class="w-px bg-slate-100 self-stretch my-2"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Calon Wakil</p>
                            <h4 class="text-lg font-bold text-slate-900 leading-tight truncate" title="{{ $kandidat->nama_wakil }}">{{ $kandidat->nama_wakil }}</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1 truncate">{{ $kandidat->prodi_wakil }}</p>
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>{{ $kandidat->votes->count() }} Votes</span>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('admin.kandidat.edit', $kandidat->id) }}"
                                class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all shadow-sm hover:shadow-blue-500/30"
                                title="Edit kandidat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            
                            {{-- ✅ FORM HAPUS — Menggunakan data-confirm agar ditangkap oleh SweetAlert2 interceptor --}}
                            <form 
                                action="{{ route('admin.kandidat.destroy', $kandidat->id) }}" 
                                method="POST"
                                data-confirm="Kandidat '{{ $kandidat->nama_ketua }} & {{ $kandidat->nama_wakil }}' akan dihapus secara permanen beserta semua foto. Tindakan ini tidak dapat dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-500 transition-all shadow-sm hover:shadow-red-500/30"
                                    title="Hapus kandidat">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Kandidat</h3>
                <p class="text-slate-500 max-w-md mx-auto mb-8">Silakan tambahkan kandidat baru untuk memulai pemilihan.</p>
                <a href="{{ route('admin.kandidat.create') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                    Tambah Kandidat Sekarang
                </a>
            </div>
        @endforelse
    </div>

</x-layouts.admin>