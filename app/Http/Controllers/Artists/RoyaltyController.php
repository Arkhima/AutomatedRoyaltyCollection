<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use App\Services\RoyaltyCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoyaltyController extends Controller
{
    protected $royaltyService;

    public function __construct(RoyaltyCalculationService $royaltyService)
    {
        $this->royaltyService = $royaltyService;
    }

    public function index(Request $request)
    {
        // Get the currently logged-in artist
        $currentArtist = Auth::user()->artist; // Adjust this based on your relationship
        
        // If user doesn't have an artist profile, redirect with error
        if (!$currentArtist) {
            return redirect()->route('artists.dashboard')
                           ->with('error', 'No artist profile found.');
        }

        // Validate inputs (artist_id is no longer needed from request)
        $validated = $request->validate([
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Initialize default values
        $royaltyData = [];
        $totalStreams = 0;
        $totalRoyalties = 0;
        $averageRate = 0;

        // Get the calculated royalties if filters are applied (or show current artist by default)
        // Changed logic: always show current artist data, optionally filtered by date
        $royaltyData = $this->royaltyService->calculateRoyalties(
            $currentArtist->id, // Always use current artist
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        // Calculate summary statistics
        $totalStreams = collect($royaltyData)->sum('total_streams');
        $totalRoyalties = collect($royaltyData)->sum('total_royalty');
        $averageRate = $totalStreams > 0 ? $totalRoyalties / $totalStreams : 0;

        return view('Artists.Royalties.index', [
            'royalties' => $royaltyData,
            'totalStreams' => $totalStreams,
            'totalRoyalties' => $totalRoyalties,
            'averageRate' => $averageRate,
            'artist' => $currentArtist, // Pass single artist instead of collection
            'startDate' => $validated['start_date'] ?? null,
            'endDate' => $validated['end_date'] ?? null,
            'hasFilters' => $request->hasAny(['start_date', 'end_date'])
        ]);
    }
}