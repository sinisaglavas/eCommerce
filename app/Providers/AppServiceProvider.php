<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Wishlist;
use App\Services\CartService;
use App\Services\CatalogCache;
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
    public function boot(CartService $cartService, CatalogCache $catalogCache): void
    {
        foreach ([Category::class, Product::class, Brand::class, ProductImage::class] as $model) {
            $model::saved(fn () => $catalogCache->invalidate());
            $model::deleted(fn () => $catalogCache->invalidate());
        }

        View::composer(['layouts.partials.header', 'layouts.partials.footer'], function ($view) use ($cartService, $catalogCache): void {
            $user = auth()->user();
            $cartQuantities = $cartService->quantities(request()->session());
            $cartProducts = Product::query()->active()->whereKey(array_keys($cartQuantities))->get()->keyBy('id');
            $cartItems = collect($cartQuantities)
                ->map(fn (int $quantity, int $productId): ?Product => $cartProducts->get($productId))
                ->filter()
                ->take(5);

            $view->with([
                'categories' => $catalogCache->categories(),
                'wishlistCount' => $user?->wishlistItems()->count() ?? 0,
                'wishlistItems' => $user === null ? collect() : Wishlist::query()->whereBelongsTo($user)->with(['product.category'])->latest()->limit(5)->get(),
                'cartCount' => array_sum($cartQuantities),
                'cartItems' => $cartItems,
            ]);
        });
    }
}
