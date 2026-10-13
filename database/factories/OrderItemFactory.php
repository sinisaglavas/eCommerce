<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['order_id' => Order::factory(), 'product_id' => Product::factory(), 'product_name' => fake()->sentence(3), 'product_sku' => fake()->unique()->bothify('SKU-####'), 'unit_price' => fake()->randomFloat(2, 10, 1000), 'quantity' => fake()->numberBetween(1, 3), 'line_total' => fake()->randomFloat(2, 10, 3000)];
    }
}
