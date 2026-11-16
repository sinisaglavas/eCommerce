@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <p class="eyebrow mb-2">CATALOG MANAGEMENT</p>
            <h1 class="h2 fw-bold mb-2">Categories</h1>
            <p class="text-body-secondary mb-0">Browse and organize product categories.</p>
        </div>
        @if (Route::has('admin.categories.create'))
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4 py-2">+ New category</a>
        @else
            <button type="button" class="btn btn-primary px-4 py-2" disabled>+ New category</button>
        @endif
    </div>

    <section class="panel overflow-hidden" aria-labelledby="categories-title">
        <div class="welcome-panel border-bottom p-4">
            <h2 id="categories-title" class="h5 mb-1">All categories</h2>
            <p class="small text-body-secondary mb-0">The name, slug, and appearance of each store category.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: #111827; --bs-table-border-color: #233047; --bs-table-hover-bg: #19243a;">
                <caption class="visually-hidden">Categories and available actions</caption>
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-3">Category</th>
                        <th scope="col" class="py-3">Slug</th>
                        <th scope="col" class="py-3">Icon</th>
                        <th scope="col" class="py-3">Color</th>
                        <th scope="col" class="px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories ?? [] as $category)
                        <tr>
                            <th scope="row" class="px-4 py-3 fw-semibold">{{ $category->name }}</th>
                            <td class="py-3"><code>{{ $category->slug }}</code></td>
                            <td class="py-3 text-body-secondary">{{ $category->icon }}</td>
                            <td class="py-3 text-body-secondary">{{ ['c-blue' => 'Blue', 'c-violet' => 'Purple', 'c-slate' => 'Gray', 'c-indigo' => 'Indigo', 'c-pink' => 'Pink', 'c-red' => 'Red'][$category->color] ?? $category->color }}</td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-inline-flex gap-2">
                                    @if (Route::has('admin.categories.edit'))
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-secondary btn-sm" aria-label="Edit category {{ $category->name }}">Edit</a>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Edit</button>
                                    @endif
                                    @if (Route::has('admin.categories.destroy'))
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Delete category {{ $category->name }}">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-4 py-5">
                                <span class="stat-icon mb-3" aria-hidden="true">▤</span>
                                <h3 class="h6">{{ isset($categories) ? 'No categories to display' : 'Category overview is not available yet' }}</h3>
                                <p class="small text-body-secondary mb-0">{{ isset($categories) ? 'Add your first category to organize your catalog.' : 'Categories will appear here once data is available.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (isset($categories) && $categories instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="p-4 border-top">{{ $categories->links('pagination::bootstrap-5') }}</div>
        @endif
    </section>
@endsection
