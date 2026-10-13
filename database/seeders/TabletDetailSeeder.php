<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\TabletDetail;
use Illuminate\Database\Seeder;

class TabletDetailSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            if ($row['category_slug'] !== 'tablets') {
                continue;
            }
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            TabletDetail::query()->firstOrCreate(['product_id' => $product->id], $row['details']);
        }
    }
}
