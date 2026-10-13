<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->active()
            ->whereHas('wishlistedBy', fn ($query) => $query->whereBelongsTo($request->user()))
            ->with('category')
            ->paginate(12);

        return view('wishlists.index', [
            'title' => 'Wishlist – Voltix',
            'products' => $products,
        ]);
    }

    public function toggle(Request $request, Product $product): JsonResponse
    {
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
            'wishlistCount' => $request->user()->wishlistItems()->count(),
            'wishlistItem' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->price, 0, ',', '.'),
                'image' => $product->image,
                'url' => route('products.show', $product->slug),
            ],
        ]);
    }
}
