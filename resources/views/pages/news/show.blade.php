<x-layouts.guest title="{{ $post->title }} - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Article Header --}}
    <article class="pt-32 pb-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-black uppercase tracking-widest mb-6">
                    {{ $post->category }}
                </span>
                <h1 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tight leading-[1.1] mb-8">
                    {{ $post->title }}
                </h1>

                <div class="flex items-center justify-center gap-6 text-slate-500 font-bold text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                             <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </div>
                        {{ $post->author->name ?? 'Admin KPUM' }}
                    </div>
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $post->published_at->format('d M Y') }}
                    </div>
                </div>
            </div>

            @if($post->featured_image)
            <div class="relative mb-16 rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-200">
                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
            </div>
            @endif

            <!-- Article Body -->
            <div class="text-lg text-slate-700 leading-relaxed whitespace-pre-wrap font-medium">
                {{ $post->content }}
            </div>

            <!-- Author Card & Share -->
            <div class="mt-16 pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-2xl font-black shrink-0 shadow-lg shadow-blue-500/30">
                        {{ substr($post->user->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Diterbitkan oleh</div>
                        <div class="text-lg font-black text-slate-900">{{ $post->user->name ?? 'Admin KPUM' }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-start sm:justify-end gap-3 w-full sm:w-auto">
                    <span class="text-sm font-black text-slate-400 uppercase tracking-widest mr-2">Share:</span>
                    @foreach(['FB', 'WA', 'TW'] as $share)
                    <button class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-900 hover:text-white transition-all font-black text-xs">
                        {{ $share }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Back Anchor -->
            <div class="mt-16 text-center">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 font-bold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Berita
                </a>
            </div>
        </div>
    </article>

    {{-- Footer --}}
    @include('pages._footer')

</x-layouts.guest>
