<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $extensions = [
            'laptops' => 'jpg',
            'smartphones' => 'png',
            'tvs' => 'jpg',
            'monitors' => 'jpeg',
            'tablets' => 'jpeg',
            'smartwatches' => 'jpeg',
        ];

        foreach (require __DIR__.'/data/products.php' as $row) {
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            for ($position = 1; $position <= 3; $position++) {
                $extension = $extensions[$row['category_slug']] ?? 'jpg';
                $path = '/images/demo/products/real/'.$row['category_slug'].'-'.$position.'.'.$extension;
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
        }
    }
}
