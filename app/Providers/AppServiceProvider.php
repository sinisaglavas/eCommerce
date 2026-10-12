<?php

namespace App\Providers;

use App\Support\StorefrontCatalog;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
            $view->with('categories', app(StorefrontCatalog::class)->categories());
        });
    }
}
