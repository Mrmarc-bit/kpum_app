<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-slate-600">
        <thead
            class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
            <tr>
                <th class="px-6 py-5 pl-8">Mahasiswa</th>
                <th class="px-6 py-5">Prodi & Fakultas</th>
                <th class="px-6 py-5">Status Voting</th>
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
                        @if($mhs->voted_at)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <div class="flex flex-col leading-none">
                                    <span class="text-[10px] font-bold uppercase tracking-wide opacity-80">Sudah Memilih</span>
                                    <span class="text-[10px] font-mono mt-0.5">{{ $mhs->voted_at->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        @else
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 text-slate-500 border border-slate-200">
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wide">Belum Memilih</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right pr-8">
                        <div
                            class="flex justify-end gap-1 opacity-60 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                            <a href="{{ route('panitia.dpt.edit', ['mahasiswa' => $mhs->id]) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </a>
                            <form action="{{ route('panitia.dpt.destroy', ['mahasiswa' => $mhs->id]) }}" method="POST"
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
                    <td colspan="4" class="px-6 py-16 text-center">
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