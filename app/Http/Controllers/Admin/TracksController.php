<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;

class TracksController extends Controller
{
    /**
     * Display a paginated list of tracks
     */
    public function index()
    {
        // Eager load the artist relation to avoid N+1 queries
        $tracks = Track::with('artist')
                       ->orderBy('created_at', 'desc')
                       ->paginate(25);

        return view('Admin.tracks.index', compact('tracks'));
    }

    /**
     * Show details for a single track
     */
    public function show($id)
    {
        $track = Track::with('artist')->findOrFail($id);

        return view('Admin.tracks.show', compact('track'));
    }
}