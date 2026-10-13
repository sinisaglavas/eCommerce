@extends('layouts.admin')

@section('title', 'Nova kategorija')

@section('content')
    <div class="mb-4">
        @if (Route::has('admin.categories.index'))
            <a href="{{ route('admin.categories.index') }}" class="small text-decoration-none">← Sve kategorije</a>
        @endif
        <p class="eyebrow mt-4 mb-2">UPRAVLJANJE KATALOGOM</p>
        <h1 class="h2 fw-bold">Nova kategorija</h1>
        <p class="text-body-secondary">Organizujte proizvode u novu kategoriju.</p>
    </div>
    @include('admin.categories._form', [
        'category' => $category ?? null,
        'action' => Route::has('admin.categories.store') ? route('admin.categories.store') : null,
        'editing' => false,
    ])
@endsection
