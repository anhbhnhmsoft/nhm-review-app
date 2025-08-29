<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'division_type', 'province_code'];

    // Mối quan hệ với bảng province
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    // Mối quan hệ với bảng wards
    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_code', 'code');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'district_code', 'code');
    }
}
