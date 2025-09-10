<?php

namespace App\Livewire;

use App\Services\ConfigService;
use App\Utils\Constants\CacheKey;
use App\Utils\Constants\ConfigName;
use App\Utils\Constants\ConfigType;
use App\Utils\HelperFunction;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class BaseComponent extends Component
{
    protected ConfigService $configService;
    public array $configs;

    private function sharedLayoutData()
    {
        return [
            'configs' => $this->configs,
        ];
    }

    /**
     * Luôn luôn triển khai với boot
     * @return void
     */
    protected function setupBase()
    {
        $this->configService = app(ConfigService::class);
        $this->configs = Cache::remember(CacheKey::ALL_CONFIG->render(), now()->addMinutes(10), function () {
            return (array)$this->configService->getAllConfig()->reduce(function ($carry, $item) {
                if ($item->config_type === ConfigType::IMAGE->value) {
                    $carry[$item->config_key] = HelperFunction::generateURLImagePath($item->config_value);
                } else {
                    $carry[$item->config_key] = $item->config_value;
                }
                return $carry;
            }, []);
        });
    }

    public function getConfigByKeys(ConfigName $key)
    {
        return $this->configs[$key->value] ?? null;
    }

    protected function view(string $view, array  $data = [], array  $layoutData = []){
        return view($view, $data)->layoutData(array_merge($this->sharedLayoutData(), $layoutData));
    }


}
