@extends('layouts.Artists.Dashboard')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-gray-700">Edit Track</h1>

{{-- Show Validation Errors --}}
@if($errors->any())
    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('Artists.Tracks.update', $track->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Track Title</label>
        <input type="text" name="title"
               value="{{ old('title', $track->title) }}"
               class="border border-gray-300 rounded p-2 w-full">
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">ISRC Code</label>
        <input type="text" name="isrc_code"
               value="{{ old('isrc_code', $track->isrc_code) }}"
               class="border border-gray-300 rounded p-2 w-full">
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Artist</label>
        <select name="artist_id" class="border border-gray-300 rounded p-2 w-full">
            <option disabled>Select an Artist</option>
            @foreach($artists as $artist)
                <option value="{{ $artist->id }}"
                    {{ old('artist_id', $track->artist_id) == $artist->id ? 'selected' : '' }}>
                    {{ $artist->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Royalty Rate Per Stream (Ksh)</label>
        <input type="number" step="0.01" name="rate_per_stream"
               value="{{ old('rate_per_stream', $track->rate_per_stream) }}"
               class="border border-gray-300 rounded p-2 w-full">
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Upload New Track File (optional)</label>
        <input type="file" name="file_path" class="border border-gray-300 rounded p-2 w-full">
        @if($track->file_path)
            <p class="text-sm mt-1 text-gray-600">Current File: <code>{{ $track->file_path }}</code></p>
        @endif
    </div>

    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Update Track
    </button>
</form>

@endsection
