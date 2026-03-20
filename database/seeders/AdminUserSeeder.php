<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');

        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->is_admin = true;
                $user->save();
                return;
            }
        }

        $user = User::first();
        if ($user) {
            $user->is_admin = true;
            $user->save();
            return;
        }

        // If no users exist, create an admin placeholder (password: password)
        User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
