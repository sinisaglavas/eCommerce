<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_view_and_remove_a_saved_product(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Saved laptop']);
        ProductImage::factory()->for($product)->create(['path' => '/images/demo/products/laptops-1.svg']);
        $this->actingAs($user);
        $this->get('/')->assertOk()->assertDontSee('data-wishlist-badge', false);
        $this->postJson(route('wishlists.toggle', $product))->assertOk()
            ->assertJsonPath('wishlisted', true)->assertJsonPath('productId', $product->id)
            ->assertJsonPath('wishlistCount', 1)->assertJsonPath('wishlistItem.name', 'Saved laptop')
            ->assertJsonPath('wishlistItem.url', route('products.show', $product->slug))
            ->assertJsonPath('wishlistItem.toggleUrl', route('wishlists.toggle', $product));
        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);
        $this->get('/')->assertOk()->assertSee('data-wishlist-remove', false)->assertSee('data-product-id="'.$product->id.'"', false);
        $this->get('/wishlist')->assertOk()->assertSee('Saved laptop');
        $this->get('/products/'.$product->slug)->assertOk()->assertSee('Remove from wishlist');
        $this->postJson(route('wishlists.toggle', $product))->assertOk()
            ->assertJsonPath('wishlisted', false)->assertJsonPath('wishlistCount', 0);
        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);
        $this->get('/')->assertOk()->assertSee('Your wishlist is empty.')
            ->assertDontSee('data-wishlist-badge', false);
    }

    public function test_wishlist_requires_login_and_is_private(): void
    {
        $this->withoutVite();
        $product = Product::factory()->create();
        $this->postJson(route('wishlists.toggle', $product))->assertUnauthorized();
        $this->get('/wishlist')->assertRedirect(route('login'));
        $owner = User::factory()->create();
        Wishlist::create(['user_id' => $owner->id, 'product_id' => $product->id]);
        $this->actingAs(User::factory()->create());
        $this->get('/wishlist')->assertOk()->assertViewHas('products', fn ($products) => $products->total() === 0);
        $this->postJson(route('wishlists.toggle', $product))->assertOk()->assertJsonPath('wishlistCount', 1);
        $this->assertDatabaseCount('wishlists', 2);
    }
}
