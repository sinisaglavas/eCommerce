<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminCatalogPreviewTest extends TestCase
{
    public function test_catalog_previews_require_an_administrator(): void
    {
        $paths = ['/admin/products', '/admin/products/create', '/admin/products/aerobook-pro-14/edit', '/admin/orders', '/admin/orders/DEMO-1002'];

        foreach ($paths as $path) {
            $this->get($path)->assertRedirect(route('login'));
        }

        $this->actingAs((new User)->forceFill(['id' => 1, 'name' => 'Customer', 'is_admin' => false]));

        foreach ($paths as $path) {
            $this->get($path)->assertForbidden();
        }
    }

    public function test_administrator_can_browse_products_and_open_prefilled_forms(): void
    {
        $this->actingAs((new User)->forceFill(['id' => 2, 'name' => 'Admin', 'is_admin' => true]));

        $this->get('/admin/products')->assertOk()->assertSee('AeroBook Pro 14')->assertSee('page=2', false);
        $this->get('/admin/products?page=2')->assertOk()->assertDontSee('AeroBook Pro 14');
        $this->get('/admin/products/create')->assertOk()->assertSee('Novi proizvod')->assertSee('Smartphones');
        $this->get('/admin/products/aerobook-pro-14/edit')->assertOk()->assertSee('value="AeroBook Pro 14"', false)->assertSee('value="1299"', false);
        $this->get('/admin/products/missing/edit')->assertNotFound();
    }

    public function test_demo_orders_are_labelled_and_totals_include_quantities(): void
    {
        $this->actingAs((new User)->forceFill(['id' => 2, 'name' => 'Admin', 'is_admin' => true]));

        $this->get('/admin/orders')->assertOk()->assertSee('Demo podaci')->assertSee('DEMO-1002');
        $this->get('/admin/orders/DEMO-1002')->assertOk()->assertSee('Demo porudžbina')->assertSee('Studio Buds')->assertSee('€358,00')->assertSee('€1.657,00');
        $this->get('/admin/orders/missing')->assertNotFound();
    }
}
