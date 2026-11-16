@extends('layouts.admin')

@section('title', 'Edit category')

@section('content')
    <div class="mb-4">
        @if (Route::has('admin.categories.index'))
            <a href="{{ route('admin.categories.index') }}" class="small text-decoration-none">← All categories</a>
        @endif
        <p class="eyebrow mt-4 mb-2">CATALOG MANAGEMENT</p>
        <h1 class="h2 fw-bold">Edit category</h1>
        <p class="text-body-secondary">Update the category name and appearance in the store.</p>
    </div>
    @include('admin.categories._form', [
        'category' => $category ?? null,
        'action' => isset($category) && Route::has('admin.categories.update') ? route('admin.categories.update', $category) : null,
        'editing' => true,
    ])
@endsection
