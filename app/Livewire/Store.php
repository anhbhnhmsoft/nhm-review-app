<?php

namespace App\Livewire;

use App\Services\StoreService;
use Livewire\Component;

class Store extends Component
{

    private StoreService $storeService;

    /**
     * State
     */
    public \App\Models\Store $store;

    public function boot(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function mount($slug)
    {
        $data = $this->storeService->getStoreBySlug($slug);
        if (!$data) {
            abort(404);
        }else{
            $this->store = $data;
        }
    }

    public function render()
    {
        return view('livewire.store');
    }
}
