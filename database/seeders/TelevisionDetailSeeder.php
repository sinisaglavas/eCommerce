<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\TelevisionDetail;
use Illuminate\Database\Seeder;

class TelevisionDetailSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            if ($row['category_slug'] !== 'tvs') {
                continue;
            }
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            TelevisionDetail::query()->firstOrCreate(['product_id' => $product->id], $row['details']);
        }
    }
}
