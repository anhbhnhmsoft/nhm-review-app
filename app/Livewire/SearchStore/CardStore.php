<?php

namespace App\Livewire\SearchStore;

use App\Models\Store;
use Livewire\Component;

class CardStore extends Component
{

    public Store $store;
    public $lat_location;
    public $lng_location;

    public function mount(Store $store, $lat_location = null, $lng_location = null)
    {
        $this->store = $store;
        $this->lat_location = $lat_location;
        $this->lng_location = $lng_location;
    }

    public function render()
    {
        return view('components.search-store.card-store');
    }
}
