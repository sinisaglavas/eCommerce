<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SmartphoneDetail;
use Illuminate\Database\Seeder;

class SmartphoneDetailSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            if ($row['category_slug'] !== 'smartphones') {
                continue;
            }
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            SmartphoneDetail::query()->firstOrCreate(['product_id' => $product->id], $row['details']);
        }
    }
}
