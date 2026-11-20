<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Services\CatalogCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CatalogCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_repeated_catalog_reads_use_cached_data_without_database_queries(): void
    {
        $product = Product::factory()->create(['name' => 'Cached laptop', 'compare_at_price' => 1500]);
        ProductImage::factory()->for($product)->create(['path' => '/images/cached-laptop.jpg']);
        $catalog = app(CatalogCache::class);
        $catalog->categories();
        $catalog->deals();
        $catalog->brands();

        DB::enableQueryLog();
        $categories = $catalog->categories();
        $deals = $catalog->deals();
        $brands = $catalog->brands();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertSame([], $queries);
        $this->assertSame(1, $categories->first()->products_count);
        $this->assertSame('Cached laptop', $deals->first()->name);
        $this->assertSame('/images/cached-laptop.jpg', $deals->first()->image);
        $this->assertSame($brands->first()->id, $deals->first()->brand->id);
        $this->assertSame($categories->first()->id, $deals->first()->category->id);
    }

    public function test_catalog_cache_expires_after_five_minutes(): void
    {
        $this->freezeTime();
        $category = Category::factory()->create(['name' => 'Original category']);
        $catalog = app(CatalogCache::class);
        $catalog->categories();
        DB::table('categories')->where('id', $category->id)->update(['name' => 'Updated outside Eloquent']);

        $this->assertSame('Original category', $catalog->categories()->first()->name);

        $this->travel(301)->seconds();

        $this->assertSame('Updated outside Eloquent', $catalog->categories()->first()->name);
    }

    public function test_admin_category_edits_refresh_home_and_navigation(): void
    {
        $this->withoutVite();
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create(['name' => 'Old category']);
        $this->get('/')->assertOk()->assertSee('Old category');

        $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Updated category',
            'icon' => 'laptop',
            'color' => 'c-blue',
        ])->assertRedirect();

        $this->get('/')->assertOk()->assertSee('Updated category')->assertDontSee('Old category');
        $this->get('/cart')->assertOk()->assertSee('Updated category');
    }

    public function test_admin_product_edits_refresh_cached_prices_names_and_images(): void
    {
        $this->withoutVite();
        $admin = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create(['name' => 'Original deal', 'price' => 400, 'compare_at_price' => 600]);
        ProductImage::factory()->for($product)->create(['path' => '/images/original.jpg', 'sort_order' => 0]);
        $this->get('/')->assertOk()->assertSee('Original deal');

        $this->actingAs($admin)->put(route('admin.products.update', $product->slug), [
            'name' => 'Updated deal',
            'brand_id' => $product->brand_id,
            'category_id' => $product->category_id,
            'price' => 450,
            'compare_at_price' => 600,
            'image' => '/images/updated.jpg',
            'description' => 'Updated description.',
        ])->assertRedirect();

        $this->get('/')->assertOk()->assertSee('Updated deal')->assertDontSee('Original deal')
            ->assertSee('/images/updated.jpg')->assertDontSee('/images/original.jpg');
        $this->assertSame('450.00', app(CatalogCache::class)->deals()->first()->price);
    }

    public function test_catalog_mutations_invalidate_empty_results_counts_and_deals(): void
    {
        $catalog = app(CatalogCache::class);
        $this->assertCount(0, $catalog->categories());
        $this->assertCount(0, $catalog->deals());
        $this->assertCount(0, $catalog->brands());

        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create(['compare_at_price' => 2000]);

        $this->assertSame(1, $catalog->categories()->first()->products_count);
        $this->assertCount(1, $catalog->deals());
        $this->assertSame(1, $catalog->brands()->first()->products_count);

        $product->update(['is_active' => false]);

        $this->assertCount(0, $catalog->deals());

        $product->delete();

        $this->assertSame(0, $catalog->categories()->first()->products_count);
        $this->assertSame(0, $catalog->brands()->first()->products_count);

        $category->delete();

        $this->assertCount(0, $catalog->categories());
    }

    public function test_brand_image_and_category_changes_refresh_cached_relationships(): void
    {
        $brand = Brand::factory()->create(['name' => 'Original brand']);
        $category = Category::factory()->create();
        $product = Product::factory()->for($brand)->for($category)->create(['compare_at_price' => 2000]);
        $image = ProductImage::factory()->for($product)->create(['path' => '/images/first.jpg']);
        $catalog = app(CatalogCache::class);
        $catalog->deals();
        $catalog->brands();

        $brand->name = 'Updated brand';
        $brand->save();

        $this->assertSame('Updated brand', $catalog->deals()->first()->brand->name);
        $this->assertSame('Updated brand', $catalog->brands()->first()->name);

        $image->update(['path' => '/images/second.jpg']);

        $this->assertSame('/images/second.jpg', $catalog->deals()->first()->image);

        $image->delete();

        $this->assertNull($catalog->deals()->first()->image);

        $category->update(['is_active' => false]);

        $this->assertCount(0, $catalog->categories());
    }

    public function test_shared_catalog_cache_does_not_share_customer_identity(): void
    {
        $this->withoutVite();
        $firstUser = User::factory()->create(['name' => 'First customer']);
        $secondUser = User::factory()->create(['name' => 'Second customer']);

        $this->actingAs($firstUser)->get('/')->assertOk()->assertSee('First customer');
        $this->actingAs($secondUser)->get('/')->assertOk()
            ->assertSee('Second customer')->assertDontSee('First customer');
    }

    public function test_checkout_uses_live_prices_even_when_a_cached_deal_is_stale(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'compare_at_price' => 150, 'stock_quantity' => 3]);
        app(CatalogCache::class)->deals();
        DB::table('products')->where('id', $product->id)->update(['price' => 120]);
        $this->actingAs($user)->post(route('cart.store', $product))->assertRedirect();

        $this->post(route('checkout.store'), [
            'first_name' => 'Demo',
            'last_name' => 'Customer',
            'email' => 'demo@example.com',
            'phone' => '+123456789',
            'address_line_1' => 'Demo Street 1',
            'city' => 'Demo City',
            'postal_code' => '10000',
            'country' => 'Demo Country',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 120, 'status' => 'awaiting_payment']);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'unit_price' => 120]);
    }
}
