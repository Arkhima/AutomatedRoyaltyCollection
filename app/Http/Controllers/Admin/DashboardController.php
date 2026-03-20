<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Artist;
use App\Models\Track;

class DashboardController extends Controller
{
    // Show admin dashboard
    public function index()
    {
        $userCount = User::count();
        $artistCount = Artist::count();
        $trackCount = Track::count();

        // Return view with counts
        return view('Admin.dashboard', compact('userCount', 'artistCount', 'trackCount'));
    }
}