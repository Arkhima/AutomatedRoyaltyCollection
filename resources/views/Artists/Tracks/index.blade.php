@extends('layouts.Artists.Dashboard')

@section('content')

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-700">Tracks</h1>
    <a href="{{ route('Artists.Tracks.create') }}"
       class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
       Add New Track
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full bg-white rounded shadow">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-2 px-4 text-left">Title</th>
            <th class="py-2 px-4 text-left">Artist</th>
            <th class="py-2 px-4 text-left">Isrc Code</th>
            <th class="py-2 px-4 text-left">Rate Per Stream (Ksh)</th>
            <th class="py-2 px-4 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($tracks as $track)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">{{ $track->title }}</td>
            <td class="py-2 px-4">{{ $track->artist->name }}</td>
            <td class="py-2 px-4">{{ $track->isrc_code }}</td>
            <td class="py-2 px-4">{{ number_format($track->rate_per_stream, 2) }}</td>
            <td class="py-2 px-4">
                <a href="{{ route('Artists.Tracks.edit', $track->id) }}"
                   class="text-blue-500 hover:underline">Edit</a>
                |
                <form method="POST" action="{{ route('Artists.Tracks.destroy', $track->id) }}"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Are you sure?')"
                            class="text-red-500 hover:underline">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
