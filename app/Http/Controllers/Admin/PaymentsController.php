<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentsController extends Controller
{
    /**
     * Display all payment requests
     */
    public function index(Request $request)
    {
        $query = Payment::with('artist.user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('artist.user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhere('id', 'LIKE', "%{$search}%");
        }

        if ($request->filled('from_date')) {
            $query->whereDate('requested_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('requested_at', '<=', $request->to_date);
        }

        $payments = $query->paginate(15)->withQueryString();

        $stats = [
            'pending' => Payment::where('status', 'pending')->count(),
            'approved' => Payment::where('status', 'approved')->count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'total_pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'total_paid_amount' => Payment::whereIn('status', ['approved', 'completed'])->sum('amount'),
        ];

        return view('Admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Show single payment details (Using route model binding)
     */
    public function show(Payment $payment)
    {
        $artistPayments = Payment::where('artist_id', $payment->artist_id)
                                 ->where('id', '!=', $payment->id)
                                 ->latest()
                                 ->limit(5)
                                 ->get();

        return view('Admin.payments.show', compact('payment', 'artistPayments'));
    }

    /**
     * Approve payment request (Using route model binding)
     */
    public function approve(Payment $payment)
    {
        $payment->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.payments.index')
                         ->with('success', "Payment of KES " . number_format($payment->amount) . " has been approved.");
    }

    /**
     * Reject payment request (Using route model binding)
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5|max:500'
        ]);

        $payment->update([
            'status' => 'rejected',
            'processed_at' => now(),
        ]);

        session()->flash('rejection_reason_' . $payment->id, $request->rejection_reason);

        return redirect()->route('admin.payments.index')
                         ->with('success', "Payment request #{$payment->id} has been rejected.");
    }

    /**
     * Mark payment as completed (Using route model binding)
     */
    public function complete(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.payments.show', $payment->id)
                         ->with('success', "Payment marked as completed.");
    }

    /**
     * Bulk action for multiple payments
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        $count = 0;
        foreach ($request->payment_ids as $id) {
            $payment = Payment::find($id);
            if ($payment) {
                $payment->update([
                    'status' => $request->action === 'approve' ? 'approved' : 'rejected',
                    'processed_at' => now(),
                ]);
                $count++;
            }
        }

        return redirect()->route('admin.payments.index')
                         ->with('success', "{$count} payment requests have been {$request->action}d.");
    }

    /**
     * Export payments report
     */
    public function export(Request $request)
    {
        $query = Payment::with('artist.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('requested_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('requested_at', '<=', $request->to_date);
        }

        $payments = $query->get();

        $filename = "payments_export_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID', 'Artist', 'Email', 'Amount (KES)', 'Method', 'Status', 'Requested Date', 'Processed Date'];

        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->artist->user->name ?? 'N/A',
                    $payment->artist->user->email ?? 'N/A',
                    $payment->amount,
                    $payment->method,
                    $payment->status,
                    $payment->requested_at?->format('Y-m-d') ?? 'N/A',
                    $payment->processed_at?->format('Y-m-d') ?? 'Pending',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}