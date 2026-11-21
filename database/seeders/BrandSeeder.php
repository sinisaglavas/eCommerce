<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            Brand::query()->firstOrCreate(['slug' => $row['brand_slug']], [
                'name' => $row['brand_name'],
                'logo_path' => $row['brand_logo_path'],
            ]);
        }
    }
}
