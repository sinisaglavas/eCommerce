<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class StorefrontProductCatalog
{
    /**
     * Temporary product data used until products are stored in the database.
     *
     * @return Collection<int, object>
     */
    public function forCategory(string $categorySlug, ?float $minPrice = null, ?float $maxPrice = null): Collection
    {
        return $this->products()
            ->where('categorySlug', $categorySlug)
            ->when($minPrice !== null, fn (Collection $products) => $products->filter(
                fn (object $product) => $product->price >= $minPrice,
            ))
            ->when($maxPrice !== null, fn (Collection $products) => $products->filter(
                fn (object $product) => $product->price <= $maxPrice,
            ))
            ->values();
    }

    public function findBySlug(string $productSlug): ?object
    {
        return $this->products()->firstWhere('slug', $productSlug);
    }

    /**
     * @return Collection<int, object>
     */
    private function products(): Collection
    {
        $images = [
            'laptops' => [
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1618424181497-157f25b6ddd5?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1649972904349-6e44c42644a7?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1779896412104-0f589e7a4e94?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1499914485622-a88fac536970?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1531297484001-80022131f5a1?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1484788984921-03950022c9ef?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1779896412244-aed1d2f8bed2?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1515378960530-7c0da6231fb1?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1508780709619-79562169bc64?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1537498425277-c283d32ef9db?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?auto=format&fit=crop&w=900&q=80',
            ],
            'smartphones' => [
                'https://images.unsplash.com/photo-1598327105666-5b89351aff97?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1556656793-08538906a9f8?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1580910051074-3eb694886505?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1551816230-ef5deaed4a26?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?auto=format&fit=crop&w=900&q=80',
            ],
            'tvs' => [
                'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1509281373149-e957c6296406?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1593784991095-a205069470b6?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1461151304267-38535e780c79?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80',
            ],
            'monitors' => [
                'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1558655146-9f40138edfeb?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1547082299-de196ea013d6?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=900&q=80',
            ],
            'audio' => [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1613629758552-443027994609?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1505751171710-1f6d0ace5a85?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1487215078519-e21cc028cb29?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=900&q=80',
            ],
            'gaming' => [
                'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1605640194512-2f7440046c2a?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1635221683689-eafdfe576abd?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1587653914557-98f6c6f9577c?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1578898395216-78dae5d29344?auto=format&fit=crop&w=900&q=80',
            ],
        ];

        $imagePositions = [];

        return collect([
            (object) ['name' => 'AeroBook Pro 14', 'categorySlug' => 'laptops', 'brand' => 'Voltix', 'price' => 1_299, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'NovaBook Air 13', 'categorySlug' => 'laptops', 'brand' => 'Nova', 'price' => 899, 'oldPrice' => 999, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Titan X16', 'categorySlug' => 'laptops', 'brand' => 'Titan', 'price' => 1_749, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'ZenBook 15', 'categorySlug' => 'laptops', 'brand' => 'Zen', 'price' => 1_159, 'oldPrice' => 1_299, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'CoreLite 14', 'categorySlug' => 'laptops', 'brand' => 'Core', 'price' => 729, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Raptor G17', 'categorySlug' => 'laptops', 'brand' => 'Raptor', 'price' => 1_999, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'AeroBook Pro 16', 'categorySlug' => 'laptops', 'brand' => 'Voltix', 'price' => 1_499, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'NovaBook Ultra 14', 'categorySlug' => 'laptops', 'brand' => 'Nova', 'price' => 1_099, 'oldPrice' => 1_249, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Titan X18 Pro', 'categorySlug' => 'laptops', 'brand' => 'Titan', 'price' => 2_199, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'ZenBook Flip 14', 'categorySlug' => 'laptops', 'brand' => 'Zen', 'price' => 1_049, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'CoreLite 13', 'categorySlug' => 'laptops', 'brand' => 'Core', 'price' => 599, 'oldPrice' => 679, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Raptor G15', 'categorySlug' => 'laptops', 'brand' => 'Raptor', 'price' => 1_649, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Pulse Slim 13', 'categorySlug' => 'laptops', 'brand' => 'Pulse', 'price' => 849, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Horizon Pro 15', 'categorySlug' => 'laptops', 'brand' => 'Horizon', 'price' => 1_349, 'oldPrice' => 1_499, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Vanguard 17', 'categorySlug' => 'laptops', 'brand' => 'Vanguard', 'price' => 1_899, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Drift Book 14', 'categorySlug' => 'laptops', 'brand' => 'Drift', 'price' => 779, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Summit Air 13', 'categorySlug' => 'laptops', 'brand' => 'Summit', 'price' => 949, 'oldPrice' => 1_049, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Forge X15', 'categorySlug' => 'laptops', 'brand' => 'Forge', 'price' => 1_799, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Nimbus 14', 'categorySlug' => 'laptops', 'brand' => 'Nimbus', 'price' => 689, 'oldPrice' => null, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Apex Ultra 16', 'categorySlug' => 'laptops', 'brand' => 'Apex', 'price' => 2_399, 'oldPrice' => 2_599, 'icon' => 'laptop', 'accent' => 'c-blue'],
            (object) ['name' => 'Nova One 15', 'categorySlug' => 'smartphones', 'brand' => 'Nova', 'price' => 899, 'oldPrice' => null, 'icon' => 'phone', 'accent' => 'c-violet'],
            (object) ['name' => 'Pixel Pro 9', 'categorySlug' => 'smartphones', 'brand' => 'Pixel', 'price' => 1_099, 'oldPrice' => 1_199, 'icon' => 'phone', 'accent' => 'c-violet'],
            (object) ['name' => 'Orbit S24', 'categorySlug' => 'smartphones', 'brand' => 'Orbit', 'price' => 749, 'oldPrice' => null, 'icon' => 'phone', 'accent' => 'c-violet'],
            (object) ['name' => 'Luma 12', 'categorySlug' => 'smartphones', 'brand' => 'Luma', 'price' => 599, 'oldPrice' => 669, 'icon' => 'phone', 'accent' => 'c-violet'],
            (object) ['name' => 'Vertex Fold', 'categorySlug' => 'smartphones', 'brand' => 'Vertex', 'price' => 1_449, 'oldPrice' => null, 'icon' => 'phone', 'accent' => 'c-violet'],
            (object) ['name' => 'Halo Mini', 'categorySlug' => 'smartphones', 'brand' => 'Halo', 'price' => 429, 'oldPrice' => null, 'icon' => 'phone', 'accent' => 'c-violet'],
            (object) ['name' => 'Vision OLED 55', 'categorySlug' => 'tvs', 'brand' => 'Vision', 'price' => 1_099, 'oldPrice' => 1_299, 'icon' => 'tv', 'accent' => 'c-slate'],
            (object) ['name' => 'CineView 65', 'categorySlug' => 'tvs', 'brand' => 'CineView', 'price' => 1_499, 'oldPrice' => null, 'icon' => 'tv', 'accent' => 'c-slate'],
            (object) ['name' => 'Stream 43', 'categorySlug' => 'tvs', 'brand' => 'Stream', 'price' => 449, 'oldPrice' => null, 'icon' => 'tv', 'accent' => 'c-slate'],
            (object) ['name' => 'Frame 50', 'categorySlug' => 'tvs', 'brand' => 'Frame', 'price' => 799, 'oldPrice' => 899, 'icon' => 'tv', 'accent' => 'c-slate'],
            (object) ['name' => 'Nova QLED 75', 'categorySlug' => 'tvs', 'brand' => 'Nova', 'price' => 1_899, 'oldPrice' => null, 'icon' => 'tv', 'accent' => 'c-slate'],
            (object) ['name' => 'Cinema 48', 'categorySlug' => 'tvs', 'brand' => 'Cinema', 'price' => 579, 'oldPrice' => null, 'icon' => 'tv', 'accent' => 'c-slate'],
            (object) ['name' => 'UltraView 27', 'categorySlug' => 'monitors', 'brand' => 'UltraView', 'price' => 429, 'oldPrice' => null, 'icon' => 'monitor', 'accent' => 'c-indigo'],
            (object) ['name' => 'Creator 32 4K', 'categorySlug' => 'monitors', 'brand' => 'Creator', 'price' => 649, 'oldPrice' => 749, 'icon' => 'monitor', 'accent' => 'c-indigo'],
            (object) ['name' => 'Swift 24', 'categorySlug' => 'monitors', 'brand' => 'Swift', 'price' => 229, 'oldPrice' => null, 'icon' => 'monitor', 'accent' => 'c-indigo'],
            (object) ['name' => 'Focus 27 QHD', 'categorySlug' => 'monitors', 'brand' => 'Focus', 'price' => 359, 'oldPrice' => null, 'icon' => 'monitor', 'accent' => 'c-indigo'],
            (object) ['name' => 'Edge Curved 34', 'categorySlug' => 'monitors', 'brand' => 'Edge', 'price' => 539, 'oldPrice' => 599, 'icon' => 'monitor', 'accent' => 'c-indigo'],
            (object) ['name' => 'Frame 22', 'categorySlug' => 'monitors', 'brand' => 'Frame', 'price' => 169, 'oldPrice' => null, 'icon' => 'monitor', 'accent' => 'c-indigo'],
            (object) ['name' => 'SoundWave Max', 'categorySlug' => 'audio', 'brand' => 'SoundWave', 'price' => 299, 'oldPrice' => 349, 'icon' => 'headphones', 'accent' => 'c-pink'],
            (object) ['name' => 'Studio Buds', 'categorySlug' => 'audio', 'brand' => 'Studio', 'price' => 179, 'oldPrice' => null, 'icon' => 'headphones', 'accent' => 'c-pink'],
            (object) ['name' => 'BassBox Mini', 'categorySlug' => 'audio', 'brand' => 'BassBox', 'price' => 129, 'oldPrice' => null, 'icon' => 'headphones', 'accent' => 'c-pink'],
            (object) ['name' => 'QuietPods Pro', 'categorySlug' => 'audio', 'brand' => 'Quiet', 'price' => 249, 'oldPrice' => 279, 'icon' => 'headphones', 'accent' => 'c-pink'],
            (object) ['name' => 'Wave Speaker', 'categorySlug' => 'audio', 'brand' => 'Wave', 'price' => 219, 'oldPrice' => null, 'icon' => 'headphones', 'accent' => 'c-pink'],
            (object) ['name' => 'Pulse ANC', 'categorySlug' => 'audio', 'brand' => 'Pulse', 'price' => 159, 'oldPrice' => null, 'icon' => 'headphones', 'accent' => 'c-pink'],
            (object) ['name' => 'Phantom Console', 'categorySlug' => 'gaming', 'brand' => 'Phantom', 'price' => 549, 'oldPrice' => null, 'icon' => 'gamepad', 'accent' => 'c-red'],
            (object) ['name' => 'Arena Wireless', 'categorySlug' => 'gaming', 'brand' => 'Arena', 'price' => 149, 'oldPrice' => 199, 'icon' => 'gamepad', 'accent' => 'c-red'],
            (object) ['name' => 'Strike Mechanical', 'categorySlug' => 'gaming', 'brand' => 'Strike', 'price' => 119, 'oldPrice' => null, 'icon' => 'gamepad', 'accent' => 'c-red'],
            (object) ['name' => 'Vortex Gaming PC', 'categorySlug' => 'gaming', 'brand' => 'Vortex', 'price' => 1_899, 'oldPrice' => 2_099, 'icon' => 'gamepad', 'accent' => 'c-red'],
            (object) ['name' => 'Neon RGB Mouse', 'categorySlug' => 'gaming', 'brand' => 'Neon', 'price' => 79, 'oldPrice' => null, 'icon' => 'gamepad', 'accent' => 'c-red'],
            (object) ['name' => 'Quest VR Headset', 'categorySlug' => 'gaming', 'brand' => 'Quest', 'price' => 449, 'oldPrice' => null, 'icon' => 'gamepad', 'accent' => 'c-red'],
        ])->map(function (object $product) use ($images, &$imagePositions): object {
            $product->slug = Str::slug($product->name);
            $categoryImages = $images[$product->categorySlug];
            $imagePosition = $imagePositions[$product->categorySlug] ?? 0;
            $product->image = $categoryImages[$imagePosition % count($categoryImages)];
            $product->gallery = [$product->image];
            $imagePositions[$product->categorySlug] = $imagePosition + 1;
            $product->description = sprintf(
                '%s by %s combines dependable performance with a clean, modern design for everyday use.',
                $product->name,
                $product->brand,
            );
            $product->specifications = [
                'Brand' => $product->brand,
                'Category' => Str::headline($product->categorySlug),
                'Warranty' => '24 months',
                'Availability' => 'In stock',
            ];

            return $product;
        });
    }
}
