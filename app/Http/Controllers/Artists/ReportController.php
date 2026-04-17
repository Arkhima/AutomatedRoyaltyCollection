<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use App\Services\RoyaltyCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    protected $royaltyService;

    public function __construct(RoyaltyCalculationService $royaltyService)
    {
        $this->royaltyService = $royaltyService;
    }

    /**
     * Show the royalties report grouped by quarter for the authenticated artist.
     */
    public function index(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return redirect()->route('Artists.Dashboard.index')
                ->with('error', 'Artist profile not found. Please complete your artist profile.');
        }

        $artistId = $artist->id;

        // Get the actual date range from streams for this artist
        $streamDateRange = \App\Models\Stream::whereHas('track', fn($q) => $q->where('artist_id', $artistId))
            ->selectRaw('MIN(as_of_date) as min_date, MAX(as_of_date) as max_date')
            ->first();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        try {
            // Use actual stream dates as defaults if available, otherwise fall back to current year
            $defaultStartDate = $streamDateRange->min_date 
                ? Carbon::parse($streamDateRange->min_date)->startOfDay() 
                : Carbon::now()->startOfYear()->startOfDay();
            $defaultEndDate = $streamDateRange->max_date 
                ? Carbon::parse($streamDateRange->max_date)->endOfDay() 
                : Carbon::now()->endOfDay();

            $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : $defaultStartDate;
            $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : $defaultEndDate;
        } catch (\Exception $e) {
            $startDate = Carbon::now()->startOfYear()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        // First, try to fetch saved royalties
        $royaltyData = $this->royaltyService->getRoyaltiesByArtistAndDateRange($artistId, $startDate->toDateString(), $endDate->toDateString());

        // If no saved royalties, calculate and save them, then fetch again
        if ($royaltyData->isEmpty()) {
            $this->royaltyService->calculateRoyalties($artistId, $startDate->toDateString(), $endDate->toDateString());
            $royaltyData = $this->royaltyService->getRoyaltiesByArtistAndDateRange($artistId, $startDate->toDateString(), $endDate->toDateString());
        }

        if ($royaltyData->isEmpty()) {
            return view('Artists.Reports.index', [
                'groupedRoyalties' => collect(),
                'totalStreams' => 0,
                'totalRoyalties' => 0,
                'averageRate' => 0,
                'chartData' => collect(),
                'topTracks' => collect(),
                'royaltyDistribution' => collect(),
                'streamDistribution' => collect(),
                'startDate' => $startDate->toDateString(),
                'endDate' => $endDate->toDateString(),
            ]);
        }

        $grouped = $royaltyData->groupBy(function ($item) {
            $date = Carbon::parse($item['as_of_date']);
            return $date->format('Y') . ' Q' . ceil($date->month / 3);
        });

        $totalStreams = $royaltyData->sum('total_streams');
        $totalRoyalties = $royaltyData->sum('total_royalty');
        $averageRate = $totalStreams > 0 ? $totalRoyalties / $totalStreams : 0;

        $chartData = $grouped->map(function ($items, $quarter) {
            return [
                'quarter' => $quarter,
                'total_royalties' => collect($items)->sum('total_royalty'),
                'total_streams' => collect($items)->sum('total_streams'),
            ];
        })->values();

        $topTracks = $royaltyData
            ->groupBy('track_id')
            ->map(fn($items) => [
                'track_title' => $items->first()['track_title'],
                'total_streams' => $items->sum('total_streams'),
            ])
            ->sortByDesc('total_streams')
            ->take(5)
            ->values();

        $royaltyDistribution = $royaltyData
            ->groupBy('track_id')
            ->map(function ($items) use ($totalRoyalties) {
                $totalRoyaltyByTrack = $items->sum('total_royalty');
                return [
                    'track_title' => $items->first()['track_title'],
                    'total_royalty' => $totalRoyaltyByTrack,
                    'percentage' => $totalRoyalties > 0 ? ($totalRoyaltyByTrack / $totalRoyalties) * 100 : 0,
                ];
            })
            ->sortByDesc('total_royalty')
            ->take(5)
            ->values();

        $streamDistribution = $royaltyData
            ->groupBy('track_id')
            ->map(fn($items) => [
                'track_title' => $items->first()['track_title'],
                'total_streams' => $items->sum('total_streams'),
            ])
            ->sortByDesc('total_streams')
            ->values();

        return view('Artists.Reports.index', [
            'groupedRoyalties' => $grouped,
            'totalStreams' => $totalStreams,
            'totalRoyalties' => $totalRoyalties,
            'averageRate' => $averageRate,
            'chartData' => $chartData,
            'topTracks' => $topTracks,
            'royaltyDistribution' => $royaltyDistribution,
            'streamDistribution' => $streamDistribution,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    /**
     * Generate report in view, PDF, or CSV based on the format parameter.
     */
    public function generate(Request $request, ?string $format = null)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return redirect()->route('Artists.Dashboard.index')
                ->with('error', 'Artist profile not found. Please complete your artist profile.');
        }

        $artistId = $artist->id;

        // Get the actual date range from streams for this artist
        $streamDateRange = \App\Models\Stream::whereHas('track', fn($q) => $q->where('artist_id', $artistId))
            ->selectRaw('MIN(as_of_date) as min_date, MAX(as_of_date) as max_date')
            ->first();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        try {
            // Use actual stream dates as defaults if available
            $defaultStartDate = $streamDateRange->min_date 
                ? Carbon::parse($streamDateRange->min_date)->startOfDay() 
                : Carbon::now()->startOfYear()->startOfDay();
            $defaultEndDate = $streamDateRange->max_date 
                ? Carbon::parse($streamDateRange->max_date)->endOfDay() 
                : Carbon::now()->endOfDay();

            $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : $defaultStartDate;
            $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : $defaultEndDate;
        } catch (\Exception $e) {
            $startDate = Carbon::now()->startOfYear()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        if ($format === 'pdf') {
            return $this->exportPdf($artistId, $startDate, $endDate);
        }

        if ($format === 'csv') {
            return $this->exportCsv($artistId, $startDate, $endDate);
        }

        // Default: show report view
        return $this->index($request);
    }

    /**
     * Export royalties report as PDF.
     */
    public function exportPdf(int $artistId, Carbon $startDate, Carbon $endDate)
    {
        $royalties = $this->royaltyService->calculateRoyalties($artistId, $startDate, $endDate);

        $streams = $royalties->map(function ($item) {
            return (object)[
                'track' => (object)[
                    'title' => $item['track_title'],
                    'isrc_code' => $item['isrc_code'] ?? 'N/A',
                    'artist' => (object)['name' => $item['artist_name']],
                    'rate_per_stream' => $item['rate_per_stream'] ?? 0,
                ],
                'stream_count' => $item['total_streams'],
                'as_of_date' => $item['as_of_date'] ?? now(),
            ];
        })->groupBy(function ($item) {
            $date = Carbon::parse($item->as_of_date);
            return $date->format('Y') . ' Q' . ceil($date->month / 3);
        });

        $pdf = PDF::loadView('Artists.Reports.pdf', [
            'streams' => $streams,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);

        return $pdf->download('royalty-report.pdf');
    }

    /**
     * Export royalties report as CSV (REPLACES EXCEL).
     */
    public function exportCsv(int $artistId, Carbon $startDate, Carbon $endDate)
    {
        $royaltyData = $this->royaltyService->getRoyaltiesByArtistAndDateRange(
            $artistId, 
            $startDate->toDateString(), 
            $endDate->toDateString()
        );

        // If no data, calculate first
        if ($royaltyData->isEmpty()) {
            $this->royaltyService->calculateRoyalties($artistId, $startDate->toDateString(), $endDate->toDateString());
            $royaltyData = $this->royaltyService->getRoyaltiesByArtistAndDateRange(
                $artistId, 
                $startDate->toDateString(), 
                $endDate->toDateString()
            );
        }

        $filename = "royalty-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($royaltyData, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Add report header info
            fputcsv($file, ['Royalty Report']);
            fputcsv($file, ['Period:', $startDate->format('M j, Y') . ' - ' . $endDate->format('M j, Y')]);
            fputcsv($file, ['Generated:', now()->format('M j, Y H:i:s')]);
            fputcsv($file, []); // Empty row
            
            // Column headers
            fputcsv($file, [
                'Quarter',
                'Track Title',
                'ISRC Code',
                'Artist Name',
                'Total Streams',
                'Rate per Stream (Ksh)',
                'Total Royalty (Ksh)',
                'Date'
            ]);

            foreach ($royaltyData as $item) {
                $date = Carbon::parse($item['as_of_date']);
                $quarter = $date->format('Y') . ' Q' . ceil($date->month / 3);
                
                fputcsv($file, [
                    $quarter,
                    $item['track_title'],
                    $item['isrc_code'] ?? 'N/A',
                    $item['artist_name'],
                    $item['total_streams'],
                    number_format($item['rate_per_stream'], 4),
                    number_format($item['total_royalty'], 2),
                    $date->format('M j, Y')
                ]);
            }

            // Add totals row
            fputcsv($file, []);
            fputcsv($file, [
                'TOTAL',
                '',
                '',
                '',
                $royaltyData->sum('total_streams'),
                '',
                number_format($royaltyData->sum('total_royalty'), 2),
                ''
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}