<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Aster', 'Novara', 'Lumen', 'Orbis', 'Vela', 'Kairo'] as $name) {
            $slug = strtolower($name);
            Brand::query()->firstOrCreate(['slug' => $slug], ['name' => $name, 'logo_path' => '/images/demo/brands/'.$slug.'.svg']);
        }
    }
}
