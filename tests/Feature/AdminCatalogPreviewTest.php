<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_an_administrator(): void
    {
        $paths = ['/admin', '/admin/categories', '/admin/products', '/admin/products/create', '/admin/products/missing/edit', '/admin/orders'];
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
        $product = Product::factory()->for($category)->for($brand)->create(['name' => 'Database product', 'price' => 123.45, 'is_active' => false]);
        Product::factory()->count(12)->for($category)->for($brand)->create();
        $this->get('/admin/categories')->assertOk()->assertSee('Database category');
        $this->get('/admin/products')->assertOk()->assertSee('Database product')->assertSee('Database brand')->assertSee('123,45')->assertSee('page=2', false);
        $this->get('/admin/products?page=2')->assertOk()->assertDontSee('Database product');
        $this->get('/admin/products/create')->assertOk()->assertSee('Database category')->assertSee('Database brand');
        $this->get('/admin/products/'.$product->slug.'/edit')->assertOk()->assertSee('value="Database product"', false)->assertSee('value="123.45"', false);
        $this->get('/admin/products/missing/edit')->assertNotFound();
        $this->get('/admin')->assertOk()->assertViewHas('productsCount', 13)->assertViewHas('categoriesCount', 1);
    }

    public function test_empty_database_and_unimplemented_orders_do_not_show_demo_data(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->get('/admin/products')->assertOk()->assertSee('Nema proizvoda za prikaz.');
        $this->get('/admin/categories')->assertOk()->assertSee('Nema kategorija za prikaz');
        $this->get('/admin/orders')->assertOk()->assertSee('Pregled porudžbina još nije dostupan')->assertDontSee('DEMO-');
        $this->get('/admin/orders/DEMO-1002')->assertNotFound();
    }
}
