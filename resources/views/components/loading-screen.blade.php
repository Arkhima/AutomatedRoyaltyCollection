<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 2000)"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-600 text-white transition-opacity duration-500"
>
    <div class="text-center">
        <h1 class="text-4xl font-bold mb-4 animate-fade-in-down">“Creativity takes courage.”</h1>
        <p class="text-lg mb-6">Loading your next spark of brilliance...</p>
        <svg class="animate-spin h-8 w-8 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
    </div>
</div>
