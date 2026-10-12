@extends('layouts.storefront')

@section('content')
  <main>
    <div id="page-content" aria-live="polite">
      @include('products.partials.page-content')
    </div>
  </main>
@endsection
