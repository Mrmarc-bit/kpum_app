<x-layouts.admin title="Manajemen Partai">
    <div x-data="{ 
        createModalOpen: false, 
        editModalOpen: false, 
        createPreview: null,
        isDragging: false,
        editData: { id: null, name: '', short_name: '', logo_url: '' },
        
        openEditModal(party) {
            this.editData = { ...party }; // Clone object to avoid direct mutation issues
            this.editData.logo_url = '/storage/' + party.logo_path;
            this.editModalOpen = true;
        },

        handleFileChange(event, type) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (type === 'create') {
                        this.createPreview = e.target.result;
                    } else {
                        this.editData.logo_url = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        },

        resetCreateForm() {
            this.createPreview = null;
            this.createModalOpen = false;
        }
    }">
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Partai</h1>
                <p class="text-slate-500 mt-2 font-medium text-sm sm:text-base">Kelola daftar partai dan logo untuk keperluan pemilu.</p>
            </div>
            <button @click="createModalOpen = true" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-1 text-sm tracking-wide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Partai
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center justify-between">
                 <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                 <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Add Button Card (Desktop) -->
            <div @click="createModalOpen = true" class="cursor-pointer group border-3 border-dashed border-slate-200 rounded-[2.5rem] flex flex-col items-center justify-center p-8 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all duration-300 min-h-[320px]">
                <div class="w-20 h-20 rounded-full bg-slate-100 group-hover:bg-indigo-100 flex items-center justify-center mb-6 transition-colors group-hover:scale-110 shadow-sm">
                    <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-600 group-hover:text-indigo-700 uppercase tracking-wide">Tambah Partai</h3>
            </div>

            @foreach($parties as $party)
                <div class="bg-white rounded-[2.5rem] shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col ring-1 ring-slate-100 group">
                    <div class="h-48 bg-slate-50 p-8 flex items-center justify-center relative overflow-hidden group">
                        <!-- Decorative Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <img src="{{ Storage::url($party->logo_path) }}" alt="{{ $party->name }}" class="max-h-full max-w-full object-contain drop-shadow-lg transform transition-transform duration-700 group-hover:scale-110 relative z-10">
                    </div>
                    <div class="p-6 flex-1 flex flex-col relative bg-white">
                        <div class="mb-4">
                            @if($party->short_name)
                                <div class="mb-2">
                                     <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-md mb-2 inline-block ring-1 ring-indigo-100">{{ $party->short_name }}</span>
                                </div>
                            @endif
                            <h3 class="font-bold text-slate-800 text-lg leading-tight line-clamp-2" title="{{ $party->name }}">{{ $party->name }}</h3>
                        </div>
                        
                        <div class="mt-auto flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button @click="openEditModal({{ $party }})" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('admin.parties.destroy', $party->id) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus partai ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $parties->links() }}
        </div>

        <!-- Create Modal -->
        <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="createModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-md" @click="resetCreateForm()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="createModalOpen" x-transition.scale class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem] border border-slate-100">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Tambah Partai Baru</h3>
                            <p class="text-slate-500 text-sm mt-1">Isi data partai dan upload logo.</p>
                        </div>
                        <button @click="resetCreateForm()" class="bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full p-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.parties.store') }}" method="POST" enctype="multipart/form-data" data-turbo="false">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Partai <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all outline-none font-medium text-slate-800 placehoder:text-slate-400" placeholder="Contoh: Partai Mahasiswa Bersatu">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Singkatan / Kode</label>
                                <input type="text" name="short_name" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all outline-none font-medium text-slate-800" placeholder="Contoh: PMB">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo Partai <span class="text-red-500">*</span></label>
                                
                                <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed rounded-2xl transition-all relative overflow-hidden group cursor-pointer"
                                    :class="isDragging ? 'border-indigo-500 bg-indigo-50 scale-[1.02]' : 'border-slate-300 bg-slate-50 hover:bg-slate-100 hover:border-indigo-300'"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="isDragging = false; const files = $event.dataTransfer.files; if(files.length > 0) { $refs.createFileInput.files = files; handleFileChange({target: {files: files}}, 'create'); }"
                                    @click="$refs.createFileInput.click()">
                                    
                                    <!-- Hidden Input for clickable area -->
                                    <input x-ref="createFileInput" type="file" name="logo" class="hidden" required accept="image/png, image/jpeg, image/jpg, image/svg+xml" @change="handleFileChange($event, 'create')">
                                    
                                    <!-- Preview (Shown if createPreview exists) -->
                                    <div x-show="createPreview" class="absolute inset-0 flex items-center justify-center bg-white z-30 p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <img :src="createPreview" class="max-h-full max-w-full object-contain drop-shadow-md">
                                        <button type="button" @click.stop="createPreview = null; $refs.createFileInput.value = ''" class="absolute top-4 right-4 bg-slate-900/10 hover:bg-slate-900/20 text-slate-600 rounded-full p-2 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <!-- Upload Input (Shown if no preview) -->
                                    <div class="space-y-4 text-center z-10 transition-opacity duration-300" :class="{ 'opacity-0': createPreview }">
                                        <div class="mx-auto w-16 h-16 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                            <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col text-sm text-slate-600">
                                            <span class="font-bold text-indigo-600 text-base">Klik untuk upload</span>
                                            <span class="text-slate-500 mt-1">atau drag & drop file ke sini</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PNG, JPG, SVG (Maks. 2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-4">
                            <button type="button" @click="resetCreateForm()" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-bold text-sm">Batal</button>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-bold text-sm shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transform">Simpan Partai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-md" @click="editModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="editModalOpen" x-transition.scale class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem] border border-slate-100">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                             <h3 class="text-xl font-black text-slate-800 tracking-tight">Edit Partai</h3>
                             <p class="text-slate-500 text-sm mt-1">Perbarui data partai.</p>
                        </div>
                        <button @click="editModalOpen = false" class="bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full p-2 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form :action="`{{ route('admin.parties.index') }}/${editData.id}`" method="POST" enctype="multipart/form-data" data-turbo="false">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Partai <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all outline-none font-medium text-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Singkatan / Kode</label>
                                <input type="text" name="short_name" x-model="editData.short_name" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all outline-none font-medium text-slate-800">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Logo (Opsional)</label>
                                
                                <div class="mt-1 flex items-center justify-center border-2 border-slate-300 border-dashed rounded-2xl p-6 bg-slate-50 relative group hover:bg-slate-100 transition-colors cursor-pointer" @click="$refs.editFileInput.click()">
                                    <div class="text-center w-full">
                                        <div class="mb-4 flex justify-center">
                                            <img :src="editData.logo_url" class="h-32 w-auto object-contain drop-shadow-sm bg-white p-2 rounded-lg">
                                        </div>
                                        
                                        <div class="relative">
                                            <input x-ref="editFileInput" id="edit-file-upload" name="logo" type="file" class="hidden" accept="image/*" @change="handleFileChange($event, 'edit')">
                                            <span class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-bold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition">
                                                Pilih Logo Baru
                                            </span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-4">Biarkan kosong jika tidak ingin mengubah</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="editModalOpen = false" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-bold text-sm">Batal</button>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-bold text-sm shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transform">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
