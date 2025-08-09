<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-test 
                            {--fresh : Delete existing test users first}
                            {--admin-only : Create only admin user}
                            {--organizer-only : Create only organizer user}
                            {--user-only : Create only regular user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users for Great Ticket application (Admin, Organizer, User)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Creating test users for Great Ticket...');
        
        $emails = [
            'admin@greatticket.my',
            'organizer@greatticket.my', 
            'user@greatticket.my'
        ];

        // Delete existing test users if --fresh option is used
        if ($this->option('fresh')) {
            $this->info('🧹 Cleaning up existing test users...');
            User::whereIn('email', $emails)->delete();
        }

        $created = [];

        // Create Admin User
        if (!$this->option('organizer-only') && !$this->option('user-only')) {
            if (!User::where('email', 'admin@greatticket.my')->exists()) {
                $admin = User::create([
                    'name' => 'System Administrator',
                    'email' => 'admin@greatticket.my',
                    'password' => Hash::make('admin123'),
                    'role' => 'a',
                    'gender' => 'male',
                    'religion_id' => 1,
                    'dob' => '1990-01-01',
                    'contact' => '+60123456789',
                    'email_verified_at' => now(),
                ]);
                $created[] = ['Admin (a)', 'admin@greatticket.my', 'admin123'];
                $this->info('✅ Admin user created');
            } else {
                $this->warn('⚠️  Admin user already exists');
            }
        }

        // Create Organizer User
        if (!$this->option('admin-only') && !$this->option('user-only')) {
            if (!User::where('email', 'organizer@greatticket.my')->exists()) {
                $organizer = User::create([
                    'name' => 'Event Organizer Pro',
                    'email' => 'organizer@greatticket.my',
                    'password' => Hash::make('organizer123'),
                    'role' => 'o',
                    'gender' => 'female',
                    'religion_id' => 2,
                    'dob' => '1985-06-15',
                    'contact' => '+60123456788',
                    'email_verified_at' => now(),
                ]);

                // Create organizer profile
                $organizer->organizer()->create([
                    'address' => 'Petaling Jaya, Selangor, Malaysia',
                    'about' => 'Professional event organizer specializing in corporate events and entertainment shows.',
                ]);
                
                $created[] = ['Organizer (o)', 'organizer@greatticket.my', 'organizer123'];
                $this->info('✅ Organizer user created');
            } else {
                $this->warn('⚠️  Organizer user already exists');
            }
        }

        // Create Regular User
        if (!$this->option('admin-only') && !$this->option('organizer-only')) {
            if (!User::where('email', 'user@greatticket.my')->exists()) {
                $user = User::create([
                    'name' => 'Customer User',
                    'email' => 'user@greatticket.my',
                    'password' => Hash::make('user123'),
                    'role' => 'u',
                    'gender' => 'male',
                    'religion_id' => 3,
                    'dob' => '1995-12-20',
                    'contact' => '+60123456787',
                    'email_verified_at' => now(),
                ]);
                $created[] = ['User (u)', 'user@greatticket.my', 'user123'];
                $this->info('✅ Regular user created');
            } else {
                $this->warn('⚠️  Regular user already exists');
            }
        }

        if (!empty($created)) {
            $this->newLine();
            $this->info('🎉 Test users created successfully!');
            $this->newLine();
            $this->table(['Role', 'Email', 'Password'], $created);
            $this->newLine();
            $this->info('💡 Use these credentials to test different user roles.');
        } else {
            $this->info('ℹ️  No new users were created (all already exist).');
        }

        return Command::SUCCESS;
    }
}
