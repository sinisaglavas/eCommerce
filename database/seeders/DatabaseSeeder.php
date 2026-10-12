<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\LaptopDetail;
use App\Models\MonitorDetail;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SmartphoneDetail;
use App\Models\SmartwatchDetail;
use App\Models\TabletDetail;
use App\Models\TelevisionDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        collect([
            ['Laptops', 'laptops', 'laptop', 'c-blue'], ['Smartphones', 'smartphones', 'phone', 'c-violet'], ['TVs', 'tvs', 'tv', 'c-slate'], ['Monitors', 'monitors', 'monitor', 'c-indigo'], ['Tablets', 'tablets', 'tablet', 'c-pink'], ['Smartwatches', 'smartwatches', 'watch', 'c-red'],
        ])->each(function (array $categoryData, int $index): void {
            $category = Category::factory()->create(['name' => $categoryData[0], 'slug' => $categoryData[1], 'icon' => $categoryData[2], 'color' => $categoryData[3], 'sort_order' => $index]);
            Product::factory()->count(10)->for($category)->recycle(Brand::factory()->count(4)->create())->create()->each(function (Product $product) use ($category): void {
                ProductImage::factory()->for($product)->create();
                match ($category->slug) {
                    'laptops' => LaptopDetail::forceCreate(['product_id' => $product->id, 'processor' => fake()->randomElement(['Intel Core Ultra 7', 'AMD Ryzen 7']), 'ram_gb' => fake()->randomElement([16, 32]), 'storage_gb' => fake()->randomElement([512, 1000]), 'display_size_inches' => 14.0]),
                    'smartphones' => SmartphoneDetail::forceCreate(['product_id' => $product->id, 'chipset' => 'Snapdragon 8', 'ram_gb' => 12, 'storage_gb' => 256, 'battery_mah' => 5000, 'has_5g' => true]),
                    'tvs' => TelevisionDetail::forceCreate(['product_id' => $product->id, 'screen_size_inches' => 55.0, 'display_technology' => 'OLED', 'resolution' => '4K', 'refresh_rate_hz' => 120]),
                    'monitors' => MonitorDetail::forceCreate(['product_id' => $product->id, 'screen_size_inches' => 27.0, 'panel_type' => 'IPS', 'resolution' => '4K', 'refresh_rate_hz' => 144]),
                    'tablets' => TabletDetail::forceCreate(['product_id' => $product->id, 'chipset' => 'M-series', 'ram_gb' => 8, 'storage_gb' => 256, 'display_size_inches' => 11.0, 'has_cellular' => true]),
                    'smartwatches' => SmartwatchDetail::forceCreate(['product_id' => $product->id, 'display_type' => 'AMOLED', 'case_size_mm' => 44, 'battery_hours' => 36, 'gps_enabled' => true, 'nfc_enabled' => true]),
                };
            });
        });
    }
}
