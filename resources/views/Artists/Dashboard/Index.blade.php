@extends('layouts.Artists.Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

  <!-- Total Royalty Earnings -->
  <div class="card">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-whitesmoke-200 text-xl font-semibold">Total Earnings</h2>
        <p class="text-3xl text-green-600 font-bold mt-2">Ksh. 240,500</p>
      </div>
      <i class="fa fa-money-bill-wave text-4xl text-green-500"></i>
    </div>
  </div>

  <!-- Tracks Count -->
  <div class="card">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-whitesmoke-50 text-xl font-semibold">Tracks Uploaded</h2>
        <p class="text-3xl text-blue-600 font-bold mt-2">18</p>
      </div>
      <i class="fa fa-music text-4xl text-red-800"></i>
    </div>
  </div>

  <!-- Latest Royalty Payment -->
  <div class="card">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-whitesmoke-50 text-xl font-semibold">Last Payment</h2>
        <p class="text-3xl text-green-600 font-bold mt-2">Ksh. 15,000</p>
      </div>
      <i class="fa fa-wallet text-4xl text-gray-800"></i>
    </div>
  </div>

</div>

<div class="mt-10">
  <h2 class="text-2xl font-bold text-gray-700 mb-4">Recent Royalty Transactions</h2>
  <div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow">
      <thead class="bg-gradient-to-r from-gray-700 to-gray-900 text-white">
        <tr>
          <th class="py-3 px-6 text-left">Date</th>
          <th class="py-3 px-6 text-left">Track</th>
          <th class="py-3 px-6 text-left">Plays</th>
          <th class="py-3 px-6 text-left">Earnings (Ksh)</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-3 px-6">2025-06-21</td>
          <td class="py-3 px-6">Love in Nairobi</td>
          <td class="py-3 px-6">12,345</td>
          <td class="py-3 px-6 text-green-600 font-semibold">5,000</td>
        </tr>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-3 px-6">2025-06-19</td>
          <td class="py-3 px-6">City Lights</td>
          <td class="py-3 px-6">8,765</td>
          <td class="py-3 px-6 text-green-600 font-semibold">3,500</td>
        </tr>
        <tr class="hover:bg-gray-50">
          <td class="py-3 px-6">2025-06-15</td>
          <td class="py-3 px-6">Ocean Breeze</td>
          <td class="py-3 px-6">15,001</td>
          <td class="py-3 px-6 text-green-600 font-semibold">6,200</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

@endsection
