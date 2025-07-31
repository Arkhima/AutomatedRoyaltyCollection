@extends('layouts.Artists.Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-700 mb-4">Royalty Calculations</h1>

{{-- Filter Form --}}
<form method="GET" action="{{ route('Artists.Royalties.index') }}" class="mb-6">
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex flex-col">
            <label for="artist_id" class="mb-1 font-semibold text-gray-600">Artist</label>
            <select name="artist_id" id="artist_id" class="border rounded px-3 py-2">
                <option value="">-- All Artists --</option>
                @foreach ($artists as $artist)
                    <option value="{{ $artist->id }}" {{ (string)$artist->id === (string) old('artist_id', $selectedArtist) ? 'selected' : '' }}>
                        {{ $artist->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="start_date" class="mb-1 font-semibold text-gray-600">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="border rounded px-3 py-2" 
                value="{{ old('start_date', $startDate) }}">
        </div>

        <div class="flex flex-col">
            <label for="end_date" class="mb-1 font-semibold text-gray-600">End Date</label>
            <input type="date" name="end_date" id="end_date" class="border rounded px-3 py-2" 
                value="{{ old('end_date', $endDate) }}">
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700">
                Filter
            </button>
            <a href="{{ route('Artists.Royalties.index') }}" class="ml-2 text-gray-600 hover:underline">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Royalty Table --}}
<table class="min-w-full bg-white rounded shadow">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-2 px-4 text-left">Track</th>
            <th class="py-2 px-4 text-left">Artist</th>
            <th class="py-2 px-4 text-right">Streams</th>
            <th class="py-2 px-4 text-right">Rate (Ksh)</th>
            <th class="py-2 px-4 text-right">Total Royalty</th>
        </tr>
    </thead>
    <tbody>
        @forelse($royalties as $royalty)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-2 px-4">{{ $royalty['track_title'] }}</td>
                <td class="py-2 px-4">{{ $royalty['artist_name'] }}</td>
                <td class="py-2 px-4 text-right">{{ number_format($royalty['total_streams']) }}</td>
                <td class="py-2 px-4 text-right">{{ number_format($royalty['rate_per_stream'], 2) }}</td>
                <td class="py-2 px-4 font-bold text-green-600 text-right">{{ number_format($royalty['total_royalty'], 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">No royalty data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
