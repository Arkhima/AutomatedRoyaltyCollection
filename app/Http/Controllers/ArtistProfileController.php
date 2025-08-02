<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtistProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $artist = $user->artist;

        return view('artists.profile.show', compact('user', 'artist'));
    }

    public function update(Request $request)
{
    $user = Auth::user();
    $artist = $user->artist;

    // ✅ If no artist profile is linked, create one
  if (!$artist) {
    $artist = \App\Models\Artist::where('email', $user->email)->first();

    if (!$artist) {
        $artist = $user->artist()->create([
            'user_id'    => $user->id,
            'name'       => $user->name ?? 'Unnamed Artist',
            'email'      => $user->email ?? null,
            'stage_name' => $user->name ?? null,
            'phone'      => $user->phone_number ?? null,
            'profile_image' => $user->avatar_url ?? null,
        ]);
    } else {
        // Link existing artist to user if not linked
        if ($artist->user_id !== $user->id) {
            $artist->user_id = $user->id;
            $artist->save();
        }
    }
}

    // ✅ Validation
    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
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
    $artist->bio = $validated['bio'] ?? null;
    $artist->instagram = $validated['instagram'] ?? null;
    $artist->twitter = $validated['twitter'] ?? null;
    $artist->save();

    return redirect()->route('Artists.Profile.show')->with('success', 'Profile updated successfully.');
}

}
