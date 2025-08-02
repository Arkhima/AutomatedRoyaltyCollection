@extends('layouts.Artists.Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-700 mb-4">My Payments</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6">
    <p class="text-xl">💰 <strong>Available Royalties:</strong> Ksh {{ number_format($availableBalance, 2) }}</p>
</div>

<form method="POST" action="{{ route('Artists.Payments.request') }}" class="mb-6">
    @csrf
    <div class="flex gap-4 items-center">
        <input type="number" name="amount" placeholder="Amount (Ksh)" class="border p-2 rounded w-1/3" required>
        <select name="method" class="border p-2 rounded w-1/3" required>
            <option value="">Select Method</option>
            <option value="mpesa">M-Pesa</option>
            <option value="bank">Bank</option>
        </select>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Request Payout</button>
    </div>
</form>

@if($payments->count())
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
            <td class="py-2 px-4 capitalize">{{ $payment->status }}</td>
            <td class="py-2 px-4">{{ $payment->requested_at ? $payment->requested_at->format('Y-m-d') : '-' }}</td>
            <td class="py-2 px-4">{{ $payment->processed_at ? $payment->processed_at->format('Y-m-d') : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<p class="text-gray-600">No payments yet.</p>
@endif
@endsection
