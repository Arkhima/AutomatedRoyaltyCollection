<?php

namespace App\Services;

use App\Models\Track;
use App\Models\Royalty;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class RoyaltyCalculationService
{
    /**
     * Calculate and save royalties for all tracks or for a specific artist and date range.
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

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        $royalties = $tracks->map(function ($track) use ($start, $end) {
            $streamsQuery = $track->streams();

            if ($start && $end) {
                $streamsQuery->whereBetween('as_of_date', [$start->toDateString(), $end->toDateString()]);
            }

            $totalStreams = $streamsQuery->sum('stream_count');
            $royaltyEarned = $totalStreams * $track->rate_per_stream;

            $calculationDate = $end ?? Carbon::now();

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

            return [
                'track_title' => $track->title,
                'artist_name' => $track->artist->name ?? 'N/A',
                'isrc_code' => $track->isrc_code,
                'total_streams' => $totalStreams,
                'rate_per_stream' => $track->rate_per_stream,
                'total_royalty' => $royaltyEarned,
                'as_of_date' => $calculationDate->toDateString(),
            ];
        });

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
            'isrc_code' => $royalty->track->isrc_code ?? 'N/A',   // <--- add this line
            'total_streams' => $royalty->total_streams,
            'rate_per_stream' => $royalty->rate_per_stream,
            'total_royalty' => $royalty->total_royalty,
            'as_of_date' => $royalty->calculation_date,
        ];
    });
}
}