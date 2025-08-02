<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Royalty;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return redirect()->route('Artists.Dashboard.index')->with('error', 'Artist account not found.');
        }

        $artistId = $artist->id;

        $totalRoyalties = Royalty::where('artist_id', $artistId)->sum('total_royalty');

        $withdrawn = Payment::where('artist_id', $artistId)
            ->where('type', 'royalty')
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $availableBalance = $totalRoyalties - $withdrawn;

        $payments = Payment::where('artist_id', $artistId)->latest()->get();

        return view('Artists.Payments.index', compact('payments', 'availableBalance'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:mpesa,bank',
        ]);

        $artist = Auth::user()->artist;

        if (!$artist) {
            return redirect()->route('Artists.Dashboard.index')->with('error', 'Artist account not found.');
        }

        $artistId = $artist->id;

        $totalRoyalties = Royalty::where('artist_id', $artistId)->sum('total_royalty');

        $withdrawn = Payment::where('artist_id', $artistId)
            ->where('type', 'royalty')
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        $availableBalance = $totalRoyalties - $withdrawn;

        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Withdrawal amount exceeds available royalties.');
        }

        Payment::create([
            'artist_id'    => $artistId,
            'amount'       => $request->amount,
            'method'       => $request->method,
            'status'       => 'pending',
            'type'         => 'royalty',
            'requested_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted!');
    }
}
