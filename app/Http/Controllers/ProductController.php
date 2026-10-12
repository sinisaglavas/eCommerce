<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $relatedProducts = Product::active()->whereBelongsTo($category)->whereKeyNot($selectedProduct)->with('category')->limit(4)->get();

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
