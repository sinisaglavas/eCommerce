@extends('layouts.admin')
@section('title', 'New product')
@section('content')
    <a href="{{ route('admin.products') }}" class="small text-decoration-none">← All products</a>
    <p class="eyebrow mt-4 mb-2">CATALOG MANAGEMENT</p>
    <h1 class="h2 fw-bold mb-2">New product</h1>
    <p class="text-body-secondary mb-4">Edit the product details and how it is presented to customers.</p>
    @include('admin.products._form', ['product' => $product ?? null, 'editing' => false])
@endsection
