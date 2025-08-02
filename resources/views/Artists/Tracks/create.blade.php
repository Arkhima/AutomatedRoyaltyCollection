@extends('layouts.Artists.Dashboard')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle text-purple-500 mr-3"></i>
            Add New Track
        </h1>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        There were {{ $errors->count() }} errors with your submission
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
        <form action="{{ route('Artists.Tracks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Track Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-headphones text-purple-500 mr-2"></i>
                    Track Title
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md"
                           placeholder="Enter track title">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-music text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- ISRC Code -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-barcode text-purple-500 mr-2"></i>
                    ISRC Code
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="isrc_code" value="{{ old('isrc_code') }}"
                           class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md font-mono"
                           placeholder="e.g. US-ABC-12-34567">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-fingerprint text-gray-400"></i>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    The International Standard Recording Code (ISRC) identifies your specific recording
                </p>
            </div>

            <!-- Artist Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-user text-purple-500 mr-2"></i>
                    Primary Artist
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <select name="artist_id" class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-3 border-gray-300 rounded-md">
                        <option disabled selected>Select an Artist</option>
                        @foreach($artists as $artist)
                            <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                                {{ $artist->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-users text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Royalty Rate -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-coins text-purple-500 mr-2"></i>
                    Royalty Rate Per Stream (Ksh)
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">Ksh.</span>
                    </div>
                    <input type="number" step="0.01" name="rate_per_stream"
                           value="{{ old('rate_per_stream') }}"
                           class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-16 pr-3 py-3 border-gray-300 rounded-md"
                           placeholder="0.00">
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    This is the amount you earn each time someone streams your track
                </p>
            </div>

            <!-- Track File Upload -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-file-audio text-purple-500 mr-2"></i>
                    Audio File
                </label>
                <div class="mt-1 flex items-center">
                    <div class="relative flex-1">
                        <input type="file" name="file_path" id="file-upload"
                               class="sr-only"
                               accept="audio/*">
                        <label for="file-upload"
                               class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                            <div class="flex items-center justify-center px-6 py-8 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        MP3, WAV, or AAC (MAX. 50MB)
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="{{ route('Artists.Tracks.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                   <i class="fas fa-arrow-left mr-2"></i>
                   Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="fas fa-save mr-2"></i>
                    Save Track
                </button>
            </div>
        </form>
    </div>
</div>

@endsection