<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'icon' => ['required', 'string', 'in:laptop,phone,tv,monitor,headphones,gamepad'],
            'color' => ['required', 'string', 'in:c-blue,c-violet,c-slate,c-indigo,c-pink,c-red'],
        ]);

        Category::query()->create([
            ...$validated,
            'sort_order' => ((int) Category::query()->max('sort_order')) + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.categories')->with('status', 'Kategorija je uspešno kreirana.');
    }

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->orderBy('sort_order')->paginate(20),
        ]);
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category)],
            'icon' => ['required', 'string', 'in:laptop,phone,tv,monitor,headphones,gamepad'],
            'color' => ['required', 'string', 'in:c-blue,c-violet,c-slate,c-indigo,c-pink,c-red'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories')->with('status', 'Kategorija je uspešno izmenjena.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories')->with('error', 'Kategorija ne može da se obriše dok sadrži proizvode.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Kategorija je uspešno obrisana.');
    }
}
