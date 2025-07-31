@extends('layouts.Auth')

@section('title', 'Create Account')
@section('subtitle', 'Join our creative community')

@section('content')
<form class="space-y-6" action="{{ route('register') }}" method="POST">
    @csrf
    
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
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="Your full name">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="your@email.com">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="••••••••">
        <div class="mt-2 text-xs text-gray-500">
            <p>Password must contain:</p>
            <ul class="list-disc list-inside pl-3">
                <li class="password-requirement" data-requirement="length">At least 8 characters</li>
                <li class="password-requirement" data-requirement="uppercase">One uppercase letter</li>
                <li class="password-requirement" data-requirement="lowercase">One lowercase letter</li>
                <li class="password-requirement" data-requirement="number">One number</li>
                <li class="password-requirement" data-requirement="special">One special character (@$!%*#?&)</li>
            </ul>
        </div>
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
               class="w-full px-4 py-3 rounded-lg border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent input-focus"
               placeholder="••••••••">
    </div>

    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input id="terms" name="terms" type="checkbox" class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded" required>
        </div>
        <div class="ml-3 text-sm">
            <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-purple-600 hover:text-purple-500">Terms</a> and <a href="#" class="text-purple-600 hover:text-purple-500">Privacy Policy</a></label>
        </div>
    </div>

    <button type="submit" 
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 btn-transition">
        Create Artist Account
    </button>
</form>

<div class="divider">or sign up with</div>

<div class="grid grid-cols-2 gap-3">
    <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 btn-transition">
        <i class="fab fa-google text-red-500 mr-2"></i> Google
    </a>
    <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 btn-transition">
        <i class="fab fa-github text-gray-800 mr-2"></i> GitHub
    </a>
</div>

@section('footer-links')
    <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">Sign in</a></p>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const requirements = {
        length: document.querySelector('[data-requirement="length"]'),
        uppercase: document.querySelector('[data-requirement="uppercase"]'),
        lowercase: document.querySelector('[data-requirement="lowercase"]'),
        number: document.querySelector('[data-requirement="number"]'),
        special: document.querySelector('[data-requirement="special"]')
    };

    passwordInput.addEventListener('input', function() {
        const value = this.value;
        
        // Check each requirement
        const hasLength = value.length >= 8;
        const hasUppercase = /[A-Z]/.test(value);
        const hasLowercase = /[a-z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecial = /[@$!%*#?&]/.test(value);
        
        // Update requirement indicators
        toggleClass(requirements.length, hasLength);
        toggleClass(requirements.uppercase, hasUppercase);
        toggleClass(requirements.lowercase, hasLowercase);
        toggleClass(requirements.number, hasNumber);
        toggleClass(requirements.special, hasSpecial);
    });

    function toggleClass(element, isValid) {
        if (isValid) {
            element.classList.add('text-green-500');
            element.classList.remove('text-gray-500');
        } else {
            element.classList.add('text-gray-500');
            element.classList.remove('text-green-500');
        }
    }
});
</script>
@endpush
@endsection