<div class="hidden md:block overflow-x-auto">
    <table class="w-full text-left text-sm text-slate-600">
        <thead
            class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
            <tr>
                <th class="px-6 py-5 pl-8">Mahasiswa</th>
                <th class="px-6 py-5">Kode Akses</th>
                <th class="px-6 py-5">Prodi & Fakultas</th>
                <th class="px-6 py-5">Status Voting</th>
                <th class="px-6 py-5">Metode</th>
                <th class="px-6 py-5 text-right pr-8">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($mahasiswas as $index => $mhs)
                <tr class="hover:bg-blue-50/40 transition-all duration-200 group">
                    <td class="px-6 py-4 pl-8">
                        <div class="flex items-center gap-4">
                            <!-- Number -->
                            <span
                                class="font-bold text-slate-400 text-sm w-6">{{ $index + $mahasiswas->firstItem() }}.</span>

                            <div>
                                <div
                                    class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors uppercase">
                                    {{ $mhs->name }}
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span
                                        class="text-[11px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $mhs->nim }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($mhs->access_code)
                            <code class="px-2 py-1 bg-slate-100 border border-slate-200 rounded font-mono text-xs font-bold text-slate-600 select-all">{{ $mhs->access_code }}</code>
                        @else
                            <span class="text-xs text-slate-400 italic">Belum digenerate</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-700">{{ $mhs->prodi ?? '-' }}</span>
                            @php
                                $fakultas = $mhs->fakultas;
                                if (!$fakultas && $mhs->prodi) {
                                    $searchProdi = strtolower($mhs->prodi);
                                    // Remove 'dan', '&' to match standard names
                                    $searchProdi = str_replace([' dan ', ' & '], ' ', $searchProdi);
                                    $searchProdi = preg_replace('/\s+/', ' ', $searchProdi);

                                    foreach (\App\Models\Mahasiswa::PRODI_LIST as $key => $val) {
                                        $normalizedKey = strtolower($key);
                                        if (str_contains($normalizedKey, $searchProdi) || str_contains($searchProdi, $normalizedKey)) {
                                            $fakultas = $val;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <span
                                class="text-[11px] text-slate-400 max-w-[200px] leading-tight">{{ $fakultas ?? 'Belum ditentukan' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($mhs->voted_at || $mhs->dpm_voted_at)
                            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm transition-all hover:shadow-md hover:border-emerald-200 cursor-default group/badge">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 group-hover/badge:scale-110 transition-transform"></span>
                                </span>
                                <div class="flex flex-col leading-none">
                                    <span class="text-[10px] font-black uppercase tracking-wider opacity-90">Sudah Memilih</span>
                                    <span class="text-[10px] font-mono font-bold mt-1 text-emerald-600/80">
                                        {{ $mhs->voted_at ? $mhs->voted_at->format('H:i') . ' WIB' : ($mhs->dpm_voted_at ? $mhs->dpm_voted_at->format('H:i') . ' WIB' : '-') }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-slate-50 text-slate-500 border border-slate-200 opacity-70 hover:opacity-100 transition-opacity">
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Belum Memilih</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($mhs->voting_method == 'offline' || $mhs->attended_at)
                             <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 border border-purple-100 text-[10px] font-bold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                Offline
                            </span>
                        @elseif($mhs->voting_method == 'online' || $mhs->voted_at || $mhs->dpm_voted_at)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Online
                            </span>
                        @else
                            <span class="text-slate-300 font-bold text-center block w-8">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right pr-8">
                        <div
                            class="flex justify-end gap-1 opacity-60 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                            @php $prefix = Request::is('panitia*') ? 'panitia' : 'admin'; @endphp
                            <a href="{{ route($prefix . '.dpt.download-letter', $mhs) }}"
                               target="_blank"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors"
                               title="Download Surat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                            <a href="{{ route($prefix . '.dpt.edit', ['mahasiswa' => $mhs->id]) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </a>
                            <form action="{{ route($prefix . '.dpt.destroy', ['mahasiswa' => $mhs->id]) }}" method="POST"
                                onsubmit="confirmDelete(event)">
                                @csrf @method('DELETE')
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div
                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-slate-700 font-bold text-lg">Belum ada data DPT</h4>
                        <p class="text-slate-400 text-sm mt-1 max-w-sm mx-auto">Mulai dengan menambahkan data manual atau
                            import CSV untuk mengisi daftar pemilih.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Card View -->
<div class="md:hidden flex flex-col gap-3 px-4 pb-4">
    @forelse($mahasiswas as $index => $mhs)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative group">
            <!-- Header -->
            <div class="flex justify-between items-start mb-3">
                <div class="pr-8">
                    <h4 class="font-bold text-slate-800 text-base uppercase leading-tight">{{ $mhs->name }}</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-mono text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $mhs->nim }}</span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 truncate max-w-[150px]">{{ $mhs->prodi ?? '-' }}</span>
                    </div>
                </div>
                <div class="absolute top-4 right-4 text-slate-300 font-bold text-xs">
                    {{ $index + $mahasiswas->firstItem() }}
                </div>
            </div>
            
            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-3 mb-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Status</span>
                    @if ($mhs->voted_at || $mhs->dpm_voted_at)
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Sudah Memilih
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            Belum Memilih
                        </span>
                    @endif
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Kode Akses</span>
                    @if ($mhs->access_code)
                        <code class="font-mono text-xs font-bold text-slate-600">{{ $mhs->access_code }}</code>
                    @else
                         <span class="text-xs italic text-slate-400">-</span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                 @php $prefix = Request::is('panitia*') ? 'panitia' : 'admin'; @endphp
                 <div class="flex items-center gap-1">
                     @if($mhs->voting_method == 'offline' || $mhs->attended_at)
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded uppercase border border-purple-100">Offline</span>
                     @endif
                 </div>
                 
                 <div class="flex items-center gap-2">
                     <a href="{{ route($prefix . '.dpt.download-letter', $mhs) }}" target="_blank" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     </a>
                     <a href="{{ route($prefix . '.dpt.edit', ['mahasiswa' => $mhs->id]) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                     </a>
                     <form action="{{ route($prefix . '.dpt.destroy', ['mahasiswa' => $mhs->id]) }}" method="POST" onsubmit="confirmDelete(event)" class="inline-block">
                        @csrf @method('DELETE')
                        <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                     </form>
                 </div>
            </div>
        </div>
    @empty
        <div class="text-center py-10 px-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
             <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
             </div>
             <p class="text-slate-500 font-bold">Tidak ada data</p>
             <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian</p>
        </div>
    @endforelse
</div>

<div
    class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="text-xs text-slate-500 font-medium">
        Menampilkan <span class="font-bold text-slate-700">{{ $mahasiswas->firstItem() ?? 0 }}</span> - <span
            class="font-bold text-slate-700">{{ $mahasiswas->lastItem() ?? 0 }}</span> dari <span
            class="font-bold text-slate-700">{{ $mahasiswas->total() }}</span> data
    </div>
    <div class="scale-90 origin-right">
        {{ $mahasiswas->links() }}
    </div>
</div>