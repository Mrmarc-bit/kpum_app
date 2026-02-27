<x-layouts.panitia :title="$title">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">

        @if($calons->isNotEmpty())
        <!-- New Candidate Action -->
        <a href="{{ route('panitia.calon_dpm.create') }}"
            class="group relative flex flex-col items-center justify-center p-8 rounded-[2rem] border-3 border-dashed border-slate-200 hover:border-purple-400 bg-slate-50/50 hover:bg-purple-50/50 transition-all duration-300 h-full min-h-[420px]">
            <div class="w-20 h-20 rounded-full bg-slate-100 group-hover:bg-purple-100 group-hover:text-purple-600 text-slate-400 flex items-center justify-center transition-all duration-300 mb-6 group-hover:scale-110 shadow-sm group-hover:shadow-md">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 group-hover:text-purple-700 transition-colors">Tambah Calon DPM</h3>
            <p class="text-sm text-slate-500 mt-2 text-center font-medium max-w-[200px]">Buat profil calon anggota Dewan Perwakilan Mahasiswa</p>
        </a>
        @endif

        @forelse($calons as $calon)
            <!-- Candidate Card -->
             <div class="group relative bg-white rounded-[2.5rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full ring-1 ring-slate-100">
                <div class="absolute top-5 right-5 z-20">
                    @if($calon->status_aktif)
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-800 shadow-sm ring-1 ring-slate-200">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                            Active
                        </span>
                    @else
                         <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white/90 backdrop-blur-md text-slate-500 shadow-sm ring-1 ring-slate-200">
                            <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>

                <!-- Image Area -->
                <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60 z-10 transition-opacity group-hover:opacity-40"></div>
                    @if($calon->foto)
                        <img src="{{ asset('storage/' . $calon->foto) }}" alt="Foto Kandidat"
                            class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                    @else
                       <div class="absolute inset-0 flex items-end justify-center bg-slate-200">
                            <svg class="w-32 h-32 text-slate-300 mb-8" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    @endif

                    <div class="absolute bottom-6 left-6 z-20 text-white">
                        <div class="text-6xl font-black tracking-tighter opacity-90 drop-shadow-lg leading-none mb-1">
                            {{ sprintf('%02d', $calon->urutan_tampil ?? 0) }}
                        </div>
                         <div class="h-1 w-12 bg-purple-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Info Area -->
                <div class="p-8 flex-1 flex flex-col bg-white relative">
                    <div class="mb-8 flex-1">
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Calon Anggota DPM</p>
                        <h4 class="text-xl font-bold text-slate-900 leading-tight mb-2 line-clamp-2" title="{{ $calon->nama }}">{{ $calon->nama }}</h4>
                         <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-md w-fit">
                                {{ $calon->fakultas }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium truncate">{{ $calon->prodi }}</span>
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between">
                         <div class="flex items-center gap-2 text-sm font-semibold text-slate-400">
                            <!-- Placeholder for stats if needed -->
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('panitia.calon_dpm.edit', $calon->id) }}"
                                class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-purple-600 transition-all shadow-sm hover:shadow-purple-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>

                            <form action="{{ route('panitia.calon_dpm.destroy', $calon->id) }}" method="POST"
                                data-confirm="Apakah Anda yakin ingin menghapus calon ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                     class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-600 transition-all shadow-sm hover:shadow-red-500/30">
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
                <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Calon DPM</h3>
                <p class="text-slate-500 max-w-md mx-auto mb-8">Silakan tambahkan calon anggota DPM baru.</p>
                <a href="{{ route('panitia.calon_dpm.create') }}" class="px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors shadow-lg shadow-purple-500/30">
                    Tambah Calon DPM
                </a>
            </div>
        @endforelse
    </div>
</x-layouts.panitia>
