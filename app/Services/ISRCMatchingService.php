<?php

namespace App\Services;

use App\Models\Track;
use App\Models\Stream;
use Illuminate\Support\Facades\Log;

class ISRCMatchingService
{
    /**
     * Import usage data from an array of rows using ISRC code matching.
     *
     * @param array $rows - array of usage data rows (from CSV or API)
     *                      Expected format: ['isrc' => string, 'streams' => int, 'date' => optional string]
     * @return int Number of matched records saved
     */
    public function importUsageData(array $rows): int
    {
        $importedCount = 0;

        foreach ($rows as $index => $row) {
            if (!isset($row['isrc']) || !isset($row['streams'])) {
                Log::warning("Row $index missing ISRC or stream count.", $row);
                continue;
            }

            $track = Track::where('isrc_code', $row['isrc'])->first();

            if (!$track) {
                Log::info("No matching track found for ISRC: {$row['isrc']}");
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
