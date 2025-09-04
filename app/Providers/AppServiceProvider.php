<?php

namespace App\Providers;

use App\Services\BannerService;
use App\Services\CategoryService;
use App\Services\ProvinceService;
use App\Services\StoreService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BannerService::class, fn() => new BannerService());
        $this->app->singleton(CategoryService::class, fn() => new CategoryService());
        $this->app->singleton(ProvinceService::class, fn() => new ProvinceService());
        $this->app->singleton(StoreService::class, fn() => new StoreService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
