<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'english_name', 'administrative_level', 'decree'
    ];

    public function wards()
    {
        return $this->hasMany(Ward::class, 'province_code', 'code');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'province_code', 'code');
    }
}
