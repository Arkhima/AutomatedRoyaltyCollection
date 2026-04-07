@extends('layouts.Artists.Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-calculator text-purple-500 mr-3"></i>
                Royalty Calculations
            </h1>
            <p class="text-gray-600 mt-1">Track your earnings from streams and downloads</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <form method="GET" action="{{ route('Artists.Royalties.index') }}">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Artist Display (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-user text-purple-500 mr-2"></i>
                            Artist
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-circle text-gray-400"></i>
                            </div>
                            <div class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900 font-semibold">
                                {{ $artist->name ?? $artist->user->name ?? 'Unknown Artist' }}
                            </div>
                        </div>
                        <!-- Hidden input to maintain artist_id if needed for backend -->
                        <input type="hidden" name="artist_id" value="{{ $artist->id }}">
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                            Start Date
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" name="start_date" id="start_date" 
                                   class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md"
                                   value="{{ old('start_date', $startDate) }}">
                        </div>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                            End Date
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" name="end_date" id="end_date" 
                                   class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md"
                                   value="{{ old('end_date', $endDate) }}">
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i class="fas fa-filter mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('Artists.Royalties.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Streams -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-headphones text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Streams</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalStreams) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Royalties -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-coins text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Royalties</p>
                        <p class="text-2xl font-semibold text-green-600">Ksh. {{ number_format($totalRoyalties, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Rate -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Avg. Rate/Stream</p>
                        <p class="text-2xl font-semibold text-purple-600">Ksh. {{ number_format($averageRate, 4) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Royalty Table -->
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
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-money-bill-wave mr-2"></i> Rate
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Royalty
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($royalties as $royalty)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $royalty['track_title'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $royalty['artist_name'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-900">{{ number_format($royalty['total_streams']) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-900">Ksh. {{ number_format($royalty['rate_per_stream'], 4) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-green-600">Ksh. {{ number_format($royalty['total_royalty'], 2) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            <i class="fas fa-search-dollar text-3xl text-gray-300 mb-2 block"></i>
                            @if($hasFilters)
                                No royalty data found for the selected date range
                            @else
                                Select a date range to view your royalty calculations
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if(count($royalties) > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="2" class="px-6 py-3 text-sm font-semibold text-gray-900 text-right">
                            Totals:
                        </td>
                        <td class="px-6 py-3 text-sm font-semibold text-gray-900 text-right">
                            {{ number_format($totalStreams) }}
                        </td>
                        <td class="px-6 py-3 text-sm font-semibold text-gray-900 text-right">
                            &mdash;
                        </td>
                        <td class="px-6 py-3 text-sm font-semibold text-green-600 text-right">
                            Ksh. {{ number_format($totalRoyalties, 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection