@extends('layouts.Artists.Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-700 mb-4">Royalty Calculations</h1>

<table class="min-w-full bg-white rounded shadow">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-2 px-4">Track</th>
            <th class="py-2 px-4">Artist</th>
            <th class="py-2 px-4">Streams</th>
            <th class="py-2 px-4">Rate (Ksh)</th>
            <th class="py-2 px-4">Total Royalty</th>
        </tr>
    </thead>
    <tbody>
        @foreach($royalties as $royalty)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $royalty['track_title'] }}</td>
                <td class="py-2 px-4">{{ $royalty['artist_name'] }}</td>
                <td class="py-2 px-4">{{ $royalty['total_streams'] }}</td>
                <td class="py-2 px-4">{{ number_format($royalty['rate_per_stream'], 2) }}</td>
                <td class="py-2 px-4 font-bold text-green-600">{{ number_format($royalty['total_royalty'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
