<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    public function test_a_category_page_paginates_products_and_filters_them_by_price(): void
    {
        $this->get('/categories/laptops')
            ->assertOk()
            ->assertSee('Laptops');

        $this->get('/categories/laptops?max_price=800')
            ->assertOk()
            ->assertSee('Laptops');
    }

    public function test_tablets_and_smartwatches_replace_the_previous_catalogue_categories(): void
    {
        $this->get('/categories/tablets')
            ->assertOk()
            ->assertSee('Tablets');

        $this->get('/categories/smartwatches')
            ->assertOk()
            ->assertSee('Smartwatches');

        $this->get('/categories/audio')->assertNotFound();
        $this->get('/categories/gaming')->assertNotFound();
    }

    public function test_a_category_ajax_request_returns_rendered_product_html(): void
    {
        $this->get('/categories/laptops', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJsonPath('activeCategorySlug', 'laptops')
            ->assertJsonStructure(['html', 'title', 'activeCategorySlug']);
    }

    public function test_a_product_page_can_be_rendered_for_ajax_navigation(): void
    {
        $product = Product::query()->whereHas('category', fn ($query) => $query->where('slug', 'laptops'))->firstOrFail();

        $this->get('/products/'.$product->slug, ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJsonPath('activeCategorySlug', 'laptops')
            ->assertJsonPath('title', 'AeroBook Pro 14 – '.config('app.name'))
            ->assertJsonStructure(['html', 'title', 'activeCategorySlug']);
    }

    public function test_each_category_uses_unique_product_images(): void
    {
        $products = Product::query()->with('images')->get();

        $this->assertSame($products->count(), $products->filter(fn (Product $product): bool => $product->image !== null)->count());
    }
}
