<x-layouts.panitia title="Tulis Berita Baru">
    <div class="px-4 py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('panitia.posts.index') }}" class="w-10 h-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Tulis Berita</h1>
                <p class="text-slate-500 mt-1 font-medium text-sm">Publikasikan informasi terbaru seputar Pemilwa.</p>
            </div>
        </div>

        <form action="{{ route('panitia.posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden" data-turbo="false">
            @csrf

            <div class="p-8 space-y-8">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-normal"
                           placeholder="Masukkan judul artikel yang menarik...">
                    @error('title') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-bold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" id="category" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all appearance-none">
                            <option value="">Pilih kategori...</option>
                            <option value="Berita" {{ old('category') == 'Berita' ? 'selected' : '' }}>Berita</option>
                            <option value="Pengumuman" {{ old('category') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Event" {{ old('category') == 'Event' ? 'selected' : '' }}>Event/Acara</option>
                        </select>
                        @error('category') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_published" class="block text-sm font-bold text-slate-700 mb-2">Status Publikasi <span class="text-red-500">*</span></label>
                        <select name="is_published" id="is_published" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                            <option value="0" {{ old('is_published') === '0' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                            <option value="1" {{ old('is_published') === '1' ? 'selected' : '' }}>Langsung Publikasikan</option>
                        </select>
                        @error('is_published') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-bold text-slate-700 mb-2">
                        Ringkasan / Kutipan Pendek <span class="text-slate-400 font-normal ml-2">(Max 250 karakter)</span>
                    </label>
                    <textarea name="excerpt" id="excerpt" rows="2" maxlength="250"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-normal"
                              placeholder="Kutipan singkat yang akan tampil di halaman depan...">{{ old('excerpt') }}</textarea>
                    @error('excerpt') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-bold text-slate-700 mb-2">Isi Berita Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="12" required
                              class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 leading-relaxed font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-normal"
                              placeholder="Tuliskan isi berita secara lengkap di sini. (Anda bisa menggunakan format teks biasa)...">{{ old('content') }}</textarea>
                    <p class="mt-3 text-sm text-slate-500 flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Gunakan paragraf baru (Enter 2x) untuk memisahkan teks.
                    </p>
                    @error('content') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Bar -->
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-4 rounded-b-3xl">
                <a href="{{ route('panitia.posts.index') }}"
                   class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold shadow-lg shadow-purple-500/20 transition-all hover:-translate-y-0.5 min-w-[140px]">
                    Simpan Berita
                </button>
            </div>
        </form>
    </div>
</x-layouts.panitia>
