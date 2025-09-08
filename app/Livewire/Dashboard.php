<?php

namespace App\Livewire;

use App\Services\BannerService;
use App\Services\CategoryService;
use App\Services\StoreService;
use Illuminate\Database\Eloquent\Collection;

 class Dashboard extends BaseComponent
{
     private BannerService $bannerService;
     private CategoryService $categoryService;
     private StoreService $storeService;

     /**
      * ---- State ----
      */

     public ?Collection $banner_index;
     public ?Collection $banners;
     public ?Collection $categories;
     public Collection $storesFeatured;

     public function boot(
         BannerService $bannerService,
         CategoryService $categoryService,
         StoreService $storeService,
     ): void
    {
        parent::setupBase();
        $this->bannerService = $bannerService;
        $this->categoryService = $categoryService;
        $this->storeService = $storeService;
    }

    public function mount(): void
    {
        // Banner
        $this->banner_index = $this->bannerService->getIndexBanner();
        $this->banners = $this->bannerService->getBanners();

        // Category
        $this->categories = $this->categoryService->getAllCategoryForHomePage();

        // địa điểm nổi bật
        $this->storesFeatured = $this->storeService->filters([
          'featured' => true
        ])->limit(8)->orderBy('sorting_order','asc')->get();
    }

    public function render()
    {
        return $this->view('livewire.dashboard');
    }
}
