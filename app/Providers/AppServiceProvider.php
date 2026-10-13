<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(CartService $cartService): void
    {
        View::composer(['layouts.partials.header', 'layouts.partials.footer'], function ($view) use ($cartService): void {
            $user = auth()->user();
            $cartQuantities = $cartService->quantities(request()->session());
            $cartProducts = Product::query()->active()->whereKey(array_keys($cartQuantities))->get()->keyBy('id');
            $cartItems = collect($cartQuantities)
                ->map(fn (int $quantity, int $productId): ?Product => $cartProducts->get($productId))
                ->filter()
                ->take(5);

            $view->with([
                'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->withCount('products')->get(),
                'wishlistCount' => $user?->wishlistItems()->count() ?? 0,
                'wishlistItems' => $user === null ? collect() : Wishlist::query()->whereBelongsTo($user)->with(['product.category'])->latest()->limit(5)->get(),
                'cartCount' => array_sum($cartQuantities),
                'cartItems' => $cartItems,
            ]);
        });
    }
}
