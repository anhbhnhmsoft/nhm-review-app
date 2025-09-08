<?php

namespace App\Livewire\Dashboard;

use App\Models\Store;
use Livewire\Component;

class CardStore extends Component
{
    public Store $store;

    public function mount(Store $store)
    {
        $this->store = $store;
    }

    public function render()
    {
        return view('components.dashboard.card-store');
    }
}
