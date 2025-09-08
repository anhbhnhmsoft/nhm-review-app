<?php

namespace App\Livewire;

use App\Services\StoreService;
use App\Services\CategoryService;
use App\Services\ProvinceService;
use App\Services\UtilityService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
class SearchStore extends BaseComponent
{
    use WithPagination;

    private StoreService $storeService;
    private CategoryService $categoryService;
    private ProvinceService $provinceService;
    private UtilityService $utilityService;
    /**
     * ---- State ----
     */

    public $filters = [];
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
        parent::setupBase();
        $this->storeService = $storeService;
        $this->categoryService = $categoryService;
        $this->provinceService = $provinceService;
        $this->utilityService = $utilityService;
        $this->loadFilterOptions();
    }

    public function mount()
    {

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

        return $this->view('livewire.search-store', [
            'stores' => $stores,
        ]);
    }

    private function loadFilterOptions(): void
    {
        $categoryList = $this->categoryService->getAllCategoryForHomePage();
        $this->categories = $categoryList ? $categoryList->pluck('name', 'id')->toArray() : [];

        $provinces = $this->provinceService->getProvinces();
        $this->provinces = $provinces ? $provinces->pluck('name', 'code')->toArray() : [];

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

    public function updated($name): void
    {
        $keysToReset = [
            'openingNow',
            'selectedCategories',
            'selectedProvince',
            'selectedDistrict',
            'selectedWard',
            'selectedUtilities',
            'status',
            'sortBy',
        ];

        if (in_array($name, $keysToReset, true)) {
            if ($name === 'selectedProvince') {
                $this->selectedDistrict = null;
                $this->selectedWard     = null;
                $this->wards            = [];
                $this->loadDistricts();
            }
            if ($name === 'selectedDistrict') {
                $this->selectedWard = null;
                $this->loadWards();
            }
            $this->resetPage();
        }
    }

    public function clearFilter(string $key, $id = null): void
    {
        switch ($key) {
            case 'all':
                $this->status = null;
                $this->openingNow = 'all';
                $this->selectedCategories = [];
                $this->selectedProvince = null;
                $this->selectedDistrict = null;
                $this->selectedWard = null;
                $this->selectedUtilities = [];
                $this->districts = [];
                $this->wards = [];
                break;
            case 'openingNow':
                $this->openingNow = 'all';
                break;
            case 'category':
                $this->selectedCategories = array_values(array_filter(
                    $this->selectedCategories ?? [],
                    fn ($cid) => (int) $cid !== (int) $id
                ));
                break;
            case 'utility':
                $this->selectedUtilities = array_values(array_filter(
                    $this->selectedUtilities ?? [],
                    fn ($uid) => (int) $uid !== (int) $id
                ));
                break;
            case 'province':
                $this->selectedProvince = null;
                $this->selectedDistrict = null;
                $this->selectedWard = null;
                $this->districts = [];
                $this->wards = [];
                break;
            case 'district':
                $this->selectedDistrict = null;
                $this->selectedWard = null;
                $this->wards = [];
                break;
            case 'ward':
                $this->selectedWard = null;
                break;
        }
        $this->resetPage();
    }
}
