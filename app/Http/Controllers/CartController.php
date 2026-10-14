<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService): View
    {
        $quantities = $cartService->quantities($request->session());
        $products = Product::query()->active()->whereKey(array_keys($quantities))->with('category')->get()
            ->keyBy(fn (Product $product): int => $product->getKey());
        $cartItems = collect($quantities)->map(function (int $quantity, int $productId) use ($products): ?array {
            $product = $products->get($productId);

            return $product === null ? null : ['product' => $product, 'quantity' => $quantity, 'subtotal' => (float) $product->price * $quantity];
        })->filter()->values();

        return view('cart.index', ['title' => 'Cart – '.config('app.name'), 'cartItems' => $cartItems, 'total' => $cartItems->sum('subtotal')]);
    }

    public function store(Request $request, Product $product, CartService $cartService): RedirectResponse|JsonResponse
    {
        abort_unless($product->is_active && $product->stock_quantity > 0, 404);
        $cartService->add($request->session(), $product);

        if ($request->expectsJson()) {
            return response()->json([
                'cartCount' => $cartService->count($request->session()),
                'cartItem' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 0, ',', '.'),
                    'image' => $product->image,
                    'url' => route('products.show', $product->slug),
                    'removeUrl' => route('cart.destroy', $product),
                ],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to your cart.');
    }

    public function update(Request $request, Product $product, CartService $cartService): RedirectResponse
    {
        abort_unless($product->is_active && $product->stock_quantity > 0, 404);
        $validated = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock_quantity]]);
        $cartService->update($request->session(), $product, $validated['quantity']);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, Product $product, CartService $cartService): RedirectResponse|JsonResponse
    {
        $cartService->remove($request->session(), $product);

        if ($request->expectsJson()) {
            return response()->json([
                'cartCount' => $cartService->count($request->session()),
                'productId' => $product->id,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from your cart.');
    }
}
