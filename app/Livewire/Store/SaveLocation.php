<?php

namespace App\Livewire\Store;

use App\Models\Store;
use App\Services\StoreService;
use Livewire\Component;

class SaveLocation extends Component
{
    private StoreService $storeService;

    public Store $store;
    public bool $status_save_loc;

    public bool $absolute;

    public function boot(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function mount(Store $store, $absolute = false)
    {
        $this->store = $store;
        $this->status_save_loc = $this->storeService->checkStatusSaveLocation($this->store);
        $this->absolute = $absolute;
    }

    public function saveLocation(): void
    {
        if (auth()->guard('web')->check()){
            $result = $this->storeService->toggleFavoriteStore($this->store);
            if ($result){
                if ($this->status_save_loc){
                    flash()->success('Bỏ lưu địa điểm thành công',title: $this->store->name);
                }else{
                    flash()->success('Lưu địa điểm thành công',title: $this->store->name);
                }
                $this->status_save_loc = $this->storeService->checkStatusSaveLocation($this->store);
            }else{
                flash()->error('Có lỗi xảy ra, vui lòng thử lại sau');
            }
        }else{
            $this->redirect('frontend.login');
        }
    }

    public function render()
    {
        return view('components.store.save-location');
    }
}
