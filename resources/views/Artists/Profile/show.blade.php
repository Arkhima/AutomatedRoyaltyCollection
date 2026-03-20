@extends('layouts.Artists.Dashboard')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Profile Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2 flex items-center justify-center">
            <i class="fas fa-user-edit text-purple-500 mr-3"></i>
            My Artist Profile
        </h2>
        <p class="text-gray-600">Update your personal information and showcase your artistic identity</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <form action="{{ route('Artists.Profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-6 text-center">
                <div class="relative inline-block">
                    <img src="{{ $user->avatar_url ? asset($user->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=7F00FF&color=fff&size=200' }}"
                         alt="Artist Avatar"
                         class="rounded-full h-32 w-32 object-cover border-4 border-white shadow-lg mx-auto hover:shadow-xl transition-shadow duration-300">
                    
                    <label for="avatar-upload" class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-md cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-camera text-purple-600"></i>
                        <span class="sr-only">Change Avatar</span>
                    </label>
                    <input id="avatar-upload" type="file" name="avatar" class="hidden">
                </div>
                
                <div class="mt-4">
                    <p class="text-sm text-gray-600 font-medium">Click the camera icon to change your profile photo</p>
                    @error('avatar')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Fields -->
            <div class="p-6 space-y-6">
                <!-- Name Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-signature text-purple-500 mr-2"></i>
                        Artist Name
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                               placeholder="Your artist name">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stage Name -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-theater-masks text-purple-500 mr-2"></i>
                        Stage Name
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="artist_name" value="{{ old('artist_name', $artist->stage_name ?? $artist->name ?? '') }}"
                               class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                               placeholder="Stage or performance name">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tag text-gray-400"></i>
                        </div>
                    </div>
                    @error('artist_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-phone text-purple-500 mr-2"></i>
                        Phone Number
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="phone" value="{{ old('phone', $artist->phone ?? $user->phone_number ?? '') }}"
                               class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                               placeholder="+254700000000">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                    </div>
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-envelope text-purple-500 mr-2"></i>
                        Email Address
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                               placeholder="your@email.com">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                

                <!-- Artist Bio -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-pencil-alt text-purple-500 mr-2"></i>
                        Artist Bio
                    </label>
                    <div class="mt-1">
                        <textarea name="bio" rows="3"
                                  class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                                  placeholder="Tell your story...">{{ old('bio', $user->bio ?? '') }}</textarea>
                        <div class="absolute mt-3 ml-3 flex items-center pointer-events-none">
                            <i class="fas fa-align-left text-gray-400"></i>
                        </div>
                    </div>
                    @error('bio')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Links -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-share-alt text-purple-500 mr-2"></i>
                        Social Links
                    </label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Instagram -->
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-instagram text-gray-400"></i>
                            </div>
                            <input type="text" name="instagram" value="{{ old('instagram', $user->instagram ?? '') }}"
                                   class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                                   placeholder="Instagram">
                        </div>
                        
                        <!-- Twitter -->
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-twitter text-gray-400"></i>
                            </div>
                            <input type="text" name="twitter" value="{{ old('twitter', $user->twitter ?? '') }}"
                                   class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md placeholder-gray-400"
                                   placeholder="Twitter">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Current Avatar Preview Modal -->
    <div id="avatar-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Profile Photo Preview</h3>
                <button onclick="document.getElementById('avatar-modal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <img id="avatar-preview" src="" alt="Preview" class="w-full rounded-lg">
            <div class="mt-4 flex justify-end">
                <button onclick="document.getElementById('avatar-modal').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Avatar preview functionality
    document.getElementById('avatar-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('avatar-preview').src = event.target.result;
                document.getElementById('avatar-modal').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection