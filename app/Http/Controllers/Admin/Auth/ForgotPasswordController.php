<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send password reset link to admin email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Use 'admins' broker from config/auth.php
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Admin password reset link sent', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()->with('success', 'Password reset link sent to your email.');
        }

        Log::warning('Failed admin password reset attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'status' => $status,
        ]);

        return back()->withErrors([
            'email' => 'We could not find an admin with that email address.',
        ]);
    }
}