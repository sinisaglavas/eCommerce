<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class StorefrontCatalog
{
    /**
     * Temporary catalogue data used until categories are stored in the database.
     *
     * @return Collection<int, object>
     */
    public function categories(): Collection
    {
        return collect([
            (object) [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'icon' => 'laptop',
                'color' => 'c-blue',
                'productCount' => 248,
                'badge' => null,
                'badgeClass' => null,
            ],
            (object) [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'icon' => 'phone',
                'color' => 'c-violet',
                'productCount' => 312,
                'badge' => 'NEW',
                'badgeClass' => 'bg-accent',
            ],
            (object) [
                'name' => 'TVs',
                'slug' => 'tvs',
                'icon' => 'tv',
                'color' => 'c-slate',
                'productCount' => 164,
                'badge' => null,
                'badgeClass' => null,
            ],
            (object) [
                'name' => 'Monitors',
                'slug' => 'monitors',
                'icon' => 'monitor',
                'color' => 'c-indigo',
                'productCount' => 137,
                'badge' => null,
                'badgeClass' => null,
            ],
            (object) [
                'name' => 'Audio',
                'slug' => 'audio',
                'icon' => 'headphones',
                'color' => 'c-pink',
                'productCount' => 205,
                'badge' => null,
                'badgeClass' => null,
            ],
            (object) [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'icon' => 'gamepad',
                'color' => 'c-red',
                'productCount' => 189,
                'badge' => '-30%',
                'badgeClass' => 'bg-danger',
            ],
        ]);
    }
}
