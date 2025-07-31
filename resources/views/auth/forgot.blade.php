@extends('layouts.Auth')

@section('title', 'Forgot Password')
@section('subtitle', 'Enter your email to reset your password')

@section('content')
<form class="space-y-6" action="{{ route('password.email') }}" method="POST">
    @csrf
    
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input id="email" name="email" type="email" autocomplete="email" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="your@email.com">
    </div>

    <button type="submit" 
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 btn-transition">
        Send Reset Link
    </button>
</form>

<div class="mt-6 text-center">
    <a href="{{ route('login') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500">
        <i class="fas fa-arrow-left mr-1"></i> Back to login
    </a>
</div>

@section('footer-links')
    <p class="text-gray-600">Need help? <a href="#" class="font-medium text-purple-600 hover:text-purple-500">Contact support</a></p>
@endsection
@endsection