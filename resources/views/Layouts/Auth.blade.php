<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login') | {{ config('app.name', 'CreativeHub') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CreativeHub - A platform for creators to share and inspire">

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #7f00ff, #e100ff);
            min-height: 100vh;
        }

        .glow-card {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
            border-radius: 16px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
            outline: none;
        }

        .btn-transition {
            transition: all 0.2s ease-in-out;
        }

        .btn-transition:hover {
            transform: translateY(-1px);
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

        .divider::before {
            margin-right: 1em;
        }

        .divider::after {
            margin-left: 1em;
        }
    </style>

    @yield('head')
</head>
<body class="flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full sm:max-w-md glow-card p-8 sm:p-10">
        <!-- Logo/Branding -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-md">
                <!-- Replace with your logo or icon -->
                <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Welcome')</h1>
            <p class="text-gray-500 mt-2 text-sm">@yield('subtitle', 'Create. Share. Inspire.')</p>
        </div>

        <!-- Main Content -->
        @yield('content')

        <!-- Footer Links -->
        <div class="mt-8 text-center text-sm text-gray-500">
            @yield('footer-links')
            
            <p class="mt-4 text-xs italic">
                "Every artist was first an amateur." – Ralph Waldo Emerson
            </p>
        </div>
    </div>

    @yield('scripts')
</body>
</html>