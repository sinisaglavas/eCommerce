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
}
