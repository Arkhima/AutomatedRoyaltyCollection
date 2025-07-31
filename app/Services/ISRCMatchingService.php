<?php

namespace App\Services;

use App\Models\Track;
use App\Models\Stream;
use Illuminate\Support\Facades\Log;

class ISRCMatchingService
{
    /**
     * Import usage data from CSV/API using ISRC code (and optional artist ID)
     */
    public function importUsageData(array $rows): int
    {
        $importedCount = 0;

        foreach ($rows as $index => $row) {
            if (!isset($row['isrc']) || !isset($row['streams'])) {
                Log::warning("Row $index missing ISRC or stream count.", $row);
                continue;
            }

            $query = Track::where('isrc_code', $row['isrc']);

            if (!empty($row['artist_id'])) {
                $query->where('artist_id', $row['artist_id']);
            }

            $track = $query->first();

            if (!$track) {
               $artistIdText = isset($row['artist_id']) ? $row['artist_id'] : 'N/A';
                                    Log::info("No matching track found for ISRC: {$row['isrc']} (Artist: {$artistIdText})");
                continue;
            }

            Stream::create([
                'track_id'     => $track->id,
                'artist_id'    => $track->artist_id,
                'stream_count' => (int) $row['streams'],
                'as_of_date'   => $row['date'] ?? now()->toDateString(),
            ]);

            $importedCount++;
        }

        return $importedCount;
    }
}
