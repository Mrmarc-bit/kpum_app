<x-layouts.admin title="Edit Berita">
    <div class="max-w-[1400px] mx-auto min-h-screen bg-[#F9FAFB]">
        <!-- Top Toolbar -->
        <div class="sticky top-0 z-40 bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 shadow-sm flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.posts.index') }}" class="text-slate-500 hover:text-slate-800 transition-colors p-2 -ml-2 rounded-full hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-xl font-bold text-slate-800">Blogger Mode <span class="text-slate-400 font-normal text-sm ml-2">Editing as {{ Auth::user()->name ?? 'Admin' }}</span></h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.posts.index') }}" class="text-sm font-bold text-slate-500 hover:text-red-500 px-4 py-2 hover:bg-red-50 rounded-lg transition-colors">Discard Changes</a>
                <button type="button" onclick="document.getElementById('post-form').submit()" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-full shadow-md shadow-orange-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Update Post
                </button>
            </div>
        </div>

        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <form id="post-form" action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8 items-start" data-turbo="false">
                @csrf
                @method('PUT')

                <!-- Main Editor Area (Left Content) -->
                <div class="w-full lg:w-3/4 flex flex-col gap-4">

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 px-6 py-4 rounded-xl font-medium border border-red-100">
                            Ada kesalahan. Pastikan Judul, Kategori & Isi terisi.
                        </div>
                    @endif

                    <!-- Title Input -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 overflow-hidden focus-within:ring-2 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all">
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required autofocus
                               class="w-full px-4 py-3 text-3xl md:text-4xl lg:text-5xl font-black text-slate-800 bg-transparent border-0 focus:ring-0 placeholder:text-slate-300 placeholder:font-bold"
                               placeholder="Title">
                    </div>

                    <!-- Quill Editor Container -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col focus-within:ring-2 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all min-h-[500px]">
                        <!-- Hidden input to store Quill HTML -->
                        <input type="hidden" name="content" id="hidden-content" value="{{ old('content', $post->content) }}">

                        <!-- Quill Editor Anchor -->
                        <div class="flex-grow w-full prose prose-slate max-w-none text-lg">
                            <div id="editor-container" class="h-full min-h-[500px] border-0">
                                {!! old('content', $post->content) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Settings (Right Sidebar) -->
                <div class="w-full lg:w-1/4 flex flex-col gap-4 lg:sticky lg:top-24">

                    <!-- Settings Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden divide-y divide-slate-100">
                        <div class="p-5 bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Post Settings
                            </h3>
                        </div>

                        <!-- Labels/Category -->
                        <div class="p-5 space-y-3">
                            <label for="category" class="block text-sm font-bold text-slate-700">Labels</label>
                            <select name="category" id="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                <option value="Berita" {{ old('category', $post->category) == 'Berita' ? 'selected' : '' }}>Berita</option>
                                <option value="Pengumuman" {{ old('category', $post->category) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="Event" {{ old('category', $post->category) == 'Event' ? 'selected' : '' }}>Event/Acara</option>
                            </select>
                        </div>

                        <!-- Excerpt / Search Description -->
                        <div class="p-5 space-y-3">
                            <label for="excerpt" class="block text-sm font-bold text-slate-700">Search description</label>
                            <textarea name="excerpt" id="excerpt" rows="3" maxlength="250"
                                      class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all placeholder:text-slate-400 resize-none"
                                      placeholder="Brief summary for UI cards...">{{ old('excerpt', $post->excerpt) }}</textarea>
                            <p class="text-xs text-slate-400 text-right">Max 250 chars</p>
                        </div>

                        <!-- Published Status -->
                        <div class="p-5 space-y-3">
                            <label class="block text-sm font-bold text-slate-700">Publishing Status</label>
                            <select name="is_published" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-900 font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                <option value="1" {{ old('is_published', $post->is_published) == '1' ? 'selected' : '' }}>Published (Live)</option>
                                <option value="0" {{ old('is_published', $post->is_published) == '0' ? 'selected' : '' }}>Draft (Hidden)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Security Info Box -->
                    <div class="bg-blue-50 text-blue-800 rounded-xl p-4 text-xs font-medium border border-blue-100/50">
                        <svg class="w-4 h-4 text-blue-600 inline-block mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <strong>Secured by Editor Policy:</strong> Your HTML input will be automatically sanitized by the server. Only standard text formatting is permitted to prevent XSS.
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Include Quill Theme & Script -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <style>
        /* Modern Blogger Overrides for Quill */
        .ql-toolbar.ql-snow {
            border: none;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 12px 16px;
            font-family: inherit;
        }
        .ql-container.ql-snow {
            border: none;
            font-size: 1.125rem;
            font-family: inherit;
            color: #1e293b;
        }
        .ql-editor {
            padding: 24px 32px;
            min-height: 500px;
        }
        .ql-editor p {
            margin-bottom: 1em;
            line-height: 1.8;
        }
        .ql-editor.ql-blank::before {
            color: #cbd5e1;
            font-style: normal;
            font-weight: 600;
        }
    </style>

    <script>
        function initializeQuill() {
            var editorContainer = document.querySelector('#editor-container');
            if (editorContainer && !editorContainer.classList.contains('ql-container')) {
                var quill = new Quill('#editor-container', {
                    theme: 'snow',
                    placeholder: 'Start writing your post...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });

                // Sinkronkan HTML dari Quill ke dalam input tersembunyi saat form di submit
                var form = document.querySelector('#post-form');
                if (form) {
                    form.onsubmit = function() {
                        var contentInput = document.querySelector('#hidden-content');
                        // get semantic HTML
                        contentInput.value = quill.root.innerHTML;
                    };
                }
            }
        }

        // Jika script dimuat normal (sinkron), Quill sudah tersedia:
        if (typeof Quill !== 'undefined') {
            initializeQuill();
        } else {
            // Berjaga-jaga jika script CDN dimuat asynchronous
            window.addEventListener('load', initializeQuill);
        }

        // Untuk menanggulangi masalah navigasi SPA (Livewire / Turbo)
        document.addEventListener('livewire:navigated', initializeQuill);
        document.addEventListener('turbo:load', initializeQuill);
        document.addEventListener('DOMContentLoaded', initializeQuill);
    </script>
</x-layouts.admin>
