<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function update(Request $request, string $product): RedirectResponse
    {
        $productModel = Product::query()->where('slug', $product)->firstOrFail();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['required', 'string', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['required', 'string'],
        ]);

        $productModel->update(collect($validated)->except('image')->all());
        $productModel->images()->updateOrCreate(
            ['sort_order' => 0],
            ['path' => $validated['image'], 'is_primary' => true],
        );

        return redirect()->route('admin.products')->with('status', 'Proizvod je uspešno izmenjen.');
    }
}
