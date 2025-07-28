@extends('layouts.Artists.Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-700 mb-4">My Payments</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('Artists.Payments.request') }}" class="mb-6">
    @csrf
    <div class="flex gap-4">
        <input type="number" name="amount" placeholder="Amount (Ksh)" class="border p-2 rounded w-1/3" required>
        <select name="method" class="border p-2 rounded w-1/3" required>
            <option value="mpesa">M-Pesa</option>
            <option value="bank">Bank</option>
        </select>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Request Payout</button>
    </div>
</form>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-2 px-4">Amount</th>
            <th class="py-2 px-4">Method</th>
            <th class="py-2 px-4">Status</th>
            <th class="py-2 px-4">Requested</th>
            <th class="py-2 px-4">Processed</th>
        </tr>
    </thead>
    <tbody>
    @foreach($payments as $payment)
        <tr class="border-b">
            <td class="py-2 px-4">Ksh {{ number_format($payment->amount, 2) }}</td>
            <td class="py-2 px-4">{{ ucfirst($payment->method) }}</td>
            <td class="py-2 px-4">{{ ucfirst($payment->status) }}</td>
            <td class="py-2 px-4">{{ $payment->requested_at ?? '-' }}</td>
            <td class="py-2 px-4">{{ $payment->processed_at ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
