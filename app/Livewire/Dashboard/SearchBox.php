<?php

namespace App\Livewire\Dashboard;

use App\Services\StoreService;
use Livewire\Component;

class SearchBox extends Component
{

    private StoreService $storeService;

    public function boot(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }
    
    public function render()
    {
        return view('components.dashboard.search-box');
    }
}
