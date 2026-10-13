<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\StorefrontCatalog;
use App\Support\StorefrontProductCatalog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request, StorefrontCatalog $catalog, StorefrontProductCatalog $products): View
    {
        $categories = $catalog->categories();
        $items = $categories->flatMap(fn (object $category) => $products->forCategory($category->slug));
        $page = max(1, (int) $request->query('page', 1));

        return view('admin.products.index', [
            'categories' => $categories->keyBy('slug'),
            'products' => new LengthAwarePaginator($items->forPage($page, 12)->values(), $items->count(), 12, $page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]),
        ]);
    }

    public function create(StorefrontCatalog $catalog): View
    {
        return view('admin.products.create', ['categories' => $catalog->categories()]);
    }

    public function edit(string $product, StorefrontCatalog $catalog, StorefrontProductCatalog $products): View
    {
        $selectedProduct = $products->findBySlug($product);
        abort_unless($selectedProduct, 404);

        return view('admin.products.edit', [
            'product' => $selectedProduct,
            'categories' => $catalog->categories(),
        ]);
    }
}
