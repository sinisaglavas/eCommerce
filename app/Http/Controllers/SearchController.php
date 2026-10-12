<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $results = Product::active()->where(function ($products) use ($query): void {
            $products->where('name', 'like', "%{$query}%")->orWhereHas('brand', fn ($brands) => $brands->where('name', 'like', "%{$query}%"));
        })->limit(6)->get()
            ->map(fn (object $product) => [
                'name' => $product->name,
                'brand' => $product->brand->name,
                'price' => $product->price,
                'image' => $product->image,
                'url' => route('products.show', $product->slug),
            ]);

        return response()->json([
            'query' => $query,
            'results' => $results,
        ]);
    }
}
