<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(), 'brand_id' => Brand::factory(), 'name' => $name = fake()->words(3, true), 'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 99999), 'sku' => fake()->unique()->bothify('SKU-####-???'), 'description' => fake()->paragraph(), 'price' => fake()->randomFloat(2, 100, 2500), 'compare_at_price' => fake()->optional()->randomFloat(2, 2600, 3000), 'stock_quantity' => fake()->numberBetween(1, 100), 'is_active' => true,
        ];
    }
}
