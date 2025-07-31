@extends('layouts.Auth')

@section('title', 'Reset Password')
@section('subtitle', 'Create a new password for your account')

@section('content')
<form class="space-y-6" action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input id="email" name="email" type="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="your@email.com" {{ $email ? 'readonly' : '' }}>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
        <input id="password" name="password" type="password" required autocomplete="new-password"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="••••••••">
        <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
    </div>

    <div>
        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input id="password-confirm" name="password_confirmation" type="password" required autocomplete="new-password"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="••••••••">
    </div>

    <button type="submit" 
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 btn-transition">
        Reset Password
    </button>
</form>

<div class="mt-6 text-center">
    <a href="{{ route('login') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500">
        <i class="fas fa-arrow-left mr-1"></i> Back to login
    </a>
</div>
@endsection