<?php

namespace App\Services;

use App\Models\Track;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class RoyaltyCalculationService
{
    /**
     * Calculate royalties for all tracks, optionally filtered by artist and date range.
     *
     * @param int|null $artistId
     * @param string|null $startDate (Y-m-d format)
     * @param string|null $endDate (Y-m-d format)
     * @return Collection
     */
    public function calculateRoyalties(?int $artistId = null, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = Track::with(['streams', 'artist']);

        if ($artistId) {
            $query->where('artist_id', $artistId);
        }

        $tracks = $query->get();

        // Parse dates if provided
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        return $tracks->map(function ($track) use ($start, $end) {
            $streams = $track->streams;

            // Filter streams by date range if set
            if ($start && $end) {
                $streams = $streams->filter(function ($stream) use ($start, $end) {
                    $date = Carbon::parse($stream->as_of_date);
                    return $date->between($start, $end);
                });
            }

            $totalStreams = $streams->sum('stream_count');
            $royaltyEarned = $totalStreams * $track->rate_per_stream;

            return [
                'track_title' => $track->title,
                'artist_name' => $track->artist->name ?? 'N/A',
                'isrc_code' => $track->isrc_code,
                'total_streams' => $totalStreams,
                'rate_per_stream' => $track->rate_per_stream,
                'total_royalty' => $royaltyEarned,
            ];
        });
    }
}
