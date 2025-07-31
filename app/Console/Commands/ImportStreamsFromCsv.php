<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ISRCMatchingService;
use Illuminate\Support\Facades\Storage;

class ImportStreamsFromCsv extends Command
{
    protected $signature = 'import:streams';
    protected $description = 'Import streaming data from demo CSV';

    protected $isrcService;

    public function __construct(ISRCMatchingService $isrcService)
    {
        parent::__construct();
        $this->isrcService = $isrcService;
    }

    public function handle()
    {
        $path = storage_path('app/streams/sample_stream_data.csv');

        if (!file_exists($path)) {
            $this->error("CSV file not found at $path");
            return;
        }

        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = array_combine($header, $data);
            }
            fclose($handle);
        }

        $count = $this->isrcService->importUsageData($rows);
        $this->info("{$count} rows successfully imported.");
    }
}
