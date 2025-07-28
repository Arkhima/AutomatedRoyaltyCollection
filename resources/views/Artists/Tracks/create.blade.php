@extends('layouts.Artists.Dashboard')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-gray-700">Add New Track</h1>

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

<form action="{{ route('Artists.Tracks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Track Title</label>
        <input type="text" name="title" value="{{ old('title') }}"
               class="border border-gray-300 rounded p-2 w-full">
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">ISRC Code</label>
        <input type="text" name="isrc_code" value="{{ old('isrc_code') }}"
               class="border border-gray-300 rounded p-2 w-full">
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Artist</label>
        <select name="artist_id" class="border border-gray-300 rounded p-2 w-full">
            <option disabled selected>Select an Artist</option>
            @foreach($artists as $artist)
                <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                    {{ $artist->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Royalty Rate Per Stream (Ksh)</label>
        <input type="number" step="0.01" name="rate_per_stream" value="{{ old('rate_per_stream') }}"
               class="border border-gray-300 rounded p-2 w-full">
    </div>

    <div class="mb-4">
        <label class="block font-medium text-gray-700">Upload Track (optional)</label>
        <input type="file" name="file_path" class="border border-gray-300 rounded p-2 w-full">
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save Track
    </button>
</form>

@endsection
