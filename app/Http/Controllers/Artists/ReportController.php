<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Stream;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoyaltyReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $artistId = $request->input('artist_id');

        $query = Stream::with(['track.artist']);

        if ($artistId) {
            $query->whereHas('track', function ($q) use ($artistId) {
                $q->where('artist_id', $artistId);
            });
        }

        $streams = $query->get()->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->as_of_date)->format('Y') . ' Q' . ceil(date('n', strtotime($item->as_of_date)) / 3);
        });

        return view('Artists.Reports.index', [
            'streams' => $streams,
            'artists' => Artist::all(),
            'selectedArtist' => $artistId
        ]);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('Artists.Reports.pdf', ['streams' => $data]);
        return $pdf->download('royalty_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new RoyaltyReportExport($request->artist_id), 'royalty_report.xlsx');
    }

    protected function getReportData(Request $request)
    {
        $artistId = $request->input('artist_id');

        $query = Stream::with(['track.artist']);

        if ($artistId) {
            $query->whereHas('track', function ($q) use ($artistId) {
                $q->where('artist_id', $artistId);
            });
        }

        return $query->get()->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->as_of_date)->format('Y') . ' Q' . ceil(date('n', strtotime($item->as_of_date)) / 3);
        });
    }
}
