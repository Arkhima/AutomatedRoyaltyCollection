<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\Track;

class TestTrackSeeder extends Seeder
{
    public function run(): void
    {
        $artist = Artist::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test Artist']
        );

        Track::firstOrCreate([
            'title' => 'Test Track',
            'isrc_code' => 'XYZ123',
            'artist_id' => $artist->id,
            'rate_per_stream' => 1.0,
        ]);
    }
}
