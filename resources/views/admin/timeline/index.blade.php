<x-layouts.admin :title="$title">
    
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Timeline Kegiatan</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm sm:text-base">Kelola jadwal dan tahapan penting pemilihan umum.</p>
        </div>
        <a href="{{ route('admin.timeline.create') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1 text-sm tracking-wide w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Agenda
        </a>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">#</th>
                        <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Nama Agenda</th>
                        <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Periode Pelaksanaan</th>
                        <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px] text-center">Status</th>
                        <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($timelines as $timeline)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-500 font-bold text-xs group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                    {{ $timeline->order }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 text-base mb-1">{{ $timeline->title }}</div>
                                <div class="text-slate-500 text-xs font-medium line-clamp-1 max-w-sm">{{ $timeline->description }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                        {{ \Carbon\Carbon::parse($timeline->start_date)->isoFormat('D MMMM Y') }}
                                    </div>
                                    <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                        <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                        {{ \Carbon\Carbon::parse($timeline->end_date)->isoFormat('D MMMM Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($timeline->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @elseif($timeline->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-600 ring-1 ring-blue-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Berlangsung
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 ring-1 ring-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Mendatang
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.timeline.edit', $timeline->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Agenda">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.timeline.destroy', $timeline->id) }}" method="POST"
                                        data-confirm="Hapus agenda ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Agenda">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h3 class="text-slate-800 font-bold mb-1">Timeline Kosong</h3>
                                    <p class="text-slate-500 text-sm max-w-xs mx-auto">Belum ada agenda kegiatan yang ditambahkan. Silakan mulai dengan menambahkan agenda baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden flex flex-col gap-4">
        @forelse($timelines as $timeline)
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">
                <!-- Status Badge Absolute -->
                <div class="absolute top-6 right-6">
                    @if($timeline->status === 'completed')
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                        </span>
                    @elseif($timeline->status === 'active')
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-600 ring-1 ring-blue-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Berlangsung
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 ring-1 ring-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Mendatang
                        </span>
                    @endif
                </div>

                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0">
                         <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-100 font-black text-slate-600 text-lg shadow-sm">
                            {{ $timeline->order }}
                         </span>
                    </div>
                    <div class="pr-20">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $timeline->title }}</h3>
                        <p class="text-slate-500 text-xs mt-1 line-clamp-2 font-medium">{{ $timeline->description }}</p>
                    </div>
                </div>

                <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100 mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Mulai</span>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            {{ \Carbon\Carbon::parse($timeline->start_date)->isoFormat('D MMM Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Selesai</span>
                         <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            {{ \Carbon\Carbon::parse($timeline->end_date)->isoFormat('D MMM Y') }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-2 border-t border-slate-50 pt-4">
                    <a href="{{ route('admin.timeline.edit', $timeline->id) }}"
                       class="flex-1 py-2.5 text-center rounded-xl bg-slate-50 text-slate-600 font-bold text-sm hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        Edit Agenda
                    </a>
                    <form action="{{ route('admin.timeline.destroy', $timeline->id) }}" method="POST"
                          class="flex-1"
                          data-confirm="Hapus agenda ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-red-50 text-red-600 font-bold text-sm hover:bg-red-100 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 px-4 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-700">Timeline Kosong</h4>
                <p class="text-xs text-slate-400 mt-1 font-medium">Belum ada agenda kegiatan.</p>
            </div>
        @endforelse
    </div>
</x-layouts.admin>