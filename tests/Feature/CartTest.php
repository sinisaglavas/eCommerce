<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_update_and_remove_a_product_in_the_session_cart(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['name' => 'Session laptop', 'price' => 499.99, 'stock_quantity' => 3]);
        ProductImage::factory()->for($product)->create(['path' => '/images/demo/products/laptops-1.svg']);

        $this->post(route('cart.store', $product))->assertRedirect(route('cart.index'))->assertSessionHas('cart.'.$product->id, 1);
        $this->get(route('cart.index'))->assertOk()->assertSee('Session laptop')->assertSee('€499,99')->assertSee('data-cart-badge', false);
        $this->patch(route('cart.update', $product), ['quantity' => 3])->assertRedirect(route('cart.index'))->assertSessionHas('cart.'.$product->id, 3);
        $this->get(route('cart.index'))->assertOk()->assertSee('€1.499,97');
        $this->delete(route('cart.destroy', $product))->assertRedirect(route('cart.index'))->assertSessionMissing('cart.'.$product->id);
        $this->get(route('cart.index'))->assertOk()->assertSee('Your cart is empty.');
    }

    public function test_cart_uses_the_current_product_price_and_rejects_invalid_quantities(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['name' => 'Current price laptop', 'price' => 200, 'stock_quantity' => 2]);

        $this->post(route('cart.store', $product))->assertRedirect(route('cart.index'));
        $product->forceFill(['price' => 250])->save();
        $this->get(route('cart.index'))->assertOk()->assertSee('€250,00');
        $this->patch(route('cart.update', $product), ['quantity' => 3])->assertRedirect()->assertSessionHasErrors('quantity');
        $this->patch(route('cart.update', $product), ['quantity' => 0])->assertRedirect()->assertSessionHasErrors('quantity');
    }

    public function test_quantity_update_returns_totals_for_ajax_cart_refresh(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['price' => 125.50, 'stock_quantity' => 4]);
        $this->post(route('cart.store', $product));

        $this->patchJson(route('cart.update', $product), ['quantity' => 3])
            ->assertOk()
            ->assertJsonPath('cartCount', 3)
            ->assertJsonPath('subtotal', 376.5)
            ->assertJsonPath('total', 376.5);
    }

    public function test_adding_a_product_returns_cart_data_for_the_header_dropdown(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['name' => 'Dropdown laptop', 'price' => 499, 'stock_quantity' => 3]);
        ProductImage::factory()->for($product)->create(['path' => '/images/demo/products/laptops-1.svg']);

        $this->postJson(route('cart.store', $product))->assertOk()
            ->assertJsonPath('cartCount', 1)
            ->assertJsonPath('cartItem.name', 'Dropdown laptop')
            ->assertJsonPath('cartItem.url', route('products.show', $product->slug))
            ->assertJsonPath('cartItem.removeUrl', route('cart.destroy', $product));
    }

    public function test_removing_a_product_returns_the_updated_cart_count_for_the_header(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['stock_quantity' => 3]);

        $this->postJson(route('cart.store', $product))->assertOk();
        $this->deleteJson(route('cart.destroy', $product))->assertOk()
            ->assertJsonPath('cartCount', 0)
            ->assertJsonPath('productId', $product->id)
            ->assertSessionMissing('cart.'.$product->id);
    }

    public function test_guest_cannot_add_a_product_to_the_cart(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $product = Product::factory()->create(['stock_quantity' => 3]);

        $this->post(route('cart.store', $product))
            ->assertRedirect(route('login'))
            ->assertSessionMissing('cart.'.$product->id);
    }
}
