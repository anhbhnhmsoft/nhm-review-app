<?php

namespace App\Livewire;

use App\Services\StoreService;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class Store extends BaseComponent
{

    private StoreService $storeService;

    /**
     * State
     */
    public \App\Models\Store $store;
    public $avgRating;
    public $avgRatingTotal;
    public bool $openStore = false;

    public $slug;

    public function boot(StoreService $storeService)
    {
        parent::setupBase();
        $this->storeService = $storeService;
    }
    #[On('reload-parent')]
    public function reload(): void
    {
        $this->store = $this->storeService->getStoreBySlug($this->slug);
        $this->avgRatingTotal = $this->storeService->getStoreById($this->store);
    }
    public function mount($slug)
    {
        $this->slug = $slug;
        $data = $this->storeService->getStoreBySlug($slug);
        if (!$data) {
            abort(404);
        } else {
            $this->store = $data;
            $openingTime = Carbon::createFromFormat('H:i', $data->opening_time);
            $closingTime = Carbon::createFromFormat('H:i', $data->closing_time);
            $now = Carbon::now();

            $this->openStore = $now->between($openingTime, $closingTime);
            $this->avgRating = $this->storeService->getAverageRating($data);
            $this->avgRatingTotal = $this->storeService->getOverallAverageRating($data);
        }
    }

    public function render()
    {
        return $this->view('livewire.store');
    }
}
