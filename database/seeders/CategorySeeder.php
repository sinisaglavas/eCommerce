<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'icon' => 'laptop',
                'color' => 'c-blue',
                'description' => 'Laptops for work, study, and creative projects.',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'icon' => 'phone',
                'color' => 'c-violet',
                'description' => 'Phones with long battery life and clear displays.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'TVs',
                'slug' => 'tvs',
                'icon' => 'tv',
                'color' => 'c-slate',
                'description' => 'TVs for movies, sports, and home entertainment.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Monitors',
                'slug' => 'monitors',
                'icon' => 'monitor',
                'color' => 'c-indigo',
                'description' => 'Monitors for office work, design, and gaming.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Tablets',
                'slug' => 'tablets',
                'icon' => 'tablet',
                'color' => 'c-pink',
                'description' => 'Tablets for learning, drawing, and entertainment.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Smartwatches',
                'slug' => 'smartwatches',
                'icon' => 'watch',
                'color' => 'c-red',
                'description' => 'Smartwatches for everyday activities.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ] as $category) {
            Category::query()->firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
