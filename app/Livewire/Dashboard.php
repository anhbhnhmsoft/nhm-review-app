<?php

namespace App\Livewire;

use App\Services\BannerService;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

 class Dashboard extends Component
{

     private BannerService $bannerService;

     private CategoryService $categoryService;


     /**
      * ---- State ----
      */

     public ?Collection $banner_index;
     public ?Collection $banners;

     public ?Collection $categories;

     public function boot(BannerService $bannerService, CategoryService $categoryService): void
    {
        $this->bannerService = $bannerService;
        $this->categoryService = $categoryService;
    }

    public function mount(): void
    {
        // Banner
        $this->banner_index = $this->bannerService->getIndexBanner();
        $this->banners = $this->bannerService->getBanners();

        // Category
        $this->categories = $this->categoryService->getAllCategoryForHomePage();

    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
