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
        $this->middleware('auth');
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

    /**
     * Generate realistic monthly stream data for the entire year
     */
    public function generateDemo(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return back()->with('error', 'You must have an artist profile to generate demo streams.');
        }

        $tracks = $artist->tracks;

        if ($tracks->count() === 0) {
            return back()->with('error', 'Please upload at least one track before generating demo streams.');
        }

        $year = $request->input('year', Carbon::now()->year);
        $streamsGenerated = 0;

        // Generate data for each month of the year
        foreach (range(1, 12) as $month) {
            // Pick a random track from the artist's catalog
            $track = $tracks->random();
            
            // Generate realistic stream count with some randomness
            $baseStreams = rand(1000, 5000);
            
            // Add seasonal trends
            $seasonalMultiplier = 1.0;
            if (in_array($month, [5, 6, 7, 8])) {
                $seasonalMultiplier = 1.3; // Summer boost
            } elseif ($month == 12) {
                $seasonalMultiplier = 1.5; // Holiday boost
            } elseif (in_array($month, [1, 2])) {
                $seasonalMultiplier = 0.8; // Post-holiday dip
            }
            
            // Add growth trend
            $growthMultiplier = 1 + (($month - 1) * 0.05);
            
            $streamCount = round($baseStreams * $seasonalMultiplier * $growthMultiplier);
            
            // Check if record already exists for this track/month/year
            $exists = Stream::where('track_id', $track->id)
                ->whereYear('as_of_date', $year)
                ->whereMonth('as_of_date', $month)
                ->exists();

            if (!$exists) {
                Stream::create([
                    'track_id' => $track->id,
                    'artist_id' => $artist->id,
                    'stream_count' => $streamCount,
                    'as_of_date' => Carbon::create($year, $month, 15),
                    'source' => 'demo',
                ]);
                
                $streamsGenerated++;
            }
        }

        // Redirect to the first month that was generated so they can see the data
        return redirect()
            ->route('Artists.Streams.index', [
                'month' => 1,  // January
                'year' => $year
            ])
            ->with('success', "Generated {$streamsGenerated} months of realistic streaming data for year {$year}!");
    }

    /**
     * Generate demo data for a specific track across all months
     */
    public function generateDemoForTrack(Request $request, Track $track)
    {
        $artist = Auth::user()->artist;

        if (!$artist || $track->artist_id !== $artist->id) {
            return back()->with('error', 'Unauthorized access to this track.');
        }

        $year = $request->input('year', Carbon::now()->year);
        $streamsGenerated = 0;

        foreach (range(1, 12) as $month) {
            // Skip if already exists
            $exists = Stream::where('track_id', $track->id)
                ->whereYear('as_of_date', $year)
                ->whereMonth('as_of_date', $month)
                ->exists();

            if (!$exists) {
                $isHitMonth = rand(1, 100) > 70;
                $baseStreams = $isHitMonth ? rand(5000, 15000) : rand(500, 3000);
                
                Stream::create([
                    'track_id' => $track->id,
                    'artist_id' => $artist->id,
                    'stream_count' => $baseStreams,
                    'as_of_date' => Carbon::create($year, $month, rand(1, 28)),
                    'source' => 'demo_track',
                ]);
                
                $streamsGenerated++;
            }
        }

        return redirect()
            ->route('Artists.Streams.index', [
                'track_id' => $track->id,
                'year' => $year
            ])
            ->with('success', "Generated {$streamsGenerated} months of data for '{$track->title}'!");
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