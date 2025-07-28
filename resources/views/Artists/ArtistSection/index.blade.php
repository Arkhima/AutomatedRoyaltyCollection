@extends('layouts.Artists.Dashboard')

@section('content')

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-700">Artists</h1>
    <a href="{{ route('Artists.ArtistSection.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
        Add New Artist
    </a>
</div>

<table class="min-w-full bg-white rounded shadow">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-2 px-4 text-left">Name</th>
            <th class="py-2 px-4 text-left">Email</th>
            <th class="py-2 px-4 text-left">Phone</th>
            <th class="py-2 px-4 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($artists as $artist)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-4">{{ $artist->name }}</td>
            <td class="py-2 px-4">{{ $artist->email }}</td>
            <td class="py-2 px-4">{{ $artist->phone }}</td>
            <td class="py-2 px-4">
                <a href="{{ route('Artists.ArtistSection.edit', $artist->id) }}"
                   class="text-blue-500 hover:underline">Edit</a> |
                <form action="{{ route('Artists.ArtistSection.destroy', $artist->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this artist?')" class="text-red-500 hover:underline">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
