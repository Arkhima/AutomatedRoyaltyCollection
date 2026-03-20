@extends('layouts.Auth')

@section('title', 'Create Account')
@section('subtitle', 'Join the ARC creative community')

@section('content')
<div class="animate-fadeInUp delay-200">
    <form class="space-y-6" action="{{ route('register') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-white mb-1">Full Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required
                class="w-full px-4 py-3 rounded-lg border border-gray-400 bg-white bg-opacity-80 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                placeholder="Your full name">
        </div>

        <div>
            <label for="artist_name" class="block text-sm font-medium text-white mb-1">Artist / Stage Name</label>
            <input id="artist_name" name="artist_name" type="text" value="{{ old('artist_name') }}" required
                class="w-full px-4 py-3 rounded-lg border border-gray-400 bg-white bg-opacity-80 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                placeholder="Your artist or stage name">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-white mb-1">Phone Number</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                class="w-full px-4 py-3 rounded-lg border border-gray-400 bg-white bg-opacity-80 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                placeholder="+254700000000">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-white mb-1">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
                class="w-full px-4 py-3 rounded-lg border border-gray-400 bg-white bg-opacity-80 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                placeholder="your@email.com">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-white mb-1">Password</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required
                class="w-full px-4 py-3 rounded-lg border border-gray-400 bg-white bg-opacity-80 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="••••••••">

            <ul class="mt-2 pl-4 list-disc space-y-1 text-xs text-white/70">
                <li class="password-requirement hidden" data-requirement="length">At least 8 characters</li>
                <li class="password-requirement hidden" data-requirement="uppercase">One uppercase letter</li>
                <li class="password-requirement hidden" data-requirement="lowercase">One lowercase letter</li>
                <li class="password-requirement hidden" data-requirement="number">One number</li>
                <li class="password-requirement hidden" data-requirement="special">One special character (@$!%*#?&)</li>
            </ul>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-white mb-1">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                class="w-full px-4 py-3 rounded-lg border border-gray-400 bg-white bg-opacity-80 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="••••••••">
        </div>

        <div class="flex items-start">
            <input id="terms" name="terms" type="checkbox" required
                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
            <label for="terms" class="ml-2 text-sm text-white">
                I agree to the <a href="#" class="text-purple-400 underline">Terms</a> and <a href="#" class="text-purple-400 underline">Privacy Policy</a>.
            </label>
        </div>

        <button type="submit"
            class="w-full py-3 px-4 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-medium shadow-md transition">
            Create Artist Account
        </button>
    </form>

    <div class="divider">or sign up with</div>

    <div class="grid grid-cols-2 gap-3">
        <a href="#" class="flex items-center justify-center py-2 px-4 rounded-lg bg-white text-gray-700 shadow hover:bg-gray-100">
            <i class="fab fa-google text-red-500 mr-2"></i> Google
        </a>
        <a href="#" class="flex items-center justify-center py-2 px-4 rounded-lg bg-white text-gray-700 shadow hover:bg-gray-100">
            <i class="fab fa-github text-gray-800 mr-2"></i> GitHub
        </a>
    </div>
</div>
@endsection

@section('footer-links')
    <p>Already have an account? 
        <a href="{{ route('login') }}" class="text-purple-400 underline">Sign in</a>
    </p>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('password');
    const requirements = {
        length: v => v.length >= 8,
        uppercase: v => /[A-Z]/.test(v),
        lowercase: v => /[a-z]/.test(v),
        number: v => /\d/.test(v),
        special: v => /[@$!%*#?&]/.test(v),
    };

    const elements = {};
    for (const key in requirements) {
        elements[key] = document.querySelector(`[data-requirement="${key}"]`);
    }

    input.addEventListener('input', () => {
        const value = input.value;
        let shown = false;

        for (const key in requirements) {
            elements[key].classList.add('hidden');
            elements[key].classList.remove('text-green-400');

            if (!shown && !requirements[key](value)) {
                elements[key].classList.remove('hidden');
                shown = true;
            } else if (requirements[key](value)) {
                elements[key].classList.remove('hidden');
                elements[key].classList.add('text-green-400');
            }
        }
    });
});
</script>
@endpush
