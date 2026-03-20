<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Administrator',
                'email' => env('ADMIN_EMAIL', 'admin@arc-system.local'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'SecurePass123!')),
                'role' => 'super_admin',
                'is_active' => true,
            ],
            [
                'name' => 'Finance Manager',
                'email' => 'finance@arc-system.local',
                'password' => Hash::make('FinancePass123!'),
                'role' => 'manager',
                'is_active' => true,
            ],
            [
                'name' => 'Admin-Arkhima',
                'email' => 'ppots@arc.com',
                'password' => Hash::make('1234567890QWER!'),
                'role' => 'support',
                'is_active' => true,
            ],
        ];

        foreach ($admins as $admin) {
            Admin::firstOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}