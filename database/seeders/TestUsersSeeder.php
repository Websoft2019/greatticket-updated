<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Seed the database with test users only.
     * This is useful for development and testing.
     */
    public function run(): void
    {
        // Clear existing test users first (optional)
        User::whereIn('email', [
            'admin@greatticket.my',
            'organizer@greatticket.my', 
            'user@greatticket.my'
        ])->delete();

        // Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@greatticket.my',
            'password' => Hash::make('admin123'),
            'role' => 'a', // admin
            'gender' => 'male',
            'religion_id' => 1,
            'dob' => '1990-01-01',
            'contact' => '+60123456789',
            'email_verified_at' => now(),
        ]);

        // Create Organizer User
        $organizer = User::create([
            'name' => 'Event Organizer Pro',
            'email' => 'organizer@greatticket.my', 
            'password' => Hash::make('organizer123'),
            'role' => 'o', // organizer
            'gender' => 'female',
            'religion_id' => 2,
            'dob' => '1985-06-15',
            'contact' => '+60123456788',
            'email_verified_at' => now(),
        ]);

        // Create organizer profile
        if (!$organizer->organizer) {
            $organizer->organizer()->create([
                'address' => 'Petaling Jaya, Selangor, Malaysia',
                'about' => 'Professional event organizer specializing in corporate events, wedding ceremonies, and entertainment shows. Over 10 years of experience in the industry.',
            ]);
        }

        // Create Regular User
        $user = User::create([
            'name' => 'Customer User',
            'email' => 'user@greatticket.my',
            'password' => Hash::make('user123'),
            'role' => 'u', // user
            'gender' => 'male',
            'religion_id' => 3,
            'dob' => '1995-12-20',
            'contact' => '+60123456787',
            'email_verified_at' => now(),
        ]);

        // Output results
        $this->command->newLine();
        $this->command->info('🎉 Test users created successfully!');
        $this->command->newLine();
        $this->command->info('Login Credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin (a)', 'admin@greatticket.my', 'admin123'],
                ['Organizer (o)', 'organizer@greatticket.my', 'organizer123'],
                ['User (u)', 'user@greatticket.my', 'user123'],
            ]
        );
        $this->command->newLine();
        $this->command->info('💡 Use these credentials to test different user roles in your application.');
    }
}
