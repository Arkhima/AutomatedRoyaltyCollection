@extends('layouts.Artists.Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Filter Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-filter text-purple-500 mr-2"></i>
                    Filter Stream Data
                </h2>
                <a href="{{ route('Artists.Streams.index') }}" 
                   class="text-sm text-purple-600 hover:text-purple-800 flex items-center">
                    <i class="fas fa-sync-alt mr-1"></i> Reset Filters
                </a>
            </div>
            
            <form method="GET" action="{{ route('Artists.Streams.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Track Filter -->
                <div>
                    <label for="track_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-music text-purple-500 mr-2"></i>
                        Track
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <select name="track_id" id="track_id" class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md">
                            <option value="">All Tracks</option>
                            @foreach($tracks as $track)
                                <option value="{{ $track->id }}" {{ $selectedTrack == $track->id ? 'selected' : '' }}>
                                    {{ $track->title }} ({{ $track->isrc_code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-compact-disc text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Month Filter -->
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                        Month
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <select name="month" id="month" class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md">
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Year Filter -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-calendar text-purple-500 mr-2"></i>
                        Year
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <select name="year" id="year" class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-day text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Apply Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 h-[42px]">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                </div>
                
                <!-- Current Filter Display -->
                <div class="flex items-end justify-end">
                    <div class="text-sm text-gray-600 bg-gray-100 px-3 py-2 rounded-md h-[42px] flex items-center">
                        @if($selectedTrack)
                            <i class="fas fa-music text-purple-500 mr-1"></i>
                            {{ $tracks->firstWhere('id', $selectedTrack)->title }} • 
                        @endif
                        <i class="fas fa-calendar text-purple-500 mx-1"></i>
                        {{ $currentMonth }}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-wave-square text-purple-500 mr-3"></i>
            Streaming Analytics
        </h1>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <a href="{{ route('Artists.Streams.upload.form') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-file-import mr-2"></i>
                Import CSV
            </a>
            <form action="{{ route('Artists.Streams.generate-demo') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-database mr-2"></i>
                    Generate Streams Data
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    @if(!$streams->isEmpty())
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Streams -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-headphones text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Streams</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($streams->sum('stream_count')) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unique Tracks -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-music text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Unique Tracks</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($streams->unique('track_id')->count()) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Daily Streams -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Avg. Daily Streams</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @php
                                $days = $streams->unique('as_of_date')->count();
                                $avg = $days > 0 ? $streams->sum('stream_count') / $days : 0;
                            @endphp
                            {{ number_format($avg) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stream Data Table -->
    @if($streams->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        @if($selectedTrack)
                            No streaming data found for <span class="font-medium">"{{ $tracks->firstWhere('id', $selectedTrack)->title }}"</span> 
                            in <span class="font-medium">{{ $currentMonth }}</span>
                        @else
                            No streaming data found for <span class="font-medium">{{ $currentMonth }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                                <i class="fas fa-music mr-2"></i> Track
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i> Artist
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">
                                <i class="fas fa-wave-square mr-2"></i> Streams
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                                <i class="fas fa-calendar-day mr-2"></i> Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($streams as $stream)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-music text-purple-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $stream->track->title ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $stream->track->isrc_code ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $stream->artist->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ number_format($stream->stream_count) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ Carbon\Carbon::parse($stream->as_of_date)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ Carbon\Carbon::parse($stream->as_of_date)->diffForHumans() }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $streams->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection