<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.partials.header', 'layouts.partials.footer'], function ($view): void {
            $view->with('categories', Category::query()->where('is_active', true)->orderBy('sort_order')->withCount('products')->get());
        });
    }
}
