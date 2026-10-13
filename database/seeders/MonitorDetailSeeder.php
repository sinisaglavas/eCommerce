<?php

namespace Database\Seeders;

use App\Models\MonitorDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MonitorDetailSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            if ($row['category_slug'] !== 'monitors') {
                continue;
            }
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            MonitorDetail::query()->firstOrCreate(['product_id' => $product->id], $row['details']);
        }
    }
}
