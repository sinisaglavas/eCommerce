<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['user_id' => User::factory(), 'number' => 'ORD-'.fake()->unique()->bothify('##########'), 'status' => 'pending', 'first_name' => fake()->firstName(), 'last_name' => fake()->lastName(), 'email' => fake()->safeEmail(), 'phone' => fake()->phoneNumber(), 'address_line_1' => fake()->streetAddress(), 'address_line_2' => null, 'city' => fake()->city(), 'postal_code' => fake()->postcode(), 'country' => fake()->country(), 'total' => fake()->randomFloat(2, 10, 1000)];
    }
}
