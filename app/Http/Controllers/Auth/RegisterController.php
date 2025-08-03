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
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'max:255', 'unique:users'],
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[A-Z]/',      // at least one uppercase
            'regex:/[a-z]/',      // at least one lowercase
            'regex:/[0-9]/',      // at least one number
            'regex:/[@$!%*#?&]/', // at least one special char
            'confirmed'
        ]
    ]);

    // Step 1: Create the user (without artist_id yet)
    $user = User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    // Step 2: Create the artist and link it to the user
    $artist = Artist::create([
        'user_id' => $user->id, // this is the important link
        'name'    => $user->name,
        'email'   => $user->email,
    ]);

    // Step 3 (optional): store artist_id back into user, if you're using it
    $user->artist_id = $artist->id;
    $user->save();

    // Step 4: Log in and redirect
    Auth::login($user);

    return redirect('/dashboard')->with('success', 'Account created and artist profile set up.');
}
}