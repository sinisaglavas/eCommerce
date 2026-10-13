<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(
        Request $request,
        string $product,
    ): View|JsonResponse {
        $selectedProduct = Product::active()->with(['category', 'laptopDetail', 'smartphoneDetail', 'televisionDetail', 'monitorDetail', 'tabletDetail', 'smartwatchDetail'])->where('slug', $product)->firstOrFail();
        $category = $selectedProduct->category;
        $isWishlisted = $request->user() !== null && Wishlist::query()->whereBelongsTo($request->user())->whereBelongsTo($selectedProduct)->exists();
        $relatedProducts = Product::active()->whereBelongsTo($category)->whereKeyNot($selectedProduct)->with('category')->limit(4)->get();

        $viewData = [
            'title' => $selectedProduct->name.' – '.config('app.name'),
            'product' => $selectedProduct,
            'category' => $category,
            'relatedProducts' => $relatedProducts,
            'isWishlisted' => $isWishlisted,
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
