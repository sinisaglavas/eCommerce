<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $with = ['brand', 'images'];

    protected $appends = ['old_price', 'image', 'gallery'];

    protected $fillable = ['category_id', 'brand_id', 'name', 'description', 'price', 'compare_at_price', 'stock_quantity', 'warranty_months', 'badge', 'is_active', 'published_at'];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value, array $attributes): array => [
                'name' => $value,
                'slug' => static::uniqueSlug($value, isset($attributes['id']) ? (int) $attributes['id'] : null),
            ],
        );
    }

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'compare_at_price' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function wishlistedBy(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function laptopDetail(): HasOne
    {
        return $this->hasOne(LaptopDetail::class);
    }

    public function smartphoneDetail(): HasOne
    {
        return $this->hasOne(SmartphoneDetail::class);
    }

    public function televisionDetail(): HasOne
    {
        return $this->hasOne(TelevisionDetail::class);
    }

    public function monitorDetail(): HasOne
    {
        return $this->hasOne(MonitorDetail::class);
    }

    public function tabletDetail(): HasOne
    {
        return $this->hasOne(TabletDetail::class);
    }

    public function smartwatchDetail(): HasOne
    {
        return $this->hasOne(SmartwatchDetail::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    protected function oldPrice(): Attribute
    {
        return Attribute::get(fn () => $this->compare_at_price);
    }

    protected function discountPercent(): Attribute
    {
        return Attribute::get(function (): ?int {
            if ($this->compare_at_price === null || (float) $this->compare_at_price <= 0) {
                return null;
            }

            return (int) round((1 - (float) $this->price / (float) $this->compare_at_price) * 100);
        });
    }

    protected function image(): Attribute
    {
        return Attribute::get(fn () => $this->images->first()?->path);
    }

    protected function gallery(): Attribute
    {
        return Attribute::get(fn () => $this->images->pluck('path')->all());
    }

    protected function accent(): Attribute
    {
        return Attribute::get(fn () => $this->category?->color ?? 'c-blue');
    }

    protected function tag(): Attribute
    {
        return Attribute::get(fn () => $this->badge);
    }

    protected function specifications(): Attribute
    {
        return Attribute::get(function (): array {
            $details = match ($this->category?->slug) {
                'laptops' => $this->laptopDetail,
                'smartphones' => $this->smartphoneDetail,
                'tvs' => $this->televisionDetail,
                'monitors' => $this->monitorDetail,
                'tablets' => $this->tabletDetail,
                'smartwatches' => $this->smartwatchDetail,
                default => null,
            };

            return collect(['Brand' => $this->brand?->name, 'Category' => $this->category?->name, 'Warranty' => $this->warranty_months.' months', 'Availability' => $this->stock_quantity > 0 ? 'In stock' : 'Out of stock'])
                ->merge(collect($details?->getAttributes() ?? [])->except(['id', 'product_id', 'created_at', 'updated_at'])->filter(fn ($value): bool => $value !== null))
                ->mapWithKeys(fn ($value, string $key): array => [str($key)->replace('_', ' ')->title()->toString() => is_bool($value) ? ($value ? 'Yes' : 'No') : $value])
                ->all();
        });
    }

    private static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
