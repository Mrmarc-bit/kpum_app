<x-layouts.admin :title="$title">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/50 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Edit Data Mahasiswa</h3>
                    <p class="text-slate-500 text-sm">Perbarui informasi DPT mahasiswa.</p>
                </div>
                <a href="{{ route('admin.dpt.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>

            <form method="POST" action="{{ route('admin.dpt.update', ['mahasiswa' => $mahasiswa->id]) }}"
                class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- NIM & Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all font-mono text-sm placeholder:text-slate-400">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Tanggal Lahir</label>
                        <input type="date" name="dob" value="{{ old('dob', $mahasiswa->dob?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $mahasiswa->name) }}"
                        class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-sm placeholder:text-slate-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ 
                    prodiMap: {{ json_encode($prodiList) }},
                    selectedProdi: '{{ old('prodi', $mahasiswa->prodi) }}',
                    fakultas: '{{ old('fakultas', $mahasiswa->fakultas) }}',

                    init() {
                        if (this.selectedProdi && !this.prodiMap[this.selectedProdi]) {
                            const normalized = this.selectedProdi.toLowerCase();
                            // Check if Key contains Value OR Value contains Key (bidirectional fuzzy)
                            const match = Object.keys(this.prodiMap).find(k => 
                                k.toLowerCase().includes(normalized) || normalized.includes(k.toLowerCase())
                            );
                            if (match) this.selectedProdi = match;
                        }
                        this.updateFakultas();
                        this.$watch('selectedProdi', () => this.updateFakultas());
                    },

                    updateFakultas() {
                        this.fakultas = this.prodiMap[this.selectedProdi] || '';
                    }
                }">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Prodi</label>
                        <div class="relative">
                            <select name="prodi" x-model="selectedProdi" required
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-sm appearance-none">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodiList as $prodi => $fakultas)
                                    <option value="{{ $prodi }}">{{ $prodi }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Fakultas</label>
                        <input type="text" name="fakultas" x-model="fakultas" readonly
                            class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-600 focus:border-slate-200 focus:ring-0 transition-all text-sm font-medium"
                            placeholder="Otomatis terisi">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.dpt.index') }}"
                        class="px-6 py-2.5 rounded-xl text-slate-600 font-bold hover:bg-slate-100 transition-all text-sm">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all text-sm flex items-center gap-2 transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>