<?php

namespace App\Services;

use App\Models\Store;
use App\Utils\Constants\StoreStatus;
use App\Utils\HelperFunction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StoreService
{
    public function searchStores(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = Store::query()
                ->with(['category', 'province', 'district', 'ward', 'reviews'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->whereIn('status', [StoreStatus::ACTIVE->value, StoreStatus::PENDING->value]);

            if ($filters['status'] && in_array($filters['status'], [StoreStatus::ACTIVE->value, StoreStatus::PENDING->value])) {
                $query->where('status', $filters['status']);
            }

            if ($filters['opening_now'] === 'Đang mở cửa') {
                $now = Carbon::now();
                $currentTime = $now->format('H:i:s');
                
                $query->whereTime('opening_time', '<=', $currentTime)
                      ->whereTime('closing_time', '>=', $currentTime);
            }

            $sortBy = $filters['sort_by'] ?? 'created_at';
            $sortDirection = $filters['sort_direction'] ?? 'desc';

            switch ($sortBy) {
                case 'name':
                    $query->orderBy('name', $sortDirection);
                    break;
                case 'rating':
                    $query->orderBy('reviews_avg_rating', $sortDirection)
                        ->orderBy('reviews_count', 'desc');
                    break;
                case 'created_at':
                default:
                    $query->orderBy('created_at', $sortDirection);
                    break;
            }

            $stores = $query->paginate(10);
            foreach ($stores as $store) {
                $store->image_url = HelperFunction::generateURLImagePath($store->logo_path ?? ($store->logo ?? null));
                $store->status_label = $this->getStoreStatusLabel($store);
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

    private function getStoreStatusLabel($store)
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
}
