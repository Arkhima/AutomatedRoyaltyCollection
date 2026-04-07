@extends('Layouts.Admin.admin')

@section('title', 'Create Track')
@section('breadcrumb', 'Admin / Tracks / Create')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">Add New Track</h2>
    <a href="{{ route('admin.tracks.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back to Tracks
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

<!-- Create Track Form -->
<div class="admin-card max-w-2xl">
    <form method="POST" action="{{ route('admin.tracks.store') }}">
        @csrf
        
        <!-- Track Title -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="title">
                Track Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                   class="admin-input w-full" placeholder="Enter track title" required>
        </div>

        <!-- Artist Selection -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="artist_id">
                Artist <span class="text-red-500">*</span>
            </label>
            <select name="artist_id" id="artist_id" class="admin-input w-full" required>
                <option value="">Select Artist</option>
                @foreach(\App\Models\Artist::with('user')->get() as $artist)
                    <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                        {{ $artist->user->name ?? $artist->name ?? 'Artist #' . $artist->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Album -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="album">
                Album
            </label>
            <input type="text" name="album" id="album" value="{{ old('album') }}" 
                   class="admin-input w-full" placeholder="Album name (optional)">
        </div>

        <!-- ISRC Code -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="isrc_code">
                ISRC Code
            </label>
            <input type="text" name="isrc_code" id="isrc_code" value="{{ old('isrc_code') }}" 
                   class="admin-input w-full" placeholder="e.g. US-A1B-23-00001">
        </div>

        <!-- Genre -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="genre">
                Genre
            </label>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" 
                   class="admin-input w-full" placeholder="e.g. Hip Hop, Pop, R&B">
        </div>

        <!-- Duration (seconds) -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="duration">
                Duration (seconds)
            </label>
            <input type="number" name="duration" id="duration" value="{{ old('duration') }}" 
                   class="admin-input w-full" placeholder="e.g. 180">
        </div>

        <!-- Rate Per Stream -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="rate_per_stream">
                Rate Per Stream ($)
            </label>
            <input type="number" step="0.0001" name="rate_per_stream" id="rate_per_stream" 
                   value="{{ old('rate_per_stream', 0.001) }}" 
                   class="admin-input w-full" placeholder="0.001">
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="status">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status" id="status" class="admin-input w-full" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-3">
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fas fa-save"></i>
                Create Track
            </button>
            <a href="{{ route('admin.tracks.index') }}" class="admin-btn admin-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection