<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Artist;

class MakeArtist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:artist {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an artist profile for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the user ID from the command argument
        $userId = $this->argument('user_id');
        
        // Find the user in the database
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {
            $this->error('User not found!');
            return 1; // Return error code
        }

        // Check if user already has an artist profile
        if ($user->artist) {
            $this->warn('User already has an artist profile!');
            $this->table(
                ['Artist ID', 'Name', 'Stage Name', 'Email'],
                [[
                    $user->artist->id,
                    $user->artist->name,
                    $user->artist->stage_name ?? 'N/A',
                    $user->artist->email
                ]]
            );
            return 0; // Return success code
        }

        // Display what we're about to create
        $this->info('Creating artist profile for:');
        $this->table(
            ['User ID', 'Name', 'Email'],
            [[$user->id, $user->name, $user->email]]
        );

        // Ask for confirmation
        if (!$this->confirm('Do you want to continue?', true)) {
            $this->warn('Artist profile creation cancelled.');
            return 0;
        }

        // Create the artist profile
        try {
            $artist = Artist::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'stage_name' => $user->name, // Use user's name as stage name
                'email' => $user->email,
                'phone' => null, // Can be updated later
                'bio' => null, // Can be updated later
            ]);

            // Show success message with table
            $this->newLine();
            $this->info('✅ Artist profile created successfully!');
            $this->table(
                ['Artist ID', 'Name', 'Stage Name', 'Email', 'User ID'],
                [[
                    $artist->id,
                    $artist->name,
                    $artist->stage_name,
                    $artist->email,
                    $artist->user_id
                ]]
            );

            return 0; // Return success code

        } catch (\Exception $e) {
            $this->error('Failed to create artist profile: ' . $e->getMessage());
            return 1; // Return error code
        }
    }
}