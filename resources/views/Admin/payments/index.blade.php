@extends('Layouts.Admin.admin')

@section('title', 'Payment Requests')
@section('breadcrumb', 'Admin / Payments')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="admin-card">
        <p class="text-sm text-gray-500">Pending</p>
        <p class="text-2xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
        <p class="text-xs">KES {{ number_format($stats['total_pending_amount']) }}</p>
    </div>
    
    <div class="admin-card">
        <p class="text-sm text-gray-500">Approved</p>
        <p class="text-2xl font-bold text-blue-500">{{ $stats['approved'] }}</p>
    </div>
    
    <div class="admin-card">
        <p class="text-sm text-gray-500">Completed</p>
        <p class="text-2xl font-bold text-green-500">{{ $stats['completed'] }}</p>
        <p class="text-xs">KES {{ number_format($stats['total_paid_amount']) }}</p>
    </div>
    
    <div class="admin-card">
        <p class="text-sm text-gray-500">Rejected</p>
        <p class="text-2xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-6">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Artist name or ID" 
                   class="admin-input">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="admin-input">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
            <select name="method" class="admin-input">
                <option value="">All Methods</option>
                <option value="mpesa" {{ request('method') == 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="admin-input">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="admin-input">
        </div>
        
        <div class="md:col-span-5 flex justify-end gap-2">
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
            <a href="{{ route('admin.payments.export') }}?{{ http_build_query(request()->all()) }}" 
               class="admin-btn admin-btn-secondary">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="mb-4">
    <button onclick="bulkApprove()" class="admin-btn admin-btn-primary admin-btn-sm">
        <i class="fas fa-check"></i> Approve Selected
    </button>
    <button onclick="bulkReject()" class="admin-btn admin-btn-danger admin-btn-sm">
        <i class="fas fa-times"></i> Reject Selected
    </button>
</div>

<!-- Payments Table -->
<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll" onclick="toggleAll()">
                </th>
                <th>ID</th>
                <th>Artist</th>
                <th>Amount (KES)</th>
                <th>Method</th>
                <th>Status</th>
                <th>Requested</th>
                <th>Processed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>
                    <input type="checkbox" class="payment-checkbox" value="{{ $payment->id }}">
                </td>
                <td>#{{ $payment->id }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <img src="{{ $payment->artist->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($payment->artist->user->name ?? 'Artist') }}" 
                             class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium">{{ $payment->artist->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->artist->user->email ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="font-bold">KES {{ number_format($payment->amount) }}</td>
                <td>
                    <span class="admin-badge {{ $payment->method }}">
                        {{ strtoupper($payment->method) }}
                    </span>
                </td>
                <td>
                    @php
                        $statusColors = [
                            'pending' => 'pending',
                            'approved' => 'active',
                            'completed' => 'active',
                            'rejected' => 'inactive'
                        ];
                    @endphp
                    <span class="admin-badge {{ $statusColors[$payment->status] ?? 'pending' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td>{{ $payment->requested_at ? $payment->requested_at->format('M d, Y') : 'N/A' }}</td>
                <td>{{ $payment->processed_at ? $payment->processed_at->format('M d, Y') : '-' }}</td>
                <td>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.payments.show', $payment->id) }}" 
                           class="admin-btn admin-btn-secondary admin-btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if($payment->status == 'pending')
                        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" 
                                    onclick="return confirm('Approve this payment of KES {{ number_format($payment->amount) }}?')">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        
                        <button onclick="showRejectModal({{ $payment->id }})" 
                                class="admin-btn admin-btn-danger admin-btn-sm">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                        
                        @if($payment->status == 'approved')
                        <form action="{{ route('admin.payments.complete', $payment->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm"
                                    onclick="return confirm('Mark this payment as completed?')">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-8 text-gray-500">
                    No payment requests found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $payments->links() }}
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Reject Payment</h3>
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

<!-- Bulk Action Form -->
<form id="bulkForm" method="POST" action="{{ route('admin.payments.bulk') }}">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="payment_ids" id="paymentIds">
</form>

@push('scripts')
<script>
// Select all checkboxes
function toggleAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
}

// Get selected payment IDs
function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.payment-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Bulk approve
function bulkApprove() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Please select at least one payment');
        return;
    }
    
    if (confirm(`Approve ${ids.length} selected payment(s)?`)) {
        document.getElementById('bulkAction').value = 'approve';
        document.getElementById('paymentIds').value = JSON.stringify(ids);
        document.getElementById('bulkForm').submit();
    }
}

// Bulk reject
function bulkReject() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Please select at least one payment');
        return;
    }
    
    if (confirm(`Reject ${ids.length} selected payment(s)?`)) {
        document.getElementById('bulkAction').value = 'reject';
        document.getElementById('paymentIds').value = JSON.stringify(ids);
        document.getElementById('bulkForm').submit();
    }
}

// Reject modal
let currentPaymentId = null;

function showRejectModal(paymentId) {
    currentPaymentId = paymentId;
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = "{{ url(env('ADMIN_PATH', 'system-access') . '/payments') }}/" + paymentId + "/reject";
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        hideRejectModal();
    }
}
</script>
@endpush
@endsection