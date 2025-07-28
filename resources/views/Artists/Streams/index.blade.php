@extends('layouts.Artists.Dashboard')

@section('content')

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-700">Streams</h1>
</div>

@if($streams->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        No streaming data found.
    </div>
@else
    <table class="min-w-full bg-white rounded shadow">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-2 px-4 text-left">Track</th>
                <th class="py-2 px-4 text-left">Artist</th>
                <th class="py-2 px-4 text-left">Streams</th>
                <th class="py-2 px-4 text-left">Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($streams as $stream)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $stream->track->title ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $stream->artist->name ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $stream->stream_count }}</td>
                <td class="py-2 px-4">{{ $stream->as_of_date }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

@endsection
