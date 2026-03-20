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
        $user = auth()->user();
        $artist = $user->artist;

        if ($artist) {
            $tracks = Track::with('artist')->where('artist_id', $artist->id)->get();
        } else {
            $tracks = collect();
        }

        return view('Artists.Tracks.index', compact('tracks'));
    }

    public function create()
    {
        $user = auth()->user();
        $artist = $user->artist;

        // Only allow selecting the current user's artist (or none if not present)
        $artists = $artist ? collect([$artist]) : collect();
        return view('Artists.Tracks.create', compact('artists', 'artist'));

    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $artist = $user->artist;

        if (!$artist) {
            return redirect()->route('Artists.Tracks.index')
                ->withErrors(['artist' => 'You must create an artist profile before adding tracks.']);
        }

        // ensure artist_id is set to the authenticated user's artist
        $request->merge(['artist_id' => $artist->id]);

        $data = $request->validate([
            'title' => 'required|string',
            'isrc_code' => 'required|string|unique:tracks,isrc_code,NULL,id,artist_id,' . $artist->id,
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
        $user = auth()->user();
        $artist = $user->artist;

        // Prevent editing tracks that don't belong to the authenticated user's artist
        if (!$artist || $track->artist_id !== $artist->id) {
            abort(403);
        }

        $artists = collect([$artist]);
        return view('Artists.Tracks.edit', compact('track', 'artists', 'artist'));
    }

    public function update(Request $request, $id)
    {
        $track = Track::findOrFail($id);
        $user = auth()->user();
        $artist = $user->artist;

        if (!$artist || $track->artist_id !== $artist->id) {
            abort(403);
        }

        // ensure artist_id is not changed and remains the authenticated user's artist
        $request->merge(['artist_id' => $artist->id]);

        $data = $request->validate([
            'title' => 'required|string',
            'isrc_code' => 'required|string|unique:tracks,isrc_code,' . $track->id . ',id,artist_id,' . $artist->id,
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
        $user = auth()->user();
        $artist = $user->artist;

        if (!$artist || $track->artist_id !== $artist->id) {
            abort(403);
        }

        $track->delete();

        return redirect()->route('Artists.Tracks.index')
            ->with('success', 'Track deleted successfully.');
    }
}
