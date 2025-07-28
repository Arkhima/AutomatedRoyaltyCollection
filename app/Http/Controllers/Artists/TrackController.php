<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;
use App\Models\Artist;

class TrackController extends Controller
{
    public function index()
    {
        $tracks = Track::with('artist')->get();
        return view('Artists.Tracks.index', compact('tracks'));
    }

    public function create()
    {
        $artists = Artist::all();
        return view('Artists.Tracks.create', compact('artists'));

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'isrc_code' => 'nullable|string',
            'artist_id' => 'required|exists:artists,id',
            'rate_per_stream' => 'required|numeric',
            'file_path' => 'nullable|file|mimes:mp3,wav,flac'
        ]);

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('tracks');
            $data['file_path'] = $path;
        }

        Track::create($data);

        return redirect()->route('Artists.Tracks.index')
            ->with('success', 'Track created successfully.');
    }

    public function edit($id)
    {
        $track = Track::findOrFail($id);
        $artists = Artist::all();
        return view('Artists.Tracks.edit', compact('track', 'artists'));
    }

    public function update(Request $request, $id)
    {
        $track = Track::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string',
            'isrc_code' => 'nullable|string',
            'artist_id' => 'required|exists:artists,id',
            'rate_per_stream' => 'required|numeric',
            'file_path' => 'nullable|file|mimes:mp3,wav,flac'
        ]);

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('tracks');
            $data['file_path'] = $path;
        }

        $track->update($data);

        return redirect()->route('Artists.Tracks.index')
            ->with('success', 'Track updated successfully.');
    }

    public function destroy($id)
    {
        $track = Track::findOrFail($id);
        $track->delete();

        return redirect()->route('Artists.Tracks.index')
            ->with('success', 'Track deleted successfully.');
    }
}
