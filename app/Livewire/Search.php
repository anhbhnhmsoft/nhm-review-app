<?php

namespace App\Livewire;

use App\Services\CategoryService;
use App\Services\ProvinceService;
use App\Services\StoreService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{

    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Url(as: 'q')]
    public $search = '';

    #[Url]
    public $category_id = '';

    #[Url]
    public $province_code = '';

    #[Url]
    public $district_code = '';

    #[Url]
    public $ward_code = '';

    #[Url]
    public $featured = '';

    #[Url]
    public $per_page = 12;

    public $categories = [];
    public $provinces = [];
    public $districts = [];
    public $wards = [];

    protected CategoryService $categoryService;
    protected StoreService $storeService;
    protected ProvinceService $provinceService;

    public function boot(StoreService $storeService, CategoryService $categoryService, ProvinceService $provinceService)
    {
        $this->storeService = $storeService;
        $this->categoryService = $categoryService;        
        $this->provinceService = $provinceService;
    }

    public function mount()
    {
        $this->loadCategories();
        $this->loadProvinces();

        if ($this->province_code) {
            $this->loadDistricts();
        }

        if ($this->district_code) {
            $this->loadWards();
        }
    }

    public function updatedProvinceCode()
    {
        $this->district_code = '';
        $this->ward_code = '';
        $this->loadDistricts();
        $this->resetPage();
    }

    public function updatedDistrictCode()
    {
        $this->ward_code = '';
        $this->loadWards();
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryId()
    {
        $this->resetPage();
    }

    public function updatedWardCode()
    {
        $this->resetPage();
    }

    public function updatedFeatured()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category_id', 'province_code', 'district_code', 'ward_code', 'featured']);
        $this->districts = [];
        $this->wards = [];
        $this->resetPage();
    }

    private function loadCategories()
    {
        $this->categories = $this->categoryService->getAllCategoryForHomePage();
    }

    private function loadProvinces()
    {
        $this->provinces = $this->provinceService->getProvinces();
    }

    private function loadDistricts()
    {
        $this->districts = $this->provinceService->getDistrictsByCodeProvince($this->province_code);
    }

    private function loadWards()
    {
        $this->wards = $this->provinceService->getWardsByCodeDistrict($this->district_code);
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
            'category_id' => $this->category_id,
            'province_code' => $this->province_code,
            'district_code' => $this->district_code,
            'ward_code' => $this->ward_code,
            'featured' => $this->featured,
        ];

        $stores = $this->storeService->search($filters, $this->per_page);

        return view('livewire.search', [
            'stores' => $stores,
        ]);
    }
}
