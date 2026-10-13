<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request, CartService $cartService): View|RedirectResponse
    {
        $cartItems = $this->cartItems($request, $cartService);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.create', [
            'title' => 'Checkout – '.config('app.name'),
            'cartItems' => $cartItems,
            'total' => $cartItems->sum('subtotal'),
        ]);
    }

    public function store(Request $request, CartService $cartService): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:32'],
            'country' => ['required', 'string', 'max:255'],
        ]);
        $cartItems = $this->cartItems($request, $cartService);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cartItems, $cartService, $request, $validated): Order {
            $order = Order::query()->create([
                ...$validated,
                'user_id' => $request->user()?->id,
                'number' => 'ORD-'.Str::upper(Str::random(10)),
                'total' => $cartItems->sum('subtotal'),
            ]);

            $order->items()->createMany($cartItems->map(fn (array $cartItem): array => [
                'product_id' => $cartItem['product']->id,
                'product_name' => $cartItem['product']->name,
                'product_sku' => $cartItem['product']->sku,
                'unit_price' => $cartItem['product']->price,
                'quantity' => $cartItem['quantity'],
                'line_total' => $cartItem['subtotal'],
            ])->all());

            $cartService->clear($request->session());

            return $order;
        });

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order): View
    {
        return view('checkout.success', [
            'title' => 'Order confirmed – '.config('app.name'),
            'order' => $order,
        ]);
    }

    /** @return Collection<int, array{product: Product, quantity: int, subtotal: float}> */
    private function cartItems(Request $request, CartService $cartService): Collection
    {
        $quantities = $cartService->quantities($request->session());
        $products = Product::query()->active()->whereKey(array_keys($quantities))->get()->keyBy('id');

        return collect($quantities)->map(function (int $quantity, int $productId) use ($products): ?array {
            $product = $products->get($productId);

            return $product === null ? null : [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => (float) $product->price * $quantity,
            ];
        })->filter()->values();
    }
}
