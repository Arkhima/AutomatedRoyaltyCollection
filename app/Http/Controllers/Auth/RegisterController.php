<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users'],
            'password'    => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special char
                'confirmed'
            ],
            'artist_name' => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:20'],
        ]);

        // Create the user (NO artist_id field needed)
        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'phone_number' => $validated['phone'],
        ]);

        // Check if there's an unclaimed artist with this email
        $existingArtist = Artist::where('email', $user->email)
                                 ->whereNull('user_id')
                                 ->first();

        if ($existingArtist) {
            // Claim this artist
            $existingArtist->update([
                'user_id'    => $user->id,
                'name'       => $validated['artist_name'],
                'stage_name' => $validated['artist_name'],
                'phone'      => $validated['phone'],
            ]);
        } else {
            // Create a new artist for this user
            Artist::create([
                'user_id'    => $user->id,
                'name'       => $validated['artist_name'],
                'stage_name' => $validated['artist_name'],
                'email'      => $user->email,  // Use the user's email
                'phone'      => $validated['phone'],
            ]);
        }

        // Log the user in
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Account created and artist profile set up.');
    }
}