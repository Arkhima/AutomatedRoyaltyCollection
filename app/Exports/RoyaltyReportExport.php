<?php

namespace App\Exports;

use App\Models\Stream;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoyaltyReportExport implements FromView
{
    protected $artistId;

    public function __construct($artistId = null)
    {
        $this->artistId = $artistId;
    }

    public function view(): View
    {
        $query = Stream::with(['track.artist']);

        if ($this->artistId) {
            $query->whereHas('track', function ($q) {
                $q->where('artist_id', $this->artistId);
            });
        }

        $streams = $query->get()->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->as_of_date)->format('Y') . ' Q' . ceil(date('n', strtotime($item->as_of_date)) / 3);
        });

        return view('Artists.Reports.excel', ['streams' => $streams]);
    }
}
