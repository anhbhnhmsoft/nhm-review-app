<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\DB;

class StoreService
{
    protected function queryStoreItem()
    {
        return Store::query()->with([
                'storeFiles'=> function ($query) {
                    $query->limit(4);
                },
                'utilities',
                'reviews',
            ])
            ->withCount(['storeFiles','reviews'])
            ->canShow();
    }

    public function getStoreBySlug($slug): Store|null
    {
        return $this->queryStoreItem()->where('slug', $slug)->first();
    }

    public function getStoreNearLocation($lat, $lng, float $radiusKm = 5)
    {
        return Store::nearBy($lat, $lng, $radiusKm)
            ->canShow()
            ->withCount(['reviews', 'reviews as reviews_avg' => function ($q) {
                $q->select(DB::raw('avg((rating_location + rating_space + rating_quality + rating_serve) / 4)'));
            },])
            ->limit(5)->get();

    }

    public function getStoreById($id)
    {
        return $this->queryStoreItem()->where('id',$id)->first();
    }

    public function getAverageRating(Store $store)
    {
        return $store->reviews()
            ->selectRaw('
                AVG(rating_location) as avg_location,
                AVG(rating_space) as avg_space,
                AVG(rating_quality) as avg_quality,
                AVG(rating_serve) as avg_serve
            ')
            ->first();
    }

    public function getOverallAverageRating(Store $store)
    {
        $ratings = $this->getAverageRating($store);
        if ($ratings) {
            $totalAverage = (
                    $ratings->avg_location +
                    $ratings->avg_space +
                    $ratings->avg_quality +
                    $ratings->avg_serve
                ) / 4;
            return round($totalAverage, 1);
        }
        return 0;
    }

}
