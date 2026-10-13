<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['ana@voltio.test', 'marko@voltio.test', 'jelena@voltio.test'] as $index => $email) {
            $user = User::query()->where('email', $email)->firstOrFail();
            $rows = array_slice(require __DIR__.'/data/products.php', $index * 14, 3);
            foreach ($rows as $row) {
                $product = Product::query()->where('sku', $row['sku'])->firstOrFail();
                Wishlist::query()->firstOrCreate(['user_id' => $user->id, 'product_id' => $product->id]);
            }
        }
    }
}
