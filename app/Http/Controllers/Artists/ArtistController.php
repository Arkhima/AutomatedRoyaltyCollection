<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::all();
        return view('Artists.ArtistSection.index', compact('artists'));
    }

    public function create()
    {
        return view('Artists.ArtistSection.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stage_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:artists,email',
            'phone' => 'nullable|string|max:20',
            'bank_details' => 'nullable|string',
            'mpesa_paybill' => 'nullable|string',
            'national_id' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('artist_profiles');
            $data['profile_image'] = $path;
        }

        Artist::create($data);

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist created successfully.');
    }

    public function edit($id)
    {
        $artist = Artist::findOrFail($id);
        return view('Artists.ArtistSection.edit', compact('artist'));
    }

    public function update(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stage_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:artists,email,' . $artist->id,
            'phone' => 'nullable|string|max:20',
            'bank_details' => 'nullable|string',
            'mpesa_paybill' => 'nullable|string',
            'national_id' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('artist_profiles');
            $data['profile_image'] = $path;
        }

        $artist->update($data);

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist updated successfully.');
    }

    public function destroy($id)
    {
        $artist = Artist::findOrFail($id);
        $artist->delete();

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist deleted successfully.');
    }
}
