<x-layouts.panitia title="Edit Kandidat">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Kandidat</h1>
                <p class="text-slate-500 mt-1">Perbarui data pasangan calon.</p>
            </div>
            <a href="{{ route('panitia.kandidat.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('panitia.kandidat.update', $kandidat->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data No Urut -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Urut</label>
                    <input type="number" name="no_urut" value="{{ old('no_urut', $kandidat->no_urut) }}"
                        class="w-full md:w-32 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800 text-center text-lg placeholder-slate-400"
                        placeholder="01" required>
                    @error('no_urut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Ketua -->
                <div class="space-y-6 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">01</span>
                        Data Calon Ketua
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama_ketua" value="{{ old('nama_ketua', $kandidat->nama_ketua) }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Nama Ketua" required>
                        @error('nama_ketua') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Program
                            Studi</label>
                        <input type="text" name="prodi_ketua" value="{{ old('prodi_ketua', $kandidat->prodi_ketua) }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Contoh: Teknik Informatika">
                    </div>
                </div>

                <!-- Wakil -->
                <div class="space-y-6 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">02</span>
                        Data Calon Wakil
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama_wakil" value="{{ old('nama_wakil', $kandidat->nama_wakil) }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Nama Wakil" required>
                        @error('nama_wakil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Program
                            Studi</label>
                        <input type="text" name="prodi_wakil" value="{{ old('prodi_wakil', $kandidat->prodi_wakil) }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Contoh: Sistem Informasi">
                    </div>
                </div>

                <!-- Foto Paslon -->
                <div class="md:col-span-2 space-y-4">
                    <label class="block text-sm font-bold text-slate-700">Foto Pasangan Calon</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden"
                            id="preview-container">
                            @if($kandidat->foto)
                                <img id="image-preview" src="{{ asset('storage/' . $kandidat->foto) }}" alt="Preview"
                                    class="w-full h-full object-cover">
                                <svg class="w-8 h-8 text-slate-400 hidden" id="placeholder-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-slate-400" id="placeholder-icon" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <img id="image-preview" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                                    alt="Preview" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto" onchange="previewImage(this)" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 cursor-pointer
                            ">
                            <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG. Maksimal 2MB. Biarkan kosong jika
                                tidak ingin mengubah foto.</p>
                            @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Visi</label>
                        <textarea name="visi" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Tuliskan visi kandidat...">{{ old('visi', $kandidat->visi) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Misi</label>
                        <textarea name="misi" rows="5"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Tuliskan misi kandidat...">{{ old('misi', $kandidat->misi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const container = document.getElementById('preview-container');
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('placeholder-icon');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layouts.panitia>