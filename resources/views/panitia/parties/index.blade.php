<x-layouts.panitia title="Manajemen Partai">
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
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Manajemen Logo Partai</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola daftar partai dan logo untuk keperluan pemilu.</p>
            </div>
            <button @click="createModalOpen = true" 
                class="w-full sm:w-auto px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/20 flex items-center justify-center gap-2 transition-all uppercase text-xs tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Partai
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Add Button Card (Desktop) -->
            <div @click="createModalOpen = true" class="cursor-pointer group border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center p-8 hover:border-purple-500 hover:bg-purple-50 transition-all duration-300 min-h-[250px]">
                <div class="w-16 h-16 rounded-full bg-slate-100 group-hover:bg-purple-100 flex items-center justify-center mb-4 transition-colors">
                    <svg class="w-8 h-8 text-slate-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="font-bold text-slate-600 group-hover:text-purple-700">Tambah Partai Baru</h3>
            </div>

            @foreach($parties as $party)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                    <div class="h-40 bg-slate-50 p-4 flex items-center justify-center relative overflow-hidden">
                        <img src="{{ Storage::url($party->logo_path) }}" alt="{{ $party->name }}" class="max-h-full max-w-full object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight mb-1">{{ $party->name }}</h3>
                        @if($party->short_name)
                            <p class="text-xs font-semibold text-purple-500 uppercase tracking-wide bg-purple-50 px-2 py-1 rounded w-fit mb-4">{{ $party->short_name }}</p>
                        @endif
                        
                        <div class="mt-auto flex justify-end gap-2 pt-4 border-t border-slate-50">
                            <button @click="openEditModal({{ $party }})" class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('panitia.parties.destroy', $party->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partai ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
                <div x-show="createModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="resetCreateForm()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="createModalOpen" x-transition.scale class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Tambah Partai Baru</h3>
                        <button @click="resetCreateForm()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('panitia.parties.store') }}" method="POST" enctype="multipart/form-data" data-turbo="false">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Partai <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all outline-none" placeholder="Contoh: Partai Mahasiswa Bersatu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Singkatan / Kode</label>
                                <input type="text" name="short_name" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all outline-none" placeholder="Contoh: PMB">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Logo Partai <span class="text-red-500">*</span></label>
                                
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg transition-all relative overflow-hidden"
                                    :class="isDragging ? 'border-purple-500 bg-purple-50 scale-[1.02]' : 'border-slate-300 bg-slate-50 hover:bg-slate-100'"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="isDragging = false; const files = $event.dataTransfer.files; if(files.length > 0) { $refs.createFileInput.files = files; handleFileChange({target: {files: files}}, 'create'); }">
                                    
                                    <!-- Hidden Input for clickable area -->
                                    <input x-ref="createFileInput" type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required accept="image/png, image/jpeg, image/jpg, image/svg+xml" @change="handleFileChange($event, 'create')">
                                    
                                    <!-- Preview (Shown if createPreview exists) -->
                                    <div x-show="createPreview" class="absolute inset-0 flex items-center justify-center bg-white rounded-lg z-30 p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <img :src="createPreview" class="max-h-full max-w-full object-contain">
                                        <button type="button" @click.prevent="createPreview = null; $refs.createFileInput.value = ''" class="absolute top-2 right-2 bg-white/80 hover:bg-white text-slate-600 rounded-full p-2 transition-colors shadow-sm backdrop-blur-sm border border-slate-200 z-40">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <!-- Upload Input (Shown if no preview) -->
                                    <div class="space-y-2 text-center z-10 transition-opacity duration-300" :class="{ 'opacity-0': createPreview }">
                                        <div class="mx-auto w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3">
                                            <svg class="h-6 w-6 text-purple-600" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col text-sm text-slate-600">
                                            <span class="font-semibold text-purple-600">Klik untuk upload</span>
                                            <span class="text-slate-500">atau drag & drop file ke sini</span>
                                        </div>
                                        <p class="text-xs text-slate-400">PNG, JPG, SVG (Maks. 2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="resetCreateForm()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">Simpan Partai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="editModalOpen" x-transition.scale class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Edit Partai</h3>
                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form :action="`{{ route('panitia.parties.index') }}/${editData.id}`" method="POST" enctype="multipart/form-data" data-turbo="false">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Partai <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Singkatan / Kode</label>
                                <input type="text" name="short_name" x-model="editData.short_name" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Ganti Logo (Opsional)</label>
                                <div class="flex items-center gap-4 mb-2">
                                    <img :src="editData.logo_url" class="w-12 h-12 object-contain rounded border border-slate-200 bg-slate-50">
                                    <span class="text-xs text-slate-500">Logo saat ini</span>
                                </div>
                                <input type="file" name="logo" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" accept="image/*" @change="handleFileChange($event, 'edit')">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium shadow-lg shadow-purple-500/30">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.panitia>
