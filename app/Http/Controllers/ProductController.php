<?php

namespace App\Http\Controllers;

use App\Support\StorefrontCatalog;
use App\Support\StorefrontProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(
        Request $request,
        string $product,
        StorefrontCatalog $catalog,
        StorefrontProductCatalog $products,
    ): View|JsonResponse {
        $selectedProduct = $products->findBySlug($product);

        abort_unless($selectedProduct, 404);

        $category = $catalog->categories()->firstWhere('slug', $selectedProduct->categorySlug);

        $relatedProducts = $products->forCategory($selectedProduct->categorySlug)
            ->reject(fn (object $relatedProduct) => $relatedProduct->slug === $selectedProduct->slug)
            ->take(4)
            ->values();

        $viewData = [
            'title' => $selectedProduct->name.' – Voltix',
            'product' => $selectedProduct,
            'category' => $category,
            'relatedProducts' => $relatedProducts,
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('products.partials.page-content', $viewData)->render(),
                'title' => $viewData['title'],
                'activeCategorySlug' => $category->slug,
            ]);
        }

        return view('products.show', $viewData);
    }
}
