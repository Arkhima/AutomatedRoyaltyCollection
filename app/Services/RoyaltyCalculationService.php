<?php

namespace App\Services;

use App\Models\Track;
use Illuminate\Support\Collection;

class RoyaltyCalculationService
{
    /**
     * Calculate royalties for all tracks.
     *
     * @return \Illuminate\Support\Collection
     */
    public function calculateRoyalties(): Collection
    {
        $tracks = Track::with(['streams', 'artist'])->get();

        return $tracks->map(function ($track) {
            $totalStreams = $track->streams->sum('stream_count');
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
