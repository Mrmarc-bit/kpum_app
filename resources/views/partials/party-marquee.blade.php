@if($parties && $parties->count() > 0)
<section class="pt-20 pb-32 bg-white border-t border-slate-100 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
            Partai Mahasiswa & Afiliasi
        </h2>
        <p class="text-lg text-slate-600 font-medium max-w-2xl mx-auto">
            Mitra demokrasi dalam membangun masa depan kampus yang lebih baik.
        </p>
    </div>

    <!-- Marquee Container -->
    <div class="relative w-full overflow-hidden group/marquee py-20">
        <!-- Gradient Masks (Enhanced) -->
        <div class="absolute inset-y-0 left-0 w-32 md:w-64 bg-gradient-to-r from-white via-white/90 to-transparent z-20 pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-32 md:w-64 bg-gradient-to-l from-white via-white/90 to-transparent z-20 pointer-events-none"></div>

        <!-- Sliding Track (CSS-only infinite scroll) -->
        <div class="flex w-full select-none gap-0">
            <!-- First Loop -->
            <div class="flex shrink-0 items-center justify-around min-w-full gap-16 animate-marquee group-hover/marquee:[animation-play-state:paused] px-8">
                @foreach($parties as $party)
                    <div class="relative flex items-center justify-center w-32 h-32 md:w-48 md:h-36 cursor-pointer group/item">
                        @if($party->logo_path)
                            <img src="{{ asset('storage/' . $party->logo_path) }}" 
                                 alt="{{ $party->short_name }}" 
                                 class="w-full h-full object-scale-down p-2 drop-shadow-sm group-hover/item:drop-shadow-2xl transition-all duration-500 filter grayscale opacity-60 group-hover/item:grayscale-0 group-hover/item:opacity-100 group-hover/item:scale-110">
                        @else
                             <div class="w-full h-full flex items-center justify-center bg-slate-50 rounded-2xl border-2 border-slate-100 group-hover/item:border-blue-200 transition-colors filter grayscale opacity-60 group-hover/item:grayscale-0 group-hover/item:opacity-100 group-hover/item:scale-110">
                                <span class="text-2xl md:text-3xl font-black text-slate-300 group-hover/item:text-blue-600 transition-colors duration-300">{{ $party->short_name }}</span>
                             </div>
                        @endif
                        
                        <!-- Tooltip Popover -->
                        <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-full opacity-0 group-hover/item:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none z-50 shadow-xl border border-slate-700">
                            {{ $party->name }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Duplicate Loop for Seamless Effect -->
            <div class="flex shrink-0 items-center justify-around min-w-full gap-16 animate-marquee group-hover/marquee:[animation-play-state:paused] px-8" aria-hidden="true">
                @foreach($parties as $party)
                    <div class="relative flex items-center justify-center w-32 h-32 md:w-48 md:h-36 cursor-pointer group/item">
                         @if($party->logo_path)
                            <img src="{{ asset('storage/' . $party->logo_path) }}" 
                                 alt="{{ $party->short_name }}" 
                                 class="w-full h-full object-scale-down p-2 drop-shadow-sm group-hover/item:drop-shadow-2xl transition-all duration-500 filter grayscale opacity-60 group-hover/item:grayscale-0 group-hover/item:opacity-100 group-hover/item:scale-110">
                        @else
                             <div class="w-full h-full flex items-center justify-center bg-slate-50 rounded-2xl border-2 border-slate-100 group-hover/item:border-blue-200 transition-colors filter grayscale opacity-60 group-hover/item:grayscale-0 group-hover/item:opacity-100 group-hover/item:scale-110">
                                <span class="text-2xl md:text-3xl font-black text-slate-300 group-hover/item:text-blue-600 transition-colors duration-300">{{ $party->short_name }}</span>
                             </div>
                        @endif

                         <!-- Tooltip Popover -->
                         <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-full opacity-0 group-hover/item:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none z-50 shadow-xl border border-slate-700">
                            {{ $party->name }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 40s linear infinite;
        }
    </style>
</section>
@endif
