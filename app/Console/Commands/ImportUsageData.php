<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ISRCMatchingService;
use App\Models\Track;

class ImportUsageData extends Command
{
    protected $signature = 'usage:import {--count=50 : Number of dummy records to create}';
    protected $description = 'Generate and import demo streaming data';

    public function __construct(
        private ISRCMatchingService $isrcService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $count = $this->option('count');
        $tracks = Track::with('artist')->limit(10)->get();

        if ($tracks->isEmpty()) {
            $this->error('No tracks found. Seed tracks first!');
            return Command::FAILURE;
        }

        $dummyData = collect()->times($count, function() use ($tracks) {
            $track = $tracks->random();
            return [
                'isrc' => $track->isrc_code,
                'artist_id' => $track->artist_id,
                'streams' => rand(1000, 100000),
                'date' => now()->subDays(rand(0, 365))->format('Y-m-d'),
            ];
        });

        $imported = $this->isrcService->importUsageData($dummyData->toArray());
        $this->info("Generated {$imported} demo stream records.");
        return Command::SUCCESS;
    }
}
