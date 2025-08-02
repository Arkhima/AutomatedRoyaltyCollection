<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RoyaltyReportExport implements FromView
{
    protected $royalties;

    public function __construct($royalties)
    {
        $this->royalties = $royalties;
    }

    public function view(): View
    {
        // Optionally group by quarter here if needed
        $groupedRoyalties = $this->royalties->groupBy(function ($item) {
            $date = \Carbon\Carbon::parse($item['as_of_date']);
            return $date->format('Y') . ' Q' . ceil($date->month / 3);
        });

        return view('Artists.Reports.excel', [
            'groupedRoyalties' => $groupedRoyalties,
        ]);
    }
}
