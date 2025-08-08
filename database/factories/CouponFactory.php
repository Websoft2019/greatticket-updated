<?php 

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'), // Ensures unique code
            'cost' => $this->faker->randomFloat(2, 1, 100), // Random float between 1 and 100
            'organizer_id' => User::factory(),
            'expire_at' => $this->faker->dateTimeBetween('tomorrow', '+1 year')->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
