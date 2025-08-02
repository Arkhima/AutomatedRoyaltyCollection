<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Track;
use App\Models\Artist;
use App\Services\ISRCMatchingService;
use Carbon\Carbon;

class ImportUsageData extends Command
{
    protected $signature = 'usage:import 
        {--count=50 : Number of demo records to generate} 
        {--artist= : ID of the artist whose tracks to use}';

    protected $description = 'Generate and import demo streaming data for a specific artist';

    public function __construct(private ISRCMatchingService $isrcService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $artistId = $this->option('artist');
        $count = (int) $this->option('count');

        // Validate artist
        $artist = Artist::find($artistId);

        if (!$artist) {
            $this->error("Artist not found. Use --artist=ID to specify a valid artist.");
            return Command::FAILURE;
        }

        // Fetch artist's tracks
        $tracks = $artist->tracks;

        if ($tracks->isEmpty()) {
            $this->warn("This artist has no tracks. Upload at least one to generate streams.");
            return Command::SUCCESS;
        }

        // Generate fake stream data across 2025
        $demoData = collect()->times($count, function () use ($tracks) {
            $track = $tracks->random();

            return [
                'isrc'      => $track->isrc_code,
                'artist_id' => $track->artist_id,
                'streams'   => rand(100, 10000),
                'date'      => Carbon::create(2025, rand(1, 12), rand(1, 28))->format('Y-m-d'),
            ];
        });

        // Import
        $imported = $this->isrcService->importUsageData($demoData->toArray());

        $this->info("✅ Successfully imported {$imported} stream records for artist ID {$artistId}");
        return Command::SUCCESS;
    }
}
