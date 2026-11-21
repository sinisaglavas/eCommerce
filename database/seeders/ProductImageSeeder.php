<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/products.php' as $row) {
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            foreach ($row['images'] as $index => $path) {
                $position = $index + 1;
                if (! is_file(public_path(ltrim($path, '/')))) {
                    throw new \RuntimeException('Missing seeded product image: '.$path);
                }
                ProductImage::query()->updateOrCreate([
                    'product_id' => $product->id, 'sort_order' => $position,
                ], [
                    'disk' => 'public', 'path' => $path,
                    'alt_text' => $product->name.' - product photo '.$position,
                    'is_primary' => $position === 1,
                ]);
            }
            $product->images()->whereNotIn('sort_order', [1, 2, 3])->delete();
        }
    }
}
