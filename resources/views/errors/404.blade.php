<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    
    <!-- Favicon -->
    @php
        $appLogo = setting('app_logo');
    @endphp
    @if($appLogo)
        <link rel="icon" href="{{ asset($appLogo) }}">
        <link rel="apple-touch-icon" href="{{ asset($appLogo) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lottie Player CDN -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            -webkit-font-smoothing: antialiased;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
            opacity: 0;
        }

        .delay-200 {
            animation-delay: 200ms;
            opacity: 0;
        }

        .delay-300 {
            animation-delay: 300ms;
            opacity: 0;
        }

        .btn-back {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="h-full bg-gradient-to-br from-slate-50 via-white to-slate-100">

    <div class="min-h-screen flex items-center justify-center px-6 lg:px-12 py-12">

        <!-- 2 Column Layout: Animation Left, Content Right -->
        <div class="w-full max-w-6xl mx-auto grid lg:grid-cols-2 gap-12 items-center">

            <!-- LEFT: Lottie Animation -->
            <div class="animate-scale-in flex items-center justify-center">
                <lottie-player src="{{ asset('animation/animations/9582514.json') }}" background="transparent" speed="1"
                    style="width: 100%; max-width: 500px; height: auto;" loop autoplay>
                </lottie-player>
            </div>

            <!-- RIGHT: Content -->
            <div class="text-left space-y-6">

                <!-- 404 Number -->
                <div class="animate-fade-in-right">
                    <h1
                        class="text-8xl lg:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900">
                        404
                    </h1>
                </div>

                <!-- Message -->
                <div class="animate-fade-in-right delay-100 space-y-3">
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-800">
                        Halaman Tidak Ditemukan
                    </h2>
                    <p class="text-base lg:text-lg text-slate-500 leading-relaxed max-w-lg">
                        Sepertinya halaman yang Anda cari telah dipindahkan, dihapus, atau tidak pernah ada
                    </p>
                </div>

                <!-- Back Button -->
                <div class="animate-fade-in-right delay-200">
                    <a href="javascript:history.back()"
                        class="btn-back inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-slate-900 to-slate-700 text-white font-bold text-sm rounded-2xl shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Kembali ke Halaman Sebelumnya</span>
                    </a>
                </div>

                <!-- Links -->
                <div class="animate-fade-in-right delay-300 flex items-center gap-6 text-sm">
                    <a href="/" class="text-slate-600 hover:text-slate-900 font-medium transition-colors">
                        Beranda
                    </a>
                    <span class="text-slate-300">•</span>
                    <a href="/login" class="text-slate-600 hover:text-slate-900 font-medium transition-colors">
                        Login
                    </a>
                </div>

            </div>

        </div>
    </div>

</body>

</html>