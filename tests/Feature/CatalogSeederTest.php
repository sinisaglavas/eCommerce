<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
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
        $this->assertDatabaseCount('brands', 6);
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
            }
        }
        $admin = User::where('email', 'admin@voltio.test')->firstOrFail();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('VoltioDemo!2026', $admin->password));
        $admin->name = 'Edited administrator';
        $admin->save();
        $product = Product::firstOrFail();
        $product->price = '77.00';
        $product->save();
        Storage::disk('public')->delete('products/demo/laptops-1.jpg');
        Storage::disk('public')->put('products/custom.jpg', 'Custom product image');
        $this->seed(DatabaseSeeder::class);
        Storage::disk('public')->assertExists('products/demo/laptops-1.jpg');
        $this->assertSame('Custom product image', Storage::disk('public')->get('products/custom.jpg'));
        $this->assertDatabaseCount('products', 84);
        $this->assertDatabaseCount('product_images', 252);
        $this->assertDatabaseCount('wishlists', 9);
        $this->assertDatabaseCount('users', 4);
        $this->assertSame('77.00', $product->fresh()->price);
        $this->assertSame('Edited administrator', $admin->fresh()->name);
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
