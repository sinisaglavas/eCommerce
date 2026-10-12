<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(
        Request $request,
        string $category,
    ): View|JsonResponse {
        $filters = $request->validate([
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $selectedCategory = Category::where('is_active', true)->where('slug', $category)->firstOrFail();
        $paginatedProducts = Product::active()->whereBelongsTo($selectedCategory)->with('category')
            ->when(isset($filters['min_price']), fn ($query) => $query->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($query) => $query->where('price', '<=', $filters['max_price']))
            ->paginate(12)->withQueryString();

        $viewData = [
            'title' => $selectedCategory->name.' – Voltix',
            'category' => $selectedCategory,
            'products' => $paginatedProducts,
            'filters' => $filters,
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('categories.partials.page-content', $viewData)->render(),
                'title' => $viewData['title'],
                'activeCategorySlug' => $selectedCategory->slug,
            ]);
        }

        return view('categories.show', $viewData);
    }
}
