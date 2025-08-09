<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@greatticket.my',
            'password' => Hash::make('admin123'),
            'role' => 'a', // admin
            'gender' => 'male',
            'religion_id' => 1, // Assuming religion ID 1 exists
            'dob' => '1990-01-01',
            'contact' => '+60123456789',
            'email_verified_at' => now(),
        ]);

        // Create Organizer User
        $organizer = User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@greatticket.my', 
            'password' => Hash::make('organizer123'),
            'role' => 'o', // organizer
            'gender' => 'female',
            'religion_id' => 2, // Assuming religion ID 2 exists
            'dob' => '1985-06-15',
            'contact' => '+60123456788',
            'email_verified_at' => now(),
        ]);

        // Create organizer profile for the organizer user
        $organizer->organizer()->create([
            'address' => 'Kuala Lumpur, Malaysia',
            'about' => 'Professional event organizer with 10+ years of experience in organizing corporate events, concerts, and conferences.',
        ]);

        // Create Regular User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@greatticket.my',
            'password' => Hash::make('user123'),
            'role' => 'u', // user
            'gender' => 'male',
            'religion_id' => 3, // Assuming religion ID 3 exists
            'dob' => '1995-12-20',
            'contact' => '+60123456787',
            'email_verified_at' => now(),
        ]);

        // Output seeded user information
        $this->command->info('✅ Users seeded successfully:');
        $this->command->info('   📧 Admin: admin@greatticket.my (password: admin123)');
        $this->command->info('   📧 Organizer: organizer@greatticket.my (password: organizer123)');
        $this->command->info('   📧 User: user@greatticket.my (password: user123)');
    }
}
