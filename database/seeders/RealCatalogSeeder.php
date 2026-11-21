<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RealCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call([CategorySeeder::class, BrandSeeder::class]);
            $categories = Category::query()->pluck('id', 'slug');
            $brands = Brand::query()->pluck('id', 'slug');
            $detailRelations = [
                'laptops' => 'laptopDetail',
                'smartphones' => 'smartphoneDetail',
                'tvs' => 'televisionDetail',
                'monitors' => 'monitorDetail',
                'tablets' => 'tabletDetail',
                'smartwatches' => 'smartwatchDetail',
            ];

            foreach (require __DIR__.'/data/products.php' as $row) {
                $attributes = Arr::except($row, ['sku', 'slug', 'category_slug', 'brand_slug', 'brand_name', 'brand_logo_path', 'details', 'images']);
                $attributes['category_id'] = $categories[$row['category_slug']];
                $attributes['brand_id'] = $brands[$row['brand_slug']];
                $existingProduct = Product::query()->where('sku', $row['sku'])->first();
                $attributes['stock_quantity'] = $existingProduct?->stock_quantity ?? $row['stock_quantity'];
                $attributes['published_at'] = $existingProduct?->published_at ?? now();

                $product = Product::query()->updateOrCreate(['sku' => $row['sku']], $attributes);
                $relation = $detailRelations[$row['category_slug']];
                $product->{$relation}()->updateOrCreate([], $row['details']);
            }

            $this->call(ProductImageSeeder::class);

            foreach (['Aster', 'Novara', 'Lumen', 'Orbis', 'Vela', 'Kairo'] as $name) {
                $slug = strtolower($name);
                Brand::query()->where('slug', $slug)->where('name', $name)
                    ->where('logo_path', '/images/demo/brands/'.$slug.'.svg')
                    ->whereDoesntHave('products')->get()->each->delete();
            }
        });
    }
}
