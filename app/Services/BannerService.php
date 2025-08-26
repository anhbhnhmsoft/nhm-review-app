<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

class BannerService
{

    public function getIndexBanner(): ?Collection
    {
        try {
            return Banner::query()
                ->where('banner_index', true)
                ->where('show',true)
                ->orderBy('sort')
                ->orderBy('id','DESC')
                ->get();
        }catch (\Exception $exception){
            return null;
        }
    }

    public function getBanners(bool $limit = true): ?Collection
    {
        try {
            $query =  Banner::query()
                ->where('banner_index', false)
                ->where('show',true)
                ->orderBy('sort')
                ->orderBy('id','DESC');

            if ($limit) {
                $query = $query->limit(6);
            }
            return $query->get();
        }catch (\Exception $exception){
            return null;
        }
    }

}
