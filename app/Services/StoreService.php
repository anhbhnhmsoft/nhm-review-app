<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Utils\Constants\StoreStatus;
use App\Utils\HelperFunction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StoreService
{
    protected function queryStoreItem()
    {
        return Store::query()->with([
            'storeFiles',
            'utilities',
            'reviews',
        ])
            ->withCount(['storeFiles', 'reviews'])
            ->canShow();
    }

    public function checkStatusSaveLocation(Store $store): bool
    {
        if (auth()->guard('web')->check()){
            return $store->users()->whereKey(auth()->guard('web')->id())->exists();
        }
        return false;
    }

    public function toggleFavoriteStore(Store $store): bool
    {
        try {
            $store->users()->toggle(auth()->guard('web')->id());
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }

    public function getStoreBySlug($slug): Store|null
    {
        try {
            return $this->queryStoreItem()->where('slug', $slug)->first();
        }catch (\Exception $exception){
            throw $exception;
        }
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
        return $this->queryStoreItem()->where('id', $id)->first();
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

    public function searchStores(array $filters = [], $sortBy = '', $lat = null, $lng = null, int $limit = 10): LengthAwarePaginator
    {
        try {
            $query = $this->filters($filters);
            $hasDistance = $this->distanceSelect($query, $lat, $lng);

            $query = $this->sortBy(
                $query,
                $sortBy,
                'desc',
                $hasDistance
            );

            $stores = $query->paginate($limit);
            foreach ($stores as $store) {
                $this->formatListItem($store, $hasDistance);
                $averageRating = $this->getOverallAverageRating($store);
                $store->overall_rating = $averageRating;
            }
            return $stores;
        } catch (\Exception $exception) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                12,
                1
            );
        }
    }

    public function filters(array $filters)
    {
        $query = Store::query()
            ->with(['category', 'province', 'district', 'ward', 'reviews'])
            ->withCount(['reviews', 'reviews as reviews_avg' => function ($q) {
                $q->select(DB::raw('avg((rating_location + rating_space + rating_quality + rating_serve) / 4)'));
            }])
            ->whereIn('status', [StoreStatus::ACTIVE->value, StoreStatus::PENDING->value]);

        if (!empty($filters['id'])){
            $query->where('id', $filters['id']);
        }

        if (!empty($filters['keyword']) && !empty(trim($filters['keyword']))){
            $keyword = trim($filters['keyword']);
            $query->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('address', 'like', '%'.$keyword.'%');
        }

        if (!empty($filters['featured'])){
            $query->where('featured', $filters['featured']);
        }

        if (!empty($filters['status']) && in_array($filters['status'], [StoreStatus::ACTIVE->value, StoreStatus::PENDING->value])) {
            $query->where('status', $filters['status']);
        }

        if (($filters['opening_now'] ?? 'all') === 'open') {
            $currentTime = Carbon::now()->format('H:i:s');
            $query->whereTime('opening_time', '<=', $currentTime)
                ->whereTime('closing_time', '>=', $currentTime);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['category_ids'])) {
            $query->whereIn('category_id', $filters['category_ids']);
        }

        if (!empty($filters['utility_id'])) {
            $query->whereHas('utilities', function ($q) use ($filters) {
                $q->whereIn('utility_id', $filters['utility_id']);
            });
        }

        if (!empty($filters['province_code'])) {
            $query->where('province_code', $filters['province_code']);
        }

        if (!empty($filters['district_code'])) {
            $query->where('district_code', $filters['district_code']);
        }

        if (!empty($filters['ward_code'])) {
            $query->where('ward_code', $filters['ward_code']);
        }

        return $query;
    }

    private function distanceSelect($query, ?float $lat, ?float $lng): bool
    {
        if ($lat && $lng) {
            $query->selectRaw(
                '(6371 * acos( cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)) )) as distance_km',
                [$lat, $lng, $lat]
            );
            return true;
        }
        return false;
    }

    public function sortBy(Builder $query, string $sortBy, string $direction = 'desc', bool $hasDistance = false)
    {
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $direction);
                break;
            case 'rating':
                $query->orderBy('reviews_avg', 'desc')
                    ->orderBy('reviews_count', 'desc');
                break;
            case 'view':
                $query->orderBy('view', 'desc');
                break;
            case 'distance':
                if ($hasDistance) {
                    $query->whereNotNull('latitude')->whereNotNull('longitude');
                    $query->orderBy('distance_km', 'asc');
                }
                break;
            default:
                $query->orderBy('created_at', $direction);
                break;
        }
        return $query;
    }

    private function formatListItem($store, bool $hasDistance): void
    {
        $store->image_url = route('public_image', ['file_path' => $store->logo_path ?? ($store->logo ?? null)]);
        if ($hasDistance && isset($store->distance_km)) {
            $store->distance_km = round((float)$store->distance_km, 1);
        }
        $store->status_label = $this->getStoreStatusLabel($store);
    }

    private function getStoreStatusLabel($store): string
    {
        $now = Carbon::now();
        $openingTime = Carbon::createFromFormat('H:i', $store->opening_time);
        $closingTime = Carbon::createFromFormat('H:i', $store->closing_time);

        if ($now->between($openingTime, $closingTime)) {
            return 'Đang mở cửa';
        } else {
            return 'Đã đóng cửa';
        }
    }

    public function getFeaturedVideos(int $limit = 6): Collection
    {
        return Store::query()
            ->whereHas('storeFiles', function ($query) {
                $query->where('file_type', 'video/mp4');
            })
            ->with(['storeFiles' => function ($query) {
                $query->where('file_type', 'video/mp4')
                    ->orderBy('created_at', 'asc')
                    ->limit(1);
            }])
            ->whereIn('status', [StoreStatus::ACTIVE->value, StoreStatus::PENDING->value])
            ->orderBy('sorting_order', 'asc')
            ->orderBy('view', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }
}
