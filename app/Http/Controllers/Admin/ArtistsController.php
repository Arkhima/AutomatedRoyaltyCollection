<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistsController extends Controller
{
    // List all artists
    public function index()
    {
        $artists = Artist::orderBy('created_at', 'desc')->paginate(25);
        return view('Admin.artists.index', compact('artists'));
    }

    // Show single artist
    public function show(Artist $artist)
    {
        return view('Admin.artists.show', compact('artist'));
    }
}