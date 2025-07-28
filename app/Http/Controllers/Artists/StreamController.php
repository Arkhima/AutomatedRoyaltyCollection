<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stream;
use App\Services\ISRCMatchingService;

class StreamController extends Controller
{
    protected $isrcService;

    public function __construct(ISRCMatchingService $isrcService)
    {
        $this->isrcService = $isrcService;
    }

    public function index()
    {
        $streams = Stream::with(['track', 'artist'])
            ->orderBy('as_of_date', 'desc')
            ->get();

        return view('Artists.Streams.index', compact('streams'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'usage_file' => 'required|file|mimes:csv,txt',
        ]);

        $rows = $this->parseCsv($request->file('usage_file'));

        $count = $this->isrcService->importUsageData($rows);

        return back()->with('success', "{$count} rows imported successfully!");
    }

    private function parseCsv($file): array
    {
        $rows = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = array_combine($header, $data);
            }
            fclose($handle);
        }
        return $rows;
    }
}
