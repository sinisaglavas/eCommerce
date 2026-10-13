<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View|JsonResponse
    {
        $viewData = [
            'title' => config('app.name').' – Electronics Store',
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->withCount('products')->get(),
            'deals' => Product::active()->whereNotNull('compare_at_price')->with('category')->inRandomOrder()->limit(3)->get(),
            'brands' => Brand::withCount('products')->orderByDesc('products_count')->limit(4)->get(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('home.partials.page-content', $viewData)->render(),
                'title' => $viewData['title'],
                'activeCategorySlug' => '',
            ]);
        }

        return view('home.index', $viewData);
    }
}
