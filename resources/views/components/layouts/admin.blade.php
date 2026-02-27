<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Super Admin' }} - {{ $settings['app_name'] ?? 'KPUM System' }}</title>

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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Turbo - SPA Navigation -->
    <script type="importmap">
        {
            "imports": {
                "@hotwired/turbo": "https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-esm.js"
            }
        }
    </script>
    <script type="module">
        import * as Turbo from "@hotwired/turbo"
        console.log('✅ Turbo loaded:', Turbo);
        window.Turbo = Turbo;
    </script>

    <style>
        /* Prevent Alpine x-show flash on load */
        [x-cloak] { display: none !important; }

        /* ===== TOAST: hanya warna background & font, jangan ganggu layout internal SweetAlert ===== */
        .swal2-popup.swal2-toast {
            font-family: 'Inter', sans-serif !important;
            border-radius: 0.875rem !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 10px -3px rgba(0,0,0,0.06) !important;
        }
        .swal2-popup.swal2-toast.swal2-icon-success {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
        }
        .swal2-popup.swal2-toast.swal2-icon-error {
            background: #fef2f2 !important;
            border: 1px solid #fecaca !important;
        }
        .swal2-popup.swal2-toast.swal2-icon-warning {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
        }
        .swal2-popup.swal2-toast.swal2-icon-info {
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
        }
        .swal2-popup.swal2-toast .swal2-title {
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            color: #1e293b !important;
        }
        .swal2-popup.swal2-toast.swal2-icon-success .swal2-timer-progress-bar { background: #10b981 !important; }
        .swal2-popup.swal2-toast.swal2-icon-error   .swal2-timer-progress-bar { background: #ef4444 !important; }
        .swal2-popup.swal2-toast.swal2-icon-warning .swal2-timer-progress-bar { background: #f59e0b !important; }
        .swal2-popup.swal2-toast.swal2-icon-info    .swal2-timer-progress-bar { background: #3b82f6 !important; }

        /* ===== CONFIRM DIALOG ===== */
        .swal2-popup:not(.swal2-toast) {
            font-family: 'Inter', sans-serif !important;
            border-radius: 1.5rem !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 25px 60px -10px rgba(0,0,0,0.15), 0 10px 25px -5px rgba(0,0,0,0.07) !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-title {
            font-size: 1.125rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-html-container {
            font-size: 0.875rem !important;
            color: #64748b !important;
            line-height: 1.6 !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-confirm {
            border-radius: 0.75rem !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            padding: 0.55rem 1.5rem !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-cancel {
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            padding: 0.55rem 1.5rem !important;
            background: #f1f5f9 !important;
            color: #64748b !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-cancel:hover {
            background: #e2e8f0 !important;
            color: #475569 !important;
        }

        /* Turbo Progress Bar */
        .turbo-progress-bar {
            position: fixed; top: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            background-size: 200% 100%;
            animation: gradient-shift 2s ease infinite;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50%       { background-position: 100% 50%; }
        }
    </style>
</head>

<body
    class="font-sans antialiased h-full flex overflow-hidden bg-slate-50 selection:bg-blue-100 selection:text-blue-900"
    x-data="{ sidebarOpen: false }">
    <!-- Ambient Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-100/50 blur-[100px] animate-[pulse_8s_ease-in-out_infinite]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-100/50 blur-[100px] animate-[pulse_10s_ease-in-out_infinite]">
        </div>
        <div
            class="absolute top-[20%] right-[10%] w-[30%] h-[30%] rounded-full bg-violet-100/40 blur-[80px] animate-[pulse_12s_ease-in-out_infinite]">
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-40 lg:hidden" x-transition.opacity
        @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white/80 backdrop-blur-xl border-r border-white/50 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-16 flex items-center px-6 border-b border-slate-100/50 bg-white/50 backdrop-blur-md">
            <div class="flex items-center gap-3">
                @if(isset($settings['app_logo']) && $settings['app_logo'])
                    <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                        class="w-8 h-8 rounded-lg object-contain bg-white shadow-sm border border-slate-100">
                @else
                    <div
                        class="bg-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        S</div>
                @endif
                <span
                    class="font-bold tracking-tight text-lg text-slate-900">{{ $settings['app_name'] ?? 'Super Admin' }}</span>
            </div>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)] pb-8" style="padding-bottom: max(2rem, env(safe-area-inset-bottom))">
            <div class="px-3 mb-2 mt-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Utama</p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                Dashboard
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manajemen Data</p>
            </div>

            <div
                x-data="{ open: {{ request()->routeIs('admin.kandidat.*') || request()->routeIs('admin.calon_dpm.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="whitespace-nowrap">Manajemen Kandidat</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''"
                        class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="mt-1 space-y-1 pl-11">
                    <a href="{{ route('admin.kandidat.index') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.kandidat.*') ? 'text-blue-700 bg-blue-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Paslon Presma
                    </a>
                    <a href="{{ route('admin.calon_dpm.index') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.calon_dpm.*') ? 'text-blue-700 bg-blue-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Calon DPM
                    </a>
                </div>
            </div>



            <a href="{{ route('admin.parties.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.parties.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.parties.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21v-8a2 2 0 012-2h14a2 2 0 012 2v8M3 21h18M3 21l2-8m14 8l-2-8m-2-3a3 3 0 10-6 0 3 3 0 006 0z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Manajemen Partai</span>
            </a>

            <a href="{{ route('admin.posts.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.posts.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.posts.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0012.586 3H15">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Portal Berita</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Pengguna (Users)</span>
            </a>
            <a href="{{ route('admin.dpt.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dpt.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.dpt.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Manajemen DPT</span>
            </a>
            <a href="{{ route('admin.analytics.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.analytics.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Realtime Analytics
            </a>
            <a href="{{ route('admin.timeline.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.timeline.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.timeline.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Timeline Kegiatan
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alat Pemilu</p>
            </div>

            <a href="{{ route('admin.scanner.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.scanner.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.scanner.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Scanner</span>
            </a>

            <a href="{{ route('admin.letters.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.letters.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.letters.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Unduh Surat</span>
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Arsip & Laporan</p>
            </div>

            <a href="{{ route('admin.reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.reports.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Pusat Laporan
            </a>

            <a href="{{ route('admin.assets.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.assets.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.assets.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                Manajemen Asset
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konfigurasi</p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.index') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.settings.index') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan Sistem
            </a>

            <a href="{{ route('admin.maintenance.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.maintenance.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.maintenance.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Pemeliharaan Situs
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.settings.letters.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="whitespace-nowrap">Template Surat</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''"
                        class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="mt-1 space-y-1 pl-11">
                    <a href="{{ route('admin.settings.letters.proof') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.settings.letters.proof') ? 'text-blue-700 bg-blue-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Surat Bukti Pilih
                    </a>
                    <a href="{{ route('admin.settings.letters.notification') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.settings.letters.notification') ? 'text-blue-700 bg-blue-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Surat Pemberitahuan
                    </a>
                </div>
            </div>


            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Danger Zone</p>
            </div>

            <a href="{{ route('admin.audit.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.audit.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.audit.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Audit Logs
            </a>
            <a href="{{ route('admin.reset.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-red-400 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Reset Election
            </a>
        </nav>


    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative z-10 bg-transparent">
        <!-- Modern Premium Navbar -->
        <header
            class="h-16 lg:h-20 bg-white/70 backdrop-blur-xl border-b border-white/60 sticky top-0 z-30 transition-all duration-300">
            <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between max-w-[1600px] mx-auto">

                <!-- Left Section: Sidebar Toggle & Breadcrumb (Desktop) -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-all active:scale-95"
                        title="Buka Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="hidden lg:flex flex-col">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mb-0.5">Administrator</p>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $title ?? 'Dashboard' }}</h2>
                    </div>
                </div>

                <!-- Center Section: Mobile Title (Centered) -->
                <div class="lg:hidden absolute left-1/2 -translate-x-1/2 flex flex-col items-center max-w-[180px] sm:max-w-xs">
                    <h2 class="text-sm font-bold text-slate-900 tracking-tight truncate w-full text-center">{{ $title ?? 'Dashboard' }}</h2>
                    <div class="h-0.5 w-4 bg-blue-500 rounded-full mt-0.5"></div>
                </div>

                <!-- Right Section: Actions & Profile -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Notifications (Static for now) -->
                    <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all hidden sm:block">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="group flex items-center gap-2 sm:gap-3 p-1 rounded-full sm:rounded-2xl hover:bg-slate-100 transition-all duration-300 focus:outline-none ring-1 ring-transparent hover:ring-slate-200">
                            <div class="relative">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full sm:rounded-xl object-cover shadow-sm ring-2 ring-white group-hover:ring-blue-100 transition-all">
                                @else
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full sm:rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-md ring-2 ring-white group-hover:ring-blue-100 transition-all">
                                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>

                            <div class="hidden md:block text-left mr-1">
                                <p class="text-sm font-bold text-slate-900 leading-none mb-1">
                                    {{ Str::words(auth()->user()->name ?? 'Administrator', 2, '') }}
                                </p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider leading-none">Super Access</p>
                            </div>

                            <svg :class="open ? 'rotate-180' : ''"
                                class="hidden sm:block w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Profile Menu Dropdown -->
                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                            class="absolute right-0 mt-3 w-64 bg-white/95 backdrop-blur-2xl border border-slate-200/60 rounded-[1.5rem] shadow-2xl shadow-slate-900/10 origin-top-right overflow-hidden z-50 focus:outline-none">

                            <div class="px-6 py-5 bg-gradient-to-br from-slate-50 to-white border-b border-slate-100">
                                <p class="text-sm font-bold text-slate-900 truncate mb-0.5">
                                    {{ auth()->user()->name ?? 'Administrator' }}
                                </p>
                                <p class="text-xs text-slate-500 font-medium truncate">{{ auth()->user()->email ?? 'admin@kpum.com' }}</p>
                            </div>

                            <div class="p-2">
                                <a href="{{ route('admin.profile.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-700 rounded-[1rem] transition-all">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    Profil Saya
                                </a>

                                <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                                    @csrf
                                    <button type="submit"
                                        class="group w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-[1rem] transition-all text-left">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                        </div>
                                        Log Out
                                    </button>
                                </form>
                            </div>

                            <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">SAHABAT System v1.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>


        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth">
            {{ $slot }}
        </main>
    </div>
    <script>
        // ===== MODERN TOAST MIXIN =====
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            showCloseButton: false,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // ===== SHOW SESSION FLASH TOASTS =====
        (function() {
            var sessionSuccess = <?php echo json_encode(session('success')); ?>;
            var sessionError   = <?php echo json_encode(session('error'));   ?>;
            var sessionInfo    = <?php echo json_encode(session('info'));    ?>;
            var sessionWarning = <?php echo json_encode(session('warning')); ?>;

            if (sessionSuccess) {
                window.Toast.fire({ icon: 'success', title: sessionSuccess });
            }
            if (sessionError) {
                window.Toast.fire({ icon: 'error', title: sessionError });
            }
            if (sessionInfo) {
                window.Toast.fire({ icon: 'info', title: sessionInfo });
            }
            if (sessionWarning) {
                window.Toast.fire({ icon: 'warning', title: sessionWarning });
            }
        })();

        // ===== GLOBAL FORM CONFIRM INTERCEPTOR =====
        // Catches any form with data-confirm attribute and shows modern SweetAlert2 modal
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const message = form.getAttribute('data-confirm');

            if (message) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Tindakan',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#f1f5f9',
                    confirmButtonText: '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg> Ya, Hapus!',
                    reverseButtons: true,
                    backdrop: 'rgba(15, 23, 42, 0.5)',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.removeAttribute('data-confirm');
                        // Fix for forms that might get detached from DOM by AJAX auto-refreshes (like in Letters index)
                        if (!document.body.contains(form)) {
                            // Ensure the detached form still has its submit button's value if needed,
                            // though simple forms usually just need to be in the DOM to be submitted.
                            form.style.display = 'none';
                            document.body.appendChild(form);
                        }

                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>
