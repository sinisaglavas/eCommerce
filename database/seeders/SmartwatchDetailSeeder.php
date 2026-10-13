<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SmartwatchDetail;
use Illuminate\Database\Seeder;

class SmartwatchDetailSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            if ($row['category_slug'] !== 'smartwatches') {
                continue;
            }
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            SmartwatchDetail::query()->firstOrCreate(['product_id' => $product->id], $row['details']);
        }
    }
}
