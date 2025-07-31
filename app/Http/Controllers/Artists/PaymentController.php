<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the artist's payments.
     */
    public function index()
    {
        $artistId = Auth::user()?->artist_id;

        if (!$artistId) {
            return redirect()->back()->with('error', 'Artist account not found.');
        }

        $payments = Payment::where('artist_id', $artistId)
            ->latest()
            ->get();

        return view('Artists.Payments.index', compact('payments'));
    }

    /**
     * Handle a payment withdrawal request.
     */
    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:mpesa,bank',
        ]);

        $artistId = Auth::user()?->artist_id;

        if (!$artistId) {
            return redirect()->back()->with('error', 'Artist account not found.');
        }

        Payment::create([
            'artist_id' => $artistId,
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted!');
    }
}
