<?php

namespace App\Http\Controllers;

use App\Services\CatalogCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, CatalogCache $catalogCache): View|JsonResponse
    {
        $viewData = [
            'title' => config('app.name').' – Electronics Store',
            'categories' => $catalogCache->categories(),
            'deals' => $catalogCache->deals(),
            'brands' => $catalogCache->brands(),
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
