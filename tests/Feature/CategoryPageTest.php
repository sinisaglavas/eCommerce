<?php

namespace Tests\Feature;

use App\Support\StorefrontCatalog;
use App\Support\StorefrontProductCatalog;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    public function test_a_category_page_paginates_products_and_filters_them_by_price(): void
    {
        $this->get('/categories/laptops')
            ->assertOk()
            ->assertSee('AeroBook Pro 14')
            ->assertSee('Product pagination');

        $this->get('/categories/laptops?max_price=800')
            ->assertOk()
            ->assertSee('CoreLite 14')
            ->assertDontSee('AeroBook Pro 14');
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
        $this->get('/products/aerobook-pro-14', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJsonPath('activeCategorySlug', 'laptops')
            ->assertJsonPath('title', 'AeroBook Pro 14 – Voltix')
            ->assertJsonStructure(['html', 'title', 'activeCategorySlug']);
    }

    public function test_each_category_uses_unique_product_images(): void
    {
        $catalog = app(StorefrontCatalog::class);
        $products = app(StorefrontProductCatalog::class);

        $catalog->categories()->each(function (object $category) use ($products): void {
            $images = $products->forCategory($category->slug)->pluck('image');

            $this->assertCount(6, $images->unique());
        });

        $allImages = $catalog->categories()
            ->flatMap(fn (object $category) => $products->forCategory($category->slug)->pluck('image'));

        $this->assertCount(36, $allImages->unique());
    }
}
