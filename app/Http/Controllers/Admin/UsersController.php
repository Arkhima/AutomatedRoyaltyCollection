<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(25);
        return view('Admin.users.index', compact('users'));
    }

    // Show single user
    public function show(User $user)
    {
        return view('Admin.users.show', compact('user'));
    }

    // Toggle user status (active/inactive)
    public function toggleStatus(User $user)
    {
        $user->status = !$user->status; // assumes boolean column
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User status updated successfully.');
    }
}