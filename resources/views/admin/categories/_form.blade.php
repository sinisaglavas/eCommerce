@php
    $icons = ['laptop' => 'Laptop', 'phone' => 'Phone', 'tv' => 'TV', 'monitor' => 'Monitor', 'headphones' => 'Headphones', 'gamepad' => 'Gaming'];
    $colors = ['c-blue' => 'Blue', 'c-violet' => 'Purple', 'c-slate' => 'Gray', 'c-indigo' => 'Indigo', 'c-pink' => 'Pink', 'c-red' => 'Red'];
@endphp
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <section class="panel overflow-hidden">
            <div class="welcome-panel border-bottom p-4">
                <h2 class="h5 mb-1">Category details</h2>
                <p class="small text-body-secondary mb-0">Fields marked with an asterisk are required.</p>
            </div>
            <form method="POST" @if($action) action="{{ $action }}" @else onsubmit="return false" @endif class="p-4">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Category name *</label>
                    <input type="text" name="name" id="name" class="form-control bg-body @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name ?? '') }}" placeholder="For example: Laptops" maxlength="255"
                           required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')<div id="name-error" class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="icon" class="form-label fw-semibold">Icon *</label>
                        <select id="icon" name="icon" class="form-select bg-body
                        @error('icon') is-invalid @enderror" required
                                @error('icon') aria-invalid="true" aria-describedby="icon-error" @enderror>
                            <option value="">Select an icon</option>
                            @foreach ($icons as $value => $label)
                                <option value="{{ $value }}" @selected(old('icon', $category->icon ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('icon')<div id="icon-error" class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="color" class="form-label fw-semibold">Color *</label>
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
                        @disabled(!$action)>{{ $editing ? 'Save changes' : 'Create category' }}
                    </button>
                    @if (Route::has('admin.categories.index'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </section>
    </div>
    <aside class="col-12 col-xl-4">
        <div class="panel p-4">
            <span class="stat-icon mb-3" aria-hidden="true">▤</span>
            <h2 class="h5">A well-organized catalog</h2>
            <p class="small text-body-secondary">A short, recognizable name helps customers find products.</p>
        </div>
    </aside>
</div>
