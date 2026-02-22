<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - KPUM</title>
    @if(isset($settings['app_logo']) && $settings['app_logo'])
        <link rel="icon" href="{{ asset((string) $settings['app_logo']) }}" sizes="32x32">
        <link rel="icon" href="{{ asset((string) $settings['app_logo']) }}" sizes="192x192">
        <link rel="apple-touch-icon" href="{{ asset((string) $settings['app_logo']) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
    class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen flex flex-col relative selection:bg-blue-100 selection:text-blue-900">
    <!-- Ambient Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]">
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_800px_at_50%_200px,#bae6fd20,transparent)]"></div>
        <div
            class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-white via-white/80 to-slate-50/50 mask-[linear-gradient(to_bottom,transparent,white)]">
        </div>

        <!-- Decorative Orbs -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full bg-blue-100/60 blur-[100px] animate-[pulse_10s_ease-in-out_infinite]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-100/60 blur-[100px] animate-[pulse_12s_ease-in-out_infinite]">
        </div>
    </div>

    <!-- Navbar -->
    <nav
        class="bg-white/70 backdrop-blur-xl border-b border-white/50 shadow-sm sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    @if(isset($settings['app_logo']) && $settings['app_logo'])
                        <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                            class="w-8 h-8 rounded-lg object-contain bg-white shadow-sm">
                    @else
                        <div
                            class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                            K</div>
                    @endif
                    <span
                        class="font-bold text-lg tracking-tight text-slate-900">{{ $settings['app_name'] ?? 'KPUM System' }}</span>
                </div>

                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-600 hidden sm:inline-block">
                        Selamat Datang, <span
                            class="text-slate-900">{{ auth('mahasiswa')->user()->name ?? 'Mahasiswa' }}</span>
                    </span>

                    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                    <form method="POST" action="{{ route('logout.mahasiswa') }}" data-turbo="false">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:text-red-600 hover:border-red-100 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 relative z-10">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white/50 backdrop-blur-md border-t border-white/50 py-8 mt-auto relative z-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm text-slate-500">
                &copy; {{ date('Y') }} Komisi Pemilihan Umum Mahasiswa. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    @fluxScripts
</body>

</html>