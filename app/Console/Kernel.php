<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Import your custom commands
use App\Console\Commands\CalculateRoyalties;
use App\Console\Commands\ImportUsageData;

class Kernel extends ConsoleKernel
{
    /**
     * Register the application's custom Artisan commands.
     *
     * @var array<class-string>
     */
    protected $commands = [
        CalculateRoyalties::class,
        ImportUsageData::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule quarterly royalty calculation
        $schedule->command('royalties:calculate')->quarterly();

        // Schedule usage data import daily (example)
        $schedule->command('usage:import')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
