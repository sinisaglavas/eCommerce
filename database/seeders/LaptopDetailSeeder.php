<?php

namespace Database\Seeders;

use App\Models\LaptopDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;

class LaptopDetailSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            if ($row['category_slug'] !== 'laptops') {
                continue;
            }
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            LaptopDetail::query()->firstOrCreate(['product_id' => $product->id], $row['details']);
        }
    }
}
