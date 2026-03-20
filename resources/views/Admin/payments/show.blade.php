@extends('layouts.admin')

@section('title', 'Payment Details')
@section('breadcrumb', 'Admin / Payments / Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Payment Details -->
    <div class="lg:col-span-2">
        <div class="admin-card">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-bold">Payment Request #{{ $payment->id }}</h2>
                <span class="admin-badge {{ 
                    $payment->status == 'pending' ? 'pending' : 
                    ($payment->status == 'approved' ? 'active' : 
                    ($payment->status == 'completed' ? 'active' : 'inactive')) 
                }} text-base">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-3xl font-bold text-yellow-500">KES {{ number_format($payment->amount) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="text-xl font-semibold">{{ strtoupper($payment->method) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Requested Date</p>
                    <p>{{ $payment->requested_at ? $payment->requested_at->format('F d, Y H:i') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Processed Date</p>
                    <p>{{ $payment->processed_at ? $payment->processed_at->format('F d, Y H:i') : 'Not processed yet' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Artist ID</p>
                    <p>#{{ $payment->artist_id }}</p>
                </div>
            </div>

            @if($payment->status == 'pending')
            <div class="flex gap-3 border-t pt-6">
                <form action="{{ route('Admin.Payments.approve', $payment->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-primary text-lg px-6 py-3"
                            onclick="return confirm('Approve this payment?')">
                        <i class="fas fa-check mr-2"></i> Approve Payment
                    </button>
                </form>
                
                <button onclick="showRejectModal({{ $payment->id }})" 
                        class="admin-btn admin-btn-danger text-lg px-6 py-3">
                    <i class="fas fa-times mr-2"></i> Reject Payment
                </button>
            </div>
            @endif

            @if($payment->status == 'approved')
            <div class="border-t pt-6">
                <form action="{{ route('Admin.Payments.complete', $payment->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-primary"
                            onclick="return confirm('Mark this payment as completed?')">
                        <i class="fas fa-check-circle mr-2"></i> Mark as Completed
                    </button>
                </form>
            </div>
            @endif

            @if(session('rejection_reason_' . $payment->id))
            <div class="mt-6 p-4 bg-red-50 rounded-lg">
                <h4 class="font-semibold text-red-700 mb-2">Rejection Reason:</h4>
                <p class="text-red-600">{{ session('rejection_reason_' . $payment->id) }}</p>
            </div>
            @endif

            @if($payment->status == 'completed' && $payment->processed_at)
            <div class="mt-6 p-4 bg-green-50 rounded-lg">
                <h4 class="font-semibold text-green-700 mb-2">Payment Completed:</h4>
                <p class="text-green-600">Processed on: {{ $payment->processed_at->format('F d, Y H:i') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Artist Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="admin-card">
            <h3 class="text-lg font-semibold mb-4">Artist Information</h3>
            
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $payment->artist->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($payment->artist->user->name ?? 'Artist') }}" 
                     class="w-16 h-16 rounded-full">
                <div>
                    <p class="font-bold">{{ $payment->artist->user->name ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-500">{{ $payment->artist->user->email ?? '' }}</p>
                </div>
            </div>

            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Artist ID:</span> #{{ $payment->artist_id }}</p>
                @if($payment->artist->stage_name)
                <p><span class="text-gray-500">Stage Name:</span> {{ $payment->artist->stage_name }}</p>
                @endif
                <p><span class="text-gray-500">Member Since:</span> {{ $payment->artist->created_at->format('M Y') }}</p>
            </div>

            <div class="border-t mt-4 pt-4">
                <a href="{{ route('Admin.Artists.edit', $payment->artist_id) ?? '#' }}" 
                   class="text-yellow-500 hover:text-yellow-600">
                    View Artist Profile <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Recent Payments from this Artist -->
        @if($artistPayments->count() > 0)
        <div class="admin-card mt-4">
            <h3 class="text-lg font-semibold mb-4">Recent Payments</h3>
            <div class="space-y-3">
                @foreach($artistPayments as $prevPayment)
                <div class="p-2 hover:bg-gray-50 rounded">
                    <div class="flex justify-between">
                        <span class="font-medium">KES {{ number_format($prevPayment->amount) }}</span>
                        <span class="admin-badge {{ $prevPayment->status }}">{{ ucfirst($prevPayment->status) }}</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $prevPayment->requested_at->format('M d, Y') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Reject Payment #{{ $payment->id }}</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for rejection
                </label>
                <textarea name="rejection_reason" rows="4" 
                          class="admin-input" 
                          placeholder="Please provide a reason..." 
                          required></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="hideRejectModal()" 
                        class="admin-btn admin-btn-secondary">
                    Cancel
                </button>
                <button type="submit" class="admin-btn admin-btn-danger">
                    Reject Payment
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(paymentId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = "{{ url('admin/payments') }}/" + paymentId + "/reject";
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('rejectForm').reset();
}

window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        hideRejectModal();
    }
}
</script>
@endpush
@endsection