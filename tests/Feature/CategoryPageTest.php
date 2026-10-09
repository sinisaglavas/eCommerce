<?php

namespace Tests\Feature;

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
            ->assertJsonPath('category.slug', 'laptops')
            ->assertJsonStructure(['html', 'title', 'category', 'filters']);
    }
}
