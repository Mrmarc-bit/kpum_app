<x-layouts.admin :title="$title">
    <div class="max-w-4xl mx-auto space-y-6 pb-20">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-black text-slate-900 border-b-4 border-red-600 inline-block pb-1">Emergency Override System</h1>
            <p class="text-red-600 font-bold bg-red-100 inline-block px-3 py-1 rounded">⚠️ CLASSIFIED - SUPER ADMIN ONLY ⚠️</p>
        </div>

        <!-- Form Area -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
            
            <!-- Statistics Header -->
            <div class="bg-slate-50 p-6 border-b border-slate-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Status DPT</h3>
                        <p class="text-sm text-slate-500">Jumlah mahasiswa belum memilih</p>
                    </div>
                    <div class="text-3xl font-black text-blue-600">
                        {{ number_format($nonVotersCount ?? 0) }}
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form id="overrideForm" action="{{ route('admin.emergency.override.execute') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- 1. Input Jumlah Suara -->
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">1. Jumlah Suara Tambahan</label>
                        <input type="number" name="jumlah_suara" min="1" max="{{ $nonVotersCount ?? 0 }}" required
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-bold text-lg"
                            placeholder="Masukkan angka (Max: {{ $nonVotersCount ?? 0 }})">
                        <p class="text-xs text-slate-500 mt-2">*Suara akan didistribusikan secara acak (backdated)</p>
                    </div>

                    <!-- 2. Pilih Presma -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase">2. Target Pemenang Presma</label>
                        <div class="grid gap-4">
                            @forelse($kandidats as $kandidat)
                                <label class="relative flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="radio" name="kandidat_id" value="{{ $kandidat->id }}" required class="w-5 h-5 text-blue-600 border-slate-300 focus:ring-blue-500">
                                    <div class="ml-4">
                                        <span class="block text-xl font-black text-slate-800">No. {{ $kandidat->no_urut }}</span>
                                        <span class="block text-sm font-medium text-slate-600">{{ $kandidat->nama_ketua }} & {{ $kandidat->nama_wakil }}</span>
                                    </div>
                                </label>
                            @empty
                                <p class="text-red-500 p-4 border border-red-200 bg-red-50 rounded">Data Kandidat Kosong!</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. Pilih DPM -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase">3. Target Pemenang DPM</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto border border-slate-200 rounded-xl p-2 bg-slate-50">
                            @forelse($calonDpms as $dpm)
                                <label class="flex items-center p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-blue-400">
                                    <input type="radio" name="calon_dpm_id" value="{{ $dpm->id }}" required class="w-4 h-4 text-purple-600 border-slate-300 focus:ring-purple-500">
                                    <div class="ml-3 overflow-hidden">
                                        <span class="block font-bold text-slate-800 text-sm">No. {{ $dpm->nomor_urut }}</span>
                                        <span class="block text-xs text-slate-600 truncate">{{ $dpm->nama }}</span>
                                        <span class="block text-[10px] text-slate-400 truncate">{{ $dpm->prodi }}</span>
                                    </div>
                                </label>
                            @empty
                                <p class="col-span-2 text-red-500 text-center p-4">Data Calon DPM Kosong!</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- 4. Konfirmasi & Password -->
                    <div class="border-t-2 border-red-100 pt-6 mt-6 space-y-4">
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <p class="text-red-800 text-sm font-medium mb-2">Konfirmasi Keamanan:</p>
                            <input type="text" name="confirm_text" required
                                class="w-full px-4 py-2 border border-red-300 rounded bg-white text-sm font-mono placeholder:text-red-200"
                                placeholder="Ketik: EXECUTE OVERRIDE">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Password Super Admin</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                                placeholder="Masukkan password Anda untuk eksekusi">
                        </div>
                    </div>

                    <!-- Tombol Eksekusi -->
                    <div class="pt-4 pb-8">
                        <button type="button"
                            onclick="confirmOverride()"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg transition-transform transform active:scale-95 text-lg flex items-center justify-center gap-2">
                            <span>🚀</span> EKSEKUSI OVERRIDE SEKARANG
                        </button>
                        
                        <a href="{{ route('admin.dashboard') }}" class="block text-center mt-4 text-slate-500 hover:text-slate-800 text-sm font-medium">
                            Batalkan & Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="text-center text-xs text-slate-400 font-mono">
            System Log ID: {{ uniqid() }} | IP: {{ request()->ip() }}
        </div>
    </div>

    <script>
        function confirmOverride() {
            Swal.fire({
                title: '⚠️ PERINGATAN FINAL',
                html: "Apakah Anda yakin ingin melakukan override data?<br><span class='text-sm text-red-500 font-normal'>Tindakan ini akan memanipulasi data suara secara permanen.</span>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Eksekusi!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#fff',
                customClass: {
                    popup: 'rounded-[2rem] shadow-2xl border-2 border-red-100 font-sans',
                    title: 'text-red-700 font-black text-xl',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-red-500/30',
                    cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-3 px-6 rounded-xl',
                    htmlContainer: 'text-slate-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('overrideForm').submit();
                }
            })
        }
    </script>
</x-layouts.admin>