<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistsController extends Controller
{
    /**
     * List all artists
     */
    public function index()
    {
        $artists = Artist::orderBy('created_at', 'desc')->paginate(25);
        return view('Admin.artists.index', compact('artists'));
    }

    /**
     * Show form to create new artist
     */
    public function create()
    {
        return view('Admin.artists.create');
    }

    /**
     * Store new artist in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:artists,email',
            'genre' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'spotify_id' => 'nullable|string|max:100',
        ]);

        Artist::create($validated);

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist created successfully.');
    }

    /**
     * Show single artist
     */
    public function show(Artist $artist)
    {
        return view('Admin.artists.show', compact('artist'));
    }

    /**
     * Delete artist from database
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();
        
        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist deleted successfully.');
    }
}