<?php

namespace App\Livewire;

use App\Services\StoreService;
use App\Utils\HelperFunction;

class Store extends BaseComponent
{
    private StoreService $storeService;

    /**
     * State
     */
    public ?\App\Models\Store $store;
    public $avgRating;
    public $avgRatingTotal;
    public bool $openStore = false;
    public $slug;
    public bool $status_save_loc;

    public function boot(StoreService $storeService)
    {
        parent::setupBase();
        $this->storeService = $storeService;
    }
    public function mount($slug)
    {
        $this->slug = $slug;
        $data = $this->storeService->getStoreBySlug($slug);
        if (!$data) {
            abort(404);
        } else {
            $this->store = $data;
            // Tăng lượt view
            $data->increment('view');
            $this->openStore = HelperFunction::checkIsStoreOpen(openingTime: $data->opening_time, closingTime: $data->closing_time);
            $this->avgRating = $this->storeService->getAverageRating($data);
            $this->avgRatingTotal = $this->storeService->getOverallAverageRating($data);
            $this->status_save_loc = $this->storeService->checkStatusSaveLocation($this->store);
        }
    }


    public function render()
    {
        return $this->view('livewire.store');
    }
}
