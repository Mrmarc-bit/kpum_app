<x-layouts.admin title="Template Pemberitahuan">
    <div x-data="{
        header: '{!! addslashes($settings['notification_header'] ?? 'KOMISI PEMILIHAN UMUM MAHASISWA') !!}',
        sub_header: '{!! addslashes($settings['notification_sub_header'] ?? 'UNIVERSITAS CONTOSO') !!}',
        title: '{!! addslashes($settings['notification_title'] ?? 'PEMBERITAHUAN PEMUNGUTAN SUARA') !!}',
        body_top: `{!! addslashes($settings['notification_body_top'] ?? 'Kami beritahukan kepada seluruh mahasiswa bahwa Pemilihan Umum Raya Mahasiswa akan dilaksanakan pada:') !!}`,
        schedule_date: '{!! addslashes($settings['notification_date'] ?? 'Senin, 20 Oktober 2024') !!}',
        schedule_time: '{!! addslashes($settings['notification_time'] ?? '08:00 - 16:00 WIB') !!}',
        voting_location: '{!! addslashes($settings['notification_location'] ?? 'E-Voting Portal (kpum.univ.ac.id)') !!}',
        body_bottom: `{!! addslashes($settings['notification_body_bottom'] ?? 'Demikian pemberitahuan ini kami sampaikan. Gunakan hak pilih Anda dengan bijak.') !!}`,
        signature_place_date: '{!! addslashes($settings['notification_signature_place_date'] ?? 'Cilacap, ..... Februari 2026') !!}',
        signature_title: '{!! addslashes($settings['notification_signature_title'] ?? 'Ketua KPUM') !!}',
        signature_name: '{!! addslashes($settings['notification_signature_name'] ?? 'Ma\'rufatul Khouro') !!}',
        signature_nim: '{!! addslashes($settings['notification_signature_nim'] ?? '22AF13003') !!}'
    }" class="space-y-8">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Template Surat Pemberitahuan</h1>
                <p class="text-slate-500 mt-2 font-medium">Sesuaikan format surat pemberitahuan pemungutan suara.</p>
            </div>

            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.settings.letters.notification.clear-cache') }}"
                    onsubmit="return confirm('Reset semua cache PDF surat pemberitahuan? PDF akan digenerate ulang saat download berikutnya.')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-orange-100 text-orange-700 font-bold rounded-2xl hover:bg-orange-200 transition-all hover:-translate-y-1 text-sm tracking-wide border border-orange-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Cache PDF
                    </button>
                </form>
                <a href="{{ route('admin.settings.letters.notification.sample') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all hover:-translate-y-1 text-sm tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Sampel
                </a>
                <button type="submit" form="settings-form"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1 text-sm tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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

        <div class="max-w-4xl mx-auto">
            
            <!-- FORM EDITOR -->
            <form id="settings-form" method="POST" action="{{ route('admin.settings.letters.notification.update') }}"
                class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 space-y-6 relative overflow-hidden">
                @csrf
                
                <div class="border-b border-slate-100 pb-6 mb-6">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        Editor Konten
                    </h2>
                </div>

                <div class="grid gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kop Surat / Instansi</label>
                        <input type="text" name="notification_header" x-model="header"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800 placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sub Header (Universitas/Fakultas)</label>
                        <input type="text" name="notification_sub_header" x-model="sub_header"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800 placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Surat</label>
                        <input type="text" name="notification_title" x-model="title"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-black text-slate-800 tracking-tight">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Paragraf Pembuka</label>
                        <textarea name="notification_body_top" rows="3" x-model="body_top"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-700 resize-none leading-relaxed"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                         <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hari, Tanggal</label>
                            <input type="text" name="notification_date" x-model="schedule_date"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Waktu</label>
                            <input type="text" name="notification_time" x-model="schedule_time"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm font-bold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lokasi / Link Voting</label>
                        <input type="text" name="notification_location" x-model="voting_location"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Paragraf Penutup</label>
                        <textarea name="notification_body_bottom" rows="3" x-model="body_bottom"
                            class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-700 resize-none leading-relaxed"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat & Tanggal Surat</label>
                            <input type="text" name="notification_signature_place_date" x-model="signature_place_date"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan Penanda Tangan</label>
                            <input type="text" name="notification_signature_title" x-model="signature_title"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                         <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Penanda Tangan</label>
                            <input type="text" name="notification_signature_name" x-model="signature_name"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIM Penanda Tangan</label>
                            <input type="text" name="notification_signature_nim" x-model="signature_nim"
                                class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-slate-800">
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-layouts.admin>
