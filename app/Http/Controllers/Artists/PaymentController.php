<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Artist;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $artistId = 1; // TODO: Replace with Auth::id() or actual artist logic
        $payments = Payment::where('artist_id', $artistId)->latest()->get();

        return view('Artists.Payments.index', compact('payments'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:mpesa,bank',
        ]);

        $artistId = 1; // Replace with Auth::id()
        Payment::create([
            'artist_id' => $artistId,
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending',
            'requested_at' => now()
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted!');
    }
}
