<x-layouts.admin title="Edit Calon DPM">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Calon DPM</h1>
                <p class="text-slate-500 mt-1">Perbarui data pasangan calon Ketua dan Wakil DPM.</p>
            </div>
            <a href="{{ route('admin.calon_dpm.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.calon_dpm.update', $calon->id) }}" method="POST" enctype="multipart/form-data" data-turbo="false"
            class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data No Urut / Urutan Tampil -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Urutan Tampil</label>
                    <div class="flex items-center gap-4">
                        <input type="number" name="urutan_tampil"
                            value="{{ old('urutan_tampil', $calon->urutan_tampil) }}"
                            class="w-full md:w-32 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800 text-center text-lg placeholder-slate-400"
                            placeholder="1" required>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="status_aktif" id="status_aktif" value="1" {{ $calon->status_aktif ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="status_aktif" class="font-bold text-slate-700 text-sm">Status Aktif</label>
                        </div>
                    </div>
                    @error('urutan_tampil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Data Calon DPM -->
                <div class="md:col-span-2 space-y-6 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">01</span>
                        Data Calon DPM
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $calon->nama) }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Nama Lengkap" required>
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Program
                            Studi</label>
                        <input type="text" name="prodi" value="{{ old('prodi', $calon->prodi) }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Contoh: Teknik Informatika">
                    </div>

                    <!-- Foto Kandidat -->
                    <div class="space-y-4">
                        <label class="block text-sm font-bold text-slate-700">Foto Kandidat</label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden"
                                id="preview-container">
                                @if($calon->foto)
                                    <img id="image-preview" src="{{ asset('storage/' . $calon->foto) }}"
                                        alt="Preview" class="w-full h-full object-cover">
                                    <svg class="w-8 h-8 text-slate-400 hidden" id="placeholder-icon" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-slate-400" id="placeholder-icon" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <img id="image-preview"
                                        src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="Preview"
                                        class="w-full h-full object-cover hidden">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto"
                                    onchange="previewImage(this, 'image-preview', 'placeholder-icon')"
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100 cursor-pointer
                                ">
                                <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                                @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Visi</label>
                        <textarea name="visi" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Tuliskan visi calon...">{{ old('visi', $calon->visi) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Misi</label>
                        <textarea name="misi" rows="5"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Tuliskan misi calon...">{{ old('misi', $calon->misi) }}</textarea>
                    </div>
                </div>

                <!-- Koalisi Partai Pengusung -->
                <div class="md:col-span-2 space-y-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">02</span>
                        Koalisi Partai Pengusung
                    </h3>
                    <p class="text-sm text-slate-500 ml-10">Pilih partai-partai yang mengusung calon ini.</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
                        @foreach($parties as $party)
                            @php $isSelected = in_array($party->id, old('party_ids', $selectedPartyIds)); @endphp
                            <label for="party_d_{{ $party->id }}" 
                                x-data="{ checked: {{ $isSelected ? 'true' : 'false' }} }"
                                :class="checked ? 'border-blue-500 bg-blue-50 shadow-md shadow-blue-100' : 'border-slate-200 bg-slate-50 hover:border-blue-300 hover:bg-blue-50/50'"
                                class="relative flex flex-col items-center gap-2.5 p-3 rounded-2xl border-2 cursor-pointer transition-all duration-200 group">
                                
                                <input type="checkbox" 
                                    id="party_d_{{ $party->id }}" 
                                    name="party_ids[]" 
                                    value="{{ $party->id }}"
                                    {{ $isSelected ? 'checked' : '' }}
                                    @change="checked = $event.target.checked"
                                    class="sr-only">

                                {{-- Checkmark pill (top-right) --}}
                                <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-150"
                                    :class="checked ? 'bg-blue-500 border-blue-500' : 'border-slate-300 bg-white'">
                                    <svg x-show="checked" x-cloak class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                {{-- Logo Container --}}
                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center overflow-hidden transition-transform group-hover:scale-105">
                                    @if($party->logo_path)
                                        <img src="{{ asset('storage/' . $party->logo_path) }}" alt="{{ $party->name }}" class="w-full h-full object-contain p-1">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 font-bold text-[10px]">
                                            {{ $party->short_name ?: substr($party->name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Party Info --}}
                                <div class="text-center w-full">
                                    <h4 class="text-[11px] font-black text-slate-900 leading-tight uppercase truncate" title="{{ $party->name }}">
                                        {{ $party->short_name ?: $party->name }}
                                    </h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight mt-0.5" x-text="checked ? 'Dipilih' : 'Dukung'"></p>
                                </div>
                            </label>
                        @endforeach
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
        function previewImage(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

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
</x-layouts.admin>