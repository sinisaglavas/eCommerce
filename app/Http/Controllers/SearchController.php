<?php

namespace App\Http\Controllers;

use App\Support\StorefrontProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request, StorefrontProductCatalog $products): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $results = $products->search($query)
            ->take(6)
            ->map(fn (object $product) => [
                'name' => $product->name,
                'brand' => $product->brand,
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
