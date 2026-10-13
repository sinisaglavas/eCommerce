<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->pluck('id', 'slug');
        $brands = Brand::query()->pluck('id', 'slug');

        foreach (require __DIR__.'/data/products.php' as $row) {
            $row['category_id'] = $categories[$row['category_slug']];
            $row['brand_id'] = $brands[$row['brand_slug']];
            unset($row['category_slug'], $row['brand_slug'], $row['details']);
            $row['published_at'] = now();
            Product::query()->firstOrCreate(['sku' => $row['sku']], $row);
        }
    }
}
