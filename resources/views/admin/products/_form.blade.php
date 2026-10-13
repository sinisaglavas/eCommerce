<div class="row g-4">
    <div class="col-12 col-xl-8">
        <section class="panel overflow-hidden">
            <div class="welcome-panel border-bottom p-4"><h2 class="h5 mb-1">Podaci o proizvodu</h2><p class="small text-body-secondary mb-0">Polja označena zvezdicom su obavezna.</p></div>
            <form method="POST" onsubmit="return false" class="p-4">
                @csrf
                @if ($editing) @method('PUT') @endif
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Naziv proizvoda *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" class="form-control bg-body @error('name') is-invalid @enderror" placeholder="Na primer: UltraView 27" maxlength="255" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')<div class="invalid-feedback" id="name-error">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold">Slug *</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $product->slug ?? '') }}" class="form-control bg-body @error('slug') is-invalid @enderror" placeholder="ultraview-27" maxlength="255" required pattern="[a-z0-9]+(-[a-z0-9]+)*" @error('slug') aria-invalid="true" aria-describedby="slug-error" @enderror>
                    @error('slug')<div class="invalid-feedback" id="slug-error">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="brand_id" class="form-label fw-semibold">Brend *</label>
                    <select id="brand_id" name="brand_id" class="form-select bg-body" required>
                        <option value="">Izaberite brend</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected((string) old('brand_id', $product->brand_id ?? '') === (string) $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<p class="text-danger small">{{ $message }}</p>@enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Cena (€) *</label>
                    <input id="price" name="price" type="number" value="{{ old('price', $product->price ?? '') }}" class="form-control bg-body @error('price') is-invalid @enderror" placeholder="429.00" min="0" step="0.01" required @error('price') aria-invalid="true" aria-describedby="price-error" @enderror>
                    @error('price')<div class="invalid-feedback" id="price-error">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="compare_at_price" class="form-label fw-semibold">Prethodna cena (€)</label>
                    <input id="compare_at_price" name="compare_at_price" type="number" value="{{ old('compare_at_price', $product->compare_at_price ?? '') }}" class="form-control bg-body @error('compare_at_price') is-invalid @enderror" placeholder="Opciono" min="0" step="0.01" @error('compare_at_price') aria-invalid="true" aria-describedby="compare_at_price-error" @enderror>
                    @error('compare_at_price')<div class="invalid-feedback" id="compare_at_price-error">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">URL slike *</label>
                    <input id="image" name="image" type="url" value="{{ old('image', $product->image ?? '') }}" class="form-control bg-body @error('image') is-invalid @enderror" placeholder="https://..." required @error('image') aria-invalid="true" aria-describedby="image-error" @enderror>
                    @error('image')<div class="invalid-feedback" id="image-error">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Kategorija *</label>
                    <select id="category_id" name="category_id" class="form-select bg-body @error('category_id') is-invalid @enderror" required @error('category_id') aria-invalid="true" aria-describedby="category-error" @enderror>
                        <option value="">Izaberite kategoriju</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback" id="category-error">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Opis *</label>
                    <textarea id="description" name="description" rows="5" class="form-control bg-body @error('description') is-invalid @enderror" required @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')<div class="invalid-feedback" id="description-error">{{ $message }}</div>@enderror
                </div>
                <p class="small text-body-secondary" role="status">Čuvanje izmena još nije dostupno.</p>
                <div class="d-flex gap-2 border-top pt-4">
                    <button class="btn btn-primary" type="submit" disabled>{{ $editing ? 'Sačuvaj izmene' : 'Kreiraj proizvod' }}</button>
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">Otkaži</a>
                </div>
            </form>
        </section>
    </div>
    <aside class="col-12 col-xl-4">
        <div class="panel p-4">
            <h2 class="h5 mb-3">Slika proizvoda</h2>
            @if ($product)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3">
                <h3 class="h6">{{ $product->name }}</h3>
            @else
                <div class="text-center border rounded p-5 mb-3"><span class="stat-icon" aria-hidden="true">◇</span></div>
            @endif
            <p class="small text-body-secondary mb-0">Koristite jasnu fotografiju proizvoda i opis koji kupcu objašnjava njegove glavne karakteristike.</p>
        </div>
    </aside>
</div>
