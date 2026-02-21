<x-layouts.panitia :title="$title">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Timeline Kegiatan</h1>
            <p class="text-slate-500 text-sm sm:text-base">Kelola jadwal dan tahapan pemilihan umum mahasiswa.</p>
        </div>
        <a href="{{ route('panitia.timeline.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 uppercase text-xs tracking-widest w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Agenda
        </a>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white/70 backdrop-blur-xl rounded-3xl border border-white/50 shadow-sm overflow-hidden text-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">Urutan</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">Agenda</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px]">Periode</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px] text-center">
                        Status</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-wider text-[10px] text-right">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($timelines as $timeline)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 font-bold text-slate-600">{{ $timeline->order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $timeline->title }}</div>
                            <div class="text-slate-500 text-xs mt-0.5 line-clamp-1 max-w-xs">{{ $timeline->description }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-600">
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Mulai:
                                    {{ \Carbon\Carbon::parse($timeline->start_date)->format('d M Y') }}</span>
                                <span
                                    class="text-[10px] font-black text-blue-500 uppercase tracking-widest leading-none">Selesai:
                                    {{ \Carbon\Carbon::parse($timeline->end_date)->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($timeline->status === 'completed')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">Selesai</span>
                            @elseif($timeline->status === 'active')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-600 text-white shadow-lg shadow-blue-500/30">Berlangsung</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500">Mendatang</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('panitia.timeline.edit', $timeline->id) }}"
                                    class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('panitia.timeline.destroy', $timeline->id) }}" method="POST"
                                    data-confirm="Hapus agenda ini?">
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                            Belum ada agenda kegiatan yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden flex flex-col gap-4">
        @forelse($timelines as $timeline)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden">
                <!-- Status Badge Absolute -->
                <div class="absolute top-4 right-4">
                    @if($timeline->status === 'completed')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">Selesai</span>
                    @elseif($timeline->status === 'active')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-600 text-white shadow-lg shadow-blue-500/30">Berlangsung</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500">Mendatang</span>
                    @endif
                </div>

                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0">
                         <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 font-black text-slate-600 text-lg">{{ $timeline->order }}</span>
                    </div>
                    <div class="pr-16">
                        <h3 class="font-bold text-slate-800 uppercase tracking-tight leading-snug">{{ $timeline->title }}</h3>
                        <p class="text-slate-500 text-xs mt-1 line-clamp-2">{{ $timeline->description }}</p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 mb-4 grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Mulai</span>
                        <span class="font-bold text-slate-700 text-xs">{{ \Carbon\Carbon::parse($timeline->start_date)->format('d M Y') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-blue-400 uppercase tracking-wider block mb-0.5">Selesai</span>
                        <span class="font-bold text-blue-600 text-xs">{{ \Carbon\Carbon::parse($timeline->end_date)->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('panitia.timeline.edit', $timeline->id) }}"
                       class="flex-1 py-2 text-center rounded-xl bg-slate-50 text-slate-600 font-bold text-xs hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        Edit Agenda
                    </a>
                    <form action="{{ route('panitia.timeline.destroy', $timeline->id) }}" method="POST"
                          class="flex-1"
                          data-confirm="Hapus agenda ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full py-2 rounded-xl bg-red-50 text-red-600 font-bold text-xs hover:bg-red-100 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-700">Belum Ada Agenda</h4>
                <p class="text-xs text-slate-400 mt-1">Timeline kegiatan masih kosong.</p>
            </div>
        @endforelse
    </div>
</x-layouts.panitia>