<x-layouts.admin :title="$title">
    <div class="max-w-2xl">
        <div class="mb-8">
            <a href="{{ route('admin.timeline.index') }}"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-4 text-sm font-bold uppercase tracking-widest text-[10px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Agenda</h1>
            <p class="text-slate-500 text-sm">Perbarui informasi agenda kegiatan ini.</p>
        </div>

        <form action="{{ route('admin.timeline.update', $timeline->id) }}" method="POST"
            class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/50 shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="title" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama
                    Kegiatan</label>
                <input type="text" name="title" id="title" required value="{{ old('title', $timeline->title) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-hidden text-sm font-bold"
                    placeholder="Contoh: PENDAFTARAN PASLON">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="start_date"
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Tanggal
                        Mulai</label>
                    <input type="date" name="start_date" id="start_date" required
                        value="{{ old('start_date', $timeline->start_date) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-hidden text-sm font-bold">
                    @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="end_date"
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Tanggal
                        Selesai</label>
                    <input type="date" name="end_date" id="end_date" required
                        value="{{ old('end_date', $timeline->end_date) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-hidden text-sm font-bold">
                    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="status"
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-hidden text-sm font-bold">
                        <option value="upcoming" {{ old('status', $timeline->status) === 'upcoming' ? 'selected' : '' }}>
                            Mendatang</option>
                        <option value="active" {{ old('status', $timeline->status) === 'active' ? 'selected' : '' }}>
                            Berlangsung</option>
                        <option value="completed" {{ old('status', $timeline->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="order"
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Urutan
                        Tampil</label>
                    <input type="number" name="order" id="order" required value="{{ old('order', $timeline->order) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-hidden text-sm font-bold">
                    @error('order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="description"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Deskripsi
                    Singkat</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-hidden text-sm font-medium"
                    placeholder="Berikan penjelasan singkat mengenai kegiatan ini...">{{ old('description', $timeline->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-4 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all outline-hidden uppercase tracking-widest text-xs">
                    Perbarui Agenda
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>