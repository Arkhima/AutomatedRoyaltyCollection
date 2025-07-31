<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RoyaltyCalculationService;

class CalculateRoyalties extends Command
{
    protected $signature = 'royalties:calculate';
    protected $description = 'Calculate royalties for all artists based on streaming data.';

    protected $royaltyService;

    public function __construct(RoyaltyCalculationService $royaltyService)
    {
        parent::__construct();
        $this->royaltyService = $royaltyService;
    }

    public function handle(): int
    {
        $this->info('Calculating royalties...');

        $this->royaltyService->calculateRoyalties();

        $this->info('Royalty calculation complete.');

        return Command::SUCCESS;
    }
}
