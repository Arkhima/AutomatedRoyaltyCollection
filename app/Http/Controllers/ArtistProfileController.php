<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtistProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('artists.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url && Storage::exists(str_replace('storage/', 'public/', $user->avatar_url))) {
                Storage::delete(str_replace('storage/', 'public/', $user->avatar_url));
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = 'storage/' . $path;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('Artists.Profile.show')->with('success', 'Profile updated successfully.');
    }
}
