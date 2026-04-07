@extends('Layouts.Admin.admin')

@section('title', 'Create Artist')
@section('breadcrumb', 'Admin / Artists / Create')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">Create New Artist</h2>
    <a href="{{ route('admin.artists.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back to Artists
    </a>
</div>

<!-- Validation Errors -->
@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Create Artist Form -->
<div class="admin-card max-w-2xl">
    <form method="POST" action="{{ route('admin.artists.store') }}">
        @csrf
        
        <!-- Artist Name -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="name">
                Artist Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                   class="admin-input w-full" placeholder="Enter artist name" required>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                Email Address
            </label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                   class="admin-input w-full" placeholder="Enter email address (optional)">
        </div>

        <!-- Genre -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="genre">
                Genre
            </label>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" 
                   class="admin-input w-full" placeholder="e.g. Hip Hop, Pop, R&B">
        </div>

        <!-- Spotify ID -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="spotify_id">
                Spotify ID
            </label>
            <input type="text" name="spotify_id" id="spotify_id" value="{{ old('spotify_id') }}" 
                   class="admin-input w-full" placeholder="Spotify Artist ID (optional)">
        </div>

        <!-- Website -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="website">
                Website
            </label>
            <input type="url" name="website" id="website" value="{{ old('website') }}" 
                   class="admin-input w-full" placeholder="https://... (optional)">
        </div>

        <!-- Bio -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="bio">
                Biography
            </label>
            <textarea name="bio" id="bio" rows="4" 
                      class="admin-input w-full" 
                      placeholder="Enter artist biography...">{{ old('bio') }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-3">
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fas fa-save"></i>
                Create Artist
            </button>
            <a href="{{ route('admin.artists.index') }}" class="admin-btn admin-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection