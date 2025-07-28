<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index ()
    {
        return view('Artists.Dashboard.index');
    }
}
