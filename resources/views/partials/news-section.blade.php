@php
    $news = \App\Models\Post::published()->orderBy('published_at', 'desc')->take(3)->get();
@endphp

@if($news->count() > 0)
<section class="py-24 relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            <!-- Headline Column -->
            <div class="lg:col-span-4 lg:sticky lg:top-32 space-y-8">
                <div class="space-y-6">
                    <h2 class="text-6xl sm:text-7xl lg:text-8xl font-black text-[#111111] leading-[0.9] tracking-tighter">
                        Warta<br>Demokrasi.
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-sm">
                        Ruang di mana mahasiswa menemukan edukasi politik, pengumuman resmi, dan perkembangan Pemilwa secara transparan dan informatif.
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

            <!-- News Cards Grid -->
            <!-- Use flex with horizontal scroll on mobile, grid on desktop to match modern dynamic layouts -->
            <div class="lg:col-span-8 flex overflow-x-auto lg:grid lg:grid-cols-2 gap-6 pb-8 snap-x snap-mandatory hide-scroll-bar" style="scrollbar-width: none;">
                <!-- Hide scrollbar style hack for webkit inline just in case -->
                <style> .hide-scroll-bar::-webkit-scrollbar { display: none; } </style>

                @foreach($news as $index => $item)
                @php
                    $bgColors = ['bg-[#C6CDFF]', 'bg-[#EAF0B0]', 'bg-[#A288A6]'];
                    $colorIndex = $index % count($bgColors);
                @endphp
                <div class="group relative flex flex-col bg-[#F4F5F6] rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 hover:-translate-y-2 snap-center shrink-0 w-[85vw] sm:w-[400px] lg:w-auto h-full">

                    <!-- Top Info -->
                    <div class="p-8 pb-4">
                        <div class="flex justify-between items-start mb-6">
                            <span class="px-4 py-1.5 rounded-full bg-[#E5E7EB] text-slate-700 text-xs font-black tracking-wide">
                                {{ $item->category }}
                            </span>
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-900 border-2 border-slate-100 group-hover:border-slate-900 transition-colors shadow-sm">
                                <svg class="w-4 h-4 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </div>

                        <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight leading-[1.1]">
                            <a href="{{ route('news.show', $item->slug) }}" class="before:absolute before:inset-0">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-3 font-medium">
                            {{ $item->excerpt }}
                        </p>
                    </div>

                    <!-- Decorative Footer Block inspired by the user's reference -->
                    <div class="px-3 pb-3 mt-auto">
                        <div class="h-[260px] w-full rounded-[2rem] {{ $bgColors[$colorIndex] }} flex items-center justify-center relative overflow-hidden group-hover:bg-opacity-90 transition-colors duration-500">

                            @if($index % 3 == 0)
                                <!-- Orangey spiky blob stretching on a bar -->
                                <svg class="w-48 h-48 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M 30 140 L 30 70 L 170 70 L 170 140" fill="none" stroke="#111" stroke-width="4" stroke-linecap="square"/>
                                    <path fill="#FF9E66" d="M100,80 l15,20 l25,-10 l-10,25 l25,15 l-25,10 l10,25 l-25,-10 l-15,20 l-15,-20 l-25,10 l10,-25 l-25,-10 l25,-15 l-10,-25 l25,10 z" />
                                    <!-- Cute Eyes -->
                                    <path d="M 85 110 Q 90 100 95 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M 105 110 Q 110 100 115 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            @elseif($index % 3 == 1)
                                <!-- Pink multi-fingered plant playing volleyball -->
                                <svg class="w-48 h-48 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#F28B9F" d="M100,160 C50,160 30,140 30,120 C30,110 40,90 50,90 C50,90 60,110 70,120 L80,70 C80,60 100,50 100,70 L110,110 L120,60 C120,50 140,50 140,60 L140,120 C140,120 160,100 170,100 C180,100 180,120 170,130 C150,150 130,160 100,160 Z"/>
                                    <!-- Cute Face -->
                                    <path d="M 85 130 Q 90 120 95 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M 115 130 Q 120 120 125 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    <!-- Volleyball -->
                                    <circle cx="120" cy="70" r="22" fill="#EAF0B0" stroke="#111" stroke-width="4"/>
                                    <path d="M 100 60 Q 120 50 140 80" fill="none" stroke="#111" stroke-width="3"/>
                                    <path d="M 105 85 Q 125 70 135 55" fill="none" stroke="#111" stroke-width="3"/>
                                </svg>
                            @else
                                <!-- Abstract forms with a ping-pong style racket -->
                                <svg class="w-48 h-48 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#B294BB" d="M 120 40 Q 180 30 180 100 Q 180 170 110 170 Q 50 170 50 100 Q 50 50 120 40 Z" />
                                    <!-- Racket -->
                                    <circle cx="90" cy="90" r="30" fill="none" stroke="#111" stroke-width="5"/>
                                    <path d="M 110 110 L 140 140" fill="none" stroke="#111" stroke-width="12" stroke-linecap="round"/>
                                    <!-- Ball/Blob -->
                                    <circle cx="140" cy="80" r="14" fill="#F4F4F4" stroke="#111" stroke-width="4"/>
                                </svg>
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
