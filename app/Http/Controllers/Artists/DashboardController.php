<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the artist dashboard.
     */
    public function index()
    {
        return view('Artists.Dashboard.index');
    }
}
