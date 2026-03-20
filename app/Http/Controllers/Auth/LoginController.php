<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user has an artist profile (looks in artists table for user_id = $user->id)
        if (!$user->artist) {
            // Redirect to create artist profile
            return redirect()->route('Artists.Profile.create')
                ->with('info', 'Please complete your artist profile to continue.');
        }

        return redirect()->intended('/dashboard')
            ->with('success', 'Logged in successfully!');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->onlyInput('email');
}
    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('User logged out', ['user_id' => $user->id ?? 'unknown']);
        
        return redirect('/login')
            ->with('success', 'You have been logged out successfully.');
    }
}