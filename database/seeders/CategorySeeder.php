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
                'description' => 'Prenosivi računari za posao, studije i kreativni rad.',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'icon' => 'phone',
                'color' => 'c-violet',
                'description' => 'Telefoni sa dugotrajnom baterijom i jasnim ekranom.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'TVs',
                'slug' => 'tvs',
                'icon' => 'tv',
                'color' => 'c-slate',
                'description' => 'Televizori za filmove, sport i kućnu zabavu.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Monitors',
                'slug' => 'monitors',
                'icon' => 'monitor',
                'color' => 'c-indigo',
                'description' => 'Monitori za kancelariju, dizajn i igre.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Tablets',
                'slug' => 'tablets',
                'icon' => 'tablet',
                'color' => 'c-pink',
                'description' => 'Tableti za učenje, crtanje i zabavu.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Smartwatches',
                'slug' => 'smartwatches',
                'icon' => 'watch',
                'color' => 'c-red',
                'description' => 'Pametni satovi za svakodnevne aktivnosti.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ] as $category) {
            Category::query()->firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
