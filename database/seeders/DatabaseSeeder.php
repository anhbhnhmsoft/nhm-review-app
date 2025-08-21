<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $responseProvince = Http::get('https://production.cas.so/address-kit/latest/provinces');
        if ($responseProvince->successful()) {
            $data = $responseProvince->json();  // Lấy dữ liệu dưới dạng mảng
            // Lưu dữ liệu vào bảng provinces
            $i = 1;
            $j = 1;
            foreach ($data['provinces'] as $provinceData) {
                Province::updateOrCreate(
                    ['code' => $provinceData['code']],
                    [
                        'name' => $provinceData['name'],
                        'english_name' => $provinceData["englishName"],
                        'administrative_level' => $provinceData["administrativeLevel"],
                        'decree' => $provinceData['decree']
                    ]
                );
                $this->command->info('Done province: ' . $provinceData['name'] . ' (' . $i++ . ')');
                $responseWards = Http::get("https://production.cas.so/address-kit/latest/provinces/{$provinceData['code']}/communes");
                if ($responseWards->successful()) {
                    $dataWards = $responseWards->json();
                    $this->command->info('Total wards for province ' . $provinceData['name'] . ': ' . count($dataWards['communes']));
                    foreach ($dataWards['communes'] as $wardData) {
                        Ward::updateOrCreate(
                            ['code' => $wardData['code'], 'province_code' => $wardData['provinceCode']],
                            [
                                'name' => $wardData['name'],
                                'english_name' => $wardData['englishName'],
                                'administrative_level' => $wardData['administrativeLevel'],
                                'decree' => $wardData['decree'],
                                'province_code' => $wardData['provinceCode'] // Province relationship
                            ]
                        );
                        $this->command->info('Done wards for province: ' . $wardData['name'] . ' (' . $j++ . ')');
                    }
                }else{
                    $this->command->error('Failed to fetch wards for province: ' . $provinceData['name']);
                }
            }
            $this->command->info('Done.');
            $this->command->info('Total provinces: ' . $i);
            $this->command->info('Total wards: ' . $j);
        }else{
            $this->command->error('Failed to fetch data from the API.');
        }
    }
}
