<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use App\Models\Stream;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoyaltyReportExport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a grouped stream report by quarter for the authenticated artist.
     */
    public function index(Request $request)
    {
        $artistId = Auth::user()?->artist_id;

        if (!$artistId) {
            return redirect()->back()->with('error', 'Artist not found.');
        }

        $streams = Stream::with(['track.artist'])
            ->whereHas('track', fn($q) => $q->where('artist_id', $artistId))
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->as_of_date)->format('Y') . ' Q' . ceil(Carbon::parse($item->as_of_date)->month / 3));

        return view('Artists.Reports.index', [
            'streams' => $streams,
        ]);
    }

    /**
     * Export stream report to PDF for the authenticated artist.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getReportData();
        $pdf = Pdf::loadView('Artists.Reports.pdf', ['streams' => $data]);
        return $pdf->download('royalty_report.pdf');
    }

    /**
     * Export stream report to Excel for the authenticated artist.
     */
    public function exportExcel(Request $request)
    {
        $artistId = Auth::user()?->artist_id;

        if (!$artistId) {
            return redirect()->back()->with('error', 'Artist not found.');
        }

        return Excel::download(new RoyaltyReportExport($artistId), 'royalty_report.xlsx');
    }

    /**
     * Fetch grouped stream report data.
     */
    protected function getReportData()
    {
        $artistId = Auth::user()?->artist_id;

        if (!$artistId) {
            return collect(); // Return empty collection if not found
        }

        return Stream::with(['track.artist'])
            ->whereHas('track', fn($q) => $q->where('artist_id', $artistId))
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->as_of_date)->format('Y') . ' Q' . ceil(Carbon::parse($item->as_of_date)->month / 3));
    }
}
