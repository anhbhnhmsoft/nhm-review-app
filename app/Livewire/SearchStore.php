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
    #[Url(history: true)]
    public array $filters = [
        'category_ids' => [],
        'province_code' => null,
        'district_code' => null,
        'ward_code' => null,
        'utilities' => [],
        'opening_now' => 'all',
    ];

    public $status = null;

    public $categories = [];

    public $provinces = [];
    public $districts = [];

    public $wards = [];

    public $utilities = [];

    #[Url(history: true)]
    public $sortBy = '';

    public $lat = null;

    public $lng = null;
    public $stores_map = [];

    public function boot(StoreService $storeService, CategoryService $categoryService, ProvinceService $provinceService, UtilityService $utilityService): void
    {
        parent::setupBase();
        $this->storeService = $storeService;
        $this->categoryService = $categoryService;
        $this->provinceService = $provinceService;
        $this->utilityService = $utilityService;
        $this->loadFilterOptions();
    }

    public function render()
    {
        $stores = $this->storeService->searchStores(
            filters: $this->filters,
            sortBy: $this->sortBy,
            lat: $this->lat,
            lng: $this->lng
        );
        $this->stores_map = $stores->getCollection()->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'address' => $s->address,
                'rate' => $s->reviews_avg ? round($s->reviews_avg, 2) : 0,
                'reviews_count' => $s->reviews_count,
                'path' => \App\Utils\HelperFunction::generateURLImagePath($s->logo_path),
                'lat' => (float) $s->latitude,
                'lng' => (float) $s->longitude,
            ])
            ->toArray();
        return $this->view('livewire.search-store', [
            'stores' => $stores,
        ]);
    }

    public function hasActiveFilters(): bool
    {
        return collect($this->filters)
            ->filter(fn ($v, $k) => $k === 'opening_now' ? $v !== 'all' : filled($v))
            ->isNotEmpty();
    }
    public function setDefaultFilters()
    {
        $this->filters = [
            'category_ids' => [],
            'province_code' => null,
            'district_code' => null,
            'ward_code' => null,
            'utilities' => [],
            'opening_now' => 'all'
        ];
        $this->sortBy = '';
    }

    public function updated($name): void
    {
        if ($name === 'filters.province_code') {
            $this->filters['district_code'] = null;
            $this->filters['ward_code'] = null;
            $this->wards = [];
            $this->loadDistricts();
        }
        if ($name === 'filters.district_code') {
            $this->filters['ward_code'] = null;
            $this->loadWards();
        }
        $this->resetPage();
    }

    public function clearFilter(string $key, $id = null): void
    {
        switch ($key) {
            case 'opening_now':
                $this->filters['opening_now'] = 'all';
                break;
            case 'category_ids':
            case 'utilities':
                if ($id){
                    $this->filters[$key] = array_filter($this->filters[$key], fn ($v) => $v != $id);
                }else{
                    $this->filters[$key] = [];
                }
                break;
            case 'province_code':
                $this->filters['province_code'] = null;
                $this->filters['district_code'] = null;
                $this->filters['ward_code'] = null;
                $this->districts = [];
                $this->wards = [];
                break;
            case 'district_code':
                $this->filters['district_code'] = null;
                $this->filters['ward_code'] = null;
                $this->wards = [];
                break;
            default:
                $this->filters[$key] = null;
                break;
        }
        $this->resetPage();
    }

    /**
     * Private
     */
    private function loadFilterOptions(): void
    {
        $this->loadUtilities();
        $this->loadCategories();
        $this->loadProvinces();
        $this->loadDistricts();
        $this->loadWards();
    }
    private function loadUtilities()
    {
        $utilities = $this->utilityService->getUtilitiesForSelect();
        $this->utilities = $utilities ?: [];
    }
    private function loadCategories()
    {
        $categoryList = $this->categoryService->getAllCategoryForHomePage();
        $this->categories = $categoryList ? $categoryList->pluck('name', 'id')->toArray() : [];
    }
    private function loadProvinces()
    {
        $provinces = $this->provinceService->getProvinces();
        $this->provinces = $provinces ? $provinces->pluck('name', 'code')->toArray() : [];
    }
    private function loadDistricts(): void
    {
        if (!empty($this->filters['province_code'])) {
            $districts = $this->provinceService->getDistrictsByCodeProvince($this->filters['province_code']);
            $this->districts = $districts ? $districts->pluck('name', 'code')->toArray() : [];
        } else {
            $this->districts = [];
        }
    }
    private function loadWards(): void
    {
        if (!empty($this->filters['district_code'])) {
            $wards = $this->provinceService->getWardsByCodeDistrict($this->filters['district_code']);
            $this->wards = $wards ? $wards->pluck('name', 'code')->toArray() : [];
        } else {
            $this->wards = [];
        }
    }
}
