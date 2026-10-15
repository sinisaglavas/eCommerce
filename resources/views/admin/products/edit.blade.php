@extends('layouts.admin')
@section('title', 'Izmeni proizvod')
@section('content')
    <a href="{{ route('admin.products') }}" class="small text-decoration-none">← Svi proizvodi</a>
    <p class="eyebrow mt-4 mb-2">UPRAVLJANJE KATALOGOM</p>
    <h1 class="h2 fw-bold mb-2">Izmeni proizvod</h1>
    <p class="text-body-secondary mb-4">Uredite podatke i način na koji je proizvod predstavljen kupcima.</p>
    @include('admin.products._form', ['product' => $product ?? null, 'editing' => true, 'action' => route('admin.products.update', $product->slug)])
@endsection
