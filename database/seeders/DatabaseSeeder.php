<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call the ReligionSeeder first to ensure religions are available
        $this->call([
            ReligionSeeder::class,
            CategorySeeder::class,
            PrivacyPolicySeeder::class,
            TermsAndConditionSeeder::class,
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'a',
            'religion_id' => 4,
            'dob' => '2000/01/01',
            'password' => Hash::make('password')
        ]);

        User::factory()->count(10)->create(); // Creates 10 normal users

        User::factory()->organizer()->count(10)->create()->each(function ($user) {
            // Create corresponding organizer details directly using the relationship
            $user->organizer()->create([
                'address' => fake()->address(),
                'about' => fake()->paragraph(),
            ]);
            Coupon::factory()->count(3)->create([
                'organizer_id' => $user->id, // Link the coupon to the current user (organizer)
            ]);
        });
        // Event::factory()->count(10)->create();
        

        Event::factory()
            ->count(10)
            ->create()
            ->each(function ($event) {
                Package::factory()->count(5)->create([
                    'event_id' => $event->id,
                ]);
            });
        // User::factory()->admin()->count(1)->create();
    }
}
