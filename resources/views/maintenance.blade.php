<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Dalam Pemeliharaan - KPUM</title>

    <!-- Favicon (Dynamic from Database) -->
    @if(setting('app_logo'))
        <link rel="icon" type="image/png" href="{{ asset(setting('app_logo')) }}">
        <link rel="apple-touch-icon" href="{{ asset(setting('app_logo')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lottie Player CDN -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- Google Fonts - Inter (Modern SaaS Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Smooth page entrance animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-80px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(80px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-left {
            animation: slideInLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-slide-right {
            animation: slideInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }
    </style>
</head>

<body class="h-full bg-white">

    <!-- Alpine.js Data Controller -->
    <div class="min-h-screen flex items-center justify-center px-6 sm:px-8 lg:px-12 py-12" x-data="{
             countdown: '',
             endTime: '{{ $endTime ?? '' }}',
             
             init() {
                 if (this.endTime) {
                     this.startCountdown();
                 }
             },
             
             startCountdown() {
                 const updateCountdown = () => {
                     const now = new Date().getTime();
                     const end = new Date(this.endTime).getTime();
                     const distance = end - now;

                     if (distance < 0) {
                         this.countdown = 'Sistem akan segera aktif';
                         this.countdown = 'Estimasi Waktu Habis';
                         return;
                     }

                     const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                     const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                     const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                     this.countdown = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                 };

                 updateCountdown();
                 setInterval(updateCountdown, 1000);
             }
         }">

        <!-- Main Container - Max Width 7xl for large screens -->
        <div class="w-full max-w-7xl mx-auto">

            <!-- 2 Column Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 xl:gap-20 items-center">

                <!-- ============================================ -->
                <!-- LEFT COLUMN - TEXT CONTENT -->
                <!-- ============================================ -->
                <div class="space-y-6 lg:space-y-8 order-2 lg:order-1">

                    <!-- Logo KPUM -->
                    <div class="shrink-0 flex items-center gap-3 animate-slide-left">
                        @if(setting('app_logo'))
                            <img src="{{ asset(setting('app_logo')) }}" alt="Logo KPUM"
                                class="w-16 h-16 rounded-full object-contain bg-white shadow-md ring-gray-200">
                        @else
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-blue-500/30 ring-2 ring-blue-200">
                                K
                            </div>
                        @endif
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Komisi Pemilihan Umum Mahasiswa</h2>
                            <p class="text-sm text-gray-500">Universitas Nahdlatul Ulama Al Ghazali</p>
                        </div>
                    </div>

                    <!-- Main Heading -->
                    <div class="space-y-4 animate-slide-left delay-100">
                        <h1
                            class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                            Sistem Sedang Dalam Pemeliharaan
                        </h1>
                    </div>

                    <!-- Description -->
                    <div class="animate-slide-left delay-100">
                        <p class="text-lg sm:text-xl text-gray-600 leading-relaxed max-w-xl">
                            {{ $message ?? 'Kami sedang melakukan peningkatan sistem untuk memastikan pemilihan berjalan aman dan stabil.' }}
                        </p>
                    </div>

                    <!-- Countdown (if exists) -->
                    <template x-if="endTime">
                        <div class="animate-slide-left delay-200">
                            <div class="inline-block bg-blue-50 border border-blue-100 rounded-xl px-6 py-4">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">
                                    Perkiraan aktif kembali dalam
                                </p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl sm:text-4xl font-bold text-gray-900 font-mono tabular-nums"
                                        x-text="countdown">
                                        00:00:00
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Maintenance Badge Button -->
                    <div class="animate-slide-left delay-300">
                        <div
                            class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg font-medium text-sm cursor-not-allowed">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            <span>Sedang Maintenance</span>
                        </div>
                    </div>

                    <!-- Footer Credit -->
                    <div class="pt-6 border-t border-gray-200 animate-slide-left delay-300">
                        <p class="text-sm text-gray-400 font-medium">
                            © {{ date('Y') }} KPUM Universitas Nahdlatul Ulama Al Ghazali Cilacap
                        </p>
                    </div>

                </div>

                <!-- ============================================ -->
                <!-- RIGHT COLUMN - LOTTIE ANIMATION -->
                <!-- ============================================ -->
                <div class="flex items-center justify-center order-1 lg:order-2 animate-slide-right">
                    <div class="w-full max-w-md lg:max-w-lg xl:max-w-xl">
                        <!-- Lottie Player Component -->
                        <lottie-player src="{{ asset('animation/animations/tmp6u1prh3_.json') }}"
                            background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay>
                        </lottie-player>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

</html>