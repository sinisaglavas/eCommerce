<?php

namespace App\Http\Controllers;

use App\Support\StorefrontCatalog;
use App\Support\StorefrontProductCatalog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(
        Request $request,
        string $category,
        StorefrontCatalog $catalog,
        StorefrontProductCatalog $products,
    ): View|JsonResponse {
        $filters = $request->validate([
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $selectedCategory = $catalog->categories()->firstWhere('slug', $category);

        abort_unless($selectedCategory, 404);

        $filteredProducts = $products->forCategory(
            $selectedCategory->slug,
            isset($filters['min_price']) ? (float) $filters['min_price'] : null,
            isset($filters['max_price']) ? (float) $filters['max_price'] : null,
        );

        $perPage = 12;
        $currentPage = max($request->integer('page', 1), 1);
        $paginatedProducts = new LengthAwarePaginator(
            $filteredProducts->forPage($currentPage, $perPage)->values(),
            $filteredProducts->count(),
            $perPage,
            $currentPage,
            [
                'path' => route('categories.show', $selectedCategory->slug),
                'pageName' => 'page',
            ],
        );

        $paginatedProducts->appends($request->except('page'));

        $viewData = [
            'title' => $selectedCategory->name.' – '.config('app.name'),
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
