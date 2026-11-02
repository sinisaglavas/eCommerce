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
                $filename = $row['category_slug'].'-'.$position.'.'.$extension;
                $sourcePath = public_path('images/demo/products/real/'.$filename);
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
        }
    }
}
