<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CatalogCache
{
    private const int Lifetime = 300;

    private const string CategoriesKey = 'catalog:v1:categories';

    private const string DealsKey = 'catalog:v1:deals';

    private const string BrandsKey = 'catalog:v1:brands';

    /** @return Collection<int, Category> */
    public function categories(): Collection
    {
        $categories = Cache::remember(self::CategoriesKey, self::Lifetime, fn (): array => Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('products')
            ->get()
            ->toArray());

        return Category::hydrate($categories);
    }

    /** @return Collection<int, Product> */
    public function deals(): Collection
    {
        $deals = Cache::remember(self::DealsKey, self::Lifetime, fn (): array => Product::query()
            ->active()
            ->whereNotNull('compare_at_price')
            ->with('category')
            ->inRandomOrder()
            ->limit(3)
            ->get()
            ->toArray());

        return new Collection(array_map(function (array $attributes): Product {
            $product = (new Product)->newFromBuilder(Arr::except($attributes, ['category', 'brand', 'images', 'old_price', 'image', 'gallery']));
            $product->setRelation('category', $attributes['category'] === null ? null : (new Category)->newFromBuilder($attributes['category']));
            $product->setRelation('brand', $attributes['brand'] === null ? null : (new Brand)->newFromBuilder($attributes['brand']));
            $product->setRelation('images', ProductImage::hydrate($attributes['images']));

            return $product;
        }, $deals));
    }

    /** @return Collection<int, Brand> */
    public function brands(): Collection
    {
        $brands = Cache::remember(self::BrandsKey, self::Lifetime, fn (): array => Brand::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(4)
            ->get()
            ->toArray());

        return Brand::hydrate($brands);
    }

    public function invalidate(): void
    {
        $this->forget();

        if (DB::transactionLevel() > 0) {
            DB::afterCommit(fn () => $this->forget());
        }
    }

    private function forget(): void
    {
        foreach ([self::CategoriesKey, self::DealsKey, self::BrandsKey] as $key) {
            Cache::forget($key);
        }
    }
}
