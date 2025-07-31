@extends('layouts.Artists.Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Filter Stream Data</h2>
            <a href="{{ route('Artists.Streams.index') }}" 
               class="text-sm text-gray-600 hover:text-gray-800 underline">
                Reset All Filters
            </a>
        </div>
        
        <form method="GET" action="{{ route('Artists.Streams.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Track Filter -->
            <div>
                <label for="track_id" class="block text-sm font-medium text-gray-700 mb-1">Track</label>
                <select name="track_id" id="track_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Tracks</option>
                    @foreach($tracks as $track)
                        <option value="{{ $track->id }}" {{ $selectedTrack == $track->id ? 'selected' : '' }}>
                            {{ $track->title }} ({{ $track->isrc_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <select name="month" id="month" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Year Filter -->
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select name="year" id="year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Apply Button -->
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 h-[42px]">
                    Apply Filters
                </button>
            </div>
            
            <!-- Current Filter Display -->
            <div class="flex items-end justify-end">
                <div class="text-sm text-gray-600 bg-gray-100 px-3 py-2 rounded-md h-[42px]">
                    @if($selectedTrack)
                        {{ $tracks->firstWhere('id', $selectedTrack)->title }} • 
                    @endif
                    {{ $currentMonth }}
                </div>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Streaming Data</h1>
        <div class="flex gap-3">
            <a href="{{ route('Artists.Streams.upload.form') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Import CSV
            </a>
            <form action="{{ route('Artists.Streams.generate-demo') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Generate Demo Data
                </button>
            </form>
        </div>
    </div>

    <!-- Stream Data Table -->
    @if($streams->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded-lg">
            <p>
                @if($selectedTrack)
                    No streaming data found for "{{ $tracks->firstWhere('id', $selectedTrack)->title }}" 
                    in {{ $currentMonth }}
                @else
                    No streaming data found for {{ $currentMonth }}
                @endif
            </p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Track</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Streams</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($streams as $stream)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $stream->track->title ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $stream->artist->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($stream->stream_count) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ Carbon\Carbon::parse($stream->as_of_date)->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $streams->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection