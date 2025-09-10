<?php

namespace App\Livewire\Dashboard;

use App\Services\StoreService;
use Livewire\Component;

class SearchBox extends Component
{

    private StoreService $storeService;

    public $lat = null;
    public $lng = null;
    public string $search = '';

    public function boot(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function render()
    {
        $sortBy = ($this->search === '' && $this->lat && $this->lng) ? 'distance' : '';
        $stores = $this->storeService->searchStores(
            filters: ['keyword' => trim($this->search)],
            sortBy: $sortBy,
            lat: $this->lat,
            lng: $this->lng,
            limit: 5
        );
        return view('components.dashboard.search-box', [
            'stores' => $stores
        ]);
    }
}
