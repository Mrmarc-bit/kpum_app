@php
    $news = \App\Models\Post::published()->orderBy('published_at', 'desc')->take(3)->get();
@endphp

@if($news->count() > 0)
<section class="py-24 relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-16 items-start">

            <!-- Headline Column -->
            <div class="lg:col-span-4 space-y-8">
                <div class="space-y-4">
                    <h2 class="text-6xl sm:text-7xl font-black text-slate-900 leading-[0.9] tracking-tighter">
                        Warta<br>Demokrasi<span class="text-blue-600">.</span>
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-sm">
                        Ikuti perkembangan terbaru, pengumuman resmi, dan edukasi politik seputar Pemilwa UNUGHA 2026.
                    </p>
                </div>

                <div class="pt-4">
                    <a href="{{ route('news.index') }}" class="group inline-flex items-center gap-3 bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-blue-600 transition-all duration-300 shadow-xl shadow-slate-200 hover:shadow-blue-500/20">
                        Lihat Semua Berita
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- News Cards Grid -->
            <div class="lg:col-span-8 grid md:grid-cols-2 gap-8">
                @foreach($news as $index => $item)
                <div class="group relative flex flex-col bg-slate-50 rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-slate-200 transition-all duration-500 hover:-translate-y-2">
                    <!-- Top Info -->
                    <div class="p-8 pb-4 flex justify-between items-start relative z-10">
                        <span class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-widest">
                            {{ $item->category }}
                        </span>

                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-900 border border-slate-200 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8 pt-0 relative z-10">
                        <h3 class="text-2xl font-black text-slate-900 mb-4 group-hover:text-blue-600 transition-colors leading-tight">
                            <a href="{{ route('news.show', $item->slug) }}">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-8">
                            {{ $item->excerpt }}
                        </p>
                    </div>

                    <!-- Decorative Footer (Simplified Illustration Style) -->
                    <div class="mt-auto relative h-48 w-full overflow-hidden flex items-end justify-center">
                        <!-- Dynamic colored background based on index -->
                        @php
                            $bgColors = ['bg-blue-100', 'bg-indigo-100', 'bg-violet-100'];
                            $iconColors = ['text-blue-400', 'text-indigo-400', 'text-violet-400'];
                            $colorIndex = $index % count($bgColors);
                        @endphp

                        <div class="absolute inset-x-4 bottom-4 top-0 rounded-[2rem] {{ $bgColors[$colorIndex] }} transition-all duration-500 group-hover:scale-[1.02]"></div>

                        <div class="relative z-10 mb-8 opacity-40 group-hover:opacity-100 transition-opacity duration-500 {{ $iconColors[$colorIndex] }}">
                            @if($index == 0)
                                <svg class="w-24 h-24 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/><path d="M14 17H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                            @elseif($index == 1)
                                <svg class="w-24 h-24 -rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M21 3.01H3c-1.1 0-2 .9-2 2V13c0 1.1.9 2 2 2h8l-4 4v1h10v-1l-4-4h8c1.1 0 2-.9 2-2v-7.99c0-1.11-.89-2-2-2zM21 13H3V5.01h18V13z"/></svg>
                            @else
                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M11.5 9C10.12 9 9 10.12 9 11.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5S12.88 9 11.5 9zM20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-2 14H6V6h12v12z"/></svg>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
