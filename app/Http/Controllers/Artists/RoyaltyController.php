<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use App\Services\RoyaltyCalculationService;
use Illuminate\Http\Request;

class RoyaltyController extends Controller
{
    protected $royaltyService;

    public function __construct(RoyaltyCalculationService $royaltyService)
    {
        $this->royaltyService = $royaltyService;
    }

    public function index(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'artist_id' => 'nullable|integer|exists:artists,id',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Initialize default values
        $royaltyData = [];
        $totalStreams = 0;
        $totalRoyalties = 0;
        $averageRate = 0;

        // Get the calculated royalties if filters are applied
        if ($request->hasAny(['artist_id', 'start_date', 'end_date'])) {
            $royaltyData = $this->royaltyService->calculateRoyalties(
                $validated['artist_id'] ?? null,
                $validated['start_date'] ?? null,
                $validated['end_date'] ?? null
            );

            // Calculate summary statistics
            $totalStreams = collect($royaltyData)->sum('total_streams');
            $totalRoyalties = collect($royaltyData)->sum('total_royalty');
            $averageRate = $totalStreams > 0 ? $totalRoyalties / $totalStreams : 0;
        }

        return view('Artists.Royalties.index', [
            'royalties' => $royaltyData,
            'totalStreams' => $totalStreams,
            'totalRoyalties' => $totalRoyalties,
            'averageRate' => $averageRate,
            'selectedArtist' => $validated['artist_id'] ?? null,
            'startDate' => $validated['start_date'] ?? null,
            'endDate' => $validated['end_date'] ?? null,
            'artists' => \App\Models\Artist::orderBy('name')->get(),
            'hasFilters' => $request->hasAny(['artist_id', 'start_date', 'end_date'])
        ]);
    }
}