@extends('layouts.Artists.Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-4">Royalty Reports</h1>

<form method="GET" class="mb-4">
    <label class="mr-2 text-gray-700">Filter by Artist:</label>
    <select name="artist_id" onchange="this.form.submit()" class="border p-2 rounded">
        <option value="">All</option>
        @foreach($artists as $artist)
            <option value="{{ $artist->id }}" {{ $selectedArtist == $artist->id ? 'selected' : '' }}>
                {{ $artist->name }}
            </option>
        @endforeach
    </select>
</form>

<div class="mb-4">
    <a href="{{ route('Artists.Reports.exportPdf', ['artist_id' => $selectedArtist]) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Export PDF</a>
    <a href="{{ route('Artists.Reports.exportExcel', ['artist_id' => $selectedArtist]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export Excel</a>
</div>

@forelse($streams as $quarter => $items)
    <div class="bg-white p-4 mb-6 rounded shadow">
        <h2 class="text-lg font-bold text-indigo-700 mb-3">{{ $quarter }}</h2>
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-4">Track</th>
                    <th class="py-2 px-4">Artist</th>
                    <th class="py-2 px-4">Streams</th>
                    <th class="py-2 px-4">Royalty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $stream)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $stream->track->title }}</td>
                        <td class="py-2 px-4">{{ $stream->track->artist->name }}</td>
                        <td class="py-2 px-4">{{ $stream->stream_count }}</td>
                        <td class="py-2 px-4">
                            Ksh {{ number_format($stream->stream_count * $stream->track->rate_per_stream, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@empty
    <div class="bg-yellow-100 text-yellow-700 p-4 rounded">
        No data available for this filter.
    </div>
@endforelse
@endsection
