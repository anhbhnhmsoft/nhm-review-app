<?php

namespace App\Livewire;

use App\Services\StoreService;
use Livewire\Component;
use Livewire\WithPagination;

class SearchStore extends Component
{
    use WithPagination;

    private StoreService $storeService;

    /**
     * ---- State ----
     */

    public $status = null;
    public $openingNow = 'Tất cả';

    public function boot(StoreService $storeService): void
    {
        $this->storeService = $storeService;
    }


    public function render()
    {
        $stores = $this->storeService->searchStores([
            'status' => $this->status,
            'opening_now' => $this->openingNow,
        ]);
        return view('livewire.searchStore.search-store', [
            'stores' => $stores,
        ]);
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function updateOpeningNow($openingNow)
    {
        $this->openingNow = $openingNow;
        $this->resetPage();
    }
}
