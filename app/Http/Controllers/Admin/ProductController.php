<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->with('category')->orderBy('id')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(string $product): View
    {
        return view('admin.products.edit', [
            'product' => Product::query()->where('slug', $product)->firstOrFail(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
        ]);
    }
}
