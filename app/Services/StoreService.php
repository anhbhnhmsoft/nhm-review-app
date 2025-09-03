<?php

namespace App\Services;

use App\Models\Store;

class StoreService
{
    public function getStoreBySlug($slug): Store|null
    {
        return Store::query()
            ->where('slug', $slug)
            ->with([
                'storeFiles'=> function ($query) {
                    $query->limit(4);
                },
                'utilities',
                'reviews',
            ])
            ->withCount('storeFiles')
            ->canShow()
            ->first();
    }
}
