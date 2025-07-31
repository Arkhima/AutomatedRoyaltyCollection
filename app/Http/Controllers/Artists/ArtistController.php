<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;

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
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('artist_profiles', 'public');
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
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('profile_image')) {
            // Optionally delete old image if needed
            if ($artist->profile_image) {
                Storage::disk('public')->delete($artist->profile_image);
            }

            $data['profile_image'] = $request->file('profile_image')->store('artist_profiles', 'public');
        }

        $artist->update($data);

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist updated successfully.');
    }

    public function destroy($id)
    {
        $artist = Artist::findOrFail($id);

        if ($artist->profile_image) {
            Storage::disk('public')->delete($artist->profile_image);
        }

        $artist->delete();

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist deleted successfully.');
    }
}
