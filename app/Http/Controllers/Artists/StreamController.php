<?php

namespace App\Http\Controllers\Artists;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use App\Models\Stream;
use App\Models\Track;
use App\Services\ISRCMatchingService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StreamController extends Controller
{
    public function __construct(private ISRCMatchingService $isrcService)
    {
        $this->middleware('auth'); // Still enforce login
    }

    public function index(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return view('Artists.Streams.no-artist');
        }

        $artistId = $artist->id;

        $validated = $request->validate([
            'month'    => 'sometimes|integer|between:1,12',
            'year'     => 'sometimes|integer|digits:4|min:2020|max:' . (date('Y') + 1),
            'track_id' => 'sometimes|exists:tracks,id,artist_id,' . $artistId,
        ]);

        $month = $validated['month'] ?? Carbon::now()->month;
        $year = $validated['year'] ?? Carbon::now()->year;

        $query = Stream::with(['track', 'artist'])
            ->whereHas('track', fn($q) => $q->where('artist_id', $artistId))
            ->whereYear('as_of_date', $year)
            ->whereMonth('as_of_date', $month);

        if (!empty($validated['track_id'])) {
            $query->where('track_id', $validated['track_id']);
        }

        $streams = $query->orderBy('as_of_date', 'desc')
            ->paginate(25)
            ->appends($request->query());

        $tracks = Track::where('artist_id', $artistId)
            ->orderBy('title')
            ->get();

        return view('Artists.Streams.index', [
            'streams'        => $streams,
            'tracks'         => $tracks,
            'currentMonth'   => Carbon::create($year, $month, 1)->format('F Y'),
            'months'         => $this->getMonthsList(),
            'years'          => $this->getYearsList(),
            'selectedMonth'  => (int) $month,
            'selectedYear'   => (int) $year,
            'selectedTrack'  => $validated['track_id'] ?? null
        ]);
    }

    public function showUploadForm()
    {
        return view('Artists.Streams.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'usage_file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $rows = $this->parseCsv($request->file('usage_file'));
            $count = $this->isrcService->importUsageData($rows);

            return redirect()
                ->route('Artists.Streams.index')
                ->with('success', "Successfully imported {$count} stream records");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function generateDemo()
    {
        Artisan::call('usage:import', ['--count' => 50]);

        return back()->with('success', '50 demo stream records generated!');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stream_data_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['isrc_code', 'stream_count', 'as_of_date', 'artist_id (optional)']);
            fputcsv($file, ['USABC1234567', '1500', '2025-07-01', '1']);
            fputcsv($file, ['USXYZ9876543', '3200', '2025-07-02', '']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function parseCsv($file): array
    {
        $rows = [];
        $requiredHeaders = ['isrc_code', 'stream_count', 'as_of_date'];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $headers = array_map('strtolower', fgetcsv($handle));

            if (!$headers || count(array_intersect($requiredHeaders, $headers)) < count($requiredHeaders)) {
                fclose($handle);
                throw new \Exception("CSV must contain headers: " . implode(', ', $requiredHeaders));
            }

            while (($data = fgetcsv($handle)) !== false) {
                if ($data && count($data) === count($headers)) {
                    $row = array_combine($headers, $data);

                    if (!empty($row['as_of_date']) && !strtotime($row['as_of_date'])) {
                        throw new \Exception("Invalid date format in CSV: " . $row['as_of_date']);
                    }

                    $rows[] = [
                        'isrc'      => $row['isrc_code'] ?? '',
                        'streams'   => $row['stream_count'] ?? 0,
                        'date'      => $row['as_of_date'] ?? now()->format('Y-m-d'),
                        'artist_id' => $row['artist_id'] ?? null
                    ];
                }
            }

            fclose($handle);
        }

        return $rows;
    }

    private function getMonthsList(): array
    {
        return collect(range(1, 12))->mapWithKeys(fn($month) => [
            $month => Carbon::create()->month($month)->format('F')
        ])->toArray();
    }

    private function getYearsList(): array
    {
        $currentYear = Carbon::now()->year;

        return collect(range($currentYear - 2, $currentYear + 1))
            ->mapWithKeys(fn($year) => [$year => $year])
            ->toArray();
    }
}
