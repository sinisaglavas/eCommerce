<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Demo seeders may only run in local or testing environments.');
        }

        DB::transaction(function (): void {
            $this->call([
                UserSeeder::class,
                CategorySeeder::class,
                BrandSeeder::class,
                ProductSeeder::class,
                LaptopDetailSeeder::class,
                SmartphoneDetailSeeder::class,
                TelevisionDetailSeeder::class,
                MonitorDetailSeeder::class,
                TabletDetailSeeder::class,
                SmartwatchDetailSeeder::class,
                ProductImageSeeder::class,
                WishlistSeeder::class,
            ]);
        });
    }
}
