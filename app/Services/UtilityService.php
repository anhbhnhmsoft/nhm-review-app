<?php

namespace App\Services;

use App\Models\Utility;

class UtilityService
{
    public function getAllUtilities()
    {
        return Utility::orderBy('name')->get();
    }

    public function getUtilitiesForSelect()
    {
        $utilities = $this->getAllUtilities();
        return $utilities->pluck('name', 'id')->toArray();
    }
}
