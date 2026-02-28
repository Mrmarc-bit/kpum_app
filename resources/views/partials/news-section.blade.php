@php
    $news = \App\Models\Post::published()->orderBy('published_at', 'desc')->take(3)->get();
@endphp

@if($news->count() > 0)
<section class="pt-8 pb-16 relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 xl:gap-20 items-start">

            <!-- Headline Column -->
            <div class="w-full lg:w-[35%] xl:w-[32%] lg:sticky lg:top-32 space-y-8 flex-shrink-0">
                <div class="space-y-6">
                    <h2 class="text-4xl sm:text-6xl lg:text-7xl font-black text-[#111111] leading-[0.9] tracking-tighter break-words">
                        Berita<br>KPUM UNUGHA.
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-sm">
                        Ruang di mana mahasiswa menemukan edukasi politik, pengumuman resmi, dan perkembangan Pemilwa UNUGHA secara transparan dan informatif.
                    </p>
                </div>

                <div class="pt-4">
                    <a href="{{ route('news.index') }}" class="group inline-flex justify-between items-center gap-4 bg-[#111111] text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-slate-800 transition-colors duration-300 w-full sm:w-auto min-w-[220px]">
                        Lihat Semua Berita
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </a>
                </div>
            </div>

            {{-- News Cards Slider --}}
            <div class="w-full lg:w-[65%] xl:w-[68%] min-w-0">

                {{-- Minimalist Scrollbar Style --}}
                <style>
                    .news-slider::-webkit-scrollbar { height: 10px; }
                    .news-slider::-webkit-scrollbar-track { background: transparent; }
                    .news-slider::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; border: 3px solid white; }
                    .news-slider::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
                </style>

                <div class="news-slider flex overflow-x-auto gap-6 xl:gap-8 pb-8 snap-x snap-mandatory scroll-smooth w-full">
                    @foreach($news as $index => $item)
                    @php
                        $bgColors = ['bg-[#C6CDFF]', 'bg-[#EAF0B0]', 'bg-[#A288A6]'];
                        $colorIndex = $index % count($bgColors);
                    @endphp
                    <!-- Card Item -->
                    <div class="w-[85%] sm:w-[calc(50%-12px)] xl:w-[calc(50%-16px)] flex-shrink-0 snap-center group relative flex flex-col bg-[#F9FAFB] rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] transition-all duration-500 hover:-translate-y-1 border border-slate-100 min-h-[460px]">

                        <!-- Top Info & Text Content -->
                        <div class="px-7 pt-7 pb-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-5">
                                <span class="px-4 py-2 rounded-full bg-[#E5E7EB] text-slate-700 text-[10px] font-black tracking-widest uppercase">
                                    {{ $item->category }}
                                </span>
                                <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center text-slate-800 shadow-sm border border-slate-100 group-hover:bg-[#111] group-hover:border-[#111] group-hover:text-white transition-colors duration-300">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </div>
                            </div>

                            <h3 class="text-2xl xl:text-[1.75rem] font-black text-[#111111] mb-3 tracking-tight leading-[1.1] pr-2">
                                <a href="{{ route('news.show', $item->slug) }}" class="before:absolute before:inset-0">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <p class="text-slate-500 text-sm xl:text-[15px] leading-relaxed line-clamp-3 font-medium">
                                {{ $item->excerpt }}
                            </p>
                        </div>

                        <!-- Decorative Footer Block or Image -->
                        <div class="px-3 pb-3 mt-auto w-full">
                            @if($item->featured_image)
                            <div class="h-[260px] md:h-[280px] w-full rounded-[2rem] overflow-hidden group-hover:scale-[0.98] transition-transform duration-500 relative">
                                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                            @else
                            <div class="h-[260px] md:h-[280px] w-full rounded-[2rem] {{ $bgColors[$colorIndex] }} flex items-center justify-center relative overflow-hidden group-hover:scale-[0.98] transition-transform duration-500">
                                <!-- Illustrations based on index -->
                                @if($index % 3 == 0)
                                    <!-- Box Blob SVG -->
                                    <svg class="w-52 h-52 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M 30 140 L 30 70 L 170 70 L 170 140" fill="none" stroke="#111" stroke-width="4" stroke-linecap="square"/>
                                        <path fill="#FF9E66" d="M100,80 l15,20 l25,-10 l-10,25 l25,15 l-25,10 l10,25 l-25,-10 l-15,20 l-15,-20 l-25,10 l10,-25 l-25,-10 l25,-15 l-10,-25 l25,10 z" />
                                        <path d="M 85 110 Q 90 100 95 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                        <path d="M 105 110 Q 110 100 115 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    </svg>
                                @elseif($index % 3 == 1)
                                    <!-- Plant Volley SVG -->
                                    <svg class="w-52 h-52 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#F28B9F" d="M100,160 C50,160 30,140 30,120 C30,110 40,90 50,90 C50,90 60,110 70,120 L80,70 C80,60 100,50 100,70 L110,110 L120,60 C120,50 140,50 140,60 L140,120 C140,120 160,100 170,100 C180,100 180,120 170,130 C150,150 130,160 100,160 Z"/>
                                        <path d="M 85 130 Q 90 120 95 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                        <path d="M 115 130 Q 120 120 125 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                        <circle cx="120" cy="70" r="22" fill="#EAF0B0" stroke="#111" stroke-width="4"/>
                                        <path d="M 100 60 Q 120 50 140 80" fill="none" stroke="#111" stroke-width="3"/>
                                        <path d="M 105 85 Q 125 70 135 55" fill="none" stroke="#111" stroke-width="3"/>
                                    </svg>
                                @else
                                    <!-- Racket SVG -->
                                    <svg class="w-52 h-52 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#B294BB" d="M 120 40 Q 180 30 180 100 Q 180 170 110 170 Q 50 170 50 100 Q 50 50 120 40 Z" />
                                        <circle cx="90" cy="90" r="30" fill="none" stroke="#111" stroke-width="5"/>
                                        <path d="M 110 110 L 140 140" fill="none" stroke="#111" stroke-width="12" stroke-linecap="round"/>
                                        <circle cx="140" cy="80" r="14" fill="#F4F4F4" stroke="#111" stroke-width="4"/>
                                    </svg>
                                @endif
                            </div>
                            @endif
                        </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
