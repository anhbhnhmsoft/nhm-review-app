<?php

namespace App\Livewire;

use App\Models\Config;
use App\Services\ConfigService;
use App\Utils\Constants\ConfigName;
use Livewire\Component;

class Register extends Component
{
    private ConfigService $configService;

    public ?Config $logo_app;

    public function boot(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function mount()
    {
        $this->logo_app = $this->configService->getConfig(ConfigName::LOGO);
    }

    public function render()
    {
        return view('livewire.register')->layoutData([
            'hideLayout' => true
        ]);
    }
}
