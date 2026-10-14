<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_an_administrator(): void
    {
        $paths = ['/admin', '/admin/categories', '/admin/categories/create', '/admin/products', '/admin/products/create', '/admin/products/missing/edit', '/admin/orders'];
        foreach ($paths as $path) {
            $this->get($path)->assertRedirect(route('login'));
        }
        $this->actingAs(User::factory()->create(['is_admin' => false]));
        foreach ($paths as $path) {
            $this->get($path)->assertForbidden();
        }
    }

    public function test_admin_catalog_uses_database_values_including_inactive_products(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $category = Category::factory()->create(['name' => 'Database category']);
        $brand = Brand::factory()->create(['name' => 'Database brand']);
        $order = Order::factory()->create(['number' => 'ORD-RECENT-1001', 'status' => 'awaiting_payment']);
        $product = Product::factory()->for($category)->for($brand)->create(['name' => 'Database product', 'price' => 123.45, 'is_active' => false]);
        Product::factory()->count(12)->for($category)->for($brand)->create();
        $this->get('/admin/categories')->assertOk()->assertSee('Database category');
        $this->get('/admin/products')->assertOk()->assertSee('Database product')->assertSee('Database brand')->assertSee('123,45')->assertSee('page=2', false);
        $this->get('/admin/products?page=2')->assertOk()->assertDontSee('Database product');
        $this->get('/admin/products/create')->assertOk()->assertSee('Database category')->assertSee('Database brand');
        $this->get('/admin/products/'.$product->slug.'/edit')->assertOk()->assertSee('value="Database product"', false)->assertSee('value="123.45"', false);
        $this->get('/admin/products/missing/edit')->assertNotFound();
        $this->get('/admin')->assertOk()
            ->assertViewHas('productsCount', 13)
            ->assertViewHas('categoriesCount', 1)
            ->assertViewHas('ordersCount', 1)
            ->assertViewHas('recentOrders', fn ($orders) => $orders->contains($order))
            ->assertSee('ORD-RECENT-1001')
            ->assertSee('Awaiting Payment');
        $this->get('/admin/orders')->assertOk()->assertSee('ORD-RECENT-1001')->assertSee('Awaiting Payment');
        $this->patch(route('admin.orders.status', $order), ['status' => 'paid'])
            ->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
    }

    public function test_empty_database_and_orders_page_do_not_show_demo_data(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->get('/admin/products')->assertOk()->assertSee('Nema proizvoda za prikaz.');
        $this->get('/admin/categories')->assertOk()->assertSee('Nema kategorija za prikaz');
        $this->get('/admin/orders')->assertOk()->assertSee('Nema porudžbina za prikaz.')->assertDontSee('DEMO-');
        $this->get('/admin/orders/DEMO-1002')->assertNotFound();
    }

    public function test_admin_can_create_a_category_with_an_automatic_slug(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->post(route('admin.categories.store'), [
            'name' => 'Gaming Laptops',
            'icon' => 'laptop',
            'color' => 'c-blue',
        ])->assertRedirect(route('admin.categories'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Gaming Laptops',
            'slug' => 'gaming-laptops',
            'icon' => 'laptop',
            'color' => 'c-blue',
        ]);
    }

    public function test_admin_can_edit_a_category_and_regenerate_its_slug(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $category = Category::factory()->create(['name' => 'Office laptops', 'slug' => 'office-laptops']);

        $this->get(route('admin.categories.edit', $category))
            ->assertOk()
            ->assertSee('Izmeni kategoriju');

        $this->put(route('admin.categories.update', $category), [
            'name' => 'Gaming laptops',
            'icon' => 'laptop',
            'color' => 'c-violet',
        ])->assertRedirect(route('admin.categories'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Gaming laptops',
            'slug' => 'gaming-laptops',
            'color' => 'c-violet',
        ]);
    }

    public function test_admin_can_delete_empty_category_but_not_category_with_products(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $emptyCategory = Category::factory()->create();

        $this->delete(route('admin.categories.destroy', $emptyCategory))
            ->assertRedirect(route('admin.categories'));

        $this->assertDatabaseMissing('categories', ['id' => $emptyCategory->id]);

        $categoryWithProduct = Category::factory()->create();
        Product::factory()->for($categoryWithProduct)->create();

        $this->delete(route('admin.categories.destroy', $categoryWithProduct))
            ->assertRedirect(route('admin.categories'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $categoryWithProduct->id]);
    }

    public function test_product_name_generates_a_slug_when_updated(): void
    {
        $product = Product::factory()->create([
            'name' => 'Office Monitor',
            'slug' => 'office-monitor',
        ]);

        $product->update(['name' => 'Creator Monitor']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Creator Monitor',
            'slug' => 'creator-monitor',
        ]);
    }

    public function test_admin_can_save_product_edits_without_entering_a_slug(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $product = Product::factory()->create(['name' => 'Original monitor', 'slug' => 'original-monitor']);
        ProductImage::factory()->for($product)->create(['path' => '/images/demo/products/monitors-1.svg']);

        $this->put(route('admin.products.update', $product->slug), [
            'name' => 'Updated monitor',
            'brand_id' => $product->brand_id,
            'price' => 799.99,
            'compare_at_price' => null,
            'image' => '/images/demo/products/monitors-2.svg',
            'category_id' => $product->category_id,
            'description' => 'Updated product description.',
        ])->assertRedirect(route('admin.products'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated monitor',
            'slug' => 'updated-monitor',
            'price' => 799.99,
        ]);
        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'path' => '/images/demo/products/monitors-2.svg',
        ]);
    }
}
