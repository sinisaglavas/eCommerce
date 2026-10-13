@extends('layouts.admin')

@section('title', 'Kategorije')

@section('content')
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <p class="eyebrow mb-2">UPRAVLJANJE KATALOGOM</p>
            <h1 class="h2 fw-bold mb-2">Kategorije</h1>
            <p class="text-body-secondary mb-0">Pregledajte i organizujte kategorije proizvoda.</p>
        </div>
        @if (Route::has('admin.categories.create'))
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4 py-2">+ Nova kategorija</a>
        @else
            <button type="button" class="btn btn-primary px-4 py-2" disabled>+ Nova kategorija</button>
        @endif
    </div>

    <section class="panel overflow-hidden" aria-labelledby="categories-title">
        <div class="welcome-panel border-bottom p-4">
            <h2 id="categories-title" class="h5 mb-1">Sve kategorije</h2>
            <p class="small text-body-secondary mb-0">Naziv, adresa i izgled svake kategorije u prodavnici.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: #111827; --bs-table-border-color: #233047; --bs-table-hover-bg: #19243a;">
                <caption class="visually-hidden">Lista kategorija i dostupne akcije</caption>
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-3">Kategorija</th>
                        <th scope="col" class="py-3">Slug</th>
                        <th scope="col" class="py-3">Ikonica</th>
                        <th scope="col" class="py-3">Boja</th>
                        <th scope="col" class="px-4 py-3 text-end">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories ?? [] as $category)
                        <tr>
                            <th scope="row" class="px-4 py-3 fw-semibold">{{ $category->name }}</th>
                            <td class="py-3"><code>{{ $category->slug }}</code></td>
                            <td class="py-3 text-body-secondary">{{ $category->icon }}</td>
                            <td class="py-3 text-body-secondary">{{ ['c-blue' => 'Plava', 'c-violet' => 'Ljubičasta', 'c-slate' => 'Siva', 'c-indigo' => 'Indigo', 'c-pink' => 'Roze', 'c-red' => 'Crvena'][$category->color] ?? $category->color }}</td>
                            <td class="px-4 py-3 text-end">
                                @if (Route::has('admin.categories.edit'))
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-secondary btn-sm" aria-label="Izmeni kategoriju {{ $category->name }}">Izmeni</a>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Izmeni</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-4 py-5">
                                <span class="stat-icon mb-3" aria-hidden="true">▤</span>
                                <h3 class="h6">{{ isset($categories) ? 'Nema kategorija za prikaz' : 'Pregled kategorija još nije dostupan' }}</h3>
                                <p class="small text-body-secondary mb-0">{{ isset($categories) ? 'Dodajte prvu kategoriju da organizujete svoju ponudu.' : 'Kategorije će se pojaviti ovde kada pregled bude povezan sa podacima.' }}</p>
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
