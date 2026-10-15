<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request, CartService $cartService): View
    {
        $products = Product::query()
            ->active()
            ->whereHas('wishlistedBy', fn ($query) => $query->whereBelongsTo($request->user()))
            ->with('category')
            ->paginate(12);

        return view('wishlists.index', [
            'title' => 'Wishlist – '.config('app.name'),
            'products' => $products,
            'cartQuantities' => $cartService->quantities($request->session()),
        ]);
    }

    public function toggle(Request $request, Product $product): JsonResponse
    {
        Log::info('Proizvod koji je stigao u kontroler:', $product->toArray());
        $wishlistItem = Wishlist::query()
            ->whereBelongsTo($request->user())
            ->whereBelongsTo($product)
            ->first();

        if ($wishlistItem !== null) {
            $wishlistItem->delete();

            return response()->json(['wishlisted' => false, 'productId' => $product->id, 'wishlistCount' => $request->user()->wishlistItems()->count()]);
        }

        Wishlist::query()->create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'wishlisted' => true,
            'productId' => $product->id,
            'wishlistCount' => $request->user()->wishlistItems()->count(),
            'wishlistItem' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->price, 0, ',', '.'),
                'image' => $product->image,
                'url' => route('products.show', $product->slug),
                'toggleUrl' => route('wishlists.toggle', $product),
            ],
        ]);
    }
}
