<?php

namespace App\Filament\Resources\Reviews\Pages;

use App\Filament\Resources\Reviews\ReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    public function getTitle(): string
    {
        $storeName = null;
        $storeId = request()->query('store_id');
        if ($storeId) {
            $storeName = \App\Models\Store::query()->find($storeId)?->name;
        }
        return $storeName ? ("Đánh giá - " . $storeName) : 'Đánh giá';
    }
}


