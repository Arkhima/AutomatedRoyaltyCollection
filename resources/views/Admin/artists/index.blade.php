@extends('Layouts.Admin.admin')

@section('title','Artists')
@section('breadcrumb', 'Admin / Artists')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Artists</h3>
        <!-- Add new artist button if needed -->
        <!-- <a href="{{ route('admin.artists.create') }}" class="btn btn-primary">Add Artist</a> -->
    </div>

    <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-4 py-2">Name</th>
                <th class="text-left px-4 py-2">Email</th>
                <th class="text-left px-4 py-2">Phone</th>
                <th class="text-left px-4 py-2">Created</th>
                <th class="text-left px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($artists as $artist)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">{{ $artist->name }}</td>
                <td class="px-4 py-2">{{ $artist->email }}</td>
                <td class="px-4 py-2">{{ $artist->phone }}</td>
                <td class="px-4 py-2">{{ $artist->created_at->toDateString() }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.artists.show', $artist->id) }}" 
                       class="text-blue-500 hover:underline text-sm">
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $artists->links() }}
    </div>
</div>
@endsection