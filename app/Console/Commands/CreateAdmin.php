<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--name= : The name of the admin}
                            {--email= : The email of the admin}
                            {--password= : The password for the admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user interactively';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔═══════════════════════════════════════════╗');
        $this->info('║     ARC Admin User Creation Wizard        ║');
        $this->info('╚═══════════════════════════════════════════╝');
        $this->newLine();

        // Get or ask for name
        $name = $this->option('name');
        if (!$name) {
            $name = $this->ask('Enter admin full name');
        }

        // Get or ask for email with validation
        $email = $this->option('email');
        while (!$email || !$this->validateEmail($email)) {
            if ($email && !$this->validateEmail($email)) {
                $this->error('Invalid email format. Please try again.');
            }
            $email = $this->ask('Enter admin email address');
        }

        // Check if email already exists
        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists!');
            
            if ($this->confirm('Do you want to make this existing user an admin instead?', true)) {
                return $this->makeExistingUserAdmin($email);
            }
            
            return 1;
        }

        // Get or ask for password
        $password = $this->option('password');
        if (!$password) {
            $password = $this->secret('Enter admin password (min 8 characters)');
            
            // Confirm password
            $passwordConfirmation = $this->secret('Confirm admin password');
            
            while ($password !== $passwordConfirmation) {
                $this->error('Passwords do not match. Please try again.');
                $password = $this->secret('Enter admin password');
                $passwordConfirmation = $this->secret('Confirm admin password');
            }
            
            while (strlen($password) < 8) {
                $this->error('Password must be at least 8 characters.');
                $password = $this->secret('Enter admin password');
                $passwordConfirmation = $this->secret('Confirm admin password');
                
                while ($password !== $passwordConfirmation) {
                    $this->error('Passwords do not match. Please try again.');
                    $password = $this->secret('Enter admin password');
                    $passwordConfirmation = $this->secret('Confirm admin password');
                }
            }
        }

        // Show summary and confirm
        $this->newLine();
        $this->info('Admin User Summary:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $name],
                ['Email', $email],
                ['Password', str_repeat('•', strlen($password))],
                ['Role', 'Administrator'],
            ]
        );
        $this->newLine();

        if (!$this->confirm('Do you want to create this admin user?', true)) {
            $this->warn('Admin creation cancelled.');
            return 0;
        }

        // Create the admin user
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]);

            $this->newLine();
            $this->info('✅ Admin user created successfully!');
            $this->table(
                ['ID', 'Name', 'Email', 'Admin'],
                [[$user->id, $user->name, $user->email, $user->is_admin ? 'Yes' : 'No']]
            );
            
            // Optional: Create artist profile for admin if needed
            if ($this->confirm('Do you want to create an artist profile for this admin?', false)) {
                $this->call('make:artist', ['user_id' => $user->id]);
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Validate email format.
     */
    private function validateEmail($email)
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email'
        ]);

        return !$validator->fails();
    }

    /**
     * Make an existing user an admin.
     */
    private function makeExistingUserAdmin($email)
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        $this->table(
            ['ID', 'Name', 'Email', 'Current Admin'],
            [[$user->id, $user->name, $user->email, $user->is_admin ? 'Yes' : 'No']]
        );

        if ($this->confirm("Make {$user->name} an administrator?", true)) {
            $user->is_admin = true;
            $user->save();
            
            $this->info("✅ User {$user->email} is now an administrator!");
            return 0;
        }

        return 0;
    }
}