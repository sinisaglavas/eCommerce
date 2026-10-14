@php
    $icons = ['laptop' => 'Laptop', 'phone' => 'Telefon', 'tv' => 'Televizor', 'monitor' => 'Monitor', 'headphones' => 'Slušalice', 'gamepad' => 'Gaming'];
    $colors = ['c-blue' => 'Plava', 'c-violet' => 'Ljubičasta', 'c-slate' => 'Siva', 'c-indigo' => 'Indigo', 'c-pink' => 'Roze', 'c-red' => 'Crvena'];
@endphp
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <section class="panel overflow-hidden">
            <div class="welcome-panel border-bottom p-4">
                <h2 class="h5 mb-1">Podaci o kategoriji</h2>
                <p class="small text-body-secondary mb-0">Polja označena zvezdicom su obavezna.</p>
            </div>
            <form method="POST" @if($action) action="{{ $action }}" @else onsubmit="return false" @endif class="p-4">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Naziv kategorije *</label>
                    <input type="text" name="name" id="name" class="form-control bg-body @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name ?? '') }}" placeholder="Na primer: Laptops" maxlength="255"
                           required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')<div id="name-error" class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="icon" class="form-label fw-semibold">Ikonica *</label>
                        <select id="icon" name="icon" class="form-select bg-body
                        @error('icon') is-invalid @enderror" required
                                @error('icon') aria-invalid="true" aria-describedby="icon-error" @enderror>
                            <option value="">Izaberite ikonicu</option>
                            @foreach ($icons as $value => $label)
                                <option value="{{ $value }}" @selected(old('icon', $category->icon ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('icon')<div id="icon-error" class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="color" class="form-label fw-semibold">Boja *</label>
                        <select id="color" name="color" class="form-select bg-body @error('color') is-invalid @enderror" required
                                @error('color') aria-invalid="true" aria-describedby="color-error" @enderror>
                            @foreach ($colors as $value => $label)
                                <option value="{{ $value }}" @selected(old('color', $category->color ?? 'c-blue') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('color')<div id="color-error" class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="border-top pt-4 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary px-4"
                        @disabled(!$action)>{{ $editing ? 'Sačuvaj izmene' : 'Kreiraj kategoriju' }}
                    </button>
                    @if (Route::has('admin.categories.index'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Otkaži</a>
                    @endif
                </div>
            </form>
        </section>
    </div>
    <aside class="col-12 col-xl-4">
        <div class="panel p-4">
            <span class="stat-icon mb-3" aria-hidden="true">▤</span>
            <h2 class="h5">Jasno organizovana ponuda</h2>
            <p class="small text-body-secondary">Kratak i prepoznatljiv naziv olakšava kupcima da pronađu proizvode.</p>
        </div>
    </aside>
</div>
