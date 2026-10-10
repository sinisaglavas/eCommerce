<?php

namespace App\Http\Controllers;

use App\Support\StorefrontCatalog;
use App\Support\StorefrontProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, StorefrontCatalog $catalog, StorefrontProductCatalog $products): View|JsonResponse
    {
        $viewData = [
            'title' => 'Voltix – Electronics Store',
            'categories' => $catalog->categories(),
            'deals' => $products->deals(3),
            'brands' => $products->topBrands(4),
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
