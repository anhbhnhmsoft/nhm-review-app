<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Category;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreService
{
    public function search(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Store::with(['category', 'province', 'district', 'ward'])
            ->where('status', 1)
            ->orderBy('featured', 'desc')
            ->orderBy('view', 'desc')
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo từ khóa
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('short_description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('address', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Lọc theo tỉnh thành
        if (!empty($filters['province_code'])) {
            $query->where('province_code', $filters['province_code']);
        }

        // Lọc theo quận huyện
        if (!empty($filters['district_code'])) {
            $query->where('district_code', $filters['district_code']);
        }

        // Lọc theo phường xã
        if (!empty($filters['ward_code'])) {
            $query->where('ward_code', $filters['ward_code']);
        }

        // Lọc store nổi bật
        if (!empty($filters['featured'])) {
            $query->where('featured', true);
        }

        return $query->paginate($perPage);
    }
}