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
            for ($position = 1; $position <= 3; $position++) {
                $path = '/images/demo/products/'.$row['category_slug'].'-'.$position.'.svg';
                if (! is_file(public_path(ltrim($path, '/')))) {
                    throw new \RuntimeException('Missing demo image: '.$path);
                }
                ProductImage::query()->firstOrCreate([
                    'product_id' => $product->id, 'sort_order' => $position,
                ], [
                    'disk' => 'public', 'path' => $path,
                    'alt_text' => $product->name.' - demo illustration '.$position,
                    'is_primary' => $position === 1,
                ]);
            }
        }
    }
}
