<?php

namespace App\Livewire\Store;

use App\Services\StoreService;
use Livewire\Component;

class DetailLocation extends Component
{
    private StoreService $storeService;

    /**
     * State
     */

    public $store;
    public int|float $avgRatingTotal;

    public function boot(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function open()
    {
    }

    public function close()
    {
    }



    public function mount($store_id)
    {
        $this->store = $this->storeService->getStoreById($store_id);
        $this->avgRatingTotal = $this->storeService->getOverallAverageRating($this->store);

    }

    public function render()
    {
        return view('components.store.detail-location');
    }
}
