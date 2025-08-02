<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Artist;
use Illuminate\Support\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Grab one artist or create one
        $artist = Artist::first() ?? Artist::factory()->create([
            'name' => 'Demo Artist',
        ]);

        // Example payments
        $methods = ['mpesa', 'bank'];
        $statuses = ['pending', 'approved', 'rejected', 'completed'];

        foreach (range(1, 5) as $i) {
            Payment::create([
                'artist_id' => $artist->id,
                'amount' => rand(5000, 15000),
                'method' => $methods[array_rand($methods)],
                'status' => $statuses[array_rand($statuses)],
                'requested_at' => Carbon::now()->subDays(rand(1, 30)),
                'processed_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}
