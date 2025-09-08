<?php

namespace App\Services;

use App\Models\Category;
use App\Utils\Constants\CategoryStatus;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function query(array $params = [])
    {
        $query = Category::query();

        if (!empty($params['parent_id'])) {
            $query->where('parent_id', $params['parent_id']);
        }

        return $query;

    }
    public function getAllCategoryForHomePage(): ?Collection
    {
        try {
            return Category::query()
                ->where('show_header_home_page', true)
                ->orWhere('show_index_home_page', true)
                ->where('status', CategoryStatus::ACTIVE->value)
                ->orderBy('id')
                ->get();
        }catch (\Exception $exception){
            return null;
        }
    }


}
