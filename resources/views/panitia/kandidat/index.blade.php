<x-layouts.panitia :title="$title">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @if($kandidats->isNotEmpty())
        <!-- New Candidate Action -->
        <a href="{{ route('panitia.kandidat.create') }}"
            class="group relative flex flex-col items-center justify-center p-8 rounded-3xl border-2 border-dashed border-slate-300 hover:border-blue-500 bg-white hover:bg-blue-50/50 transition-all duration-300 h-full min-h-[400px]">
            <div
                class="w-16 h-16 rounded-full bg-slate-50 group-hover:bg-blue-100 flex items-center justify-center transition-colors mb-4">
                <svg class="w-8 h-8 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 group-hover:text-blue-700">Tambah Paslon Baru</h3>
            <p class="text-sm text-slate-500 mt-2 text-center font-medium">Buat profil kandidat Ketua & Wakil</p>
        </a>
        @endif

        @forelse($kandidats as $kandidat)
            <!-- Candidate Card -->
            <div
                class="group relative bg-white/70 backdrop-blur-xl rounded-3xl border border-white/50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
                <div class="absolute top-0 right-0 p-4 z-10">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                </div>
                <div class="aspect-video bg-gradient-to-br from-blue-400 to-indigo-600 relative overflow-hidden">
                    @if($kandidat->foto)
                        <img src="{{ asset('storage/' . $kandidat->foto) }}" alt="Foto Paslon {{ $kandidat->no_urut }}"
                            class="w-full h-full object-cover">
                    @else
                        <!-- Mock Image Placeholder -->
                        <div class="absolute inset-0 flex items-end justify-center">
                            <div class="w-40 h-40 bg-slate-900/10 backdrop-blur-sm rounded-t-full"></div>
                            <div class="w-40 h-36 bg-slate-900/20 backdrop-blur-md rounded-t-full -ml-10"></div>
                        </div>
                    @endif
                    <div class="absolute bottom-4 left-4 text-white">
                        <h2 class="text-3xl font-black opacity-80 drop-shadow-md">{{ sprintf('%02d', $kandidat->no_urut) }}
                        </h2>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex gap-4 mb-4 flex-1">
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ketua</p>
                            <h4 class="font-bold text-slate-800 line-clamp-2">{{ $kandidat->nama_ketua }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-1">{{ $kandidat->prodi_ketua }}</p>
                        </div>
                        <div class="w-px bg-slate-100"></div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Wakil</p>
                            <h4 class="font-bold text-slate-800 line-clamp-2">{{ $kandidat->nama_wakil }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-1">{{ $kandidat->prodi_wakil }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <!-- Placeholder for votes, can be connected to real data later -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span>0 Votes</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('panitia.kandidat.edit', $kandidat->id) }}"
                                class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"><svg
                                    class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg></a>

                            <form action="{{ route('panitia.kandidat.destroy', $kandidat->id) }}" method="POST"
                                data-confirm="Apakah Anda yakin ingin menghapus kandidat ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-2 text-center py-12 text-slate-500">
                Belum ada kandidat yang terdaftar.
            </div>
        @endforelse
    </div>
</x-layouts.panitia>
