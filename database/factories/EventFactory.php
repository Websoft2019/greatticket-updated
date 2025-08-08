<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'title' => $this->faker->sentence,
            'date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'time' => $this->faker->time,
            'vennue' => $this->faker->address,
            'primary_photo' => $this->faker->imageUrl,
            'seat_view' => $this->faker->imageUrl,
            'highlight' => $this->faker->text(200),
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'description' => $this->faker->paragraph,
            // 'organizer_id' => \App\Models\User::factory()->organizer(), // Use the organizer state
            'organizer_id' => User::where('role', 'o')->inRandomOrder()->first()->id, // Reference an existing organizer'
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
