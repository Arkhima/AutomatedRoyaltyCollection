<?php

namespace App\Services;

use App\Models\Track;
use App\Models\Stream;
use App\Models\Royalty;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class RoyaltyCalculationService
{
    /**
     * Calculate and save royalties for all tracks or for a specific artist and date range.
     * Creates monthly royalty records to avoid duplication and ensure accurate reporting.
     *
     * @param int|null $artistId
     * @param string|null $startDate (Y-m-d format)
     * @param string|null $endDate (Y-m-d format)
     * @return Collection
     */
    public function calculateRoyalties(?int $artistId = null, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = Track::with('artist');

        if ($artistId) {
            $query->where('artist_id', $artistId);
        }

        $tracks = $query->get();

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $royalties = collect();

        foreach ($tracks as $track) {
            // Get streams grouped by month for this track within the date range
            $monthlyStreams = Stream::where('track_id', $track->id)
                ->whereBetween('as_of_date', [$start->toDateString(), $end->toDateString()])
                ->selectRaw('YEAR(as_of_date) as year, MONTH(as_of_date) as month, SUM(stream_count) as total_streams')
                ->groupByRaw('YEAR(as_of_date), MONTH(as_of_date)')
                ->get();

            foreach ($monthlyStreams as $monthData) {
                $totalStreams = $monthData->total_streams;
                $royaltyEarned = $totalStreams * $track->rate_per_stream;
                
                // Use end of month as the calculation date for consistency
                $calculationDate = Carbon::create($monthData->year, $monthData->month, 1)->endOfMonth();

                Royalty::updateOrCreate(
                    [
                        'track_id' => $track->id,
                        'artist_id' => $track->artist_id,
                        'calculation_date' => $calculationDate->toDateString(),
                    ],
                    [
                        'total_streams' => $totalStreams,
                        'rate_per_stream' => $track->rate_per_stream,
                        'total_royalty' => $royaltyEarned,
                    ]
                );

                $royalties->push([
                    'track_id' => $track->id,
                    'track_title' => $track->title,
                    'artist_name' => $track->artist->name ?? 'N/A',
                    'isrc_code' => $track->isrc_code,
                    'total_streams' => $totalStreams,
                    'rate_per_stream' => $track->rate_per_stream,
                    'total_royalty' => $royaltyEarned,
                    'as_of_date' => $calculationDate->toDateString(),
                ]);
            }
        }

        return $royalties;
    }

    /**
     * Fetch saved royalties for an artist within an optional date range.
     *
     * @param int $artistId
     * @param string|null $startDate (Y-m-d format)
     * @param string|null $endDate (Y-m-d format)
     * @return Collection
     */
   public function getRoyaltiesByArtistAndDateRange(int $artistId, ?string $startDate = null, ?string $endDate = null): Collection
{
    $query = Royalty::where('artist_id', $artistId)->with('track');

    if ($startDate && $endDate) {
        $query->whereBetween('calculation_date', [$startDate, $endDate]);
    } elseif ($startDate) {
        $query->where('calculation_date', '>=', $startDate);
    } elseif ($endDate) {
        $query->where('calculation_date', '<=', $endDate);
    }

    $royalties = $query->get();

    return $royalties->map(function ($royalty) {
        return [
            'track_id' => $royalty->track_id,
            'track_title' => $royalty->track->title ?? 'N/A',
            'artist_name' => $royalty->artist->name ?? 'N/A',
            'isrc_code' => $royalty->track->isrc_code ?? 'N/A',
            'total_streams' => $royalty->total_streams,
            'rate_per_stream' => $royalty->rate_per_stream,
            'total_royalty' => $royalty->total_royalty,
            'as_of_date' => $royalty->calculation_date,
        ];
    });
}

    /**
     * Get real-time stream totals directly from the streams table (for verification/analytics).
     * This bypasses the royalties table and calculates directly from stream data.
     *
     * @param int $artistId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection
     */
    public function getStreamAnalytics(int $artistId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = Stream::with(['track', 'artist'])
            ->whereHas('track', fn($q) => $q->where('artist_id', $artistId));

        if ($startDate && $endDate) {
            $query->whereBetween('as_of_date', [$startDate, $endDate]);
        }

        return $query->get();
    }

    /**
     * Recalculate all royalties for an artist by clearing old records and recalculating from streams.
     * Use this to fix data inconsistencies.
     *
     * @param int $artistId
     * @return Collection
     */
    public function recalculateAllRoyalties(int $artistId): Collection
    {
        // Delete existing royalties for this artist
        Royalty::where('artist_id', $artistId)->delete();

        // Get the date range from actual stream data
        $dateRange = Stream::whereHas('track', fn($q) => $q->where('artist_id', $artistId))
            ->selectRaw('MIN(as_of_date) as min_date, MAX(as_of_date) as max_date')
            ->first();

        if (!$dateRange->min_date || !$dateRange->max_date) {
            return collect();
        }

        // Recalculate royalties for the full date range
        return $this->calculateRoyalties($artistId, $dateRange->min_date, $dateRange->max_date);
    }
}