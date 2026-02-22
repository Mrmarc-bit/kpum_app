<x-layouts.panitia title="File Manager">
    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ 
        view: '{{ request('view', 'grid') }}', 
        uploadModal: false, 
        newFolderModal: false,
        renameModal: false,
        previewModal: false,
        previewUrl: '',
        previewName: '',
        previewType: '',
        isDragging: false,
        dragCounter: 0
    }">
        
        <!-- Drag & Drop Overlay (Global) -->
        <div x-show="isDragging" 
             class="fixed inset-0 z-[100] bg-indigo-500/90 backdrop-blur-sm flex flex-col items-center justify-center text-white"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;"
             @dragover.prevent="isDragging = true"
             @dragleave.prevent="dragCounter--; if(dragCounter === 0) isDragging = false"
             @drop.prevent="isDragging = false; document.getElementById('global-file-input').files = $event.dataTransfer.files; document.getElementById('global-upload-form').submit();">
            
            <svg class="w-24 h-24 mb-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            <h2 class="text-3xl font-bold mb-2">Lepaskan File Disini</h2>
            <p class="text-indigo-100 text-lg">Upload file langsung ke folder ini</p>
        </div>

        <!-- Header -->
        <h1 class="text-2xl font-bold text-slate-800">File Manager</h1>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-slate-500 overflow-x-auto whitespace-nowrap scrollbar-hide py-2">
                <a href="{{ route('panitia.assets.index') }}" 
                   class="hover:text-indigo-600 transition-colors flex items-center {{ !$currentFolder ? 'font-bold text-slate-800 px-2 py-1 bg-slate-100 rounded-lg' : '' }}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Root
                </a>
                
                @if($currentFolder)
                    @foreach($currentFolder->path as $path)
                        <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <a href="{{ route('panitia.assets.index', ['folder' => $path->id]) }}" 
                           class="hover:text-indigo-600 transition-colors {{ $loop->last ? 'font-bold text-slate-800 px-2 py-1 bg-slate-100 rounded-lg' : '' }}">
                            {{ $path->name }}
                        </a>
                    @endforeach
                @endif
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <div class="bg-slate-100 p-1 rounded-lg border border-slate-200 hidden sm:flex">
                    <a href="{{ route('panitia.assets.index', array_merge(request()->query(), ['view' => 'grid'])) }}" 
                       class="p-2 rounded-md transition-all {{ request('view') != 'list' ? 'bg-white shadow text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </a>
                    <a href="{{ route('panitia.assets.index', array_merge(request()->query(), ['view' => 'list'])) }}" 
                       class="p-2 rounded-md transition-all {{ request('view') === 'list' ? 'bg-white shadow text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </a>
                </div>

                <div class="h-8 w-px bg-slate-300 mx-2 hidden sm:block"></div>

                <button @click="newFolderModal = true" 
                        class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                    <span class="hidden sm:inline">Folder Baru</span>
                </button>
                <button @click="uploadModal = true" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Upload File</span>
                </button>
            </div>
        </div>

        <!-- Global Upload Form (Hidden) -->
        <form id="global-upload-form" action="{{ route('panitia.assets.store') }}" method="POST" enctype="multipart/form-data" data-turbo="false" class="hidden">
            @csrf
            <input type="hidden" name="folder_id" value="{{ optional($currentFolder)->id }}">
            <input type="file" name="files[]" id="global-file-input" multiple onchange="this.form.submit()">
        </form>

        <!-- Content Area -->
        <div class="min-h-[500px]">
            @if($folders->count() === 0 && $assets->count() === 0)
                <div class="flex flex-col items-center justify-center p-12 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50 h-96 mt-6">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6 ring-4 ring-indigo-50/50">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Folder ini Kosong</h3>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto">Upload file atau buat folder baru untuk mulai mengatur aset digital Anda.</p>
                </div>
            @else
                @if(request('view') === 'list')
                    <!-- List View (Table) -->
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mt-6">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Ukuran</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Tipe</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Diubah</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-20 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($folders as $folder)
                                    <tr class="group hover:bg-slate-50 transition-colors cursor-pointer" data-href="{{ route('panitia.assets.index', ['folder' => $folder->id]) }}" onclick="window.location=this.dataset.href">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-8 h-8 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                                <span class="font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $folder->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-400">-</td>
                                        <td class="px-6 py-4 text-sm text-slate-400">Folder</td>
                                        <td class="px-6 py-4 text-sm text-slate-400">{{ $folder->updated_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="relative" x-data="{ open: false }" @click.stop>
                                                <button @click="open = !open" @click.away="open = false" class="p-1.5 hover:bg-slate-200 rounded-lg text-slate-400 hover:text-slate-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                </button>
                                                <div x-show="open" class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-10 py-1"
                                                     x-transition:enter="transition ease-out duration-100" style="display: none;">
                                                    <button data-rename-url="{{ route('panitia.assets.folder.rename', $folder) }}" data-rename-name="{{ e($folder->name) }}" onclick="openRenameModal(this.dataset.renameUrl, this.dataset.renameName)" 
                                                            class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        Rename
                                                    </button>
                                                    <form action="{{ route('panitia.assets.folder.destroy', $folder) }}" method="POST" onsubmit="return confirm('Hapus folder ini?')">
                                                        @csrf 
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($assets as $asset)
                                    <tr class="group hover:bg-slate-50 transition-colors cursor-pointer"
                                        @click="previewUrl = '{{ $asset->url }}'; previewName = '{{ $asset->name }}'; previewType = '{{ $asset->mime_type }}'; 
                                                if(previewType.startsWith('image/')) { previewModal = true; } else { window.open(previewUrl, '_blank'); }">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if(str_starts_with($asset->mime_type, 'image/'))
                                                    <img src="{{ $asset->url }}" class="w-8 h-8 rounded bg-slate-100 object-cover border border-slate-200">
                                                @else
                                                    <div class="w-8 h-8 flex items-center justify-center bg-indigo-50 rounded text-indigo-500 border border-indigo-100">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                                <span class="font-medium text-slate-700">{{ $asset->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $asset->file_size_human }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $asset->mime_type }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $asset->updated_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <!-- List View Menu similar to Grid -->
                                            <div class="relative" x-data="{ open: false }" @click.stop>
                                                <button @click="open = !open" @click.away="open = false" class="p-1 hover:bg-slate-200 rounded-full text-slate-400">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                                </button>
                                                <div x-show="open" class="absolute right-0 top-6 w-32 bg-white rounded-lg shadow-xl border border-slate-100 z-50 py-1" style="display: none;">
                                                    <a href="{{ $asset->url }}" download class="w-full text-left px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 flex items-center gap-2">Download</a>
                                                    <button data-rename-url="{{ route('panitia.assets.rename', $asset) }}" data-rename-name="{{ e($asset->name) }}" onclick="openRenameModal(this.dataset.renameUrl, this.dataset.renameName)" class="w-full text-left px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 flex items-center gap-2">Rename</button>
                                                    <form action="{{ route('panitia.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Hapus file?')">
                                                        @csrf 
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Grid View - Refined Design -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mt-6">
                        @foreach($folders as $folder)
                            <div class="group relative p-2 rounded-2xl hover:bg-slate-50 transition-all cursor-pointer"
                                 data-href="{{ route('panitia.assets.index', ['folder' => $folder->id]) }}" onclick="window.location=this.dataset.href">
                                <!-- Folder Thumbnail -->
                                <div class="aspect-[4/3] w-full rounded-xl overflow-hidden bg-amber-50 border border-amber-100 flex items-center justify-center relative mb-3 shadow-sm group-hover:shadow-md transition-all">
                                    <svg class="w-16 h-16 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                    
                                    <!-- 3-Dots Menu -->
                                    <div class="absolute top-2 right-2" onclick="event.stopPropagation()">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" @click.away="open = false" class="bg-white/80 hover:bg-white p-1 rounded-full text-slate-500 shadow-sm transition-all border border-transparent hover:border-slate-200">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                            </button>
                                            <div x-show="open" class="absolute right-0 top-8 w-32 bg-white rounded-lg shadow-xl border border-slate-100 z-50 py-1" style="display: none;">
                                                <button data-rename-url="{{ route('panitia.assets.folder.rename', $folder) }}" data-rename-name="{{ e($folder->name) }}" onclick="openRenameModal(this.dataset.renameUrl, this.dataset.renameName)" class="w-full text-left px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">Rename</button>
                                                <form action="{{ route('panitia.assets.folder.destroy', $folder) }}" method="POST" onsubmit="return confirm('Hapus folder ini?')">
                                                    @csrf 
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Meta -->
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Created on {{ $folder->created_at->format('M d, Y') }}</p>
                                
                                <!-- Title -->
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="p-1 rounded bg-amber-100 text-amber-600">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 truncate block flex-1" title="{{ $folder->name }}">{{ $folder->name }}</span>
                                </div>
                                
                                <!-- Footer -->
                                <p class="text-[10px] text-slate-400">Updated <span class="text-indigo-600 font-bold">{{ $folder->updated_at->diffForHumans() }}</span></p>
                            </div>
                        @endforeach

                        @foreach($assets as $asset)
                            <div class="group relative p-2 rounded-2xl hover:bg-slate-50 transition-all cursor-pointer"
                                 @click="previewUrl = '{{ $asset->url }}'; previewName = '{{ $asset->name }}'; previewType = '{{ $asset->mime_type }}'; 
                                         if(previewType.startsWith('image/')) { previewModal = true; } else { window.open(previewUrl, '_blank'); }">
                                
                                <!-- Thumbnail -->
                                <div class="aspect-[4/3] w-full rounded-xl overflow-hidden bg-slate-100 relative mb-3 shadow-sm group-hover:shadow-md transition-all">
                                    @if(str_starts_with($asset->mime_type, 'image/'))
                                        <img src="{{ $asset->url }}" class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-500">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-indigo-50">
                                            <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                    @endif
                                    
                                    <!-- 3-Dots Menu (Always visible when button is there, but button style can be subtle) -->
                                    <div class="absolute top-2 right-2" onclick="event.stopPropagation()">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" @click.away="open = false" 
                                                    class="bg-white/90 hover:bg-white p-1.5 rounded-full text-slate-600 shadow-sm transition-all border border-transparent hover:border-slate-200">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                            </button>
                                            <!-- Dropdown Menu -->
                                            <div x-show="open" class="absolute right-0 top-8 w-32 bg-white rounded-lg shadow-xl border border-slate-100 z-50 py-1" style="display: none;">
                                                <button data-rename-url="{{ route('panitia.assets.rename', $asset) }}" data-rename-name="{{ e($asset->name) }}" onclick="openRenameModal(this.dataset.renameUrl, this.dataset.renameName)" class="w-full text-left px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                                    Rename
                                                </button>
                                                <a href="{{ $asset->url }}" download class="w-full text-left px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                                    Download
                                                </a>
                                                <form action="{{ route('panitia.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Hapus file?')">
                                                    @csrf 
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Meta Text -->
                                <p class="text-[10px] text-slate-400 font-medium mb-1">Created on {{ $asset->created_at->format('M d, Y') }}</p>
                                
                                <!-- Filename with Icon -->
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="p-1 rounded bg-indigo-100 text-indigo-600 flex-shrink-0">
                                        @if(str_starts_with($asset->mime_type, 'image/'))
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 truncate block flex-1" title="{{ $asset->name }}">{{ $asset->name }}</span>
                                </div>
                                
                                <!-- Footer Meta -->
                                <p class="text-[10px] text-slate-400 truncate">Updated <span class="text-indigo-600 font-bold">{{ $asset->updated_at->diffForHumans() }}</span></p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8">
                    {{ $assets->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Create Folder Modal -->
        <div x-show="newFolderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl scale-100" @click.away="newFolderModal = false">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Buat Folder Baru</h3>
                <form action="{{ route('panitia.assets.folder.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ optional($currentFolder)->id }}">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Folder</label>
                        <input type="text" name="name" required placeholder="Contoh: Dokumen Legal" autofocus
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50">
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="newFolderModal = false" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl font-bold text-sm transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-lg shadow-indigo-500/20">Buat Folder</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Upload File Modal -->
        <div x-show="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            <div class="bg-white rounded-2xl p-8 w-full max-w-lg shadow-2xl" @click.away="uploadModal = false">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Upload File</h3>
                    <button @click="uploadModal = false" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <form action="{{ route('panitia.assets.store') }}" method="POST" enctype="multipart/form-data" data-turbo="false" class="space-y-6" x-data="{ filesCount: 0 }">
                    @csrf
                    <input type="hidden" name="folder_id" value="{{ optional($currentFolder)->id }}">

                    <div class="relative group cursor-pointer">
                        <input type="file" name="files[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               @change="filesCount = $event.target.files.length">
                        <div class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-2xl bg-slate-50 group-hover:bg-indigo-50/50 group-hover:border-indigo-400 transition-all">
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                             <p class="text-sm font-medium text-slate-700" x-show="filesCount === 0">Klik atau geser file kesini</p>
                            <p class="text-sm font-bold text-indigo-600" x-show="filesCount > 0" x-text="filesCount + ' file dipilih'"></p>
                            <p class="text-xs text-slate-400 mt-1">Bisa upload banyak file sekaligus</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="uploadModal = false" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl font-bold text-sm transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-lg shadow-indigo-500/20">Upload Sekarang</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Modal -->
        <div x-show="previewModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;" @click="previewModal = false">
            <div class="relative w-full max-w-sm flex flex-col items-center justify-center p-4" @click.stop>
                <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl max-h-[300px] w-full flex items-center justify-center border border-slate-100">
                    <button @click="previewModal = false" class="absolute top-2 right-2 text-white/90 hover:text-white transition-colors bg-black/40 hover:bg-black/60 rounded-full p-1 backdrop-blur-md z-10 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <img :src="previewUrl" class="w-full h-auto max-h-[300px] object-contain bg-checkered">
                </div>
                <div class="mt-3 text-white/90 font-medium text-xs bg-black/60 px-4 py-1.5 rounded-full backdrop-blur-md shadow-lg truncat max-w-full text-center" x-text="previewName"></div>
            </div>
            
            <style>
                .bg-checkered {
                    background-image: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #f0f0f0 75%), linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
                    background-size: 20px 20px;
                    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
                }
            </style>
        </div>

        <!-- Rename Modal -->
        <div id="rename-modal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 scale-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Ubah Nama</h3>
                <form id="rename-form" action="" method="POST">
                    @csrf 
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Baru</label>
                        <input type="text" name="name" id="rename-input" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-sans" required>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeRenameModal()" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors text-sm font-bold">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200 text-sm font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Drag & Drop Global Events -->
    <script>
        document.addEventListener('dragover', function(e) {
            e.preventDefault();
            // This is handled by Alpine x-data, but we need to prevent default here for drop to work
        }, false);

        document.addEventListener('drop', function(e) {
            e.preventDefault();
        }, false);

        function openRenameModal(url, name) {
            const modal = document.getElementById('rename-modal');
            const form = document.getElementById('rename-form');
            const input = document.getElementById('rename-input');
            
            form.action = url;
            input.value = name;
            modal.style.display = 'flex';
            setTimeout(() => { input.focus(); input.select(); }, 100);
        }

        function closeRenameModal() {
            document.getElementById('rename-modal').style.display = 'none';
        }
    </script>
</x-layouts.panitia>
