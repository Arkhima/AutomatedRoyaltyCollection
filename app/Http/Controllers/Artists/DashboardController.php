<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Track;
use App\Models\Royalty;

class DashboardController extends Controller
{
    public function index()
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return redirect()->route('Artists.Dashboard.index')->with('error', 'Artist account not found.');
        }

        $artistId = $artist->id;

        // Last processed payment
        $lastPayment = Payment::where('artist_id', $artistId)
            ->where('status', 'completed')
            ->orderByDesc('processed_at')
            ->first();

        // Total tracks uploaded
        $totalTracks = Track::where('artist_id', $artistId)->count();

        // Total earnings from royalties
        $totalEarnings = Royalty::where('artist_id', $artistId)->sum('total_royalty');

        // Recent royalty transactions (latest 5)
        $recentRoyalties = Royalty::with('track')
            ->where('artist_id', $artistId)
            ->orderByDesc('calculation_date')
            ->limit(5)
            ->get();

        return view('Artists.Dashboard.index', compact(
            'lastPayment',
            'totalTracks',
            'totalEarnings',
            'recentRoyalties'
        ));
    }
}
