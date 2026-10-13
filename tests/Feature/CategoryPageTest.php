<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_filters_and_pagination_use_database_products(): void
    {
        $category = Category::factory()->create(['slug' => 'laptops']);
        Product::factory()->for($category)->create(['name' => 'Budget Database Laptop', 'price' => 500]);
        Product::factory()->for($category)->count(12)->create(['price' => 1200]);
        Product::factory()->for($category)->create(['name' => 'Hidden Database Laptop', 'price' => 400, 'is_active' => false]);
        $this->get('/categories/laptops')->assertOk()->assertSee('page=2', false)->assertDontSee('Hidden Database Laptop');
        $this->get('/categories/laptops?max_price=800')->assertOk()->assertSee('Budget Database Laptop')->assertViewHas('products', fn ($products) => $products->total() === 1);
        $this->get('/categories/laptops?min_price=900')->assertOk()->assertDontSee('Budget Database Laptop');
        $this->get('/categories/missing')->assertNotFound();
    }

    public function test_ajax_category_and_product_use_database_content(): void
    {
        $category = Category::factory()->create(['slug' => 'database-category']);
        $product = Product::factory()->for($category)->create(['name' => 'Database Screen']);
        ProductImage::factory()->for($product)->create(['path' => 'https://example.test/screen.jpg']);
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];
        $this->get('/categories/database-category', $headers)->assertOk()->assertJsonPath('activeCategorySlug', 'database-category')->assertJsonStructure(['html', 'title']);
        $this->get('/products/'.$product->slug, $headers)->assertOk()->assertJsonPath('title', 'Database Screen – '.config('app.name'))->assertSee('screen.jpg');
        $product->is_active = false;
        $product->save();
        $this->get('/products/'.$product->slug)->assertNotFound();
    }

    public function test_home_works_with_empty_database_and_database_deals(): void
    {
        $this->get('/')->assertOk();
        $product = Product::factory()->create(['name' => 'Database deal', 'price' => 100, 'compare_at_price' => 150]);
        $this->get('/')->assertOk()->assertSee($product->name);
        $this->get('/', ['X-Requested-With' => 'XMLHttpRequest'])->assertOk()->assertJsonStructure(['html', 'title']);
    }
}
