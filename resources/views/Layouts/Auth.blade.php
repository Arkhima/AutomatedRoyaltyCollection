<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Login') | {{ config('app.name', 'CreativeHub') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="CreativeHub - A platform for creators to share and inspire" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .background-slideshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            animation-duration: 24s;
            animation-fill-mode: forwards;
        }

        .slide1 { background-image: url('{{ asset('images/backgrounds/slide1.jpg') }}'); animation-name: fadeZoom1; }
        .slide2 { background-image: url('{{ asset('images/backgrounds/slide2.jpg') }}'); animation-name: fadeZoom2; }
        .slide3 { background-image: url('{{ asset('images/backgrounds/slide3.jpg') }}'); animation-name: fadeZoom3; }
        .slide4 { background-image: url('{{ asset('images/backgrounds/slide4.jpg') }}'); animation-name: fadeZoom4; }

        @keyframes fadeZoom1 {
            0% { opacity: 1; transform: scale(1); }
            20% { opacity: 1; transform: scale(1.05); }
            25% { opacity: 0; transform: scale(1); }
            100% { opacity: 0; transform: scale(1); }
        }

        @keyframes fadeZoom2 {
            0% { opacity: 0; transform: scale(1); }
            25% { opacity: 1; transform: scale(1); }
            45% { opacity: 1; transform: scale(1.05); }
            50% { opacity: 0; transform: scale(1); }
            100% { opacity: 0; transform: scale(1); }
        }

        @keyframes fadeZoom3 {
            0% { opacity: 0; transform: scale(1); }
            50% { opacity: 0; transform: scale(1); }
            55% { opacity: 1; transform: scale(1); }
            75% { opacity: 1; transform: scale(1.05); }
            80% { opacity: 0; transform: scale(1); }
            100% { opacity: 0; transform: scale(1); }
        }

        @keyframes fadeZoom4 {
            0% { opacity: 0; transform: scale(1); }
            75% { opacity: 0; transform: scale(1); }
            80% { opacity: 1; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.05); }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: -5;
        }

        .glow-card {
            background: rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.3);
        }

        /* Optional: Clean scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #9CA3AF;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #E5E7EB;
        }

        .divider::before { margin-right: 1em; }
        .divider::after { margin-left: 1em; }

        /* Fade in up animation and stagger delays */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }
        .delay-800 { animation-delay: 0.8s; }
        .delay-900 { animation-delay: 0.9s; }
        .delay-1000 { animation-delay: 1s; }
    </style>

    @yield('head')
</head>

<body class="h-screen w-full relative overflow-hidden bg-black text-white">

    <!-- Background Slideshow -->
    <div class="background-slideshow">
        <div class="slide slide1"></div>
        <div class="slide slide2"></div>
        <div class="slide slide3"></div>
        <div class="slide slide4"></div>
    </div>
    <div class="overlay"></div>

    <!-- Wrapper to center card and prevent overflow -->
    <div class="relative z-10 h-screen w-full flex items-center justify-center overflow-auto p-4 sm:p-6">

        <!-- Auth Card -->
        <div class="w-full max-w-md glow-card p-6 sm:p-10">

            <!-- Logo -->
            <div class="text-center mb-6 animate-fadeInUp delay-100">
                <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden shadow-md">
                    <img src="{{ asset('images/ARC LOGO.png') }}" alt="ARC Logo" class="w-full h-full object-contain" />
                </div>
                <h1 class="text-2xl font-bold text-white">@yield('title', 'Welcome')</h1>
                <p class="text-white/70 mt-1 text-sm">@yield('subtitle', 'Create. Share. Inspire.')</p>
            </div>

            <!-- Main Content -->
            <div class="animate-fadeInUp delay-200">
                @yield('content')
            </div>

            <!-- Footer Section -->
            <div class="mt-8 text-center text-sm text-white/70 animate-fadeInUp delay-300">
                @yield('footer-links')
                <p class="mt-4 text-xs italic opacity-80">"Every artist was first an amateur." – Ralph Waldo Emerson</p>
            </div>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
