<?php

namespace App\Livewire\Store;

use App\Services\StoreService;
use Livewire\Component;

class NearbyLocation extends Component
{
    private StoreService $storeService;

    /**
     * State
     */
    public $latitude;
    public $longitude;
    public $stores;
    public $store_id;

    public function boot(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function mount($store_id,$latitude, $longitude)
    {
        $this->store_id = $store_id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->stores = $this->storeService->getStoreNearLocation($latitude, $longitude, 5);
    }

    public function render()
    {
        return view('components.store.nearby-location');
    }
}
