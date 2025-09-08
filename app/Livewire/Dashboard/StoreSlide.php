<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Services\CategoryService;
use App\Services\StoreService;
use Livewire\Component;

class StoreSlide extends Component
{
    private StoreService $storeService;
    private CategoryService $categoryService;
    /**
     * State
     */
    public Category $category;

    public string $order_by = 'created_at';

    public \Illuminate\Database\Eloquent\Collection $stores;
    public \Illuminate\Database\Eloquent\Collection $category_child;


    public function boot(StoreService $storeService, CategoryService $categoryService)
    {
        $this->storeService = $storeService;
        $this->categoryService = $categoryService;
    }

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->category_child = $this->categoryService->query(['parent_id' => $category->id])->select(['name', 'id'])->limit(3)->get();
        $this->loadStores();
    }

    public function sortOrder($orderBy)
    {
        $this->order_by = $orderBy;
        $this->loadStores();
        $this->dispatch('refresh-swiper');
    }

    public function render()
    {
        return view('components.dashboard.store-slide');
    }

    private function loadStores()
    {
        $queryStore = $this->storeService->filters(['category_id' => $this->category->id])->limit(10);
        $this->stores = $this->storeService->sortBy($queryStore, $this->order_by)->get();
    }
}
