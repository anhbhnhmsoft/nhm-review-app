<?php

namespace App\Livewire;

use App\Services\StoreService;
use App\Services\CategoryService;
use App\Services\ProvinceService;
use App\Services\UtilityService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SearchStore extends Component
{
    use WithPagination;

    private StoreService $storeService;
    private CategoryService $categoryService;
    private ProvinceService $provinceService;
    private UtilityService $utilityService;
    /**
     * ---- State ----
     */

    public $status = null;
    public $openingNow = 'all';
    public $categories = [];
    public $selectedCategories = [];
    public $provinces = [];
    public $selectedProvince = null;
    public $districts = [];
    public $selectedDistrict = null;
    public $wards = [];
    public $selectedWard = null;
    public $utilities = [];
    public $selectedUtilities = [];
    public $sortBy = null;
    public $userLat = null;
    public $userLng = null;

    public function boot(StoreService $storeService, CategoryService $categoryService, ProvinceService $provinceService, UtilityService $utilityService): void
    {
        $this->storeService = $storeService;
        $this->categoryService = $categoryService;
        $this->provinceService = $provinceService;
        $this->utilityService = $utilityService;
        $this->loadCategories();
        $this->loadProvinces();
        $this->loadUtilities();
    }

    public function render()
    {
        $stores = $this->storeService->searchStores([
            'status' => $this->status,
            'opening_now' => $this->openingNow,
            'category_id' => $this->selectedCategories,
            'province_code' => $this->selectedProvince,
            'district_code' => $this->selectedDistrict,
            'ward_code' => $this->selectedWard,
            'utility_id' => $this->selectedUtilities,
            'sort_by' => $this->sortBy,
            'user_lat' => $this->userLat,
            'user_lng' => $this->userLng,
        ]);
        
        return view('livewire.searchStore.search-store', [
            'stores' => $stores,
        ]);
    }
    
    private function loadCategories(): void
    {
        $categoryList = $this->categoryService->getAllCategoryForHomePage();
        $this->categories = $categoryList ? $categoryList->pluck('name', 'id')->toArray() : [];
    }

    private function loadProvinces(): void
    {
        $provinces = $this->provinceService->getProvinces();
        $this->provinces = $provinces ? $provinces->pluck('name', 'code')->toArray() : [];
    }

    private function loadUtilities(): void
    {
        $utilities = $this->utilityService->getUtilitiesForSelect();
        $this->utilities = $utilities ?: [];
    }

    private function loadDistricts(): void
    {
        if (!empty($this->selectedProvince)) {
            $districts = $this->provinceService->getDistrictsByCodeProvince($this->selectedProvince);
            $this->districts = $districts ? $districts->pluck('name', 'code')->toArray() : [];
        } else {
            $this->districts = [];
        }
    }

    private function loadWards(): void
    {
        if (!empty($this->selectedDistrict)) {
            $wards = $this->provinceService->getWardsByCodeDistrict($this->selectedDistrict);
            $this->wards = $wards ? $wards->pluck('name', 'code')->toArray() : [];
        } else {
            $this->wards = [];
        }
    }

    public function updateStatus()
    {
        $this->resetPage();
    }

    public function updatedOpeningNow(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategories(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedProvince(): void
    {
        $this->selectedDistrict = null;
        $this->selectedWard     = null;
        $this->wards            = [];
    
        $this->loadDistricts();
        $this->resetPage();
    }
    
    public function updatedSelectedDistrict(): void
    {
        $this->selectedWard = null;
        
        $this->loadWards();
        $this->resetPage();
    }

    public function updatedSelectedWard(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedUtilities(): void
    {
        $this->resetPage();
    }

    public function clearAllFilters(): void
    {
        $this->status = null;
        $this->openingNow = 'all';
        $this->selectedCategories = [];
        $this->selectedProvince = null;
        $this->selectedDistrict = null;
        $this->selectedWard = null;
        $this->selectedUtilities = [];

        $this->districts = [];
        $this->wards = [];

        $this->resetPage();
    }

    public function removeCategory(int $categoryId): void
    {
        $this->selectedCategories = array_values(array_filter(
            $this->selectedCategories,
            fn ($id) => (int) $id !== (int) $categoryId
        ));
        $this->resetPage();
    }

    public function removeUtility(int $utilityId): void
    {
        $this->selectedUtilities = array_values(array_filter(
            $this->selectedUtilities,
            fn ($id) => (int) $id !== (int) $utilityId
        ));
        $this->resetPage();
    }

    public function clearOpeningNow(): void
    {
        $this->openingNow = 'all';
        $this->resetPage();
    }

    public function clearProvince(): void
    {
        $this->selectedProvince = null;
        $this->selectedDistrict = null;
        $this->selectedWard = null;
        $this->districts = [];
        $this->wards = [];
        $this->resetPage();
    }

    public function clearDistrict(): void
    {
        $this->selectedDistrict = null;
        $this->selectedWard = null;
        $this->wards = [];
        $this->resetPage();
    }

    public function clearWard(): void
    {
        $this->selectedWard = null;
        $this->resetPage();
    }
}
