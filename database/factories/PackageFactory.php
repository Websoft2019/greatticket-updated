<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cost = $this->faker->randomFloat(2, 10, 1000);
        return [
            'slug' => $this->faker->slug,
            'title' => $this->faker->sentence,
            'cost' => $cost,
            'actual_cost' => $cost,
            'photo' => $this->faker->imageUrl(), // Random image URL
            'description' => $this->faker->paragraph(),
            'event_id' => Event::factory(), // Create a related event
            'capacity' => $this->faker->numberBetween(50, 100), // Random capacity between 50 and 500
            'consumed_seat' => $this->faker->numberBetween(0, 50), // Random consumed seats
        ];
    }
}
