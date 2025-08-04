@extends('layouts.Auth')

@section('title', 'Sign In')
@section('subtitle', 'Welcome back to ARC! Please enter your details')

@section('content')

{{-- Optional logo --}}
{{-- Uncomment and replace the src path if you want to use a logo --}}
{{--
<div class="flex justify-center mb-6">
    <img src="{{ asset('images/arc-logo.png') }}" alt="ARC Logo" class="h-12">
</div>
--}}

<form class="space-y-6" action="{{ route('login') }}" method="POST">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-white mb-1">Email Address</label>
        <input id="email" name="email" type="email" autocomplete="email" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="your@email.com">
    </div>

    <div>
        <div class="flex justify-between items-center mb-1">
            <label for="password" class="block text-sm font-medium text-white">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-purple-600 hover:text-purple-500">Forgot password?</a>
        </div>
        <input id="password" name="password" type="password" autocomplete="current-password" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="••••••••">
    </div>

    <div class="flex items-center">
        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
        <label for="remember-me" class="ml-2 block text-sm text-white">Remember me</label>
    </div>

    <button type="submit" 
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 btn-transition">
        Sign In
    </button>
</form>

<div class="divider">or continue with</div>

<div class="grid grid-cols-2 gap-3">
    <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 btn-transition">
        <i class="fab fa-google text-red-500 mr-2"></i> Google
    </a>
    <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 btn-transition">
        <i class="fab fa-github text-gray-800 mr-2"></i> GitHub
    </a>
</div>

@section('footer-links')
    <p class="text-white">Don't have an account? <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-500">Sign up</a></p>
@endsection

@endsection
