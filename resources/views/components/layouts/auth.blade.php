<x-layouts.guest :title="$title ?? ''">
    <div class="flex min-h-screen items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
         <!-- Background Decoration details (consistent with Login) -->
        <div class="absolute inset-0 z-0">
             <div class="absolute -top-[30%] -left-[10%] w-[70%] h-[70%] rounded-full bg-blue-100/40 blur-3xl opacity-60 animate-[pulse_10s_ease-in-out_infinite]"></div>
             <div class="absolute -bottom-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-indigo-100/40 blur-3xl opacity-60 animate-[pulse_12s_ease-in-out_infinite]"></div>
        </div>

        <div class="w-full max-w-md space-y-8 bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-xl border border-white/60 relative z-10">
             <!-- Logo or specific auth header if needed -->
             <div class="text-center mb-6">
                 <div class="mx-auto w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-md mb-4">
                     K
                 </div>
                 @if(isset($title))
                 <h2 class="text-xl font-bold text-slate-900">{{ $title }}</h2>
                 @endif
             </div>

            {{ $slot }}
            
             <div class="border-t border-slate-100 pt-6 text-center mt-6">
                 <p class="text-[10px] text-slate-300 uppercase tracking-widest font-semibold">
                     Secure KPUM System
                 </p>
            </div>
        </div>
    </div>
</x-layouts.guest>
