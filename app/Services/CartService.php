<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;

class CartService
{
    private const string SessionKey = 'cart';

    /** @return array<int, int> */
    public function quantities(Session $session): array
    {
        return collect($session->get(self::SessionKey, []))
            ->filter(fn (mixed $quantity, mixed $productId): bool => is_numeric($productId) && filter_var($quantity, FILTER_VALIDATE_INT) !== false && (int) $quantity > 0)
            ->mapWithKeys(fn (mixed $quantity, mixed $productId): array => [(int) $productId => (int) $quantity])
            ->all();
    }

    public function add(Session $session, Product $product): void
    {
        $quantities = $this->quantities($session);
        $productId = $product->getKey();
        $quantities[$productId] = min(($quantities[$productId] ?? 0) + 1, $product->stock_quantity);
        $session->put(self::SessionKey, $quantities);
    }

    public function update(Session $session, Product $product, int $quantity): void
    {
        $quantities = $this->quantities($session);
        $productId = $product->getKey();

        if (! array_key_exists($productId, $quantities)) {
            return;
        }

        $quantities[$productId] = min($quantity, $product->stock_quantity);
        $session->put(self::SessionKey, $quantities);
    }

    public function remove(Session $session, Product $product): void
    {
        $quantities = $this->quantities($session);
        unset($quantities[$product->getKey()]);
        $session->put(self::SessionKey, $quantities);
    }

    public function count(Session $session): int
    {
        return array_sum($this->quantities($session));
    }

    public function clear(Session $session): void
    {
        $session->forget(self::SessionKey);
    }
}
