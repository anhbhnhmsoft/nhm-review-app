<?php

namespace App\Http\Controllers;

use App\Services\ProvinceService;

class CommonController extends Controller
{
    private ProvinceService $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function getKeyGoogleMap()
    {

        return response()->json([
            'key' => config('services.google.map_key_api'),
            'map_id' => config('services.google.map_id'),
        ]);
    }

    public function getProvinces()
    {
        $provinces = $this->provinceService->getProvinces();
        return response()->json([
            'provinces' => $provinces,
        ]);
    }

    public function getDistricts($code)
    {
        $district = $this->provinceService->getDistrictsByCodeProvince($code);
        return response()->json([
           'districts' => $district
        ]);
    }

    public function getWards($code)
    {
        $wards = $this->provinceService->getWardsByCodeDistrict($code);
        return response()->json([
           'wards' => $this->provinceService->getWardsByCodeDistrict($code),
        ]);
    }


}
