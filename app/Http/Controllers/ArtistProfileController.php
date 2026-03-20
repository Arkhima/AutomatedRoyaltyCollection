<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Artist;
use Illuminate\Http\RedirectResponse;

class ArtistProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $artist = $user->artist;

        return view('artists.profile.show', compact('user', 'artist'));
    }

    /**
     * Show list of artists for the current user (moved from ArtistController)
     */
    public function index()
    {
        $user = Auth::user();
        $artists = Artist::where('user_id', $user->id)->get();
        return view('Artists.ArtistSection.index', compact('artists'));
    }

    public function create()
    {
        return view('Artists.ArtistSection.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stage_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:artists,email',
            'phone' => 'nullable|string|max:20',
            'bank_details' => 'nullable|string',
            'national_id' => 'nullable|string',
        ]);

        $data['user_id'] = $user->id;
        Artist::create($data);

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist created successfully.');
    }

    public function update(Request $request)
{
    $user = Auth::user();
    $artist = $user->artist;

    // ✅ If no artist profile is linked, create one
  if (!$artist) {
    $artist = \App\Models\Artist::where('email', $user->email)->first();

    if ($artist) {
        // Only link the artist if it isn't already owned by another user
        if ($artist->user_id === null) {
            $artist->user_id = $user->id;
            $artist->save();
        } else {
            // Artist exists and is owned by someone else — create a fresh artist for this user
            $artist = $user->artist()->create([
                'user_id'    => $user->id,
                'name'       => $user->name ?? 'Unnamed Artist',
                'email'      => $user->email ?? null,
                'stage_name' => $user->name ?? null,
                'phone'      => $user->phone_number ?? null,
                'profile_image' => $user->avatar_url ?? null,
            ]);
        }
    } else {
        $artist = $user->artist()->create([
            'user_id'    => $user->id,
            'name'       => $user->name ?? 'Unnamed Artist',
            'email'      => $user->email ?? null,
            'stage_name' => $user->name ?? null,
            'phone'      => $user->phone_number ?? null,
            'profile_image' => $user->avatar_url ?? null,
        ]);
    }
}

    // ✅ Validation
    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
        'artist_name'  => 'nullable|string|max:255',
        'phone'        => 'nullable|string|max:20',
        'bio'          => 'nullable|string',
        'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'instagram'    => 'nullable|string|max:255',
        'twitter'      => 'nullable|string|max:255',
    ]);

    // ✅ Handle avatar upload
    if ($request->hasFile('avatar')) {
        if ($user->avatar_url && Storage::exists(str_replace('storage/', 'public/', $user->avatar_url))) {
            Storage::delete(str_replace('storage/', 'public/', $user->avatar_url));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar_url = 'storage/' . $path;
    }

    // ✅ Update user
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->save();

    // ✅ Update artist
    $artist->name = $validated['artist_name'] ?? $artist->name ?? $user->name;
    $artist->stage_name = $validated['artist_name'] ?? $artist->stage_name ?? $user->name;
    $artist->phone = $validated['phone'] ?? $artist->phone;
    $artist->bio = $validated['bio'] ?? null;
    $artist->instagram = $validated['instagram'] ?? null;
    $artist->twitter = $validated['twitter'] ?? null;
    $artist->save();

    Auth::setUser($user);


    return redirect()->route('Artists.Profile.show')->with('success', 'Profile updated successfully.');
}

    public function edit($id)
    {
        $artist = Artist::findOrFail($id);

        if ($artist->user_id !== auth()->id()) {
            abort(403);
        }

        return view('Artists.ArtistSection.edit', compact('artist'));
    }

    public function destroy($id)
    {
        $artist = Artist::findOrFail($id);

        if ($artist->user_id !== auth()->id()) {
            abort(403);
        }

        $artist->delete();

        return redirect()->route('Artists.ArtistSection.index')
            ->with('success', 'Artist deleted successfully.');
    }


}
