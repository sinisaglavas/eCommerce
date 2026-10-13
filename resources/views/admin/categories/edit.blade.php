@extends('layouts.admin')

@section('title', 'Izmeni kategoriju')

@section('content')
    <div class="mb-4">
        @if (Route::has('admin.categories.index'))
            <a href="{{ route('admin.categories.index') }}" class="small text-decoration-none">← Sve kategorije</a>
        @endif
        <p class="eyebrow mt-4 mb-2">UPRAVLJANJE KATALOGOM</p>
        <h1 class="h2 fw-bold">Izmeni kategoriju</h1>
        <p class="text-body-secondary">Ažurirajte naziv i izgled kategorije u prodavnici.</p>
    </div>
    @include('admin.categories._form', [
        'category' => $category ?? null,
        'action' => isset($category) && Route::has('admin.categories.update') ? route('admin.categories.update', $category) : null,
        'editing' => true,
    ])
@endsection
