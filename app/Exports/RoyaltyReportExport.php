<?php

namespace App\Exports;

use App\Models\Stream;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class RoyaltyReportExport implements FromView
{
    protected ?int $artistId;

    public function __construct(?int $artistId = null)
    {
        $this->artistId = $artistId;
    }

    public function view(): View
    {
        $query = Stream::with(['track.artist']);

        if ($this->artistId) {
            $query->whereHas('track', fn($q) => $q->where('artist_id', $this->artistId));
        }

        $streams = $query->get()->groupBy(function ($item) {
            $date = Carbon::parse($item->as_of_date);
            return $date->format('Y') . ' Q' . ceil($date->month / 3);
        });

        return view('Artists.Reports.excel', [
            'streams' => $streams,
        ]);
    }
}
