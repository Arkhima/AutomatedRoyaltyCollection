@extends('layouts.Artists.Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

  <!-- Total Royalty Earnings Card -->
  <div class="card group relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-green-700 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
    <div class="flex items-center justify-between relative z-10">
      <div>
        <h2 class="text-gray-700 text-lg font-semibold">Total Earnings</h2>
        <p class="text-3xl font-bold mt-2 text-green-600">Ksh. {{ number_format($totalEarnings, 2) }}</p>
        {{-- You can calculate % increase here if you want --}}
      </div>
      <div class="p-3 rounded-full bg-green-100 text-green-600">
        <i class="fa fa-money-bill-wave text-3xl"></i>
      </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-green-400 to-green-600"></div>
  </div>

  <!-- Tracks Count Card -->
  <div class="card group relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-700 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
    <div class="flex items-center justify-between relative z-10">
      <div>
        <h2 class="text-gray-700 text-lg font-semibold">Tracks Uploaded</h2>
        <p class="text-3xl font-bold mt-2 text-blue-600">{{ $totalTracks }}</p>
        {{-- Optionally: new tracks this month --}}
      </div>
      <div class="p-3 rounded-full bg-blue-100 text-blue-600">
        <i class="fa fa-music text-3xl"></i>
      </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 to-indigo-600"></div>
  </div>

  <!-- Latest Royalty Payment Card -->
  <div class="card group relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-yellow-700 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
    <div class="flex items-center justify-between relative z-10">
      <div>
        <h2 class="text-gray-700 text-lg font-semibold">Last Payment</h2>
        <p class="text-3xl font-bold mt-2 text-amber-600">
          Ksh. {{ number_format(optional($lastPayment)->amount ?? 0, 2) }}
        </p>
        <p class="text-sm text-gray-500 mt-1">
          <i class="far fa-calendar text-amber-500 mr-1"></i>
          {{ optional($lastPayment)->processed_at ? $lastPayment->processed_at->format('d M Y') : 'No payments yet' }}
        </p>
      </div>
      <div class="p-3 rounded-full bg-amber-100 text-amber-600">
        <i class="fa fa-wallet text-3xl"></i>
      </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-yellow-600"></div>
  </div>

</div>

<!-- Recent Transactions Section -->
<div class="mt-10">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-700 flex items-center">
      <i class="fas fa-history text-purple-500 mr-3"></i>
      Recent Royalty Transactions
    </h2>
    <a href="#" class="text-sm font-medium text-purple-600 hover:text-purple-800 flex items-center">
      View All <i class="fas fa-chevron-right ml-1"></i>
    </a>
  </div>

  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-gray-800 to-gray-900">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
              <i class="far fa-calendar mr-2"></i> Date
            </th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
              <i class="fas fa-music mr-2"></i> Track
            </th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
              <i class="fas fa-headphones mr-2"></i> Plays
            </th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
              <i class="fas fa-coins mr-2"></i> Earnings
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse ($recentRoyalties as $royalty)
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
  <div class="text-sm text-gray-900">
    {{ optional(\Carbon\Carbon::parse($royalty->calculation_date))->format('Y-m-d') ?? 'N/A' }}
  </div>
</td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                  <i class="fas fa-music text-purple-600"></i>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ $royalty->track->title ?? 'Unknown Track' }}</div>
                  {{-- Optionally show more info --}}
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ number_format($royalty->total_streams) }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                Ksh. {{ number_format($royalty->total_royalty, 2) }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No recent transactions found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-right text-sm">
      <span class="text-gray-600">Showing <span class="font-medium">{{ $recentRoyalties->count() }}</span> of your latest transactions</span>
    </div>
  </div>
</div>

@endsection
