<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\MonitorDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SmartwatchDetail;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RealCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeders_populate_related_data_and_can_be_repeated(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('categories', 6);
        $this->assertDatabaseCount('brands', 23);
        $this->assertDatabaseCount('products', 84);
        $this->assertDatabaseCount('product_images', 252);
        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('wishlists', 9);

        foreach (['laptop', 'smartphone', 'television', 'monitor', 'tablet', 'smartwatch'] as $type) {
            $this->assertDatabaseCount($type.'_details', 14);
        }
        foreach (Product::all() as $product) {
            $this->assertCount(3, $product->images);
            $this->assertSame(1, $product->images->where('is_primary', true)->count());
            $this->assertTrue($product->compare_at_price === null || $product->compare_at_price > $product->price);
            foreach ($product->images as $image) {
                $this->assertStringStartsWith('/storage/products/demo/', $image->path);
                $storagePath = substr($image->path, strlen('/storage/'));
                Storage::disk('public')->assertExists($storagePath);
                $this->assertSame(
                    file_get_contents(public_path('images/demo/products/real/'.basename($storagePath))),
                    Storage::disk('public')->get($storagePath),
                );
                $this->assertNotFalse(getimagesizefromstring(Storage::disk('public')->get($storagePath)));
            }
            $this->assertCount(3, $product->images->map(fn ($image): string => hash('sha256', Storage::disk('public')->get(substr($image->path, strlen('/storage/')))))->unique());
            $this->assertFileExists(public_path(ltrim($product->brand->logo_path, '/')));
        }
        $admin = User::where('email', 'admin@voltio.test')->firstOrFail();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('VoltioDemo!2026', $admin->password));
        $admin->name = 'Edited administrator';
        $admin->save();
        $product = Product::firstOrFail();
        $product->price = '77.00';
        $product->save();
        $seededImagePath = substr($product->fresh()->image, strlen('/storage/'));
        Storage::disk('public')->delete($seededImagePath);
        Storage::disk('public')->put('products/custom.jpg', 'Custom product image');
        $this->seed(DatabaseSeeder::class);
        Storage::disk('public')->assertExists($seededImagePath);
        $this->assertSame('Custom product image', Storage::disk('public')->get('products/custom.jpg'));
        $this->assertDatabaseCount('products', 84);
        $this->assertDatabaseCount('product_images', 252);
        $this->assertDatabaseCount('wishlists', 9);
        $this->assertDatabaseCount('users', 4);
        $this->assertSame('77.00', $product->fresh()->price);
        $this->assertSame('Edited administrator', $admin->fresh()->name);
    }

    public function test_real_catalog_import_replaces_demo_content_without_losing_orders_or_inventory(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $product = Product::query()->where('sku', 'VOLTIO-DEMO-01-01')->firstOrFail();
        $oldBrand = Brand::factory()->create([
            'name' => 'Aster', 'slug' => 'aster', 'logo_path' => '/images/demo/brands/aster.svg',
        ]);
        $product->update(['name' => 'Aster Book 01', 'brand_id' => $oldBrand->id, 'price' => '599.00', 'stock_quantity' => 2]);
        $product->laptopDetail()->update(['storage_gb' => 123]);
        $product->images()->firstOrFail()->update(['path' => '/images/demo/products/real/laptops-1.jpg']);
        $order = Order::factory()->create(['total' => '599.00']);
        $item = OrderItem::factory()->for($order)->for($product)->create([
            'product_name' => 'Aster Book 01', 'product_sku' => $product->sku,
            'unit_price' => '599.00', 'quantity' => 1, 'line_total' => '599.00',
        ]);
        $customProduct = Product::factory()->create(['name' => 'Administrator product', 'price' => '345.00']);
        $userCount = User::query()->count();

        $this->seed(RealCatalogSeeder::class);
        $this->seed(RealCatalogSeeder::class);

        $updated = $product->fresh(['laptopDetail']);
        $this->assertSame('GIGABYTE GAMING A16 EVHS3FR894SH', $updated->name);
        $this->assertSame('1299.95', $updated->price);
        $this->assertSame('Gigabyte', $updated->brand->name);
        $this->assertSame(1024, $updated->laptopDetail->storage_gb);
        $this->assertSame(2, $updated->stock_quantity);
        $this->assertCount(3, $updated->images);
        $this->assertStringContainsString('gigabyte-gaming-a16-', $updated->image);
        $this->assertSame($product->id, $item->fresh()->product_id);
        $this->assertSame('Aster Book 01', $item->fresh()->product_name);
        $this->assertSame('599.00', $item->fresh()->unit_price);
        $this->assertSame('599.00', $order->fresh()->total);
        $this->assertSame('345.00', $customProduct->fresh()->price);
        $this->assertModelMissing($oldBrand);
        $this->assertDatabaseCount('products', 85);
        $this->assertDatabaseCount('product_images', 252);
        $this->assertDatabaseCount('users', $userCount);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_fractional_specs_and_model_specific_gallery_are_visible_on_product_pages(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $this->withoutVite();
        $monitor = Product::query()->where('sku', 'VOLTIO-DEMO-04-03')->firstOrFail();
        $watch = Product::query()->where('sku', 'VOLTIO-DEMO-06-09')->firstOrFail();

        $this->assertEquals(0.03, $monitor->monitorDetail->response_time_ms);
        $this->assertEquals(1.31, $watch->smartwatchDetail->display_size_inches);
        $response = $this->get('/products/'.$monitor->slug)->assertOk()->assertSee('0.03')->assertSee('3 photos');
        foreach ($monitor->images as $image) {
            $response->assertSee($image->path, false);
        }
        $this->get('/products/'.$watch->slug)->assertOk()->assertSee('1.31');
    }

    public function test_real_catalog_import_rolls_back_when_a_required_photo_is_missing(): void
    {
        Storage::fake('public');

        $originalPublicPath = public_path();
        $this->app->usePublicPath(storage_path('framework/testing/missing-catalog-assets'));

        try {
            $this->seed(RealCatalogSeeder::class);
            $this->fail('Importing a catalog with missing photos must fail.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('Missing seeded product image:', $exception->getMessage());
        } finally {
            $this->app->usePublicPath($originalPublicPath);
        }

        $this->assertDatabaseCount('categories', 0);
        $this->assertDatabaseCount('brands', 0);
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('product_images', 0);
        $this->assertDatabaseCount('laptop_details', 0);
    }

    public function test_specification_rollback_protects_fractional_monitor_response_times(): void
    {
        MonitorDetail::factory()->create([
            'product_id' => Product::factory(), 'response_time_ms' => 0.03,
        ]);
        $migration = require database_path('migrations/2026_11_21_111815_preserve_fractional_product_specifications.php');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('would lose fractional product specifications');
        $migration->down();
    }

    public function test_specification_rollback_protects_precise_watch_display_sizes(): void
    {
        SmartwatchDetail::factory()->create([
            'product_id' => Product::factory(), 'display_size_inches' => 1.31,
        ]);
        $migration = require database_path('migrations/2026_11_21_111815_preserve_fractional_product_specifications.php');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('would lose fractional product specifications');
        $migration->down();
    }

    public function test_translation_migration_updates_only_original_demo_descriptions(): void
    {
        $demoCategory = Category::factory()->create([
            'slug' => 'laptops',
            'description' => 'Prenosivi računari za posao, studije i kreativni rad.',
        ]);
        $customizedCategory = Category::factory()->create([
            'slug' => 'tablets',
            'description' => 'A description written by the store administrator.',
        ]);
        $otherCategory = Category::factory()->create([
            'slug' => 'custom-category',
            'description' => 'Prenosivi računari za posao, studije i kreativni rad.',
        ]);
        $migration = require database_path('migrations/2026_11_16_155210_translate_default_category_descriptions_to_english.php');

        $migration->up();
        $migration->up();

        $this->assertSame('Laptops for work, study, and creative projects.', $demoCategory->fresh()->description);
        $this->assertSame('A description written by the store administrator.', $customizedCategory->fresh()->description);
        $this->assertSame('Prenosivi računari za posao, studije i kreativni rad.', $otherCategory->fresh()->description);

        $migration->down();

        $this->assertSame('Prenosivi računari za posao, studije i kreativni rad.', $demoCategory->fresh()->description);
        $this->assertSame('A description written by the store administrator.', $customizedCategory->fresh()->description);
    }

    public function test_seeded_catalog_renders_in_storefront_and_admin(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $this->withoutVite();
        $this->get('/')->assertOk()->assertSee('/storage/products/demo/', false);
        foreach (Category::all() as $category) {
            $this->get('/categories/'.$category->slug)->assertOk()->assertSee('page=2', false);
            $product = $category->products()->active()->firstOrFail();
            $this->get('/products/'.$product->slug)->assertOk()->assertSee($product->name)->assertSee('3 photos');
        }
        $this->actingAs(User::where('email', 'admin@voltio.test')->firstOrFail());
        $this->get('/admin/products')->assertOk();
        $this->actingAs(User::where('email', 'ana@voltio.test')->firstOrFail());
        $this->get('/wishlist')->assertOk();
        foreach (['sessions', 'password_reset_tokens', 'jobs', 'failed_jobs', 'cache', 'passkeys'] as $table) {
            $this->assertSame(0, DB::table($table)->count());
        }
    }
}
