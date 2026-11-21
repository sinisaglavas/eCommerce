<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $disk = Storage::disk('public');
        foreach (require __DIR__.'/data/products.php' as $row) {
            $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
            foreach ($row['images'] as $index => $path) {
                $position = $index + 1;
                $filename = basename($path);
                $sourcePath = public_path(ltrim($path, '/'));
                $storagePath = 'products/demo/'.$filename;

                if (! is_file($sourcePath)) {
                    throw new \RuntimeException('Missing seeded product image: '.$sourcePath);
                }

                if (! $disk->exists($storagePath) && ! $disk->put($storagePath, file_get_contents($sourcePath))) {
                    throw new \RuntimeException('Unable to store seeded product image: '.$storagePath);
                }

                ProductImage::query()->updateOrCreate([
                    'product_id' => $product->id, 'sort_order' => $position,
                ], [
                    'disk' => 'public', 'path' => '/storage/'.$storagePath,
                    'alt_text' => $product->name.' - product photo '.$position,
                    'is_primary' => $position === 1,
                ]);
            }
            $product->images()->whereNotIn('sort_order', [1, 2, 3])->delete();
        }
    }
}
