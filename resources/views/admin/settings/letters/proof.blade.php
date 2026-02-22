<x-layouts.admin title="Pengaturan Surat">
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Surat</h1>
                <p class="text-slate-500 mt-2 font-medium">Sesuaikan template surat bukti pemilihan yang akan diunduh mahasiswa.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.settings.letters.proof.sample') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all hover:-translate-y-1 text-sm tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Unduh Sampel
                </a>
                <button type="submit" form="settings-form"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1 text-sm tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-50 text-green-700 p-4 rounded-2xl border border-green-200 flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <form id="settings-form" method="POST" action="{{ route('admin.settings.letters.proof.update') }}"
                enctype="multipart/form-data" data-turbo="false" class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">
            @csrf
            
            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-slate-100">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                     <h2 class="text-xl font-black text-slate-800 tracking-tight">Template Surat Bukti Pilih</h2>
                     <p class="text-slate-500 text-sm font-medium">Atur konten dinamis untuk surat keterangan memilih.</p>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-10">
                <!-- Left Column: Content -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kop Surat (Header)</label>
                        <input type="text" name="letter_header" value="{{ $settings['letter_header'] ?? 'KOMISI PEMILIHAN UMUM MAHASISWA' }}"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all font-bold text-slate-800 placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sub Header</label>
                        <input type="text" name="letter_sub_header" value="{{ $settings['letter_sub_header'] ?? 'UNIVERSITAS CONTOSO' }}"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all font-bold text-slate-800 placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Teks Watermark</label>
                        <input type="text" name="letter_watermark_text" value="{{ $settings['letter_watermark_text'] ?? 'OFFICIAL COPY' }}"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all font-bold text-slate-800 placeholder:text-slate-400"
                            placeholder="Contoh: OFFICIAL COPY, SALINAN SAH, DRAFT">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Paragraf Pembuka</label>
                        <textarea name="letter_body_top" rows="3"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all font-medium text-slate-700 resize-none leading-relaxed">{{ $settings['letter_body_top'] ?? 'Menerangkan bahwa mahasiswa dengan identitas di bawah ini:' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Paragraf Penutup</label>
                        <textarea name="letter_body_bottom" rows="4"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all font-medium text-slate-700 resize-none leading-relaxed">{{ $settings['letter_body_bottom'] ?? 'Telah menggunakan hak suaranya pada Pemilihan Umum Raya Mahasiswa tahun ini. Surat ini adalah dokumen sah dan dapat digunakan sebagai bukti partisipasi.' }}</textarea>
                    </div>
                </div>
                
                <!-- Right Column: Footer & Signature -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Footer (Catatan Kaki)</label>
                        <input type="text" name="letter_footer" value="{{ $settings['letter_footer'] ?? 'Dokumen ini dibuat secara otomatis oleh sistem e-voting.' }}"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all font-medium text-slate-600 text-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat & Tanggal Surat</label>
                            <input type="text" name="letter_signature_place_date" x-model="signature_place_date"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan Penanda Tangan</label>
                            <input type="text" name="letter_signature_title" x-model="signature_title"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                         <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Penanda Tangan</label>
                            <input type="text" name="letter_signature_name" x-model="signature_name"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIM Penanda Tangan</label>
                            <input type="text" name="letter_signature_nim" x-model="signature_nim"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div x-data="{ 
                        sigName: null, 
                        sigPreview: {{ isset($settings['letter_signature_path']) && $settings['letter_signature_path'] ? "'" . asset((string) $settings['letter_signature_path']) . "'" : 'null' }} 
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Scan Tanda Tangan (PNG/JPG)</label>
                        
                        <!-- File Input (Hidden) -->
                        <input type="file" name="letter_signature_path" class="hidden" x-ref="sig" accept="image/*" x-on:change="
                                sigName = $refs.sig.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    sigPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.sig.files[0]);
                            ">

                        <!-- 1. EMPTY STATE -->
                        <div class="w-full h-48 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-slate-100 hover:border-purple-500 transition-all cursor-pointer flex flex-col items-center justify-center gap-2 group"
                            x-show="!sigPreview" @click="$refs.sig.click()">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-2 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-slate-700 group-hover:text-purple-700 transition-colors">Klik untuk upload tanda tangan</p>
                                <p class="text-xs text-slate-400 font-medium mt-1">Disarankan menggunakan gambar transparan</p>
                            </div>
                        </div>

                        <!-- 2. PREVIEW STATE -->
                        <div class="relative w-full h-48 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden group"
                            x-show="sigPreview" style="display: none;">
                            <!-- Checkered background for transparency -->
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 10px 10px;"></div>
                            
                            <img :src="sigPreview" class="absolute inset-0 w-full h-full object-contain p-4 z-10">
                            
                            <!-- Overlay Action -->
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 backdrop-blur-sm">
                                <button type="button" @click="$refs.sig.click()" class="bg-white text-slate-800 text-xs font-bold py-2 px-4 rounded-xl shadow-lg hover:bg-slate-50 transition-colors">Ganti Tanda Tangan</button>
                            </div>
                        </div>
                         @error('letter_signature_path')
                            <p class="text-red-500 text-xs font-bold mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Scan Stempel -->
                    <div x-data="{ 
                        stampName: null, 
                        stampPreview: {{ isset($settings['letter_stamp_path']) && $settings['letter_stamp_path'] ? "'" . asset((string) $settings['letter_stamp_path']) . "'" : 'null' }} 
                    }" class="mt-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Scan Stempel (PNG/JPG)</label>
                        
                        <!-- File Input (Hidden) -->
                        <input type="file" name="letter_stamp_path" class="hidden" x-ref="stamp" accept="image/*" x-on:change="
                                stampName = $refs.stamp.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    stampPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.stamp.files[0]);
                            ">

                        <!-- 1. EMPTY STATE -->
                        <div class="w-full h-48 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-slate-100 hover:border-purple-500 transition-all cursor-pointer flex flex-col items-center justify-center gap-2 group"
                            x-show="!stampPreview" @click="$refs.stamp.click()">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-2 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-slate-700 group-hover:text-purple-700 transition-colors">Klik untuk upload stempel</p>
                                <p class="text-xs text-slate-400 font-medium mt-1">Disarankan menggunakan gambar transparan</p>
                            </div>
                        </div>

                        <!-- 2. PREVIEW STATE -->
                        <div class="relative w-full h-48 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden group"
                            x-show="stampPreview" style="display: none;">
                            <!-- Checkered background for transparency -->
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 10px 10px;"></div>
                            
                            <img :src="stampPreview" class="absolute inset-0 w-full h-full object-contain p-4 z-10">
                            
                            <!-- Overlay Action -->
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 backdrop-blur-sm">
                                <button type="button" @click="$refs.stamp.click()" class="bg-white text-slate-800 text-xs font-bold py-2 px-4 rounded-xl shadow-lg hover:bg-slate-50 transition-colors">Ganti Stempel</button>
                            </div>
                        </div>
                         @error('letter_stamp_path')
                            <p class="text-red-500 text-xs font-bold mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
