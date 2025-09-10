<?php

namespace App\Livewire;

use App\Services\BannerService;
use App\Services\CategoryService;
use App\Services\StoreService;
use App\Services\ArticleService;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends BaseComponent
{
    private BannerService $bannerService;
    private CategoryService $categoryService;
    private StoreService $storeService;
    private ArticleService $articleService;

    /**
     * ---- State ----
     */

    public ?Collection $banner_index;
    public ?Collection $banners;
    public ?Collection $categories;
    public Collection $storesFeatured;
    public Collection $featuredVideos;
    public Collection $newsArticles;
    public $pressArticle;
    public Collection $handbookArticles;
    public string $pressFirstLine = '';

    public function boot(
        BannerService $bannerService,
        CategoryService $categoryService,
        StoreService $storeService,
        ArticleService $articleService,
    ): void {
        parent::setupBase();
        $this->bannerService = $bannerService;
        $this->categoryService = $categoryService;
        $this->storeService = $storeService;
        $this->articleService = $articleService;
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
        ])->limit(8)->orderBy('sorting_order', 'asc')->get();

        $this->featuredVideos = $this->storeService->getFeaturedVideos(6);

        $this->newsArticles = $this->articleService->getNewsArticles(5);

        $this->pressArticle = $this->articleService->getPressArticles(1)->first();
        if ($this->pressArticle) {
            $this->pressFirstLine = $this->articleService->extractFirstHeading($this->pressArticle->content ?? '');
        }

        // Cẩm nang
        $this->handbookArticles = $this->articleService->getHandbookArticles(5);
    }

    public function render()
    {
        return $this->view('livewire.dashboard');
    }
}
