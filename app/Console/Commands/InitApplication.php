<?php

namespace App\Console\Commands;

use App\Models\Province;
use App\Models\Ward;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InitApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init_application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use to Init all aplication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrateCode = Artisan::call('migrate');

        if ($migrateCode === Command::SUCCESS) {
            $this->info('Lệnh migrate đã thành công!');
        } else {
            $this->error('Lỗi khi chạy migrate!');
            return Command::FAILURE;
        }
        $resultProvinceWard = $this->initProvinceWards();
        if ($resultProvinceWard === true) {
            $this->info('Seeding tỉnh thành thành công');
        }else{
            $this->error('Lỗi khi chạy Seeding tỉnh thành thành công!');
            return Command::FAILURE;
        }
    }

    private function initProvinceWards(): bool
    {
        DB::beginTransaction();
        try {
            $responseProvince = Http::get('https://production.cas.so/address-kit/latest/provinces');
            if ($responseProvince->successful()) {
                $data = $responseProvince->json();  // Lấy dữ liệu dưới dạng mảng
                // Lưu dữ liệu vào bảng provinces
                foreach ($data['provinces'] as $provinceData) {
                    Province::query()->updateOrCreate(
                        ['code' => $provinceData['code']],
                        [
                            'name' => $provinceData['name'],
                            'english_name' => $provinceData["englishName"],
                            'administrative_level' => $provinceData["administrativeLevel"],
                            'decree' => $provinceData['decree']
                        ]
                    );
                    $responseWards = Http::get("https://production.cas.so/address-kit/latest/provinces/{$provinceData['code']}/communes");
                    if ($responseWards->successful()) {
                        $dataWards = $responseWards->json();
                        foreach ($dataWards['communes'] as $wardData) {
                            Ward::query()->updateOrCreate(
                                ['code' => $wardData['code'], 'province_code' => $wardData['provinceCode']],
                                [
                                    'name' => $wardData['name'],
                                    'english_name' => $wardData['englishName'],
                                    'administrative_level' => $wardData['administrativeLevel'],
                                    'decree' => $wardData['decree'],
                                    'province_code' => $wardData['provinceCode'] // Province relationship
                                ]
                            );
                        }
                    }else{
                        DB::rollBack();
                        return false;
                    }
                }
                DB::commit();
                return true;
            }else{
                DB::rollBack();
                return false;
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
    }

}
