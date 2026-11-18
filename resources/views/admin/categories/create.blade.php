@extends('layouts.admin')

@section('title', 'New category')

@section('content')
    <div class="mb-4">
        @if (Route::has('admin.categories.index'))
            <a href="{{ route('admin.categories.index') }}" class="small text-decoration-none">← All categories</a>
        @endif
        <p class="eyebrow mt-4 mb-2">CATALOG MANAGEMENT</p>
        <h1 class="h2 fw-bold">New category</h1>
        <p class="text-body-secondary">Organize products in a new category.</p>
    </div>
    @include('admin.categories._form', [
        'category' => $category ?? null,
        'action' => Route::has('admin.categories.store') ? route('admin.categories.store') : null,
        'editing' => false,
    ])
@endsection
