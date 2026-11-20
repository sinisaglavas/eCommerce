<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CatalogCache;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class RedisCatalogCacheTest extends TestCase
{
    use DatabaseMigrations;

    public function test_catalog_survives_a_real_redis_round_trip_and_refreshes_after_changes(): void
    {
        config([
            'cache.default' => 'redis',
            'cache.prefix' => 'voltio-test-'.Str::uuid().'-',
        ]);
        Cache::purge('redis');
        $catalog = app(CatalogCache::class);

        try {
            $product = Product::factory()->create(['name' => 'Redis deal', 'price' => 100, 'compare_at_price' => 150]);
            ProductImage::factory()->for($product)->create(['path' => '/images/redis-deal.jpg']);
            $catalog->categories();
            $catalog->deals();
            $catalog->brands();

            DB::enableQueryLog();
            $categories = $catalog->categories();
            $deal = $catalog->deals()->first();
            $brands = $catalog->brands();
            $queries = DB::getQueryLog();
            DB::disableQueryLog();

            $this->assertSame([], $queries);
            $this->assertSame('Redis deal', $deal->name);
            $this->assertSame('100.00', $deal->price);
            $this->assertSame('/images/redis-deal.jpg', $deal->image);
            $this->assertSame($categories->first()->id, $deal->category->id);
            $this->assertSame($brands->first()->id, $deal->brand->id);

            DB::transaction(fn () => $product->update(['price' => 120]));

            $this->assertSame('120.00', $catalog->deals()->first()->price);
        } finally {
            DB::disableQueryLog();
            $catalog->invalidate();
        }
    }
}
