<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creative App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="//unpkg.com/alpinejs"></script>
</head>
<body class="bg-white text-gray-900">

    {{-- Loading screen --}}
    <x-loading-screen />

    {{-- Page Content --}}
    <div id="app" class="relative z-0">
        @yield('content')
    </div>

</body>
</html>
