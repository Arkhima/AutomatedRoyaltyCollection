<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        // Completely separate view, no user references
        return view('admin.auth.login');
    }

    /**
     * Handle admin login using separate admin guard.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // ✅ Use 'admin' guard specifically - completely separate from users
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            // ✅ Check if admin account is active
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                
                throw ValidationException::withMessages([
                    'email' => 'This admin account has been deactivated.',
                ]);
            }

            // ✅ Update last login timestamp
            $admin->update(['last_login_at' => now()]);

            Log::info('Admin logged in', [
                'admin_id' => $admin->id,
                'email' => $admin->email,
                'ip' => $request->ip(),
                'role' => $admin->role,
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Welcome back, {$admin->name}!");
        }

        Log::warning('Failed admin login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Log the admin out using admin guard.
     */
    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // ✅ Use admin guard for logout
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Admin logged out', [
            'admin_id' => $admin?->id,
            'email' => $admin?->email,
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}